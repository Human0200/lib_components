<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;

if (!Loader::includeModule('iblock')) {
    ShowError("Модуль Инфоблоков не установлен");
    return;
}

// Обработка параметров
$arParams['MARK_TEXT'] = trim($arParams['MARK_TEXT']) ?: 'что внутри';
$arParams['TAGLINE_ROW_1'] = trim($arParams['TAGLINE_ROW_1']) ?: 'Вместе с CRM-решением вы получите';
$arParams['TAGLINE_ROW_2'] = trim($arParams['TAGLINE_ROW_2']) ?: 'полный набор инструментов Битрикс24 для работы';
$arParams['TITLE'] = trim($arParams['TITLE']) ?: 'Что внутри готового решения?';

// Получаем ID связанного элемента из свойства SLIDER_TOOLS_CARDS
$arParams['IBLOCK_ID'] = intval($arParams['IBLOCK_ID']);
$arParams['ELEMENT_ID'] = intval($arParams['ELEMENT_ID']);
$arParams['PROPERTY_CODE'] = trim($arParams['PROPERTY_CODE']) ?: 'SLIDER_TOOLS_CARDS';

// Передаем текстовые параметры
$arResult['MARK_TEXT'] = $arParams['MARK_TEXT'];
$arResult['TAGLINE_ROW_1'] = $arParams['TAGLINE_ROW_1'];
$arResult['TAGLINE_ROW_2'] = $arParams['TAGLINE_ROW_2'];
$arResult['TITLE'] = $arParams['TITLE'];

// Формируем массив инструментов
$arResult['ITEMS'] = [];

// Если передан IBLOCK_ID и ELEMENT_ID, получаем данные из инфоблока
if ($arParams['IBLOCK_ID'] > 0 && $arParams['ELEMENT_ID'] > 0) {
    // Получаем основной элемент со свойством SLIDER_TOOLS_CARDS
    $dbElement = CIBlockElement::GetList(
        [],
        [
            'IBLOCK_ID' => $arParams['IBLOCK_ID'],
            'ID' => $arParams['ELEMENT_ID'],
            'ACTIVE' => 'Y'
        ],
        false,
        false,
        ['ID', 'IBLOCK_ID']
    );
    
    if ($arElement = $dbElement->GetNextElement()) {
        $arProps = $arElement->GetProperties();
        
        // Получаем ID связанного элемента из свойства SLIDER_TOOLS_CARDS (одиночное поле)
        $linkedElementId = 0;
        if (!empty($arProps[$arParams['PROPERTY_CODE']]['VALUE'])) {
            $linkedElementId = intval($arProps[$arParams['PROPERTY_CODE']]['VALUE']);
        }
        
        // Если есть связанный элемент, получаем его данные
        if ($linkedElementId > 0) {
            $dbLinkedElement = CIBlockElement::GetList(
                [],
                [
                    'ID' => $linkedElementId,
                    'ACTIVE' => 'Y'
                ],
                false,
                false,
                ['ID', 'IBLOCK_ID', 'NAME', 'PROPERTY_*']
            );
            
            if ($arLinkedElement = $dbLinkedElement->GetNextElement()) {
                $arLinkedFields = $arLinkedElement->GetFields();
                $arLinkedProps = $arLinkedElement->GetProperties();
                
                // Главный заголовок из свойства TITLE или NAME элемента
                $mainTitle = '';
                if (!empty($arLinkedProps['TITLE']['VALUE'])) {
                    $mainTitle = trim($arLinkedProps['TITLE']['VALUE']);
                } else {
                    $mainTitle = trim($arLinkedFields['NAME']);
                }
                
                // Обрабатываем множественное свойство BLOCK (simai_complex)
                if (!empty($arLinkedProps['BLOCK']['VALUE'])) {
                    $blockValues = $arLinkedProps['BLOCK']['VALUE'];
                    
                    // Если это не массив, преобразуем в массив
                    if (!is_array($blockValues)) {
                        $blockValues = [$blockValues];
                    }
                    
                    // Каждый BLOCK - это один инструмент (слайд)
                    foreach ($blockValues as $blockValue) {
                        // Проверяем структуру simai_complex
                        if (!is_array($blockValue) || !isset($blockValue['SUB_VALUES'])) {
                            continue;
                        }
                        
                        $subValues = $blockValue['SUB_VALUES'];
                        
                        // Извлекаем DESCRIPTION из SUB_VALUES
                        $description = '';
                        if (isset($subValues['DESCRIPTION']['VALUE'])) {
                            $descValue = $subValues['DESCRIPTION']['VALUE'];
                            
                            // Обрабатываем формат HTML-поля
                            if (is_array($descValue) && isset($descValue['TEXT'])) {
                                $description = trim($descValue['TEXT']);
                            } else {
                                $description = trim($descValue);
                            }
                        }
                        
                        // Извлекаем IMAGE из SUB_VALUES (множественное поле)
                        $images = [];
                        if (isset($subValues['IMAGE']['VALUE'])) {
                            $imageValue = $subValues['IMAGE']['VALUE'];
                            
                            // IMAGE - это множественное поле, может быть массив ID файлов
                            if (is_array($imageValue)) {
                                foreach ($imageValue as $imageId) {
                                    if ($imageId) {
                                        $imagePath = CFile::GetPath($imageId);
                                        if ($imagePath) {
                                            $images[] = $imagePath;
                                        }
                                    }
                                }
                            } elseif ($imageValue) {
                                // Или одиночное значение
                                $imagePath = CFile::GetPath($imageValue);
                                if ($imagePath) {
                                    $images[] = $imagePath;
                                }
                            }
                        }
                        
                        // Добавляем элемент в результат только если есть описание или изображения
                        if ($description || !empty($images)) {
                            $arResult['ITEMS'][] = [
                                'NAME' => $mainTitle,
                                'DESCRIPTIONS' => [$description],
                                'IMAGES' => $images,
                            ];
                        }
                    }
                }
            }
        }
    }
}

// Если элементы не получены из инфоблока, используем параметры компонента (fallback)
if (empty($arResult['ITEMS'])) {
    for($toolNum = 1; $toolNum <= 2; $toolNum++) {
        $name = trim($arParams['TOOL_'.$toolNum.'_NAME']);
        
        if(!$name) continue;
        
        // Собираем описания
        $descriptions = [];
        for($i = 1; $i <= 5; $i++) {
            $desc = trim($arParams['TOOL_'.$toolNum.'_DESC_'.$i]);
            if($desc) {
                $descriptions[] = $desc;
            }
        }
        
        // Собираем изображения
        $images = [];
        for($i = 1; $i <= 5; $i++) {
            $image = trim($arParams['TOOL_'.$toolNum.'_IMAGE_'.$i]);
            if($image) {
                $images[] = $image;
            }
        }
        
        if(!empty($descriptions) || !empty($images)) {
            $arResult['ITEMS'][] = [
                'NAME' => $name,
                'DESCRIPTIONS' => $descriptions,
                'IMAGES' => $images,
            ];
        }
    }
}

// Подключаем шаблон
$this->IncludeComponentTemplate();
?>