<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

// Подключаем модуль инфоблоков для формирования списка
$arIBlocks = [];
if (\Bitrix\Main\Loader::includeModule('iblock')) {
    $dbIBlock = \CIBlock::GetList(["SORT" => "ASC"], ["ACTIVE" => "Y"]);
    while ($arIBlock = $dbIBlock->Fetch()) {
        $arIBlocks[$arIBlock["ID"]] = "[" . $arIBlock["ID"] . "] " . $arIBlock["NAME"];
    }
}

$arComponentParameters = [
    "PARAMETERS" => [
        // === ОСНОВНЫЕ НАСТРОЙКИ ===
        "MARK_TEXT" => [
            "PARENT" => "BASE",
            "NAME" => "Текст метки (верхний текст)",
            "TYPE" => "STRING",
            "DEFAULT" => "что внутри",
        ],
        "TAGLINE_ROW_1" => [
            "PARENT" => "BASE",
            "NAME" => "Тэглайн - строка 1",
            "TYPE" => "STRING",
            "DEFAULT" => "Вместе с CRM-решением вы получите",
        ],
        "TAGLINE_ROW_2" => [
            "PARENT" => "BASE",
            "NAME" => "Тэглайн - строка 2",
            "TYPE" => "STRING",
            "DEFAULT" => "полный набор инструментов Битрикс24 для работы",
        ],
        "TITLE" => [
            "PARENT" => "BASE",
            "NAME" => "Заголовок секции",
            "TYPE" => "STRING",
            "DEFAULT" => "Что внутри готового решения?",
        ],
        
        // === НАСТРОЙКИ ИНФОБЛОКА ===
        "IBLOCK_ID" => [
            "PARENT" => "DATA_SOURCE",
            "NAME" => "Инфоблок основного элемента",
            "TYPE" => "LIST",
            "VALUES" => $arIBlocks,
            "ADDITIONAL_VALUES" => "Y",
            "DEFAULT" => "",
            "REFRESH" => "Y",
            "DESCRIPTION" => "Инфоблок, в котором находится основной элемент (решение)"
        ],
        "ELEMENT_ID" => [
            "PARENT" => "DATA_SOURCE",
            "NAME" => "ID основного элемента",
            "TYPE" => "STRING",
            "DEFAULT" => "",
            "DESCRIPTION" => "ID элемента со свойством SLIDER_TOOLS_CARDS"
        ],
        "PROPERTY_CODE" => [
            "PARENT" => "DATA_SOURCE",
            "NAME" => "Код свойства привязки",
            "TYPE" => "STRING",
            "DEFAULT" => "SLIDER_TOOLS_CARDS",
            "DESCRIPTION" => "Код ОДИНОЧНОГО свойства 'Привязка к элементу', которое содержит ID контейнера инструментов"
        ],
        
        // === ИНФОРМАЦИЯ О СТРУКТУРЕ ===
        "INFO_STRUCTURE" => [
            "PARENT" => "DATA_SOURCE",
            "NAME" => "📋 Структура данных",
            "TYPE" => "CUSTOM",
            "JS_FILE" => "",
            "JS_EVENT" => "",
            "JS_DATA" => "",
            "DEFAULT" => "
<div style='padding:10px; background:#f0f0f0; border-radius:5px; margin-top:10px;'>
    <strong>Связанный элемент должен содержать:</strong><br>
    • <b>TITLE</b> (string) - общий заголовок инструмента<br>
    • <b>BLOCK</b> (simai_complex, множественное) - блоки-слайды<br>
    <br>
    <strong>Каждый BLOCK содержит SUB_VALUES:</strong><br>
    • <b>DESCRIPTION</b> - описание слайда<br>
    • <b>IMAGE</b> - изображение(я) слайда<br>
    <br>
    <em>Все слайды одного инструмента имеют общий TITLE</em>
</div>",
        ],
        
        // === FALLBACK: Инструмент 1 ===
        "TOOL_1_NAME" => [
            "PARENT" => "FALLBACK",
            "NAME" => "Название инструмента",
            "TYPE" => "STRING",
            "DEFAULT" => "ЧАТ И ВИДЕОЗВОНКИ",
            "DESCRIPTION" => "Используется если данные не получены из инфоблока"
        ],
        "TOOL_1_DESC_1" => [
            "PARENT" => "FALLBACK",
            "NAME" => "Описание 1",
            "TYPE" => "TEXT",
            "DEFAULT" => "",
        ],
        "TOOL_1_DESC_2" => [
            "PARENT" => "FALLBACK",
            "NAME" => "Описание 2",
            "TYPE" => "TEXT",
            "DEFAULT" => "",
        ],
        "TOOL_1_DESC_3" => [
            "PARENT" => "FALLBACK",
            "NAME" => "Описание 3",
            "TYPE" => "TEXT",
            "DEFAULT" => "",
        ],
        "TOOL_1_DESC_4" => [
            "PARENT" => "FALLBACK",
            "NAME" => "Описание 4",
            "TYPE" => "TEXT",
            "DEFAULT" => "",
        ],
        "TOOL_1_DESC_5" => [
            "PARENT" => "FALLBACK",
            "NAME" => "Описание 5",
            "TYPE" => "TEXT",
            "DEFAULT" => "",
        ],
        "TOOL_1_IMAGE_1" => [
            "PARENT" => "FALLBACK",
            "NAME" => "Картинка 1 (URL)",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        "TOOL_1_IMAGE_2" => [
            "PARENT" => "FALLBACK",
            "NAME" => "Картинка 2 (URL)",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        "TOOL_1_IMAGE_3" => [
            "PARENT" => "FALLBACK",
            "NAME" => "Картинка 3 (URL)",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        "TOOL_1_IMAGE_4" => [
            "PARENT" => "FALLBACK",
            "NAME" => "Картинка 4 (URL)",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        "TOOL_1_IMAGE_5" => [
            "PARENT" => "FALLBACK",
            "NAME" => "Картинка 5 (URL)",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        
        // === FALLBACK: Инструмент 2 ===
        "TOOL_2_NAME" => [
            "PARENT" => "FALLBACK",
            "NAME" => "Название инструмента",
            "TYPE" => "STRING",
            "DEFAULT" => "ЗАДАЧИ И ПРОЕКТЫ",
        ],
        "TOOL_2_DESC_1" => [
            "PARENT" => "FALLBACK",
            "NAME" => "Описание 1",
            "TYPE" => "TEXT",
            "DEFAULT" => "",
        ],
        "TOOL_2_DESC_2" => [
            "PARENT" => "FALLBACK",
            "NAME" => "Описание 2",
            "TYPE" => "TEXT",
            "DEFAULT" => "",
        ],
        "TOOL_2_DESC_3" => [
            "PARENT" => "FALLBACK",
            "NAME" => "Описание 3",
            "TYPE" => "TEXT",
            "DEFAULT" => "",
        ],
        "TOOL_2_DESC_4" => [
            "PARENT" => "FALLBACK",
            "NAME" => "Описание 4",
            "TYPE" => "TEXT",
            "DEFAULT" => "",
        ],
        "TOOL_2_DESC_5" => [
            "PARENT" => "FALLBACK",
            "NAME" => "Описание 5",
            "TYPE" => "TEXT",
            "DEFAULT" => "",
        ],
        "TOOL_2_IMAGE_1" => [
            "PARENT" => "FALLBACK",
            "NAME" => "Картинка 1 (URL)",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        "TOOL_2_IMAGE_2" => [
            "PARENT" => "FALLBACK",
            "NAME" => "Картинка 2 (URL)",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        "TOOL_2_IMAGE_3" => [
            "PARENT" => "FALLBACK",
            "NAME" => "Картинка 3 (URL)",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        "TOOL_2_IMAGE_4" => [
            "PARENT" => "FALLBACK",
            "NAME" => "Картинка 4 (URL)",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        "TOOL_2_IMAGE_5" => [
            "PARENT" => "FALLBACK",
            "NAME" => "Картинка 5 (URL)",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        
        "CACHE_TIME" => [
            "DEFAULT" => 3600,
        ],
    ],
    "GROUPS" => [
        "DATA_SOURCE" => [
            "NAME" => "Источник данных (Инфоблок)",
            "SORT" => 100
        ],
        "FALLBACK" => [
            "NAME" => "Резервные значения (Fallback)",
            "SORT" => 900
        ],
    ]
];
?>