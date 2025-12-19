<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

CModule::IncludeModule('iblock');

// Обработка параметров
$arParams['IBLOCK_ID'] = intval($arParams['IBLOCK_ID']);
$arParams['CACHE_TIME'] = intval($arParams['CACHE_TIME']) ?: 3600;
$arParams['PAGE_CODE'] = trim($arParams['PAGE_CODE']) || '1';
$arParams['MARK'] = trim($arParams['MARK']) ?: 'Цены на лицензии и тарифы';
$arParams['TITLE'] = trim($arParams['TITLE']) ?: 'Из чего складывается стоимость внедрения?';
$arParams['REQUEST_TITLE'] = trim($arParams['REQUEST_TITLE']) ?: "Вы сделали первый шаг к автоматизации\nждем ваши заявки!";
$arParams['BUTTON_TEXT'] = trim($arParams['BUTTON_TEXT']) ?: 'заказать внедрение';
$arParams['BUTTON_LINK'] = trim($arParams['BUTTON_LINK']) ?: '#';
$arParams['TAGLINE_ROW_1'] = trim($arParams['TAGLINE_ROW_1']) ?: 'Подберите для своего дела наиболее подходящий';
$arParams['TAGLINE_ROW_2'] = trim($arParams['TAGLINE_ROW_2']) ?: 'и комфортный тарифный план.';

if(!$arParams['IBLOCK_ID']) {
    ShowError('Не указан ID инфоблока');
    return;
}

// Кеширование
$cache = new CPHPCache();
$cache_id = 'individual_steps_'.$arParams['IBLOCK_ID'].'_'.$arParams['PAGE_CODE'];
$cache_path = '/individual/steps/';

if($arParams['CACHE_TIME'] > 0 && $cache->InitCache($arParams['CACHE_TIME'], $cache_id, $cache_path)) {
    $arResult = $cache->GetVars();
} elseif($cache->StartDataCache()) {
    // Фильтр
    $filter = [
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'ACTIVE' => 'Y'
    ];
    
    // Если указан код страницы, добавляем его в фильтр
    if($arParams['PAGE_CODE']) {
        $filter['PROPERTY_PAGE_CODE'] = $arParams['PAGE_CODE'];
    }
    
    // Выборка полей
    $arSelect = [
        'ID',
        'NAME',
        'PREVIEW_TEXT',
        'SORT',
        'PROPERTY_PAGE_CODE'
    ];
    
    // Получаем элементы
    $dbElements = CIBlockElement::GetList(
        ['SORT' => 'ASC'],
        $filter,
        false,
        false,
        $arSelect
    );
    
    $arResult['MARK'] = $arParams['MARK'];
    $arResult['TITLE'] = $arParams['TITLE'];
    $arResult['REQUEST_TITLE'] = $arParams['REQUEST_TITLE'];
    $arResult['BUTTON_TEXT'] = $arParams['BUTTON_TEXT'];
    $arResult['BUTTON_LINK'] = $arParams['BUTTON_LINK'];
    $arResult['TAGLINE_ROW_1'] = $arParams['TAGLINE_ROW_1'];
    $arResult['TAGLINE_ROW_2'] = $arParams['TAGLINE_ROW_2'];
    $arResult['ITEMS'] = [];
    
    $counter = 1;
    while($element = $dbElements->GetNext()) {
        $arResult['ITEMS'][] = [
            'ID' => $element['ID'],
            'NUMBER' => str_pad($counter, 2, '0', STR_PAD_LEFT),
            'TITLE' => $element['NAME'],
            'TEXT' => $element['PREVIEW_TEXT'],
            'PAGE_CODE' => $element['PROPERTY_PAGE_CODE_VALUE']
        ];
        $counter++;
    }
    
    $cache->EndDataCache($arResult);
}

// Подключаем шаблон
$this->IncludeComponentTemplate();
?>