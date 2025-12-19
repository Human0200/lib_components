<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile(__FILE__);
CJSCore::Init(array("jquery"));
// Получаем данные для SEO
$siteName = "3D Group";
$currentPage = $APPLICATION->GetCurPage();
$serverName = "https://".SITE_SERVER_NAME;
?>
<!DOCTYPE html>
<html lang="<?=LANGUAGE_ID?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="format-detection" content="telephone=no, address=no, email=no">

    <!-- ✅ Themes & colors -->
    <meta name="theme-color" content="#ffffff">
    <meta name="background-color" content="#ffffff">
    
    <!-- ✅ PWA -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="<?=$siteName?>">
    <meta name="apple-mobile-web-app-title" content="<?=$siteName?>">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-orientation" content="portrait">
    
    <!-- ✅ Основные мета-теги Bitrix -->
    <title><?$APPLICATION->ShowTitle()?></title>
	<?$APPLICATION->ShowHead()?>
    
    <!-- ✅ Дополнительные мета-теги (если не заданы в Bitrix) -->
    <?if($APPLICATION->GetPageProperty("description") == ''):?>
        <meta name="description" content="3D Group - готовые решения и индивидуальное внедрение">
    <?endif?>
    
    <!-- ✅ Robots (можно управлять через админку Bitrix) -->
    <?if($APPLICATION->GetPageProperty("robots") == ''):?>
        <meta name="robots" content="index, follow">
    <?endif?>
    
    <!-- ✅ Canonical -->
    <link href="<?=$serverName.$currentPage?>" rel="canonical">
    
    <!-- ✅ Open Graph -->
    <meta property="og:title" content="<?$APPLICATION->ShowTitle()?>">
    <meta property="og:description" content="<?=$APPLICATION->GetPageProperty("description") ?: "3D Group - готовые решения и индивидуальное внедрение"?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?=$serverName.$currentPage?>">
    <meta property="og:image" content="<?=$serverName?><?=SITE_TEMPLATE_PATH?>/assets/images/og-image.jpg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="<?$APPLICATION->ShowTitle()?>">
    <meta property="og:locale" content="ru_RU">
    <meta property="og:site_name" content="<?=$siteName?>">
    
    <!-- ✅ Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?$APPLICATION->ShowTitle()?>">
    <meta name="twitter:description" content="<?=$APPLICATION->GetPageProperty("description") ?: "3D Group - готовые решения и индивидуальное внедрение"?>">
    <meta name="twitter:image" content="<?=$serverName?><?=SITE_TEMPLATE_PATH?>/assets/images/og-image.jpg">
    <meta name="twitter:image:alt" content="<?$APPLICATION->ShowTitle()?>">
    <meta name="twitter:site" content="@3dgroup">
    
    <!-- ✅ JSON-LD Schema -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "<?=$siteName?>",
        "description": "3D Group - готовые решения и индивидуальное внедрение",
        "url": "<?=$serverName?>",
        "logo": "<?=$serverName?><?=SITE_TEMPLATE_PATH?>/assets/images/logo.png",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "просп. Победы, 29, корп. 1",
            "addressLocality": "Липецк",
            "addressCountry": "RU"
        },
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+7-967-555-34-44",
            "contactType": "customer service",
            "email": "info@3d-group.space"
        }
    }
    </script>
    
    <!-- ✅ Styles -->
    <link href="<?=SITE_TEMPLATE_PATH?>/css/styles.css" rel="stylesheet">
</head>
<body>
    <?$APPLICATION->ShowPanel()?>
    <div class="app">
        <?php
        // Выводим весь хедер через компонент
        $APPLICATION->IncludeComponent(
	"leadspace:header.links", 
	".default", 
	[
		"HLBLOCK_ID" => "2",
		"SORT_FIELD" => "UF_SORT",
		"SORT_ORDER" => "ASC",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"LOGO_SVG_PATH" => "/local/templates/leadspace/assets/images/header.svg",
		"LOGO_ALT" => "3D Group",
		"FAVICON_SVG" => "/local/templates/leadspace/assets/favicons/favicon.svg",
		"FAVICON_PNG" => "/local/templates/leadspace/assets/favicons/favicon-96x96.png",
		"FAVICON_ICO" => "/local/templates/leadspace/assets/favicons/favicon.ico",
		"APPLE_TOUCH_ICON" => "/local/templates/leadspace/assets/favicons/apple-touch-icon.png",
		"COMPONENT_TEMPLATE" => ".default"
	],
	false
);
        ?>