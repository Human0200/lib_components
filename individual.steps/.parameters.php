<?php
// components/custom/individual.steps/.parameters.php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

CModule::IncludeModule('iblock');

$arIBlocks = [];
$dbIBlock = CIBlock::GetList(['SORT' => 'ASC'], ['ACTIVE' => 'Y']);
while($arIBlock = $dbIBlock->Fetch()) {
    $arIBlocks[$arIBlock['ID']] = '['.$arIBlock['ID'].'] '.$arIBlock['NAME'];
}

$arComponentParameters = [
    "PARAMETERS" => [
        "IBLOCK_ID" => [
            "PARENT" => "BASE",
            "NAME" => "Инфоблок",
            "TYPE" => "LIST",
            "VALUES" => $arIBlocks,
            "REFRESH" => "Y",
        ],
        "PAGE_CODE" => [
            "PARENT" => "BASE",
            "NAME" => "Код страницы (для фильтрации элементов)",
            "TYPE" => "STRING",
            "DEFAULT" => "1",
        ],
        "MARK" => [
            "PARENT" => "BASE",
            "NAME" => "Метка (mark)",
            "TYPE" => "STRING",
            "DEFAULT" => "Цены на лицензии и тарифы",
        ],
        "TITLE" => [
            "PARENT" => "BASE",
            "NAME" => "Заголовок",
            "TYPE" => "STRING",
            "DEFAULT" => "Из чего складывается стоимость внедрения?",
        ],
        "REQUEST_TITLE" => [
            "PARENT" => "BASE",
            "NAME" => "Заголовок блока заявки",
            "TYPE" => "TEXT",
            "DEFAULT" => "Вы сделали первый шаг к автоматизации\nждем ваши заявки!",
        ],
        "BUTTON_TEXT" => [
            "PARENT" => "BASE",
            "NAME" => "Текст кнопки",
            "TYPE" => "STRING",
            "DEFAULT" => "заказать внедрение",
        ],
        "BUTTON_LINK" => [
            "PARENT" => "BASE",
            "NAME" => "Ссылка кнопки",
            "TYPE" => "STRING",
            "DEFAULT" => "#",
        ],
        "TAGLINE_ROW_1" => [
            "PARENT" => "BASE",
            "NAME" => "Слоган - строка 1",
            "TYPE" => "STRING",
            "DEFAULT" => "Подберите для своего дела наиболее подходящий",
        ],
        "TAGLINE_ROW_2" => [
            "PARENT" => "BASE",
            "NAME" => "Слоган - строка 2",
            "TYPE" => "STRING",
            "DEFAULT" => "и комфортный тарифный план.",
        ],
        "CACHE_TIME" => [
            "DEFAULT" => 3600,
        ],
    ],
];
?>