<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// Подключаем модуль highloadblock
if (!CModule::IncludeModule('highloadblock')) {
    ShowError('Модуль highloadblock не установлен');
    return;
}

// Определяем тип страницы из константы или используем main по умолчанию
$pageType = defined('HEADER_TYPE') ? HEADER_TYPE : 'main';

// Валидация типа страницы (только разрешенные значения)
$allowedTypes = ['main', 'cases', 'solutions'];
if (!in_array($pageType, $allowedTypes)) {
    $pageType = 'main';
}

// Параметры компонента
$arParams['HLBLOCK_ID'] = intval($arParams['HLBLOCK_ID']) ?: 2;
$arParams['CACHE_TIME'] = intval($arParams['CACHE_TIME']) ?: 3600;
$arParams['SORT_ORDER'] = in_array($arParams['SORT_ORDER'], ['ASC', 'DESC']) ? $arParams['SORT_ORDER'] : 'ASC';
$arParams['SORT_FIELD'] = trim($arParams['SORT_FIELD']) ?: 'UF_SORT';

// Параметры брендинга с дефолтными значениями
$arParams['LOGO_SVG_PATH'] = trim($arParams['LOGO_SVG_PATH']) ?: SITE_TEMPLATE_PATH . "/assets/images/logo.svg";
$arParams['LOGO_ALT'] = trim($arParams['LOGO_ALT']) ?: "3D Group";
$arParams['FAVICON_SVG'] = trim($arParams['FAVICON_SVG']) ?: SITE_TEMPLATE_PATH . "/assets/favicons/favicon.svg";
$arParams['FAVICON_PNG'] = trim($arParams['FAVICON_PNG']) ?: SITE_TEMPLATE_PATH . "/assets/favicons/favicon-96x96.png";
$arParams['FAVICON_ICO'] = trim($arParams['FAVICON_ICO']) ?: SITE_TEMPLATE_PATH . "/assets/favicons/favicon.ico";
$arParams['APPLE_TOUCH_ICON'] = trim($arParams['APPLE_TOUCH_ICON']) ?: SITE_TEMPLATE_PATH . "/assets/favicons/apple-touch-icon.png";

// Функция для получения ссылок из Highload-блока
function getHeaderLinks($hlblockId, $sortField, $sortOrder, $pageType)
{
    try {
        $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById($hlblockId)->fetch();
        if (!$hlblock) {
            return [];
        }
        
        $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
        $entityClass = $entity->getDataClass();
        
        // Получаем все активные ссылки
        $allLinks = $entityClass::getList([
            'select' => ['*'],
            'order' => [$sortField => $sortOrder],
            'filter' => ['=UF_ACTIVE' => true]
        ])->fetchAll();
        
        // Фильтруем ссылки по типу страницы с учетом множественных значений
        $filteredLinks = [];
        foreach ($allLinks as $link) {
            if (empty($link['UF_PAGE_TYPE'])) {
                continue;
            }
            
            // Разбиваем значение по запятой и убираем пробелы
            $pageTypes = array_map('trim', explode(',', $link['UF_PAGE_TYPE']));
            
            // Если текущий тип страницы есть в списке - добавляем ссылку
            if (in_array($pageType, $pageTypes)) {
                $filteredLinks[] = $link;
            }
        }
        
        return $filteredLinks;
        
    } catch (Exception $e) {
        return [];
    }
}

// Кеширование с учетом типа страницы
$cache = new CPHPCache();
$cache_id = 'header_links_' . $arParams['HLBLOCK_ID'] . '_' . $arParams['SORT_FIELD'] . '_' . $arParams['SORT_ORDER'] . '_' . $pageType;
$cache_path = '/header_links/';

if ($arParams['CACHE_TIME'] > 0 && $cache->InitCache($arParams['CACHE_TIME'], $cache_id, $cache_path)) {
    $arResult = $cache->GetVars();
} elseif ($cache->StartDataCache()) {
    
    // Получаем ссылки из Highload-блока с учетом типа страницы
    $arResult['LINKS'] = getHeaderLinks($arParams['HLBLOCK_ID'], $arParams['SORT_FIELD'], $arParams['SORT_ORDER'], $pageType);
    $arResult['PAGE_TYPE'] = $pageType;
    
    $cache->EndDataCache($arResult);
}

// Передаем параметры брендинга в результат
$arResult['BRANDING'] = [
    'LOGO_SVG_PATH' => $arParams['LOGO_SVG_PATH'],
    'LOGO_ALT' => $arParams['LOGO_ALT'],
    'FAVICON_SVG' => $arParams['FAVICON_SVG'],
    'FAVICON_PNG' => $arParams['FAVICON_PNG'],
    'FAVICON_ICO' => $arParams['FAVICON_ICO'],
    'APPLE_TOUCH_ICON' => $arParams['APPLE_TOUCH_ICON'],
];

$this->IncludeComponentTemplate();
?>