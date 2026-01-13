<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// Подключаем модуль инфоблоков
if (!\Bitrix\Main\Loader::includeModule('iblock')) {
    return;
}

// Получаем список активных инфоблоков
$arIBlocks = [];
$dbIBlock = \CIBlock::GetList(["SORT" => "ASC"], ["ACTIVE" => "Y"]);
while ($arIBlock = $dbIBlock->Fetch()) {
    $arIBlocks[$arIBlock["ID"]] = "[" . $arIBlock["ID"] . "] " . $arIBlock["NAME"];
}

$arComponentParameters = [
    "GROUPS" => [
        "DATA_SOURCE" => ["NAME" => "Источник данных", "SORT" => 10],
        "DISPLAY" => ["NAME" => "Отображение блоков", "SORT" => 20],
        "TEMPLATES" => ["NAME" => "Шаблоны компонентов", "SORT" => 30],
        "INTRO" => ["NAME" => "Case Intro - Основное"],
        "DETAILS" => ["NAME" => "Case Details"],
        "TARGETS" => ["NAME" => "Targets List"],
        "ROADMAP" => ["NAME" => "Case Roadmap"],
        "STAGES" => ["NAME" => "Case Stages"],
        "RESULTS" => ["NAME" => "Results"],
        "FEATURES" => ["NAME" => "Features"],
        "ADDITIONAL" => ["NAME" => "Дополнительно"],
    ],

    "PARAMETERS" => [
        // === ИСТОЧНИК ДАННЫХ ===
        "IBLOCK_ID" => [
            "PARENT" => "DATA_SOURCE",
            "NAME" => "Инфоблок кейсов",
            "TYPE" => "LIST",
            "VALUES" => $arIBlocks,
            "DEFAULT" => "6",
            "REFRESH" => "Y",
        ],
        "ELEMENT_ID" => [
            "PARENT" => "DATA_SOURCE",
            "NAME" => "ID элемента кейса",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],

        // === ОТОБРАЖЕНИЕ ===
        "BLOCKS_ORDER" => [
            "PARENT" => "DISPLAY",
            "NAME" => "Порядок блоков",
            "TYPE" => "STRING",
            "DEFAULT" => "intro,details,targets,roadmap,stages,results,features",
            "COLS" => 60,
        ],

        // === ШАБЛОНЫ ===
        "INTRO_TEMPLATE" => [
            "PARENT" => "TEMPLATES",
            "NAME" => "Шаблон для Case Intro",
            "TYPE" => "STRING",
            "DEFAULT" => ".default",
        ],
        "DETAILS_TEMPLATE" => [
            "PARENT" => "TEMPLATES",
            "NAME" => "Шаблон для Case Details",
            "TYPE" => "STRING",
            "DEFAULT" => ".default",
        ],
        "TARGETS_TEMPLATE" => [
            "PARENT" => "TEMPLATES",
            "NAME" => "Шаблон для Targets List",
            "TYPE" => "STRING",
            "DEFAULT" => ".default",
        ],
        "ROADMAP_TEMPLATE" => [
            "PARENT" => "TEMPLATES",
            "NAME" => "Шаблон для Roadmap",
            "TYPE" => "STRING",
            "DEFAULT" => ".default",
        ],
        "STAGES_TEMPLATE" => [
            "PARENT" => "TEMPLATES",
            "NAME" => "Шаблон для Stages",
            "TYPE" => "STRING",
            "DEFAULT" => ".default",
        ],
        "RESULTS_TEMPLATE" => [
            "PARENT" => "TEMPLATES",
            "NAME" => "Шаблон для Results",
            "TYPE" => "STRING",
            "DEFAULT" => ".default",
        ],
        "FEATURES_TEMPLATE" => [
            "PARENT" => "TEMPLATES",
            "NAME" => "Шаблон для Features",
            "TYPE" => "STRING",
            "DEFAULT" => ".default",
        ],

        // === INTRO ПАРАМЕТРЫ ===
        "INTRO_TITLE_WORD" => [
            "PARENT" => "INTRO",
            "NAME" => "Слово заголовка (КЕЙС)",
            "TYPE" => "STRING",
            "DEFAULT" => "КЕЙС",
        ],
        "INTRO_SUBTITLE" => [
            "PARENT" => "INTRO",
            "NAME" => "Подзаголовок",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        "INTRO_CARD_MARK" => [
            "PARENT" => "INTRO",
            "NAME" => "Метка карточки",
            "TYPE" => "STRING",
            "DEFAULT" => "Внедрение и настройка Битрикс24",
        ],
        "INTRO_BUTTON_TEXT" => [
            "PARENT" => "INTRO",
            "NAME" => "Текст кнопки",
            "TYPE" => "STRING",
            "DEFAULT" => "заказать внедрение",
        ],
        "INTRO_BUTTON_LINK" => [
            "PARENT" => "INTRO",
            "NAME" => "Ссылка кнопки",
            "TYPE" => "STRING",
            "DEFAULT" => "#modal-feedback",
        ],

        // === DETAILS ПАРАМЕТРЫ ===
        "DETAILS_MARK_TEXT" => [
            "PARENT" => "DETAILS",
            "NAME" => "Текст метки",
            "TYPE" => "STRING",
            "DEFAULT" => "компания",
        ],
        "DETAILS_BLOCK_1_TITLE" => [
            "PARENT" => "DETAILS",
            "NAME" => "Блок 1 - Заголовок",
            "TYPE" => "STRING",
            "DEFAULT" => "О клиенте",
        ],
        "DETAILS_BLOCK_2_TITLE" => [
            "PARENT" => "DETAILS",
            "NAME" => "Блок 2 - Заголовок",
            "TYPE" => "STRING",
            "DEFAULT" => "задачи клиента",
        ],

        // === TARGETS ПАРАМЕТРЫ ===
        "TARGETS_TITLE" => [
            "PARENT" => "TARGETS",
            "NAME" => "Заголовок",
            "TYPE" => "STRING",
            "DEFAULT" => "Цели проекта",
        ],

        // === ROADMAP ПАРАМЕТРЫ ===
        "ROADMAP_MARK_TEXT" => [
            "PARENT" => "ROADMAP",
            "NAME" => "Текст метки",
            "TYPE" => "STRING",
            "DEFAULT" => "Сроки и этапы работы над проектом",
        ],
        "ROADMAP_TITLE" => [
            "PARENT" => "ROADMAP",
            "NAME" => "Заголовок",
            "TYPE" => "STRING",
            "DEFAULT" => "Дорожная карта",
        ],

        // === STAGES ПАРАМЕТРЫ ===
        "STAGES_TITLE" => [
            "PARENT" => "STAGES",
            "NAME" => "Заголовок",
            "TYPE" => "STRING",
            "DEFAULT" => "Этапы внедрения",
        ],

        // === RESULTS ПАРАМЕТРЫ ===
        "RESULTS_TITLE" => [
            "PARENT" => "RESULTS",
            "NAME" => "Заголовок",
            "TYPE" => "STRING",
            "DEFAULT" => "Результаты и выводы",
        ],
        "RESULTS_MARK" => [
            "PARENT" => "RESULTS",
            "NAME" => "Метка",
            "TYPE" => "STRING",
            "DEFAULT" => "Статистика",
        ],

        // === FEATURES ПАРАМЕТРЫ ===
        "FEATURES_TITLE" => [
            "PARENT" => "FEATURES",
            "NAME" => "Заголовок",
            "TYPE" => "STRING",
            "DEFAULT" => "Особенности проекта",
        ],

        // === ДОПОЛНИТЕЛЬНО ===
        "CSS_CLASS" => [
            "PARENT" => "ADDITIONAL",
            "NAME" => "CSS класс обертки",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],

        "CACHE_TIME" => [
            "DEFAULT" => 3600,
        ],
    ],
];
?>