<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

use Bitrix\Main\Loader;

class CasePageComponent extends CBitrixComponent
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
        $arParams['BLOCKS_ORDER'] = trim($arParams['BLOCKS_ORDER']) ?: 'intro,details,targets,roadmap,stages,results,features';
        $arParams['IBLOCK_ID'] = (int) ($arParams['IBLOCK_ID'] ?: 6);
        $arParams['ELEMENT_ID'] = (int) $arParams['ELEMENT_ID'];

        // Инициализация параметров INTRO
        $arParams['INTRO_TITLE_WORD'] = $arParams['INTRO_TITLE_WORD'] ?? 'КЕЙС';
        $arParams['INTRO_SUBTITLE'] = $arParams['INTRO_SUBTITLE'] ?? '';
        $arParams['INTRO_CARD_MARK'] = $arParams['INTRO_CARD_MARK'] ?? 'Внедрение и настройка Битрикс24';
        $arParams['INTRO_BUTTON_TEXT'] = $arParams['INTRO_BUTTON_TEXT'] ?? 'заказать внедрение';
        $arParams['INTRO_BUTTON_LINK'] = $arParams['INTRO_BUTTON_LINK'] ?? '#modal-feedback';

        // Инициализация параметров DETAILS
        $arParams['DETAILS_MARK_TEXT'] = $arParams['DETAILS_MARK_TEXT'] ?? 'компания';
        $arParams['DETAILS_BLOCK_1_TITLE'] = $arParams['DETAILS_BLOCK_1_TITLE'] ?? 'О клиенте';
        $arParams['DETAILS_BLOCK_2_TITLE'] = $arParams['DETAILS_BLOCK_2_TITLE'] ?? 'задачи клиента';

        // Инициализация параметров TARGETS
        $arParams['TARGETS_TITLE'] = $arParams['TARGETS_TITLE'] ?? 'Цели проекта';

        // Инициализация параметров ROADMAP
        $arParams['ROADMAP_MARK_TEXT'] = $arParams['ROADMAP_MARK_TEXT'] ?? 'Сроки и этапы работы над проектом';
        $arParams['ROADMAP_TITLE'] = $arParams['ROADMAP_TITLE'] ?? 'Дорожная карта';

        // Инициализация параметров STAGES
        $arParams['STAGES_TITLE'] = $arParams['STAGES_TITLE'] ?? 'Этапы внедрения';

        // Инициализация параметров RESULTS
        $arParams['RESULTS_TITLE'] = $arParams['RESULTS_TITLE'] ?? 'Результаты и выводы';
        $arParams['RESULTS_MARK'] = $arParams['RESULTS_MARK'] ?? 'Статистика';

        // Инициализация параметров FEATURES
        $arParams['FEATURES_TITLE'] = $arParams['FEATURES_TITLE'] ?? 'Особенности проекта';

        $arParams['CACHE_TIME'] = intval($arParams['CACHE_TIME']) ?: 3600;

        return $arParams;
    }

    /**
     * Получает данные из инфоблока кейса
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
            ["*", "PROPERTY_*"]
        );

        if ($obj = $dbRes->GetNextElement()) {
            $fields = $obj->GetFields();
            $props = $obj->GetProperties();

            $result = [
                'FIELDS' => $fields,
                'PROPS' => $props
            ];
        }

        return $result;
    }

    /**
     * Получает параметры для блока по префиксу
     */
    protected function getBlockParams($prefix)
    {
        $params = [];
        $prefixLength = strlen($prefix) + 1;

        foreach ($this->arParams as $key => $value) {
            if (strpos($key, $prefix . '_') === 0) {
                $newKey = substr($key, $prefixLength);
                $params[$newKey] = $value;
            }
        }

        $params['CACHE_TIME'] = $this->arParams['CACHE_TIME'];
        $params['CACHE_TYPE'] = $this->arParams['CACHE_TYPE'] ?? 'A';

        return $params;
    }

    /**
     * Мапит данные из инфоблока в параметры компонентов
     */
    protected function mapIblockDataToParams($data)
    {
        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
        if (empty($data)) {
            return;
        }

        $fields = $data['FIELDS'];
        $props = $data['PROPS'];

        // === INTRO маппинг ===
        if (!empty($fields['NAME'])) {
            $this->arResult['INTRO_PARAMS']['SUBTITLE'] = $fields['NAME'];
        }
        if (!empty($props['LOGO'])) {
            $this->arResult['INTRO_PARAMS']['CARD_IMAGE'] = \CFile::GetPath($props['LOGO']['VALUE']);
        }

        // Мапим теги из свойств
        for ($i = 1; $i <= 5; $i++) {
            if (!empty($props['HASHTAGS']['VALUE'][$i - 1])) {
                $this->arResult['INTRO_PARAMS']['TAG_' . $i] = $props['HASHTAGS']['VALUE'][$i - 1];
            }
        }

        // Мапим WORKPLACES, REALIZATION_DAYS, LICENSE
        if (!empty($props['WORKPLACES_COUNT']['VALUE'])) {
            $this->arResult['INTRO_PARAMS']['WORKPLACES'] = $props['WORKPLACES_COUNT']['VALUE'];
        }
        if (!empty($props['REALIZATION_DAYS']['VALUE'])) {
            $this->arResult['INTRO_PARAMS']['PROJECT_DAYS'] = $props['REALIZATION_DAYS']['VALUE'];
        }
        if (!empty($props['LICENSE']['VALUE'])) {
            $this->arResult['INTRO_PARAMS']['LICENSE'] = $props['LICENSE']['VALUE'];
        }

        // === DETAILS маппинг ===
        if (!empty($props['BG_IMAGE']['VALUE'])) {
            $this->arResult['DETAILS_PARAMS']['BG_IMAGE'] = \CFile::GetPath($props['BG_IMAGE']['VALUE']);
        }
        if (!empty($props['BLOCK_1_TEXT']['VALUE']['TEXT'])) {
            $this->arResult['DETAILS_PARAMS']['BLOCK_1_TEXT'] = $props['BLOCK_1_TEXT']['VALUE']['TEXT'];
        } elseif (!empty($props['BLOCK_1_TEXT']['VALUE'])) {
            $this->arResult['DETAILS_PARAMS']['BLOCK_1_TEXT'] = $props['BLOCK_1_TEXT']['VALUE'];
        }
        if (!empty($fields['NAME'])) {
            $this->arResult['DETAILS_PARAMS']['BLOCK_1_CLIENT_NAME'] = $fields['NAME'];
        }
        if (!empty($props['BLOCK_1_CLIENT_LINK']['VALUE'])) {
            $this->arResult['DETAILS_PARAMS']['BLOCK_1_CLIENT_LINK'] = $props['BLOCK_1_CLIENT_LINK']['VALUE'];
        }
        if (!empty($props['BLOCK_2_TEXT']['VALUE']['TEXT'])) {
            $this->arResult['DETAILS_PARAMS']['BLOCK_2_TEXT'] = $props['BLOCK_2_TEXT']['VALUE']['TEXT'];
        } elseif (!empty($props['BLOCK_2_TEXT']['VALUE'])) {
            $this->arResult['DETAILS_PARAMS']['BLOCK_2_TEXT'] = $props['BLOCK_2_TEXT']['VALUE'];
        }

        // === TARGETS маппинг ===
        $this->arResult['TARGETS_PARAMS']['TARGET_' . 1 . '_TITLE'] = "Внедрить";
        $this->arResult['TARGETS_PARAMS']['TARGET_' . 2 . '_TITLE'] = "Настроить";
        $this->arResult['TARGETS_PARAMS']['TARGET_' . 3 . '_TITLE'] = "Оптимизировать";
        for ($i = 1; $i <= 3; $i++) {
            // if (!empty($props['TARGET_' . $i . '_TITLE']['VALUE'])) {
            //     $this->arResult['TARGETS_PARAMS']['TARGET_' . $i . '_TITLE'] = $props['TARGET_' . $i . '_TITLE']['VALUE'];
            // }
            if (!empty($props['TARGET_' . $i . '_TEXT']['VALUE']['TEXT'])) {
                $this->arResult['TARGETS_PARAMS']['TARGET_' . $i . '_TEXT'] = $props['TARGET_' . $i . '_TEXT']['VALUE']['TEXT'];
            }
        }

        // === ROADMAP маппинг ===
        // if (!empty($props['TABLE_HEADER_YEAR']['VALUE'])) {
        //     $this->arResult['ROADMAP_PARAMS']['TABLE_HEADER_YEAR'] = $props['TABLE_HEADER_YEAR']['VALUE'];
        // }
        $this->arResult['ROADMAP_PARAMS']['TABLE_HEADER_YEAR'] = "2024 год";
        $this->arResult['ROADMAP_PARAMS']['MARK_TEXT'] = "Сроки и этапы работы над проектом";
        $this->arResult['ROADMAP_PARAMS']['TITLE'] = "Дорожная карта";

        $this->arResult['ROADMAP_PARAMS']['STEP_' . 1 . '_NAME'] = "Предпроектная аналитика и выбор лицензии Битрикс24";
        $this->arResult['ROADMAP_PARAMS']['STEP_' . 2 . '_NAME'] = "Формирование технического задания";
        $this->arResult['ROADMAP_PARAMS']['STEP_' . 3 . '_NAME'] = "Настройка системы";
        $this->arResult['ROADMAP_PARAMS']['STEP_' . 4 . '_NAME'] = "Обучение";
        $this->arResult['ROADMAP_PARAMS']['STEP_' . 5 . '_NAME'] = "Сопровождение и доработки";

         $this->arResult['ROADMAP_PARAMS']['STEP_' . 1 . '_DURATION'] = "5 дней";
        $this->arResult['ROADMAP_PARAMS']['STEP_' . 2 . '_DURATION'] = "3 дня";
        $this->arResult['ROADMAP_PARAMS']['STEP_' . 3 . '_DURATION'] = "14 дней";
        $this->arResult['ROADMAP_PARAMS']['STEP_' . 4 . '_DURATION'] = "7 дней";
        $this->arResult['ROADMAP_PARAMS']['STEP_' . 5 . '_DURATION'] = "н.в";
        // for ($i = 1; $i <= 7; $i++) {
        //     if (!empty($props['STEP_' . $i . '_NAME']['VALUE'])) {
        //         $this->arResult['ROADMAP_PARAMS']['STEP_' . $i . '_NAME'] = $props['STEP_' . $i . '_NAME']['VALUE'];
        //     }
        //     if (!empty($props['STEP_' . $i . '_TETRIS']['VALUE'])) {
        //         $this->arResult['ROADMAP_PARAMS']['STEP_' . $i . '_TETRIS'] = $props['STEP_' . $i . '_TETRIS']['VALUE'];
        //     }
        //     if (!empty($props['STEP_' . $i . '_DURATION']['VALUE'])) {
        //         $this->arResult['ROADMAP_PARAMS']['STEP_' . $i . '_DURATION'] = $props['STEP_' . $i . '_DURATION']['VALUE'];
        //     }
        // }

        // === STAGES маппинг ===
        foreach ($props['STAGES_INTEGRATION']['VALUE'] as $index => $stageTitle) {
            $this->arResult['STAGES_PARAMS']['STAGE_' . ($index + 1) . '_TITLE'] = $stageTitle['SUB_VALUES']['TITLE_BLOCK']['VALUE'];
            $this->arResult['STAGES_PARAMS']['STAGE_' . ($index + 1) . '_TEXT'] = $stageTitle['SUB_VALUES']['DESCRIPTION_BLOCK']['VALUE']['TEXT'] ?? $stageTitle['SUB_VALUES']['DESCRIPTION_BLOCK']['VALUE'];
            $this->arResult['STAGES_PARAMS']['STAGE_' . ($index + 1) . '_IMAGE'] = CFile::GetPath($stageTitle['SUB_VALUES']['IMAGE_BLOCK']['VALUE']);
        }

        // === RESULTS маппинг ===
        for ($cardNum = 1; $cardNum <= 2; $cardNum++) {

            $this->arResult['RESULTS_PARAMS']['CARD_' . 1 . '_IMAGE'] = "/local/templates/leadspace/assets/images/results/green.webp";
            $this->arResult['RESULTS_PARAMS']['CARD_' . 1 . '_TITLE'] = "Рост показателей";
            $this->arResult['RESULTS_PARAMS']['CARD_' . 2 . '_IMAGE'] = "/local/templates/leadspace/assets/images/results/red.webp";
            $this->arResult['RESULTS_PARAMS']['CARD_' . 2 . '_TITLE'] = "Снижение издержек";

            if ($cardNum == 1) {
                foreach ($props['GROWTH_INDICATORS']['VALUE'] as $index => $indicator) {
                    $this->arResult['RESULTS_PARAMS']['CARD_' . $cardNum . '_ITEM_' . ($index + 1) . '_NUMBER'] = $indicator['SUB_VALUES']['PROCENT_GROWTH']['VALUE'];
                    $this->arResult['RESULTS_PARAMS']['CARD_' . $cardNum . '_ITEM_' . ($index + 1) . '_PCT'] = "%";
                    $this->arResult['RESULTS_PARAMS']['CARD_' . $cardNum . '_ITEM_' . ($index + 1) . '_TEXT'] = $indicator['SUB_VALUES']['INDICATOR_GROWTH']['VALUE'];
                    $this->arResult['RESULTS_PARAMS']['CARD_' . $cardNum . '_ITEM_' . ($index + 1) . '_CAPTION'] = $indicator['SUB_VALUES']['DESCRIPTION_GROWTH']['VALUE']['TEXT'];
                }
            } else {
                foreach ($props['REDACTION_COSTS']['VALUE'] as $index => $indicator) {
                    $this->arResult['RESULTS_PARAMS']['CARD_' . $cardNum . '_ITEM_' . ($index + 1) . '_NUMBER'] = $indicator['SUB_VALUES']['PROCENT_REDACTION']['VALUE'];
                    $this->arResult['RESULTS_PARAMS']['CARD_' . $cardNum . '_ITEM_' . ($index + 1) . '_PCT'] = "%";
                    $this->arResult['RESULTS_PARAMS']['CARD_' . $cardNum . '_ITEM_' . ($index + 1) . '_TEXT'] = $indicator['SUB_VALUES']['INDICATOR_REDACTION']['VALUE'];
                    $this->arResult['RESULTS_PARAMS']['CARD_' . $cardNum . '_ITEM_' . ($index + 1) . '_CAPTION'] = $indicator['SUB_VALUES']['DESCRIPTION_REDACTION']['VALUE']['TEXT'];
                }
            }
        }

        // === FEATURES маппинг ===
        if (!empty($props['TAGLINE_ROW1']['VALUE'])) {
            $this->arResult['FEATURES_PARAMS']['TAGLINE_ROW1'] = $props['TAGLINE_ROW1']['VALUE']['TEXT'];
        }
        if (!empty($props['TAGLINE_ROW2']['VALUE'])) {
            $this->arResult['FEATURES_PARAMS']['TAGLINE_ROW2'] = $props['TAGLINE_ROW2']['VALUE']['TEXT'];
        }
        if (!empty($props['FEATURES_LIST']['VALUE']['TEXT'])) {
            $this->arResult['FEATURES_PARAMS']['FEATURES_LIST'] = $props['FEATURES_LIST']['VALUE']['TEXT'];
        } elseif (!empty($props['FEATURES_LIST']['VALUE'])) {
            $this->arResult['FEATURES_PARAMS']['FEATURES_LIST'] = $props['FEATURES_LIST']['VALUE'];
        }
    }

    public function executeComponent()
    {
        if (!$this->checkModules())
            return;

        $this->arResult['BLOCKS_ORDER'] = array_map('trim', explode(',', $this->arParams['BLOCKS_ORDER']));

        // Получаем параметры для каждого блока
        $this->arResult['INTRO_PARAMS'] = $this->getBlockParams('INTRO');
        $this->arResult['DETAILS_PARAMS'] = $this->getBlockParams('DETAILS');
        $this->arResult['TARGETS_PARAMS'] = $this->getBlockParams('TARGETS');
        $this->arResult['ROADMAP_PARAMS'] = $this->getBlockParams('ROADMAP');
        $this->arResult['STAGES_PARAMS'] = $this->getBlockParams('STAGES');
        $this->arResult['RESULTS_PARAMS'] = $this->getBlockParams('RESULTS');
        $this->arResult['FEATURES_PARAMS'] = $this->getBlockParams('FEATURES');

        $this->arResult['CSS_CLASS'] = $this->arParams['CSS_CLASS'] ?? '';

        // Получаем данные из инфоблока и мапим их
        $iblockData = $this->getIblockData();
        $this->mapIblockDataToParams($iblockData);

        // Шаблоны компонентов
        $this->arResult['TEMPLATES'] = [
            'intro' => $this->arParams['INTRO_TEMPLATE'] ?? '.default',
            'details' => $this->arParams['DETAILS_TEMPLATE'] ?? '.default',
            'targets' => $this->arParams['TARGETS_TEMPLATE'] ?? '.default',
            'roadmap' => $this->arParams['ROADMAP_TEMPLATE'] ?? '.default',
            'stages' => $this->arParams['STAGES_TEMPLATE'] ?? '.default',
            'results' => $this->arParams['RESULTS_TEMPLATE'] ?? '.default',
            'features' => $this->arParams['FEATURES_TEMPLATE'] ?? '.default',
        ];

        $this->includeComponentTemplate();
    }
}
