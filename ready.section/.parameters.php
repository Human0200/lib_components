<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = [
    "PARAMETERS" => [
        "IBLOCK_ID" => [
            "PARENT" => "DATA_SOURCE",
            "NAME" => "ID инфоблока бизнес-интеграций",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        "IBLOCK_CODE" => [
            "PARENT" => "DATA_SOURCE",
            "NAME" => "Код инфоблока бизнес-интеграций",
            "TYPE" => "STRING",
            "DEFAULT" => "business_integrations",
        ],
        "IBLOCK_TYPE" => [
            "PARENT" => "DATA_SOURCE",
            "NAME" => "Тип инфоблока",
            "TYPE" => "STRING",
            "DEFAULT" => "services",
        ],
        "ELEMENT_ID" => [
            "PARENT" => "DATA_SOURCE",
            "NAME" => "ID элемента со свойством SLIDER_SERVICES (если указан, берутся связанные элементы)",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        "SERVICES_TITLE" => [
            "PARENT" => "VISUAL",
            "NAME" => "Заголовок сервисов",
            "TYPE" => "STRING",
            "DEFAULT" => "100+ готовых интеграций и сервисов!",
        ],
        "BUSINESS_TITLE" => [
            "PARENT" => "VISUAL",
            "NAME" => "Заголовок бизнес-интеграций",
            "TYPE" => "STRING",
            "DEFAULT" => "Интеграции с нишевыми сервисами для бизнеса",
        ],
        "SHOW_SERVICES" => [
            "PARENT" => "VISUAL",
            "NAME" => "Показывать блок сервисов",
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
        ],
        "SHOW_BUSINESS" => [
            "PARENT" => "VISUAL",
            "NAME" => "Показывать блок бизнес-интеграций",
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
        ],
        "ITEMS_COUNT" => [
            "PARENT" => "DATA_SOURCE",
            "NAME" => "Количество элементов для вывода",
            "TYPE" => "STRING",
            "DEFAULT" => "6",
        ],
        "SORT_BY" => [
            "PARENT" => "DATA_SOURCE",
            "NAME" => "Поле сортировки",
            "TYPE" => "LIST",
            "VALUES" => [
                "SORT" => "Сортировка",
                "ID" => "ID",
                "NAME" => "Название",
                "ACTIVE_FROM" => "Дата начала активности",
                "DATE_CREATE" => "Дата создания",
            ],
            "DEFAULT" => "SORT",
        ],
        "SORT_ORDER" => [
            "PARENT" => "DATA_SOURCE",
            "NAME" => "Порядок сортировки",
            "TYPE" => "LIST",
            "VALUES" => [
                "ASC" => "По возрастанию",
                "DESC" => "По убыванию",
            ],
            "DEFAULT" => "ASC",
        ],
        "CACHE_TIME" => [
            "DEFAULT" => 3600,
        ],
    ],
];