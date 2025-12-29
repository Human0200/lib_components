<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

// Карта компонентов
$componentMap = [
    'topbar' => 'leadspace:topbar',
    'whom' => 'leadspace:whom.cards',
    'tools' => 'leadspace:bitrix24.tools',
    'ready' => 'leadspace:ready.section',
];

// CSS класс для обертки
$wrapperClass = 'landing-page';
if (!empty($arResult['CSS_CLASS'])) {
    $wrapperClass .= ' ' . htmlspecialchars($arResult['CSS_CLASS']);
}
?>

<div class="<?=$wrapperClass?>">
    <?php
    // Выводим блоки в заданном порядке
    foreach ($arResult['BLOCKS_ORDER'] as $blockKey):
        // Получаем имя компонента
        if (!isset($componentMap[$blockKey])) {
            continue;
        }
        
        $componentName = $componentMap[$blockKey];
        $template = $arResult['TEMPLATES'][$blockKey];
        $params = $arResult[strtoupper($blockKey) . '_PARAMS'];
        
        // Подключаем компонент
        $APPLICATION->IncludeComponent(
            $componentName,
            $template,
            $params,
            $component,
            ['HIDE_ICONS' => 'Y']
        );
    endforeach;
    ?>
</div>