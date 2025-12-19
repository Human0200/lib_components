<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

CModule::IncludeModule('iblock');

// Обработка параметров
$arParams['MARK_TEXT'] = trim($arParams['MARK_TEXT']) ?: 'Мы не просто продаем — мы помогаем вам использовать Битрикс24';
$arParams['MARK_HIGHLIGHT'] = trim($arParams['MARK_HIGHLIGHT']) ?: 'на полную мощность';
$arParams['TITLE'] = trim($arParams['TITLE']) ?: 'Лицензии Битрикс24';
$arParams['CAPTION'] = trim($arParams['CAPTION']) ?: 'В соответствии с политикой 1С-Битрикс24 мы не продаем лицензии выше или ниже установленных цен';
$arParams['FOOTER_TEXT'] = trim($arParams['FOOTER_TEXT']) ?: 'В таблице цены со скидкой 50% для тех, кто покупает подписку впервые';

$arParams['IBLOCK_ID_CLOUD'] = intval($arParams['IBLOCK_ID_CLOUD']);
$arParams['IBLOCK_ID_BOXED'] = intval($arParams['IBLOCK_ID_BOXED']);
$arParams['IBLOCK_ID_SUBSCRIPTION'] = intval($arParams['IBLOCK_ID_SUBSCRIPTION']);
$arParams['CACHE_TIME'] = intval($arParams['CACHE_TIME']) ?: 3600;

// Кеширование
$cache = new CPHPCache();
$cache_id = 'bitrix24_licenses_'.md5(serialize($arParams));
$cache_path = '/bitrix24/licenses/';

if($arParams['CACHE_TIME'] > 0 && $cache->InitCache($arParams['CACHE_TIME'], $cache_id, $cache_path)) {
    $arResult = $cache->GetVars();
} elseif($cache->StartDataCache()) {
    
    // Передаем текстовые параметры
    $arResult['MARK_TEXT'] = $arParams['MARK_TEXT'];
    $arResult['MARK_HIGHLIGHT'] = $arParams['MARK_HIGHLIGHT'];
    $arResult['TITLE'] = $arParams['TITLE'];
    $arResult['CAPTION'] = $arParams['CAPTION'];
    $arResult['FOOTER_TEXT'] = $arParams['FOOTER_TEXT'];
    $arResult['MORE_CAPTION'] = $arParams['MORE_CAPTION'];
    
    // Функция для получения лицензий из инфоблока
    function getLicenses($iblockId) {
        if(!$iblockId) return [];
        
        $arFilter = [
            'IBLOCK_ID' => $iblockId,
            'ACTIVE' => 'Y'
        ];
        
        // Получаем элементы
        $dbElements = CIBlockElement::GetList(
            ['SORT' => 'ASC', 'ID' => 'ASC'],
            $arFilter,
            false,
            false,
            ['ID', 'NAME', 'IBLOCK_ID']
        );
        
        $items = [];
        
        while($obElement = $dbElements->GetNextElement()) {
            $arFields = $obElement->GetFields();
            $arProps = $obElement->GetProperties();
            
            // Получаем базовые цены
            $priceMonth = floatval($arProps['PRICE_MONTH']['VALUE']);
            $priceYear = floatval($arProps['PRICE_YEAR']['VALUE']);
            $discount = intval($arProps['DISCOUNT']['VALUE']);
            
            // АВТОМАТИЧЕСКИЙ РАСЧЕТ цены со скидкой
            $priceYearDiscounted = $priceYear;
            if($discount > 0 && $priceYear > 0) {
                $priceYearDiscounted = $priceYear * (1 - 0 / 100); //$discount
            }
            
            // АВТОМАТИЧЕСКИЙ РАСЧЕТ цены в месяц из годовой цены
            // Если цена в месяц не указана, рассчитываем из годовой
            if($priceMonth == 0 && $priceYear > 0) {
                $priceMonth = round($priceYear / 12);
            }
            
            // Обработка списка функций (множественное свойство)
            $features = [];
            if(!empty($arProps['FEATURES']['VALUE'])) {
                $features = is_array($arProps['FEATURES']['VALUE']) 
                    ? $arProps['FEATURES']['VALUE'] 
                    : [$arProps['FEATURES']['VALUE']];
            }
            
            $featuresDisabled = [];
            if(!empty($arProps['FEATURES_DISABLED']['VALUE'])) {
                $featuresDisabled = is_array($arProps['FEATURES_DISABLED']['VALUE']) 
                    ? $arProps['FEATURES_DISABLED']['VALUE'] 
                    : [$arProps['FEATURES_DISABLED']['VALUE']];
            }
            
            $items[] = [
                'ID' => $arFields['ID'],
                'NAME' => $arFields['NAME'],
                'DESCRIPTION' => $arProps['DESCRIPTION']['VALUE'],
                'EMPLOYEES' => $arProps['EMPLOYEES']['VALUE'],
                'PRICE_MONTH' => $priceMonth,
                'PRICE_YEAR' => $priceYear,
                'PRICE_YEAR_DISCOUNTED' => $priceYearDiscounted,
                'DISCOUNT' => $discount,
                'FEATURES' => $features,
                'FEATURES_DISABLED' => $featuresDisabled,
                'TYPE' => $arProps['TYPE']['VALUE']
            ];
        }
        
        return $items;
    }
    
    // Получаем облачные лицензии
    $arResult['CLOUD_LICENSES'] = getLicenses($arParams['IBLOCK_ID_CLOUD']);
    
    // Получаем коробочные лицензии
    $arResult['BOXED_LICENSES'] = getLicenses($arParams['IBLOCK_ID_BOXED']);
    
    // Получаем подписки
    $arResult['SUBSCRIPTIONS'] = getLicenses($arParams['IBLOCK_ID_SUBSCRIPTION']);
    
    // Формируем варианты цен для карточки Энтерпрайз
    $arResult['ENTERPRISE_OPTIONS'] = [];
    
    for($i = 1; $i <= 3; $i++) {
        $employees = trim($arParams['ENTERPRISE_OPTION_'.$i.'_EMPLOYEES']);
        $priceMonth = intval($arParams['ENTERPRISE_OPTION_'.$i.'_PRICE_MONTH']);
        $priceYear = intval($arParams['ENTERPRISE_OPTION_'.$i.'_PRICE_YEAR']);
        
        if($employees && $priceMonth && $priceYear) {
            $arResult['ENTERPRISE_OPTIONS'][] = [
                'EMPLOYEES' => $employees,
                'PRICE_MONTH' => number_format($priceMonth, 0, '', ' ') . ' ₽/мес.',
                'PRICE_YEAR' => number_format($priceYear, 0, '', ' ') . ' ₽/год',
                'PRICE_YEAR_ORIGINAL' => $priceYear,
            ];
        }
    }

$arResult['SUBSCRIPTION_CLOUD_OPTIONS'] = [];
for($i = 1; $i <= 3; $i++) {
    $employees = trim($arParams['SUBSCRIPTION_CLOUD_OPTION_'.$i.'_EMPLOYEES']);
    $priceMonth = intval($arParams['SUBSCRIPTION_CLOUD_OPTION_'.$i.'_PRICE_MONTH']);
    $priceYear = intval($arParams['SUBSCRIPTION_CLOUD_OPTION_'.$i.'_PRICE_YEAR']);
    
    if($employees && $priceMonth && $priceYear) {
        $arResult['SUBSCRIPTION_CLOUD_OPTIONS'][] = [
            'EMPLOYEES' => $employees,
            'PRICE_MONTH' => number_format($priceMonth, 0, '', ' ') . ' ₽/мес.',
            'PRICE_YEAR' => number_format($priceYear, 0, '', ' ') . ' ₽/год'
        ];
    }
}
    
    $cache->EndDataCache($arResult);
}

// Подключаем шаблон
$this->IncludeComponentTemplate();
?>