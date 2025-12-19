<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

// Подготовка данных
$arResult = [
    'TITLE' => $arParams['TITLE'] ?: 'Результаты и выводы',
    'MARK' => $arParams['MARK'] ?: 'Статистика',
    'CARDS' => []
];

for ($i = 1; $i <= 2; $i++) {
    $cardTitle = $arParams['CARD_' . $i . '_TITLE'] ?? '';
    $cardImage = $arParams['CARD_' . $i . '_IMAGE'] ?? '';
    
    $items = [];
    for ($j = 1; $j <= 3; $j++) {
        $number = $arParams['CARD_' . $i . '_ITEM_' . $j . '_NUMBER'] ?? '';
        $pct = $arParams['CARD_' . $i . '_ITEM_' . $j . '_PCT'] ?? '';
        $text = $arParams['CARD_' . $i . '_ITEM_' . $j . '_TEXT'] ?? '';
        $caption = $arParams['CARD_' . $i . '_ITEM_' . $j . '_CAPTION'] ?? '';
        
        if ($number || $pct || $text || $caption) {
            $items[] = [
                'NUMBER' => $number,
                'PCT' => $pct,
                'TEXT' => $text,
                'CAPTION' => $caption
            ];
        }
    }
    
    if ($cardTitle || $cardImage || !empty($items)) {
        $arResult['CARDS'][] = [
            'TITLE' => $cardTitle,
            'IMAGE' => $cardImage,
            'ITEMS' => $items
        ];
    }
}

$this->IncludeComponentTemplate();
?>