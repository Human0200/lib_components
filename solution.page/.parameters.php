<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

// Подключаем модуль инфоблоков для формирования списка
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
    // === ОПИСАНИЕ ГРУПП (ВИЗУАЛЬНЫЕ СЕКЦИИ) ===
    "GROUPS" => [
        "DATA_SOURCE" => [
            "NAME" => "Источник данных (Инфоблок)",
            "SORT" => 10
        ],
        "DISPLAY" => [
            "NAME" => "Отображение блоков",
            "SORT" => 20
        ],
        "TEMPLATES" => [
            "NAME" => "Шаблоны компонентов",
            "SORT" => 30
        ],
        "TOPBAR" => ["NAME" => "Topbar - Основное"],
        "TOPBAR_PRICE" => ["NAME" => "Topbar - Цена"],
        "TOPBAR_CARDS" => ["NAME" => "Topbar - Карточки"],
        "TOPBAR_BUTTONS" => ["NAME" => "Topbar - Кнопки"],
        "WHOM" => ["NAME" => "Whom Cards - Основное"],
        "WHOM_CARD_1" => ["NAME" => "Whom Cards - Карточка 1"],
        "WHOM_CARD_2" => ["NAME" => "Whom Cards - Карточка 2"],
        "WHOM_CARD_3" => ["NAME" => "Whom Cards - Карточка 3"],
        "TOOLS" => ["NAME" => "Tools - Основное"],
        "TOOLS_1" => ["NAME" => "Tools - Инструмент 1"],
        "TOOLS_2" => ["NAME" => "Tools - Инструмент 2"],
        "ADDITIONAL" => ["NAME" => "Дополнительно"],
    ],

    "PARAMETERS" => [
        // === НАСТРОЙКИ ИСТОЧНИКА ДАННЫХ (НОВОЕ) ===
        "IBLOCK_ID" => [
            "PARENT" => "DATA_SOURCE",
            "NAME" => "Выберите инфоблок",
            "TYPE" => "LIST",
            "VALUES" => $arIBlocks,
            "ADDITIONAL_VALUES" => "Y",
            "REFRESH" => "Y",
        ],
        "ELEMENT_ID" => [
            "PARENT" => "DATA_SOURCE",
            "NAME" => "ID элемента (для подстановки данных)",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],

        // === НАСТРОЙКИ ОТОБРАЖЕНИЯ БЛОКОВ ===
        "BLOCKS_ORDER" => [
            "PARENT" => "DISPLAY",
            "NAME" => "Порядок блоков (через запятую: topbar,whom,tools)",
            "TYPE" => "STRING",
            "DEFAULT" => "topbar,whom,tools",
            "COLS" => 50,
        ],
        
        // === ШАБЛОНЫ КОМПОНЕНТОВ ===
        "TOPBAR_TEMPLATE" => [
            "PARENT" => "TEMPLATES",
            "NAME" => "Шаблон для Topbar",
            "TYPE" => "STRING",
            "DEFAULT" => ".default",
        ],
        "WHOM_CARDS_TEMPLATE" => [
            "PARENT" => "TEMPLATES",
            "NAME" => "Шаблон для Whom Cards",
            "TYPE" => "STRING",
            "DEFAULT" => ".default",
        ],
        "TOOLS_TEMPLATE" => [
            "PARENT" => "TEMPLATES",
            "NAME" => "Шаблон для Tools",
            "TYPE" => "STRING",
            "DEFAULT" => ".default",
        ],
        
        // === ПАРАМЕТРЫ TOPBAR ===
        "TOPBAR_IMAGE" => [
            "PARENT" => "TOPBAR",
            "NAME" => "Изображение",
            "TYPE" => "STRING",
            "DEFAULT" => "/assets/images/topbar/01.webp",
        ],
        "TOPBAR_TAGLINE" => [
            "PARENT" => "TOPBAR",
            "NAME" => "Слоган",
            "TYPE" => "STRING",
            "DEFAULT" => "Банкротство физ.лиц это ответственная сфера",
        ],
        "TOPBAR_TITLE" => [
            "PARENT" => "TOPBAR",
            "NAME" => "Заголовок",
            "TYPE" => "STRING",
            "DEFAULT" => "Банкротство физических лиц",
        ],
        "TOPBAR_SUBTITLE" => [
            "PARENT" => "TOPBAR",
            "NAME" => "Подзаголовок",
            "TYPE" => "STRING",
            "DEFAULT" => "Полностью готовое решение для ниши",
        ],
        "TOPBAR_PRICE_OLD" => [
            "PARENT" => "TOPBAR_PRICE",
            "NAME" => "Старая цена",
            "TYPE" => "STRING",
            "DEFAULT" => "35 000 р",
        ],
        "TOPBAR_PRICE_NEW" => [
            "PARENT" => "TOPBAR_PRICE",
            "NAME" => "Новая цена",
            "TYPE" => "STRING",
            "DEFAULT" => "0",
        ],
        "TOPBAR_PRICE_CURRENCY" => [
            "PARENT" => "TOPBAR_PRICE",
            "NAME" => "Валюта",
            "TYPE" => "STRING",
            "DEFAULT" => "рублей",
        ],
        "TOPBAR_PRICE_NOTE" => [
            "PARENT" => "TOPBAR_PRICE",
            "NAME" => "Примечание к цене",
            "TYPE" => "TEXT",
            "DEFAULT" => "*входит в счет приобретения годовой лицензии через нас",
        ],
        
        // Карточки Topbar
        "TOPBAR_CARD_1_TEXT" => [
            "PARENT" => "TOPBAR_CARDS",
            "NAME" => "Карточка 1 - Текст",
            "TYPE" => "TEXT",
            "DEFAULT" => "90% клиентов получили одобрение",
        ],
        "TOPBAR_CARD_1_NUMBER" => [
            "PARENT" => "TOPBAR_CARDS",
            "NAME" => "Карточка 1 - Число",
            "TYPE" => "STRING",
            "DEFAULT" => "20+",
        ],
        "TOPBAR_CARD_1_LABEL" => [
            "PARENT" => "TOPBAR_CARDS",
            "NAME" => "Карточка 1 - Подпись",
            "TYPE" => "STRING",
            "DEFAULT" => "установок",
        ],
        "TOPBAR_CARD_2_TEXT" => [
            "PARENT" => "TOPBAR_CARDS",
            "NAME" => "Карточка 2 - Текст",
            "TYPE" => "TEXT",
            "DEFAULT" => "Быстрый старт работы",
        ],
        "TOPBAR_CARD_2_PREFIX" => [
            "PARENT" => "TOPBAR_CARDS",
            "NAME" => "Карточка 2 - Префикс",
            "TYPE" => "STRING",
            "DEFAULT" => "до",
        ],
        "TOPBAR_CARD_2_NUMBER" => [
            "PARENT" => "TOPBAR_CARDS",
            "NAME" => "Карточка 2 - Число",
            "TYPE" => "STRING",
            "DEFAULT" => "7",
        ],
        "TOPBAR_CARD_2_LABEL" => [
            "PARENT" => "TOPBAR_CARDS",
            "NAME" => "Карточка 2 - Подпись",
            "TYPE" => "STRING",
            "DEFAULT" => "дней",
        ],
        "TOPBAR_CARD_3_TEXT" => [
            "PARENT" => "TOPBAR_CARDS",
            "NAME" => "Карточка 3 - Текст",
            "TYPE" => "TEXT",
            "DEFAULT" => "Профессиональная поддержка",
        ],
        "TOPBAR_CARD_3_PREFIX" => [
            "PARENT" => "TOPBAR_CARDS",
            "NAME" => "Карточка 3 - Префикс",
            "TYPE" => "STRING",
            "DEFAULT" => "от",
        ],
        "TOPBAR_CARD_3_NUMBER" => [
            "PARENT" => "TOPBAR_CARDS",
            "NAME" => "Карточка 3 - Число",
            "TYPE" => "STRING",
            "DEFAULT" => "150",
        ],
        "TOPBAR_CARD_3_LABEL" => [
            "PARENT" => "TOPBAR_CARDS",
            "NAME" => "Карточка 3 - Подпись",
            "TYPE" => "STRING",
            "DEFAULT" => "т.р",
        ],
        
        // Кнопки Topbar
        "TOPBAR_BUTTON_1_TEXT" => [
            "PARENT" => "TOPBAR_BUTTONS",
            "NAME" => "Кнопка 1 - Текст",
            "TYPE" => "STRING",
            "DEFAULT" => "Заказать внедрение",
        ],
        "TOPBAR_BUTTON_1_LINK" => [
            "PARENT" => "TOPBAR_BUTTONS",
            "NAME" => "Кнопка 1 - Ссылка",
            "TYPE" => "STRING",
            "DEFAULT" => "#",
        ],
        "TOPBAR_BUTTON_1_CURSOR" => [
            "PARENT" => "TOPBAR_BUTTONS",
            "NAME" => "Кнопка 1 - Текст курсора",
            "TYPE" => "STRING",
            "DEFAULT" => "Перейти",
        ],
        "TOPBAR_BUTTON_2_TEXT" => [
            "PARENT" => "TOPBAR_BUTTONS",
            "NAME" => "Кнопка 2 - Текст",
            "TYPE" => "STRING",
            "DEFAULT" => "Попробовать 7 дней бесплатно",
        ],
        "TOPBAR_BUTTON_2_LINK" => [
            "PARENT" => "TOPBAR_BUTTONS",
            "NAME" => "Кнопка 2 - Ссылка",
            "TYPE" => "STRING",
            "DEFAULT" => "#",
        ],
        
        // === ПАРАМЕТРЫ WHOM CARDS ===
        "WHOM_MARK" => [
            "PARENT" => "WHOM",
            "NAME" => "Метка",
            "TYPE" => "STRING",
            "DEFAULT" => "для кого",
        ],
        "WHOM_TITLE" => [
            "PARENT" => "WHOM",
            "NAME" => "Заголовок",
            "TYPE" => "STRING",
            "DEFAULT" => "Кому подходит данное решение?",
        ],
        "WHOM_BUTTON_TEXT" => [
            "PARENT" => "WHOM",
            "NAME" => "Текст кнопки",
            "TYPE" => "STRING",
            "DEFAULT" => "Попробовать 7 дней бесплатно",
        ],
        "WHOM_BUTTON_LINK" => [
            "PARENT" => "WHOM",
            "NAME" => "Ссылка кнопки",
            "TYPE" => "STRING",
            "DEFAULT" => "#",
        ],
        
        // Карточки Whom
        "WHOM_CARD_1_IMAGE" => [
            "PARENT" => "WHOM_CARD_1",
            "NAME" => "Изображение",
            "TYPE" => "STRING",
            "DEFAULT" => "/assets/images/whom/01.webp",
        ],
        "WHOM_CARD_1_TITLE" => [
            "PARENT" => "WHOM_CARD_1",
            "NAME" => "Заголовок",
            "TYPE" => "STRING",
            "DEFAULT" => "Частным юристам",
        ],
        "WHOM_CARD_1_TEXT" => [
            "PARENT" => "WHOM_CARD_1",
            "NAME" => "Текст (формат: от|1|до|5|сотрудников)",
            "TYPE" => "STRING",
            "DEFAULT" => "от|1|до|5|сотрудников",
        ],
        "WHOM_CARD_1_BACK_SUBTITLE" => [
            "PARENT" => "WHOM_CARD_1",
            "NAME" => "Подзаголовок (обратная сторона)",
            "TYPE" => "STRING",
            "DEFAULT" => "Численность от 1 до 5 сотрудников",
        ],
        "WHOM_CARD_1_BACK_TITLE" => [
            "PARENT" => "WHOM_CARD_1",
            "NAME" => "Заголовок (обратная сторона)",
            "TYPE" => "STRING",
            "DEFAULT" => "Частным юристам",
        ],
        "WHOM_CARD_1_BACK_TEXT" => [
            "PARENT" => "WHOM_CARD_1",
            "NAME" => "Текст (обратная сторона)",
            "TYPE" => "TEXT",
            "DEFAULT" => "Идеальное решение для частной практики",
        ],
        
        // Карточка 2
        "WHOM_CARD_2_IMAGE" => [
            "PARENT" => "WHOM_CARD_2",
            "NAME" => "Изображение",
            "TYPE" => "STRING",
            "DEFAULT" => "/assets/images/whom/02.webp",
        ],
        "WHOM_CARD_2_TITLE" => [
            "PARENT" => "WHOM_CARD_2",
            "NAME" => "Заголовок",
            "TYPE" => "STRING",
            "DEFAULT" => "Небольшим компаниям",
        ],
        "WHOM_CARD_2_TEXT" => [
            "PARENT" => "WHOM_CARD_2",
            "NAME" => "Текст (формат: от|3|до|50|сотрудников)",
            "TYPE" => "STRING",
            "DEFAULT" => "от|3|до|50|сотрудников",
        ],
        "WHOM_CARD_2_BACK_SUBTITLE" => [
            "PARENT" => "WHOM_CARD_2",
            "NAME" => "Подзаголовок (обратная сторона)",
            "TYPE" => "STRING",
            "DEFAULT" => "Численность от 3 до 50 сотрудников",
        ],
        "WHOM_CARD_2_BACK_TITLE" => [
            "PARENT" => "WHOM_CARD_2",
            "NAME" => "Заголовок (обратная сторона)",
            "TYPE" => "STRING",
            "DEFAULT" => "Небольшим компаниям",
        ],
        "WHOM_CARD_2_BACK_TEXT" => [
            "PARENT" => "WHOM_CARD_2",
            "NAME" => "Текст (обратная сторона)",
            "TYPE" => "TEXT",
            "DEFAULT" => "Масштабируемое решение для роста бизнеса",
        ],
        
        // Карточка 3
        "WHOM_CARD_3_IMAGE" => [
            "PARENT" => "WHOM_CARD_3",
            "NAME" => "Изображение",
            "TYPE" => "STRING",
            "DEFAULT" => "/assets/images/whom/03.webp",
        ],
        "WHOM_CARD_3_TITLE" => [
            "PARENT" => "WHOM_CARD_3",
            "NAME" => "Заголовок",
            "TYPE" => "STRING",
            "DEFAULT" => "Юридическим компаниям",
        ],
        "WHOM_CARD_3_TEXT" => [
            "PARENT" => "WHOM_CARD_3",
            "NAME" => "Текст",
            "TYPE" => "STRING",
            "DEFAULT" => "от|10|до|1000|сотрудников",
        ],
        "WHOM_CARD_3_BACK_SUBTITLE" => [
            "PARENT" => "WHOM_CARD_3",
            "NAME" => "Подзаголовок (обратная сторона)",
            "TYPE" => "STRING",
            "DEFAULT" => "Численность от 10 до 1000 сотрудников",
        ],
        "WHOM_CARD_3_BACK_TITLE" => [
            "PARENT" => "WHOM_CARD_3",
            "NAME" => "Заголовок (обратная сторона)",
            "TYPE" => "STRING",
            "DEFAULT" => "Юридическим компаниям",
        ],
        "WHOM_CARD_3_BACK_TEXT" => [
            "PARENT" => "WHOM_CARD_3",
            "NAME" => "Текст (обратная сторона)",
            "TYPE" => "TEXT",
            "DEFAULT" => "Корпоративное решение для больших команд",
        ],
        
        // === ПАРАМЕТРЫ TOOLS ===
        "TOOLS_MARK_TEXT" => [
            "PARENT" => "TOOLS",
            "NAME" => "Текст метки",
            "TYPE" => "STRING",
            "DEFAULT" => "что внутри",
        ],
        "TOOLS_TAGLINE_ROW_1" => [
            "PARENT" => "TOOLS",
            "NAME" => "Тэглайн - строка 1",
            "TYPE" => "STRING",
            "DEFAULT" => "Вместе с CRM-решением вы получите",
        ],
        "TOOLS_TAGLINE_ROW_2" => [
            "PARENT" => "TOOLS",
            "NAME" => "Тэглайн - строка 2",
            "TYPE" => "STRING",
            "DEFAULT" => "полный набор инструментов Битрикс24",
        ],
        "TOOLS_TITLE" => [
            "PARENT" => "TOOLS",
            "NAME" => "Заголовок",
            "TYPE" => "STRING",
            "DEFAULT" => "Что внутри готового решения?",
        ],
        
        // Инструмент 1
        "TOOLS_TOOL_1_NAME" => [
            "PARENT" => "TOOLS_1",
            "NAME" => "Название",
            "TYPE" => "STRING",
            "DEFAULT" => "ЧАТ И ВИДЕОЗВОНКИ",
        ],
        "TOOLS_TOOL_1_DESC_1" => [
            "PARENT" => "TOOLS_1",
            "NAME" => "Описание 1",
            "TYPE" => "TEXT",
            "DEFAULT" => "",
        ],
        "TOOLS_TOOL_1_DESC_2" => [
            "PARENT" => "TOOLS_1",
            "NAME" => "Описание 2",
            "TYPE" => "TEXT",
            "DEFAULT" => "",
        ],
        "TOOLS_TOOL_1_DESC_3" => [
            "PARENT" => "TOOLS_1",
            "NAME" => "Описание 3",
            "TYPE" => "TEXT",
            "DEFAULT" => "",
        ],
        "TOOLS_TOOL_1_DESC_4" => [
            "PARENT" => "TOOLS_1",
            "NAME" => "Описание 4",
            "TYPE" => "TEXT",
            "DEFAULT" => "",
        ],
        "TOOLS_TOOL_1_DESC_5" => [
            "PARENT" => "TOOLS_1",
            "NAME" => "Описание 5",
            "TYPE" => "TEXT",
            "DEFAULT" => "",
        ],
        "TOOLS_TOOL_1_IMAGE_1" => [
            "PARENT" => "TOOLS_1",
            "NAME" => "Картинка 1",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        "TOOLS_TOOL_1_IMAGE_2" => [
            "PARENT" => "TOOLS_1",
            "NAME" => "Картинка 2",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        "TOOLS_TOOL_1_IMAGE_3" => [
            "PARENT" => "TOOLS_1",
            "NAME" => "Картинка 3",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        "TOOLS_TOOL_1_IMAGE_4" => [
            "PARENT" => "TOOLS_1",
            "NAME" => "Картинка 4",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        "TOOLS_TOOL_1_IMAGE_5" => [
            "PARENT" => "TOOLS_1",
            "NAME" => "Картинка 5",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        
        // Инструмент 2
        "TOOLS_TOOL_2_NAME" => [
            "PARENT" => "TOOLS_2",
            "NAME" => "Название",
            "TYPE" => "STRING",
            "DEFAULT" => "ЗАДАЧИ И ПРОЕКТЫ",
        ],
        "TOOLS_TOOL_2_DESC_1" => [
            "PARENT" => "TOOLS_2",
            "NAME" => "Описание 1",
            "TYPE" => "TEXT",
            "DEFAULT" => "",
        ],
        "TOOLS_TOOL_2_DESC_2" => [
            "PARENT" => "TOOLS_2",
            "NAME" => "Описание 2",
            "TYPE" => "TEXT",
            "DEFAULT" => "",
        ],
        "TOOLS_TOOL_2_DESC_3" => [
            "PARENT" => "TOOLS_2",
            "NAME" => "Описание 3",
            "TYPE" => "TEXT",
            "DEFAULT" => "",
        ],
        "TOOLS_TOOL_2_DESC_4" => [
            "PARENT" => "TOOLS_2",
            "NAME" => "Описание 4",
            "TYPE" => "TEXT",
            "DEFAULT" => "",
        ],
        "TOOLS_TOOL_2_DESC_5" => [
            "PARENT" => "TOOLS_2",
            "NAME" => "Описание 5",
            "TYPE" => "TEXT",
            "DEFAULT" => "",
        ],
        "TOOLS_TOOL_2_IMAGE_1" => [
            "PARENT" => "TOOLS_2",
            "NAME" => "Картинка 1",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        "TOOLS_TOOL_2_IMAGE_2" => [
            "PARENT" => "TOOLS_2",
            "NAME" => "Картинка 2",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        "TOOLS_TOOL_2_IMAGE_3" => [
            "PARENT" => "TOOLS_2",
            "NAME" => "Картинка 3",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        "TOOLS_TOOL_2_IMAGE_4" => [
            "PARENT" => "TOOLS_2",
            "NAME" => "Картинка 4",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        "TOOLS_TOOL_2_IMAGE_5" => [
            "PARENT" => "TOOLS_2",
            "NAME" => "Картинка 5",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        
        // === ДОПОЛНИТЕЛЬНЫЕ НАСТРОЙКИ ===
        "CSS_CLASS" => [
            "PARENT" => "ADDITIONAL",
            "NAME" => "CSS класс для обертки",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        
        "CACHE_TIME" => [
            "DEFAULT" => 3600,
        ],
    ],
];
?>