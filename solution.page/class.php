<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

class LandingPageComponent extends CBitrixComponent
{
    protected function checkModules()
    {
        if (!Loader::includeModule('iblock')) {
            ShowError("Модуль Инфоблоков не установлен");
            return false;
        }
        return true;
    }

    public function onPrepareComponentParams($arParams)
    {
        $arParams['BLOCKS_ORDER'] = trim($arParams['BLOCKS_ORDER']) ?: 'topbar,whom,tools,ready';
        $arParams['IBLOCK_ID'] = (int)$arParams['IBLOCK_ID'];
        $arParams['ELEMENT_ID'] = (int)$arParams['ELEMENT_ID'];

        // Инициализация параметров ready, если они не переданы
        $arParams['READY_IBLOCK_ID'] = $arParams['READY_IBLOCK_ID'] ?? $arParams['IBLOCK_ID'] ?? 0;
        $arParams['READY_IBLOCK_CODE'] = $arParams['READY_IBLOCK_CODE'] ?? 'business_integrations';
        $arParams['READY_IBLOCK_TYPE'] = $arParams['READY_IBLOCK_TYPE'] ?? 'services';
        $arParams['READY_ELEMENT_ID'] = $arParams['READY_ELEMENT_ID'] ?? $arParams['ELEMENT_ID'] ?? 0;
        $arParams['READY_SERVICES_TITLE'] = $arParams['READY_SERVICES_TITLE'] ?? '100+ готовых интеграций и сервисов!';
        $arParams['READY_BUSINESS_TITLE'] = $arParams['READY_BUSINESS_TITLE'] ?? 'Интеграции с нишевыми сервисами для бизнеса';
        $arParams['READY_SHOW_SERVICES'] = $arParams['READY_SHOW_SERVICES'] ?? 'Y';
        $arParams['READY_SHOW_BUSINESS'] = $arParams['READY_SHOW_BUSINESS'] ?? 'Y';
        $arParams['READY_ITEMS_COUNT'] = $arParams['READY_ITEMS_COUNT'] ?? '6';
        $arParams['READY_SORT_BY'] = $arParams['READY_SORT_BY'] ?? 'SORT';
        $arParams['READY_SORT_ORDER'] = $arParams['READY_SORT_ORDER'] ?? 'ASC';
        $arParams['READY_TEMPLATE'] = $arParams['READY_TEMPLATE'] ?? '.default';

        $arParams['CACHE_TIME'] = intval($arParams['CACHE_TIME']) ?: 3600;
        return $arParams;
    }

    /**
     * Получает динамические данные из элемента инфоблока
     */
    protected function getIblockData()
    {
        if ($this->arParams['IBLOCK_ID'] <= 0 || $this->arParams['ELEMENT_ID'] <= 0) {
            return [];
        }

        $result = [];
        $dbRes = \CIBlockElement::GetList(
            [],
            [
                "IBLOCK_ID" => $this->arParams['IBLOCK_ID'],
                "ID" => $this->arParams['ELEMENT_ID'],
                "ACTIVE" => "Y"
            ],
            false,
            false,
            ["*", "ID", "IBLOCK_ID", "NAME", "PREVIEW_TEXT", "DETAIL_TEXT", "DETAIL_PICTURE", "PROPERTY_*"]
        );

        if ($obj = $dbRes->GetNextElement()) {
            $fields = $obj->GetFields();
            $props = $obj->GetProperties();

            $result = [
                // === TOPBAR данные (уже есть) ===
                'TITLE' => $fields['NAME'] ?? '',
                'SUBTITLE' => $fields['PREVIEW_TEXT'] ?? '',
                'TAGLINE' => $fields['DETAIL_TEXT'] ?? '',
                'IMAGE' => !empty($fields['DETAIL_PICTURE']) ? \CFile::GetPath($fields['DETAIL_PICTURE']) : '',
                'PRICE_NEW' => $props['PRICE']['VALUE'] ?? '',
                'PRICE_OLD' => $props['PRICE_OLD']['VALUE'] ?? '',
                'CARD_1_NUMBER' => $props['INSTALLINGS']['VALUE'] ?? '',
                'CARD_1_TEXT' => $props['CARD_1_TEXT']['VALUE'] ?? '',
                'CARD_2_NUMBER' => $props['DAYS']['VALUE'] ?? '',
                'CARD_2_TEXT' => $props['CARD_2_TEXT']['VALUE'] ?? '',
                'CARD_3_NUMBER' => $props['PRICE']['VALUE'] ?? '',
                'CARD_3_TEXT' => $props['CARD_3_TEXT']['VALUE'] ?? '',

                // === WHOM CARD 1 ===
                'WHOM_CARD_1_TITLE' => $props['TITLE_BLOCK_1']['VALUE'] ?? '',
                'WHOM_CARD_1_TEXT' => $props['COUNT_BLOCK_1']['VALUE'] ?? '',
                'WHOM_CARD_1_IMAGE' => !empty($props['IMAGE_BLOCK_1']['VALUE'])
                    ? \CFile::GetPath($props['IMAGE_BLOCK_1']['VALUE']) : '',
                'WHOM_CARD_1_BACK_SUBTITLE' => $props['COUNT_BLOCK_1']['VALUE'] ?? '',
                'WHOM_CARD_1_BACK_TITLE' => $props['TITLE_BLOCK_1']['VALUE'] ?? '',
                'WHOM_CARD_1_BACK_TEXT' => $props['DESCRIPTION_BLOCK_1']['VALUE']['TEXT'] ?? $props['DESCRIPTION_BLOCK_1']['VALUE'] ?? '',

                // === WHOM CARD 2 ===
                'WHOM_CARD_2_TITLE' => $props['TITLE_BLOCK_2']['VALUE'] ?? '',
                'WHOM_CARD_2_TEXT' => $props['COUNT_BLOCK_2']['VALUE'] ?? '',
                'WHOM_CARD_2_IMAGE' => !empty($props['IMAGE_BLOCK_2']['VALUE'])
                    ? \CFile::GetPath($props['IMAGE_BLOCK_2']['VALUE']) : '',
                'WHOM_CARD_2_BACK_SUBTITLE' => $props['COUNT_BLOCK_2']['VALUE'] ?? '',
                'WHOM_CARD_2_BACK_TITLE' => $props['TITLE_BLOCK_2']['VALUE'] ?? '',
                'WHOM_CARD_2_BACK_TEXT' => $props['DESCRIPTION_BLOCK_2']['VALUE']['TEXT'] ?? $props['DESCRIPTION_BLOCK_2']['VALUE'] ?? '',

                // === WHOM CARD 3 ===
                'WHOM_CARD_3_TITLE' => $props['TITLE_BLOCK_3']['VALUE'] ?? '',
                'WHOM_CARD_3_TEXT' => $props['COUNT_BLOCK_3']['VALUE'] ?? '',
                'WHOM_CARD_3_IMAGE' => !empty($props['IMAGE_BLOCK_3']['VALUE'])
                    ? \CFile::GetPath($props['IMAGE_BLOCK_3']['VALUE']) : '',
                'WHOM_CARD_3_BACK_SUBTITLE' => $props['COUNT_BLOCK_3']['VALUE'] ?? '',
                'WHOM_CARD_3_BACK_TITLE' => $props['TITLE_BLOCK_3']['VALUE'] ?? '',
                'WHOM_CARD_3_BACK_TEXT' => $props['DESCRIPTION_BLOCK_3']['VALUE']['TEXT'] ?? $props['DESCRIPTION_BLOCK_3']['VALUE'] ?? '',

                'DYNAMIC_PROPS' => $props
            ];
        }
        return $result;
    }

    protected function getBlockParams($prefix)
    {
        $params = [];
        foreach ($this->arParams as $key => $value) {
            if (strpos($key, $prefix . '_') === 0) {
                $newKey = substr($key, strlen($prefix) + 1);
                $params[$newKey] = $value;
            }
        }
        
        // Добавляем общие параметры кеширования
        $params['CACHE_TIME'] = $this->arParams['CACHE_TIME'];
        $params['CACHE_TYPE'] = $this->arParams['CACHE_TYPE'];
        
        return $params;
    }

    public function executeComponent()
    {
        if (!$this->checkModules()) return;

        $this->arResult['BLOCKS_ORDER'] = explode(',', $this->arParams['BLOCKS_ORDER']);
        
        // Получаем параметры для каждого блока
        $this->arResult['TOPBAR_PARAMS'] = $this->getBlockParams('TOPBAR');
        $this->arResult['WHOM_PARAMS'] = $this->getBlockParams('WHOM');
        $this->arResult['TOOLS_PARAMS'] = $this->getBlockParams('TOOLS');
        $this->arResult['READY_PARAMS'] = $this->getBlockParams('READY');
        
        // Добавляем CSS класс для обертки
        $this->arResult['CSS_CLASS'] = $this->arParams['CSS_CLASS'] ?? '';

        $dynamicData = $this->getIblockData();

        if (!empty($dynamicData)) {
            // === TOPBAR маппинг (уже есть) ===
            $topbarMapping = [
                'TITLE' => 'TITLE',
                'SUBTITLE' => 'SUBTITLE',
                'TAGLINE' => 'TAGLINE',
                'IMAGE' => 'IMAGE',
                'PRICE_NEW' => 'PRICE_NEW',
                'PRICE_OLD' => 'PRICE_OLD',
                'CARD_1_NUMBER' => 'CARD_1_NUMBER',
                'CARD_1_TEXT' => 'CARD_1_TEXT',
                'CARD_2_NUMBER' => 'CARD_2_NUMBER',
                'CARD_2_TEXT' => 'CARD_2_TEXT',
                'CARD_3_NUMBER' => 'CARD_3_NUMBER',
                'CARD_3_TEXT' => 'CARD_3_TEXT',
            ];

            foreach ($topbarMapping as $iblockKey => $paramKey) {
                if (!empty($dynamicData[$iblockKey])) {
                    $this->arResult['TOPBAR_PARAMS'][$paramKey] = $dynamicData[$iblockKey];
                }
            }

            // === WHOM маппинг ===
            $whomMapping = [
                // Карточка 1
                'WHOM_CARD_1_TITLE' => 'CARD_1_TITLE',
                'WHOM_CARD_1_TEXT' => 'CARD_1_TEXT',
                'WHOM_CARD_1_IMAGE' => 'CARD_1_IMAGE',
                'WHOM_CARD_1_BACK_SUBTITLE' => 'CARD_1_BACK_SUBTITLE',
                'WHOM_CARD_1_BACK_TITLE' => 'CARD_1_BACK_TITLE',
                'WHOM_CARD_1_BACK_TEXT' => 'CARD_1_BACK_TEXT',

                // Карточка 2
                'WHOM_CARD_2_TITLE' => 'CARD_2_TITLE',
                'WHOM_CARD_2_TEXT' => 'CARD_2_TEXT',
                'WHOM_CARD_2_IMAGE' => 'CARD_2_IMAGE',
                'WHOM_CARD_2_BACK_SUBTITLE' => 'CARD_2_BACK_SUBTITLE',
                'WHOM_CARD_2_BACK_TITLE' => 'CARD_2_BACK_TITLE',
                'WHOM_CARD_2_BACK_TEXT' => 'CARD_2_BACK_TEXT',

                // Карточка 3
                'WHOM_CARD_3_TITLE' => 'CARD_3_TITLE',
                'WHOM_CARD_3_TEXT' => 'CARD_3_TEXT',
                'WHOM_CARD_3_IMAGE' => 'CARD_3_IMAGE',
                'WHOM_CARD_3_BACK_SUBTITLE' => 'CARD_3_BACK_SUBTITLE',
                'WHOM_CARD_3_BACK_TITLE' => 'CARD_3_BACK_TITLE',
                'WHOM_CARD_3_BACK_TEXT' => 'CARD_3_BACK_TEXT',
            ];

            foreach ($whomMapping as $iblockKey => $paramKey) {
                if (!empty($dynamicData[$iblockKey])) {
                    $this->arResult['WHOM_PARAMS'][$paramKey] = $dynamicData[$iblockKey];
                }
            }

            // Сохраняем свойства для возможного использования
            $this->arResult['TOPBAR_PARAMS']['IBLOCK_PROPS'] = $dynamicData['DYNAMIC_PROPS'];
            $this->arResult['WHOM_PARAMS']['IBLOCK_PROPS'] = $dynamicData['DYNAMIC_PROPS'];
            
            // Передаем ELEMENT_ID в READY_PARAMS для связанных элементов
            if ($this->arParams['ELEMENT_ID'] > 0) {
                $this->arResult['READY_PARAMS']['ELEMENT_ID'] = $this->arParams['ELEMENT_ID'];
            }
        }

        // Шаблоны компонентов
        $this->arResult['TEMPLATES'] = [
            'topbar' => $this->arParams['TOPBAR_TEMPLATE'] ?? '.default',
            'whom' => $this->arParams['WHOM_CARDS_TEMPLATE'] ?? '.default',
            'tools' => $this->arParams['TOOLS_TEMPLATE'] ?? '.default',
            'ready' => $this->arParams['READY_TEMPLATE'] ?? '.default', // ← Добавлено!
        ];

        $this->includeComponentTemplate();
    }
}