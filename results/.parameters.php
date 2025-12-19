<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arComponentParameters = [
    "PARAMETERS" => [
        "TITLE" => [
            "PARENT" => "BASE",
            "NAME" => "Заголовок секции",
            "TYPE" => "STRING",
            "DEFAULT" => "Результаты и выводы",
        ],
        "MARK" => [
            "PARENT" => "BASE",
            "NAME" => "Метка секции",
            "TYPE" => "STRING",
            "DEFAULT" => "Статистика",
        ],
        "CACHE_TIME" => [
            "DEFAULT" => 3600,
        ],
    ],
    "GROUPS" => []
];

// Генерируем параметры для 2 карточек через цикл
for($i = 1; $i <= 2; $i++) {
    $groupName = "CARD_".$i;
    
    // Создаем группу
    $arComponentParameters["GROUPS"][$groupName] = [
        "NAME" => "Карточка ".$i
    ];
    
    // Параметры карточки
    $arComponentParameters["PARAMETERS"]["CARD_".$i."_IMAGE"] = [
        "PARENT" => $groupName,
        "NAME" => "Изображение карточки",
        "TYPE" => "STRING",
        "DEFAULT" => "",
    ];
    
    $arComponentParameters["PARAMETERS"]["CARD_".$i."_TITLE"] = [
        "PARENT" => $groupName,
        "NAME" => "Заголовок карточки",
        "TYPE" => "STRING",
        "DEFAULT" => "",
    ];
    
    // Генерируем параметры для 3 элементов списка в каждой карточке
    for($j = 1; $j <= 3; $j++) {
        $arComponentParameters["PARAMETERS"]["CARD_".$i."_ITEM_".$j."_NUMBER"] = [
            "PARENT" => $groupName,
            "NAME" => "Элемент ".$j." - число",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ];
        
        $arComponentParameters["PARAMETERS"]["CARD_".$i."_ITEM_".$j."_PCT"] = [
            "PARENT" => $groupName,
            "NAME" => "Элемент ".$j." - единица измерения (%, +, и т.д.)",
            "TYPE" => "STRING",
            "DEFAULT" => "%",
        ];
        
        $arComponentParameters["PARAMETERS"]["CARD_".$i."_ITEM_".$j."_TEXT"] = [
            "PARENT" => $groupName,
            "NAME" => "Элемент ".$j." - описание (маленький текст)",
            "TYPE" => "TEXTAREA",
            "DEFAULT" => "",
            "ROWS" => 2,
        ];
        
        $arComponentParameters["PARAMETERS"]["CARD_".$i."_ITEM_".$j."_CAPTION"] = [
            "PARENT" => $groupName,
            "NAME" => "Элемент ".$j." - подпись (дополнительный текст)",
            "TYPE" => "TEXTAREA",
            "DEFAULT" => "",
            "ROWS" => 2,
        ];
    }
}