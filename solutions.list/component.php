<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule('iblock');

// Обработка параметров
$arParams['IBLOCK_ID'] = intval($arParams['IBLOCK_ID']);
$arParams['CACHE_TIME'] = intval($arParams['CACHE_TIME']) ?: 3600;
$arParams['TITLE'] = trim($arParams['TITLE']) ?: 'Решения';
$arParams['CHECK_404'] = isset($arParams['CHECK_404']) ? $arParams['CHECK_404'] === 'Y' : false;

if(!$arParams['IBLOCK_ID']) {
    ShowError('Не указан ID инфоблока');
    return;
}

$arResult['IBLOCK_ID'] = $arParams['IBLOCK_ID'];

// Функция проверки URL на 404
function checkUrlExists($url) {
    if(empty($url)) {
        return false;
    }
    
    // Проверяем только внутренние URL
    if(strpos($url, '/') !== 0) {
        return true;
    }
    
    $documentRoot = $_SERVER['DOCUMENT_ROOT'];
    $filePath = $documentRoot . $url;
    
    // Убираем параметры из URL
    $filePath = preg_replace('/\?.*$/', '', $filePath);
    
    // Проверяем существование файла или директории с index.php
    if(file_exists($filePath)) {
        return true;
    }
    
    // Проверяем index.php в директории
    if(is_dir($filePath) && file_exists($filePath . '/index.php')) {
        return true;
    }
    
    return false;
}

// Кеширование
$cache = new CPHPCache();
$cache_id = 'solutions_list_'.$arParams['IBLOCK_ID'].'_'.($arParams['CHECK_404'] ? '1' : '0');
$cache_path = '/solutions/list/';

if($arParams['CACHE_TIME'] > 0 && $cache->InitCache($arParams['CACHE_TIME'], $cache_id, $cache_path)) {
    $arResult = $cache->GetVars();
} elseif($cache->StartDataCache()) {
    // Фильтр
    $filter = [
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'ACTIVE' => 'Y'
    ];
    
    // Выборка полей
    $arSelect = [
        'ID',
        'NAME',
        'DETAIL_PAGE_URL',
        'PREVIEW_PICTURE',
        'DETAIL_PICTURE',
        'PROPERTY_CLIENTS_COUNT',
        'PROPERTY_IMAGE',
        'PROPERTY_URL',
        'PROPERTY_LOGO',
        'IBLOCK_ID',
    ];
    
    // Параметры навигации
    $arNavParams = false;
    
    // Получаем элементы
    $dbElements = CIBlockElement::GetList(
        ['SORT' => 'ASC'],
        $filter,
        false,
        $arNavParams,
        $arSelect
    );
    
    $arResult['TITLE'] = $arParams['TITLE'];
    $arResult['SUBTITLE'] = $arParams['SUBTITLE'];
    $arResult['ITEMS'] = [];
    
    while($element = $dbElements->GetNext()) {
        // Получаем картинку
        $image = '';
        if($element['PREVIEW_PICTURE']) {
            $image = CFile::GetPath($element['PREVIEW_PICTURE']);
        } elseif($element['PROPERTY_IMAGE_VALUE']) {
            $image = CFile::GetPath($element['PROPERTY_IMAGE_VALUE']);
        } elseif($element['DETAIL_PICTURE']) {
            $image = CFile::GetPath($element['DETAIL_PICTURE']);
        }  elseif($element['PROPERTY_LOGO_VALUE']) {
            $image = CFile::GetPath($element['PROPERTY_LOGO_VALUE']);
        }
        
        // Проверяем URL на 404
        $detailPageUrl = $element['DETAIL_PAGE_URL'];
        if($arParams['CHECK_404'] && !checkUrlExists($detailPageUrl)) {
            $detailPageUrl = '';
        }
        
        // Количество клиентов
        $clientsCount = $element['PROPERTY_CLIENTS_COUNT_VALUE'] ?: '11';
        
        $arResult['ITEMS'][] = [
            'ID' => $element['ID'],
            'NAME' => $element['NAME'],
            'DETAIL_PAGE_URL' => $detailPageUrl,
            'IMAGE' => $image,
            'CLIENTS_COUNT' => $clientsCount
        ];
    }
    
    $cache->EndDataCache($arResult);
}

$this->setResultCacheKeys(array(
    'IBLOCK_ID',
));

// Подключаем шаблон
$this->IncludeComponentTemplate();

global $USER, $APPLICATION;
if($USER->IsAuthorized())
{
    if(
        $APPLICATION->GetShowIncludeAreas()
        || $arParams["SET_TITLE"]
    )
    {
        $arButtons = CIBlock::GetPanelButtons(
            $arResult["IBLOCK_ID"],
            0,
            0,
            array("SECTION_BUTTONS" => false)
        );
        if($APPLICATION->GetShowIncludeAreas())
        {
            $this->addIncludeAreaIcons(CIBlock::GetComponentMenu($APPLICATION->GetPublicShowMode(), $arButtons));
        }
    }
}
?>