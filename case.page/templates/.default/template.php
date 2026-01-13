<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */

// Карта компонентов
$componentMap = [
    'intro' => 'leadspace:case.intro',
    'details' => 'leadspace:case.details',
    'targets' => 'leadspace:targets.list',
    // 'roadmap' => 'leadspace:case.roadmap',
    'stages' => 'leadspace:case.stages',
    'results' => 'leadspace:results',
    'features' => 'leadspace:features',
];

// Карта include-файлов (что подключать после какого блока)
$includeMap = [
    // Пример: 'intro' => '/local/include/after-intro.php',
];

// CSS класс для обертки
$wrapperClass = 'case-page';
if (!empty($arResult['CSS_CLASS'])) {
    $wrapperClass .= ' ' . htmlspecialchars($arResult['CSS_CLASS']);
}
?>

<div class="<?=$wrapperClass?>">
    <?php
    // Выводим блоки в заданном порядке
    foreach ($arResult['BLOCKS_ORDER'] as $blockKey):
        $blockKey = trim($blockKey);
        
        // Проверяем, есть ли компонент для этого блока
        if (!isset($componentMap[$blockKey])) {
            continue;
        }
        
        $componentName = $componentMap[$blockKey];
        $template = $arResult['TEMPLATES'][$blockKey] ?? '.default';
        $params = $arResult[strtoupper($blockKey) . '_PARAMS'] ?? [];
        
        // Подключаем компонент
        $APPLICATION->IncludeComponent(
            $componentName,
            $template,
            $params,
            false,
            ['HIDE_ICONS' => 'Y']
        );
        
        // Проверяем, нужно ли подключить include-файл после этого блока
        if (isset($includeMap[$blockKey]) && file_exists($_SERVER['DOCUMENT_ROOT'] . $includeMap[$blockKey])):
            $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                [
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "inc",
                    "EDIT_TEMPLATE" => "",
                    "PATH" => $includeMap[$blockKey]
                ]
            );
        endif;
        
    endforeach;
    ?>
</div>