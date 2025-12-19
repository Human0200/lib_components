<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

CModule::IncludeModule('iblock');

// Обработка параметров
$arParams['IBLOCK_ID'] = intval($arParams['IBLOCK_ID']);
$arParams['IBLOCK_CODE'] = trim($arParams['IBLOCK_CODE']);
$arParams['IBLOCK_TYPE'] = trim($arParams['IBLOCK_TYPE']);
$arParams['ITEMS_COUNT'] = intval($arParams['ITEMS_COUNT']) ?: 6;
$arParams['SORT_BY'] = trim($arParams['SORT_BY']) ?: 'SORT';
$arParams['SORT_ORDER'] = in_array($arParams['SORT_ORDER'], ['ASC', 'DESC']) ? $arParams['SORT_ORDER'] : 'ASC';
$arParams['CACHE_TIME'] = intval($arParams['CACHE_TIME']) ?: 3600;

// Параметры фильтрации по страницам
$arParams['PAGE_CODE'] = trim($arParams['PAGE_CODE'] ?? '');
$arParams['SHOW_ALL_IF_NO_PAGE'] = !isset($arParams['SHOW_ALL_IF_NO_PAGE']) || $arParams['SHOW_ALL_IF_NO_PAGE'] !== 'N';

// Визуальные параметры
$arParams['SERVICES_TITLE'] = trim($arParams['SERVICES_TITLE']) ?: '100+ готовых интеграций и сервисов!';
$arParams['BUSINESS_TITLE'] = trim($arParams['BUSINESS_TITLE']) ?: 'Интеграции с нишевыми сервисами для бизнеса';
$arParams['SHOW_SERVICES'] = !isset($arParams['SHOW_SERVICES']) || $arParams['SHOW_SERVICES'] !== 'N';
$arParams['SHOW_BUSINESS'] = !isset($arParams['SHOW_BUSINESS']) || $arParams['SHOW_BUSINESS'] !== 'N';

// ========== ФУНКЦИЯ АВТОМАТИЧЕСКОГО ОПРЕДЕЛЕНИЯ СТРАНИЦЫ ==========
function autoDetectPageCode() {
    global $APPLICATION;
    
    // Получаем текущий путь
    $currentDir = $APPLICATION->GetCurDir();
    
    // Очищаем от слешей
    $currentDir = trim($currentDir, '/');
    
    // Проверка главной страницы
    if (empty($currentDir) || $currentDir === 'index.php') {
        return 'main';
    }
    
    // Разбиваем URL на части
    $urlParts = array_filter(explode('/', $currentDir));
    
    // Берем последнюю часть URL
    if (!empty($urlParts)) {
        $lastPart = end($urlParts);
        
        // Очищаем от расширений
        $pageCode = preg_replace('/\.(php|html|htm)$/', '', $lastPart);
        
        // Ограничиваем длину
        $pageCode = substr($pageCode, 0, 50);
        
        if (!empty($pageCode)) {
            return $pageCode;
        }
    }
    
    // Если всё равно пусто - возвращаем 'main'
    return 'main';
}

// Всегда определяем код страницы автоматически (игнорируем параметр из настроек)
$arParams['PAGE_CODE'] = autoDetectPageCode();

// Функция получения ID инфоблока
function getIblockId($arParams) {
    if($arParams['IBLOCK_ID'] > 0) {
        return $arParams['IBLOCK_ID'];
    }
    
    $filter = ['ACTIVE' => 'Y'];
    
    if($arParams['IBLOCK_CODE']) {
        $filter['CODE'] = $arParams['IBLOCK_CODE'];
    }
    
    if($arParams['IBLOCK_TYPE']) {
        $filter['TYPE'] = $arParams['IBLOCK_TYPE'];
    }
    
    $iblock = CIBlock::GetList([], $filter)->Fetch();
    
    return $iblock ? $iblock['ID'] : 0;
}

// Получаем ID инфоблока
$arResult['IBLOCK_ID'] = getIblockId($arParams);

if(!$arResult['IBLOCK_ID']) {
    ShowError('Инфоблок бизнес-интеграций не найден');
    return;
}

// Кеширование с учетом кода страницы
$cache = new CPHPCache();
$cache_id = 'business_integrations_'.$arResult['IBLOCK_ID'].'_'.$arParams['ITEMS_COUNT'].'_'.$arParams['PAGE_CODE'].'_'.$arParams['SHOW_ALL_IF_NO_PAGE'];
$cache_path = '/business/integrations/';

if($arParams['CACHE_TIME'] > 0 && $cache->InitCache($arParams['CACHE_TIME'], $cache_id, $cache_path)) {
    $arResult = $cache->GetVars();
} elseif($cache->StartDataCache()) {
    
    // ========== БЛОК БИЗНЕС-ИНТЕГРАЦИЙ (динамический из инфоблока) ==========
    $filter = [
        'IBLOCK_ID' => $arResult['IBLOCK_ID'],
        'ACTIVE' => 'Y'
    ];
    
    // Добавляем фильтр по странице
    if (!empty($arParams['PAGE_CODE'])) {
        $filter['PROPERTY_SHOW_ON_PAGES'] = $arParams['PAGE_CODE'];
    } elseif (!$arParams['SHOW_ALL_IF_NO_PAGE']) {
        $filter['PROPERTY_SHOW_ON_PAGES'] = false;
    }
    
    // Выборка полей
    $arSelect = [
        'ID',
        'NAME',
        'PREVIEW_TEXT',
        'DETAIL_TEXT',
        'PREVIEW_PICTURE',
        'PROPERTY_IMAGE',
        'PROPERTY_SHOW_ON_PAGES'
    ];
    
    // Параметры навигации
    $arNavParams = false;
    if($arParams['ITEMS_COUNT'] > 0) {
        $arNavParams = ['nTopCount' => $arParams['ITEMS_COUNT']];
    }
    
    // Получаем элементы
    $dbElements = CIBlockElement::GetList(
        [$arParams['SORT_BY'] => $arParams['SORT_ORDER']],
        $filter,
        false,
        $arNavParams,
        $arSelect
    );
    
    $arResult['BUSINESS_ITEMS'] = [];
    
    while($element = $dbElements->GetNext()) {
        // Дополнительная проверка для точного совпадения кода страницы
        if (!empty($arParams['PAGE_CODE'])) {
            $showOnPages = $element['PROPERTY_SHOW_ON_PAGES_VALUE'] ?? '';
            
            if (!empty($showOnPages)) {
                if (is_array($showOnPages)) {
                    $pagesArray = $showOnPages;
                } else {
                    $pagesArray = explode(',', $showOnPages);
                    $pagesArray = array_map('trim', $pagesArray);
                }
                
                // Проверяем, есть ли нужный код страницы
                if (!in_array($arParams['PAGE_CODE'], $pagesArray)) {
                    continue;
                }
            } elseif (!$arParams['SHOW_ALL_IF_NO_PAGE']) {
                continue;
            }
        }
        
        // Получаем изображение
        $image = '';
        if($element['PROPERTY_IMAGE_VALUE']) {
            $image = CFile::GetPath($element['PROPERTY_IMAGE_VALUE']);
        } elseif($element['PREVIEW_PICTURE']) {
            $image = CFile::GetPath($element['PREVIEW_PICTURE']);
        }
        
        // Текст
        $text = $element['PREVIEW_TEXT'] ?: $element['DETAIL_TEXT'];
        
        $arResult['BUSINESS_ITEMS'][] = [
            'ID' => $element['ID'],
            'NAME' => $element['NAME'],
            'TEXT' => $text,
            'IMAGE' => $image
        ];
    }
    
    // ========== БЛОК СЕРВИСОВ (статические данные) ==========
    $arResult['SERVICES'] = [
        [
            'TITLE' => 'Мессенджеры и<br>социальные сети',
            'ICONS' => [
                '/local/templates/leadspace/assets/images/ready/icon-viber.svg',
                '/local/templates/leadspace/assets/images/ready/icon-tg.svg',
                '/local/templates/leadspace/assets/images/ready/icon-wa.svg',
                '/local/templates/leadspace/assets/images/ready/icon-vk.svg'
            ],
            'CLASS' => 'ready__services-card--01'
        ],
        [
            'TITLE' => 'Онлайн-кассы, платежные<br>агрегаторы, эквайринг',
            'ICONS' => [
                '/local/templates/leadspace/assets/images/ready/icon-sber.svg',
                '/local/templates/leadspace/assets/images/ready/icon-tbank.svg',
                '/local/templates/leadspace/assets/images/ready/icon-ukassa.svg',
                '/local/templates/leadspace/assets/images/ready/icon-lifepay.svg',
                '/local/templates/leadspace/assets/images/ready/icon-robokassa.svg'
            ],
            'CLASS' => 'ready__services-card--02'
        ],
        [
            'TITLE' => 'Службы доставки и<br>телефонии',
            'ICONS' => [
                '/local/templates/leadspace/assets/images/ready/icon-mtc.svg',
                '/local/templates/leadspace/assets/images/ready/icon-yandex.svg',
                '/local/templates/leadspace/assets/images/ready/icon-cdek.svg',
                '/local/templates/leadspace/assets/images/ready/icon-post.svg',
                '/local/templates/leadspace/assets/images/ready/icon-rt.svg'
            ],
            'CLASS' => 'ready__services-card--03'
        ]
    ];

    // Передаем параметры
    $arResult['SERVICES_TITLE'] = $arParams['SERVICES_TITLE'];
    $arResult['BUSINESS_TITLE'] = $arParams['BUSINESS_TITLE'];
    $arResult['SHOW_SERVICES'] = $arParams['SHOW_SERVICES'];
    $arResult['SHOW_BUSINESS'] = $arParams['SHOW_BUSINESS'];
    $arResult['PAGE_CODE'] = $arParams['PAGE_CODE'];
    $arResult['DETECTED_PAGE'] = true;
    
    $cache->EndDataCache($arResult);
}

// Подключаем шаблон
$this->IncludeComponentTemplate();
?>