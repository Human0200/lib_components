<?php

/**
 * Автоматическое создание страниц при добавлении элементов инфоблока
 */
class AutoPageCreator
{
    /**
     * Создание страницы при добавлении элемента
     */
    public static function OnAfterElementAdd(&$arFields)
    {
        if (!$arFields['ID'] || !$arFields['IBLOCK_ID']) {
            return;
        }

        self::CreatePage($arFields);
    }

    /**
     * Обновление страницы при изменении элемента
     */
    public static function OnAfterElementUpdate(&$arFields)
    {
        if (!$arFields['ID'] || !$arFields['IBLOCK_ID']) {
            return;
        }

        self::CreatePage($arFields, true);
    }

    /**
     * Удаление страницы при удалении элемента
     */
    public static function OnBeforeElementDelete($ID)
    {
        CModule::IncludeModule('iblock');

        $element = CIBlockElement::GetByID($ID)->Fetch();
        if (!$element) {
            return;
        }

        $iblock = CIBlock::GetByID($element['IBLOCK_ID'])->Fetch();
        if (!$iblock) {
            return;
        }

        // Определяем директорию
        $directory = self::GetDirectoryByIblockCode($iblock['CODE']);
        if (!$directory) {
            return;
        }

        if ($element['CODE']) {
            $pagePath = $directory . $element['CODE'] . '/';

            // Удаляем директорию с файлами
            if (file_exists($pagePath)) {
                self::RemoveDirectory($pagePath);
            }
        }
    }

    /**
     * Создание файла страницы
     */
    private static function CreatePage($arFields, $isUpdate = false)
    {
        CModule::IncludeModule('iblock');

        // Получаем информацию об инфоблоке
        $iblock = CIBlock::GetByID($arFields['IBLOCK_ID'])->Fetch();
        if (!$iblock) {
            return;
        }

        // Определяем директорию и компонент в зависимости от кода инфоблока
        $directory = self::GetDirectoryByIblockCode($iblock['CODE']);
        $componentName = self::GetComponentByIblockCode($iblock['CODE']);

        if (!$directory || !$componentName) {
            return; // Не создаем страницы для других инфоблоков
        }

        // Получаем полную информацию об элементе
        $element = CIBlockElement::GetByID($arFields['ID'])->GetNextElement();
        if (!$element) {
            return;
        }

        $arElement = $element->GetFields();

        // Формируем символьный код (если его нет)
        $code = $arElement['CODE'];
        if (!$code) {
            $code = CUtil::translit($arElement['NAME'], 'ru', [
                'max_len' => 100,
                'change_case' => 'L',
                'replace_space' => '-',
                'replace_other' => '-',
                'delete_repeat_replace' => true,
            ]);

            // Обновляем код элемента
            $el = new CIBlockElement;
            $el->Update($arFields['ID'], ['CODE' => $code]);
        }

        // Создаем директорию, если не существует
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Путь к странице элемента
        $pagePath = $directory . $code . '/';

        // Создаем директорию элемента
        if (!file_exists($pagePath)) {
            mkdir($pagePath, 0755, true);
        }

        // Путь к файлу index.php
        $indexFile = $pagePath . 'index.php';

        // Проверяем, нужно ли создавать/обновлять файл
        if (file_exists($indexFile)) {
            return; // Файл уже существует, не перезаписываем НИКОГДА
        }

        // Генерируем содержимое страницы
        $pageContent = self::GeneratePageContent($arElement, $componentName, $iblock['CODE']);

        // Записываем файл
        file_put_contents($indexFile, $pageContent);

        // Обновляем DETAIL_PAGE_URL элемента, если нужно
        $detailUrl = '/' . $iblock['CODE'] . '/' . $code . '/';
        if ($arElement['DETAIL_PAGE_URL'] !== $detailUrl) {
            $el = new CIBlockElement;
            $el->Update($arFields['ID'], ['DETAIL_PAGE_URL' => $detailUrl]);
        }
    }

    /**
     * Получение директории по коду инфоблока
     */
    private static function GetDirectoryByIblockCode($code)
    {
        $directories = [
            'cases' => $_SERVER['DOCUMENT_ROOT'] . '/cases/',
            'solutions' => $_SERVER['DOCUMENT_ROOT'] . '/solutions/',
            // Добавьте другие инфоблоки здесь:
            // 'news' => $_SERVER['DOCUMENT_ROOT'] . '/news/',
        ];

        return isset($directories[$code]) ? $directories[$code] : null;
    }

    /**
     * Получение компонента по коду инфоблока
     */
    private static function GetComponentByIblockCode($code)
    {
        $components = [
            'cases' => 'leadspace:cases.detail',
            'solutions' => 'leadspace:solutions.detail',
            // Добавьте другие инфоблоки здесь:
            // 'news' => 'bitrix:news.detail',
        ];

        return isset($components[$code]) ? $components[$code] : null;
    }

    /**
     * Генерация содержимого страницы
     */
    private static function GeneratePageContent($arElement, $componentName, $iblockCode)
    {
        $title = addslashes($arElement['NAME']);
        $elementId = $arElement['ID'];

        // Выбираем шаблон в зависимости от типа инфоблока
        if ($iblockCode === 'cases') {
            return self::GenerateCasePage($title, $elementId);
        } elseif ($iblockCode === 'solutions') {
            return self::GenerateSolutionPage($title, $elementId);
        }

        // Для остальных инфоблоков - простая страница
        return self::GenerateDefaultPage($title, $componentName, $arElement, $iblockCode);
    }

    /**
     * Генерация страницы кейса
     */
    private static function GenerateCasePage($title, $elementId)
    {
        $content = <<<'PHP'
<?
define('HEADER_TYPE', 'cases');
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("TITLE_PLACEHOLDER");
?>
<div class="cursor"></div>
<div class="glass"></div>
<div class="ruler"></div>

<main class="main">

<?$APPLICATION->IncludeComponent(
    "leadspace:case.intro", 
    ".default", 
    [
        "TITLE_WORD" => "КЕЙС",
        "SUBTITLE" => "TITLE_PLACEHOLDER",
        "CARD_MARK" => "Внедрение и настройка Битрикс24",
        "CARD_IMAGE" => "/local/templates/leadspace/assets/images/intro/01.webp",
        "TAG_1" => "#воронка продаж",
        "TAG_2" => "#телефония",
        "TAG_3" => "#интеграция",
        "TAG_4" => "",
        "TAG_5" => "",
        "BUTTON_TEXT" => "заказать внедрение",
        "BUTTON_LINK" => "#modal-feedback",
        "WORKPLACES" => "20",
        "PROJECT_DAYS" => "7",
        "LICENSE" => "Коробочная версия Битрикс24",
        "CACHE_TIME" => "3600",
        "CACHE_TYPE" => "A",
    ],
    false
);?>

<?$APPLICATION->IncludeComponent(
    "leadspace:case.details", 
    ".default", 
    [
        "BG_IMAGE" => "/local/templates/leadspace/assets/cases/details-bg.webp",
        "MARK_TEXT" => "компания",
        "BLOCK_1_TITLE" => "О клиенте",
        "BLOCK_1_TEXT" => "<p>Описание клиента...</p>",
        "BLOCK_1_CLIENT_NAME" => "Название компании",
        "BLOCK_1_CLIENT_LINK" => "#modal-feedback",
        "BLOCK_2_TITLE" => "задачи клиента",
        "BLOCK_2_TEXT" => "<p>Описание задач...</p>",
        "CACHE_TIME" => "3600",
        "CACHE_TYPE" => "A"
    ],
    false
);?>

<?php
$APPLICATION->IncludeComponent(
    "leadspace:targets.list", 
    ".default", 
    array(
        "TITLE" => "Цели проекта",
        "TARGET_1_TITLE" => "Заголовок",
        "TARGET_1_TEXT" => "Описание.",
        "TARGET_2_TITLE" => "Заголовок", 
        "TARGET_2_TEXT" => "Описание",
        "TARGET_3_TITLE" => "Заголовок",
        "TARGET_3_TEXT" => "Описание.",
        "CACHE_TIME" => 3600
    )
);
?>

<?$APPLICATION->IncludeComponent(
    "leadspace:case.roadmap",
    ".default",
    [
        "MARK_TEXT" => "Сроки и этапы работы над проектом",
        "TITLE" => "Дорожная карта",
        "TABLE_HEADER_YEAR" => "2024 год",
        "STEP_1_NAME" => "Предпроектная аналитика",
        "STEP_1_TETRIS" => "Y",
        "STEP_1_DURATION" => "5 дней",
        "STEP_2_NAME" => "Формирование ТЗ",
        "STEP_2_TETRIS" => "N",
        "STEP_2_DURATION" => "3 дня",
        "STEP_3_NAME" => "Настройка системы",
        "STEP_3_TETRIS" => "N",
        "STEP_3_DURATION" => "14 дней",
        "STEP_4_NAME" => "Обучение",
        "STEP_4_TETRIS" => "N",
        "STEP_4_DURATION" => "7 дней",
        "STEP_5_NAME" => "Сопровождение",
        "STEP_5_TETRIS" => "N",
        "STEP_5_DURATION" => "н.в",
        "CACHE_TIME" => 3600,
    ]
);?>

<?$APPLICATION->IncludeComponent(
    "leadspace:case.stages",
    ".default",
    [
        "TITLE" => "Этапы внедрения",
        "STAGE_1_TITLE" => "предпроектное иследование",
        "STAGE_1_TEXT" => "<p>Описание этапа...</p>",
        "STAGE_1_IMAGE" => "/upload/stages/01.webp",
        "STAGE_2_TITLE" => "Внедрение\nБитрикс24",
        "STAGE_2_TEXT" => "<p>Описание этапа...</p>",
        "STAGE_2_IMAGE" => "/upload/stages/02.webp",
        "STAGE_3_TITLE" => "Бизнес-процессы",
        "STAGE_3_TEXT" => "<p>Описание этапа...</p>",
        "STAGE_3_IMAGE" => "/upload/stages/03.webp",
        "CACHE_TIME" => 3600,
    ]
);?>

<?$APPLICATION->IncludeComponent(
	"leadspace:results", 
	".default", 
	[
		"TITLE" => "Результаты и выводы",
		"MARK" => "Статистика",
		"CARD_1_IMAGE" => "/local/templates/leadspace/assets/images/results/green.webp",
		"CARD_1_TITLE" => "Рост показателей",
		"CARD_1_ITEM_1_PCT" => "%",
		"CARD_1_ITEM_1_TEXT" => "прозрачности в контроле охраны труда",
		"COMPONENT_TEMPLATE" => ".default",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"CARD_1_ITEM_1_NUMBER" => "99",
		"CARD_1_ITEM_1_CAPTION" => "",
		"CARD_1_ITEM_2_NUMBER" => "70",
		"CARD_1_ITEM_2_PCT" => "%",
		"CARD_1_ITEM_2_TEXT" => "времени на ведение журналов и планирование инструктажей",
		"CARD_1_ITEM_2_CAPTION" => "",
		"CARD_1_ITEM_3_NUMBER" => "60",
		"CARD_1_ITEM_3_PCT" => "%",
		"CARD_1_ITEM_3_TEXT" => "скорости реакции на просрочки и истечение сроков документов",
		"CARD_1_ITEM_3_CAPTION" => "",
		"CARD_2_IMAGE" => "/local/templates/leadspace/assets/images/results/red.webp",
		"CARD_2_TITLE" => "Снижение издержек",
		"CARD_2_ITEM_1_NUMBER" => "50",
		"CARD_2_ITEM_1_PCT" => "%",
		"CARD_2_ITEM_1_TEXT" => "ошибок при работе с документами и графиками",
		"CARD_2_ITEM_1_CAPTION" => "",
		"CARD_2_ITEM_2_NUMBER" => "40",
		"CARD_2_ITEM_2_PCT" => "%",
		"CARD_2_ITEM_2_TEXT" => "нагрузки на специалиста по ОТ и HR-отдел",
		"CARD_2_ITEM_2_CAPTION" => "",
		"CARD_2_ITEM_3_NUMBER" => "30",
		"CARD_2_ITEM_3_PCT" => "%",
		"CARD_2_ITEM_3_TEXT" => "бумажного документооборота за счёт перехода в цифровую среду",
		"CARD_2_ITEM_3_CAPTION" => ""
	],
	false
);?>

<?php
$APPLICATION->IncludeComponent(
	"leadspace:features", 
	".default", 
	[
		"TITLE" => "Особенности проекта",
		"TAGLINE_ROW1" => "Ключевая особенность: создание полноценного цифрового рабочего места специалиста по охране труда.",
		"TAGLINE_ROW2" => "Дополнительные особенности:",
		"FEATURES_LIST" => "Интеграция учёта СИЗ, инструктажей, медосмотров и аттестаций в одной CRM.
Автоматические напоминания и задачи на всех стадиях процессов.
Возможность гибкой настройки под структуру и специфику предприятия",
		"COMPONENT_TEMPLATE" => ".default",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600"
	],
	false
);
?>

<?$APPLICATION->IncludeComponent(
    "leadspace:solutions.list", 
    "other", 
    [
        "IBLOCK_ID" => "6",
        "CACHE_TIME" => "3600",
        "TITLE" => "другие кейсы",
        "CACHE_TYPE" => "A"
    ],
    false
);?>

<figure class="group-d3"></figure>

<?$APPLICATION->IncludeComponent(
	"bitrix:form.result.new", 
	"feedback", 
	[
		"WEB_FORM_ID" => "1",
		"IGNORE_CUSTOM_TEMPLATE" => "N",
		"USE_EXTENDED_ERRORS" => "Y",
		"SEF_MODE" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"LIST_URL" => "https://3d-group.space/thank.php",
		"EDIT_URL" => "",
		"SUCCESS_URL" => "#form-success",
		"SECTION_TITLE" => "Ответим на всё, что вас интересует",
		"SECTION_TEXT" => "Вы знаете цели, мы знаем инструмент. \n Создадим оптимальное решение вместе.",
		"BUTTON_TEXT" => "отправить",
		"PRIVACY_URL" => "/privacy/",
		"PERSONAL_DATA_URL" => "/personal-data/",
		"CHAIN_ITEM_TEXT" => "",
		"CHAIN_ITEM_LINK" => "",
		"COMPONENT_TEMPLATE" => "feedback",
		"VARIABLE_ALIASES" => [
			"WEB_FORM_ID" => "1",
			"RESULT_ID" => "1",
		]
	],
	false
);?>

<?$APPLICATION->IncludeComponent(
    "bitrix:form.result.new",
    "modal",
    [
        "WEB_FORM_ID" => "2", 
        "IGNORE_CUSTOM_TEMPLATE" => "N",
        "USE_EXTENDED_ERRORS" => "Y",
        "SEF_MODE" => "N",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600",
        "LIST_URL" => "",
        "EDIT_URL" => "",
        "SUCCESS_URL" => "#form-success",
        "CHAIN_ITEM_TEXT" => "",
        "CHAIN_ITEM_LINK" => "",
        "PRIVACY_URL" => "/privacy/",
        "PERSONAL_DATA_URL" => "/personal-data/",
        "BUTTON_TEXT" => "отправить",
    ],
    false
);?>

</main>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');?>
PHP;

        return str_replace('TITLE_PLACEHOLDER', $title, $content);
    }

    /**
     * Генерация страницы решения
     */
    private static function GenerateSolutionPage($title, $elementId)
    {
        $content = <<<'PHP'
<?
define('HEADER_TYPE', 'solutions');
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("TITLE_PLACEHOLDER");
?>
<div class="cursor"></div>
<div class="glass"></div>
<div class="ruler"></div>

<main class="main">

<?php
$APPLICATION->IncludeComponent(
	"leadspace:solution.page", 
	".default", 
	[
		"COMPONENT_TEMPLATE" => ".default",
		"BLOCKS_ORDER" => "topbar,benefits,whom,tools,ready",
		"TOPBAR_TEMPLATE" => ".default",
		"WHOM_CARDS_TEMPLATE" => ".default",
		"TOOLS_TEMPLATE" => ".default",
		"IBLOCK_ID" => "3",
		"ELEMENT_ID" => "7",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"TOPBAR_IMAGE" => "",
		"TOPBAR_TAGLINE" => "Банкротство физ.лиц это ответственная сфера",
		"TOPBAR_TITLE" => "",
		"TOPBAR_SUBTITLE" => "Полностью готовое решение для ниши",
		"TOPBAR_PRICE_OLD" => "35 000 р",
		"TOPBAR_PRICE_NEW" => "0",
		"TOPBAR_PRICE_CURRENCY" => "рублей",
		"TOPBAR_PRICE_NOTE" => "*входит в счет приобретения годовой лицензии через нас",
		"TOPBAR_CARD_1_TEXT" => "90% клиентов получили одобрение",
		"TOPBAR_CARD_1_NUMBER" => "22220+",
		"TOPBAR_CARD_1_LABEL" => "установок",
		"TOPBAR_CARD_2_TEXT" => "Быстрый старт работы",
		"TOPBAR_CARD_2_PREFIX" => "до",
		"TOPBAR_CARD_2_NUMBER" => "7",
		"TOPBAR_CARD_2_LABEL" => "дней",
		"TOPBAR_CARD_3_TEXT" => "Профессиональная поддержка",
		"TOPBAR_CARD_3_PREFIX" => "от",
		"TOPBAR_CARD_3_NUMBER" => "",
		"TOPBAR_CARD_3_LABEL" => "т.р",
		"TOPBAR_BUTTON_1_TEXT" => "Заказать внедрение",
		"TOPBAR_BUTTON_1_LINK" => "#",
		"TOPBAR_BUTTON_1_CURSOR" => "Перейти",
		"TOPBAR_BUTTON_2_TEXT" => "Попробовать 7 дней бесплатно",
		"TOPBAR_BUTTON_2_LINK" => "#",
		"WHOM_MARK" => "для кого",
		"WHOM_TITLE" => "Кому подходит данное решение?",
		"WHOM_BUTTON_TEXT" => "Попробовать 7 дней бесплатно",
		"WHOM_BUTTON_LINK" => "#",
		"WHOM_CARD_1_IMAGE" => "/assets/images/whom/01.webp",
		"WHOM_CARD_1_TITLE" => "Частным юристам",
		"WHOM_CARD_1_TEXT" => "от|1|до|5|сотрудников",
		"WHOM_CARD_1_BACK_SUBTITLE" => "Численность от 1 до 5 сотрудников",
		"WHOM_CARD_1_BACK_TITLE" => "Частным юристам",
		"WHOM_CARD_1_BACK_TEXT" => "Идеальное решение для частной практики",
		"WHOM_CARD_2_IMAGE" => "/assets/images/whom/02.webp",
		"WHOM_CARD_2_TITLE" => "Небольшим компаниям",
		"WHOM_CARD_2_TEXT" => "от|3|до|50|сотрудников",
		"WHOM_CARD_2_BACK_SUBTITLE" => "Численность от 3 до 50 сотрудников",
		"WHOM_CARD_2_BACK_TITLE" => "Небольшим компаниям",
		"WHOM_CARD_2_BACK_TEXT" => "Масштабируемое решение для роста бизнеса",
		"WHOM_CARD_3_IMAGE" => "/assets/images/whom/03.webp",
		"WHOM_CARD_3_TITLE" => "Юридическим компаниям",
		"WHOM_CARD_3_TEXT" => "от|10|до|1000|сотрудников",
		"WHOM_CARD_3_BACK_SUBTITLE" => "Численность от 10 до 1000 сотрудников",
		"WHOM_CARD_3_BACK_TITLE" => "Юридическим компаниям",
		"WHOM_CARD_3_BACK_TEXT" => "Корпоративное решение для больших команд",
		"TOOLS_MARK_TEXT" => "что внутри",
		"TOOLS_TAGLINE_ROW_1" => "Вместе с CRM-решением вы получите",
		"TOOLS_TAGLINE_ROW_2" => "полный набор инструментов Битрикс24",
		"TOOLS_TITLE" => "Что внутри готового решения?",
		"TOOLS_TOOL_1_NAME" => "ЧАТ И ВИДЕОЗВОНКИ",
		"TOOLS_TOOL_1_DESC_1" => "",
		"TOOLS_TOOL_1_DESC_2" => "",
		"TOOLS_TOOL_1_DESC_3" => "",
		"TOOLS_TOOL_1_DESC_4" => "",
		"TOOLS_TOOL_1_DESC_5" => "",
		"TOOLS_TOOL_1_IMAGE_1" => "",
		"TOOLS_TOOL_1_IMAGE_2" => "",
		"TOOLS_TOOL_1_IMAGE_3" => "",
		"TOOLS_TOOL_1_IMAGE_4" => "",
		"TOOLS_TOOL_1_IMAGE_5" => "",
		"TOOLS_TOOL_2_NAME" => "ЗАДАЧИ И ПРОЕКТЫ",
		"TOOLS_TOOL_2_DESC_1" => "123",
		"TOOLS_TOOL_2_DESC_2" => "123",
		"TOOLS_TOOL_2_DESC_3" => "123",
		"TOOLS_TOOL_2_DESC_4" => "123",
		"TOOLS_TOOL_2_DESC_5" => "123",
		"TOOLS_TOOL_2_IMAGE_1" => "123",
		"TOOLS_TOOL_2_IMAGE_2" => "123",
		"TOOLS_TOOL_2_IMAGE_3" => "123",
		"TOOLS_TOOL_2_IMAGE_4" => "123",
		"TOOLS_TOOL_2_IMAGE_5" => "123",
		"CSS_CLASS" => "",
		"READY_TEMPLATE" => ".default",
		"READY_IBLOCK_ID" => "12",
		"READY_IBLOCK_CODE" => "business_integrations",
		"READY_IBLOCK_TYPE" => "news",
		"READY_ELEMENT_ID" => "7",
		"READY_ITEMS_COUNT" => "6",
		"READY_SORT_BY" => "SORT",
		"READY_SORT_ORDER" => "ASC",
		"READY_SHOW_SERVICES" => "Y",
		"READY_SHOW_BUSINESS" => "Y",
		"READY_SERVICES_TITLE" => "100+ готовых интеграций и сервисов!",
		"READY_BUSINESS_TITLE" => "Интеграции с нишевыми сервисами для бизнеса"
	],
	false
);
?>
<?$APPLICATION->IncludeComponent(
	"leadspace:individual.steps", 
	"other", 
	[
		"IBLOCK_ID" => "4",
		"CACHE_TIME" => "3600",
		"COMPONENT_TEMPLATE" => "other",
		"TITLE" => "Из чего складывается стоимость внедрения?",
		"CACHE_TYPE" => "A",
		"PAGE_CODE" => "bankrotstvo-fizicheskih-lic",
		"MARK" => "Цены на лицензии и тарифы",
		"REQUEST_TITLE" => "Вы сделали первый шаг к автоматизацииждем ваши заявки!",
		"BUTTON_TEXT" => "заказать внедрение",
		"BUTTON_LINK" => "#modal-feedback",
		"TAGLINE_ROW_1" => "Подберите для своего дела наиболее подходящий",
		"TAGLINE_ROW_2" => "и комфортный тарифный план."
	],
	false
);?>

<?php
$APPLICATION->IncludeComponent(
	"leadspace:tariffs.slider", 
	".default", 
	[
		"IBLOCK_ID" => "7",
		"TITLE" => "Тарифы наших работ",
		"CACHE_TIME" => "3600",
		"COMPONENT_TEMPLATE" => ".default",
		"CACHE_TYPE" => "A"
	],
	false
);
?>

<?$APPLICATION->IncludeComponent(
	"leadspace:bitrix24.licenses", 
	".default", 
	[
		"IBLOCK_ID_CLOUD" => "9",
		"IBLOCK_ID_BOXED" => "10",
		"IBLOCK_ID_SUBSCRIPTION" => "11",
		"CACHE_TIME" => "3600",
		"COMPONENT_TEMPLATE" => ".default",
		"MARK_TEXT" => "Мы не просто продаем — мы помогаем вам использовать Битрикс24",
		"MARK_HIGHLIGHT" => "на полную мощность",
		"TITLE" => "Лицензии Битрикс24",
		"CAPTION" => "В соответствии с политикой 1С-Битрикс24 мы не продаем лицензии выше или ниже установленных цен",
		"FOOTER_TEXT" => "В тарифах цены со скидкой 50% для тех, кто покупает подписку впервые",
		"CACHE_TYPE" => "A",
		"ENTERPRISE_OPTION_1_EMPLOYEES" => "250",
		"ENTERPRISE_OPTION_1_PRICE_MONTH" => "33990",
		"ENTERPRISE_OPTION_1_PRICE_YEAR" => "244728",
		"ENTERPRISE_OPTION_2_EMPLOYEES" => "500",
		"ENTERPRISE_OPTION_2_PRICE_MONTH" => "59990",
		"ENTERPRISE_OPTION_2_PRICE_YEAR" => "431928",
		"ENTERPRISE_OPTION_3_EMPLOYEES" => "1000",
		"ENTERPRISE_OPTION_3_PRICE_MONTH" => "99990",
		"ENTERPRISE_OPTION_3_PRICE_YEAR" => "719928",
		"SUBSCRIPTION_CLOUD_OPTION_1_EMPLOYEES" => "250",
		"SUBSCRIPTION_CLOUD_OPTION_1_PRICE_MONTH" => "4995",
		"SUBSCRIPTION_CLOUD_OPTION_1_PRICE_YEAR" => "59940",
		"SUBSCRIPTION_CLOUD_OPTION_2_EMPLOYEES" => "500",
		"SUBSCRIPTION_CLOUD_OPTION_2_PRICE_MONTH" => "5995",
		"SUBSCRIPTION_CLOUD_OPTION_2_PRICE_YEAR" => "71940",
		"SUBSCRIPTION_CLOUD_OPTION_3_EMPLOYEES" => "1000",
		"SUBSCRIPTION_CLOUD_OPTION_3_PRICE_MONTH" => "7495",
		"SUBSCRIPTION_CLOUD_OPTION_3_PRICE_YEAR" => "89940",
		"MORE_CAPTION" => "Цены указаны со скидкой 40% при условии покупки облачного тарифа и подписки BitrixGPT + Маркетплейс 
в одном заказе (на 12 месяцев).
Срок проведения акции с 1 декабря по 31 декабря 2025"
	],
	false
);?>


<?php
$APPLICATION->IncludeComponent(
    "leadspace:promo.marquee",
    ".default",
    [
        "TAGLINE_TOP" => "купите лицензию через нас — и получите",
        "TAGLINE_BOTTOM" => "больше, чем просто доступ к Битрикс24",
        
        "ITEM_1" => "1. Бесплатную службу поддержки на весь срок действия лицензии.",
        "ITEM_2" => "2. Часы обучения для пользователей при покупке лицензии на год.",
        "ITEM_3" => "3. Быстрое подключение лицензии. Мы активируем лицензию в тот же день!",
        "ITEM_4" => "",
        "ITEM_5" => "",
        
        "CACHE_TIME" => 3600,
    ]
);
?>

<?php
$APPLICATION->IncludeComponent(
	"leadspace:reviews.slider", 
	".default", 
	[
		"IBLOCK_ID" => "8",
		"MARK" => "отзывы",
		"TITLE" => "Отзывы покупателей",
		"TEXT" => "Нашими готовыми решениями пользуются уже 170+ клиентов и мы регулярно получаем от них обратную связь.",
		"CACHE_TIME" => "3600",
		"COMPONENT_TEMPLATE" => ".default",
		"CACHE_TYPE" => "A"
	],
	false
);
?>

<?$APPLICATION->IncludeComponent(
	"leadspace:solutions.list", 
	"other", 
	[
		"IBLOCK_ID" => "3",
		"CACHE_TIME" => "3600",
		"COMPONENT_TEMPLATE" => "other",
		"TITLE" => "Посмотреть все готовые решения",
		"CACHE_TYPE" => "N",
		"SUBTITLE" => "",
		"CHECK_404" => "Y"
	],
	false
);?>

<figure class="group-d3"></figure>
<?$APPLICATION->IncludeComponent(
	"bitrix:form.result.new", 
	"feedback", 
	[
		"WEB_FORM_ID" => "1",
		"IGNORE_CUSTOM_TEMPLATE" => "N",
		"USE_EXTENDED_ERRORS" => "Y",
		"SEF_MODE" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"LIST_URL" => "https://3d-group.space/thank.php",
		"EDIT_URL" => "",
		"SUCCESS_URL" => "#form-success",
		"SECTION_TITLE" => "Ответим на всё, что вас интересует",
		"SECTION_TEXT" => "Вы знаете цели, мы знаем инструмент. \n Создадим оптимальное решение вместе.",
		"BUTTON_TEXT" => "отправить",
		"PRIVACY_URL" => "/privacy/",
		"PERSONAL_DATA_URL" => "/personal-data/",
		"CHAIN_ITEM_TEXT" => "",
		"CHAIN_ITEM_LINK" => "",
		"COMPONENT_TEMPLATE" => "feedback",
		"VARIABLE_ALIASES" => [
			"WEB_FORM_ID" => "1",
			"RESULT_ID" => "1",
		]
	],
	false
);?>

<?$APPLICATION->IncludeComponent(
	"bitrix:form.result.new", 
	"modal", 
	[
		"WEB_FORM_ID" => "2",
		"IGNORE_CUSTOM_TEMPLATE" => "N",
		"USE_EXTENDED_ERRORS" => "Y",
		"SEF_MODE" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"LIST_URL" => "",
		"EDIT_URL" => "",
		"SUCCESS_URL" => "#form-success",
		"CHAIN_ITEM_TEXT" => "",
		"CHAIN_ITEM_LINK" => "",
		"PRIVACY_URL" => "/privacy/",
		"PERSONAL_DATA_URL" => "/personal-data/",
		"BUTTON_TEXT" => "отправить",
		"COMPONENT_TEMPLATE" => "modal",
		"VARIABLE_ALIASES" => [
			"WEB_FORM_ID" => "WEB_FORM_ID",
			"RESULT_ID" => "RESULT_ID",
		]
	],
	false
);?>
</main>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');?>
PHP;

        return str_replace('TITLE_PLACEHOLDER', $title, $content);
    }

    /**
     * Генерация простой страницы по умолчанию (для других инфоблоков)
     */
    private static function GenerateDefaultPage($title, $componentName, $arElement, $iblockCode)
    {
        $elementId = $arElement['ID'];

        $content = <<<PHP
<?php
require(\$_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
\$APPLICATION->SetTitle("{$title}");
?>

<?php
\$APPLICATION->IncludeComponent(
    "{$componentName}",
    "",
    [
        "ELEMENT_ID" => {$elementId},
        "ELEMENT_CODE" => "{$arElement['CODE']}",
        "IBLOCK_CODE" => "{$iblockCode}",
        "IBLOCK_TYPE" => "content",
        "SET_TITLE" => "Y",
        "SET_BROWSER_TITLE" => "Y",
        "SET_META_KEYWORDS" => "Y",
        "SET_META_DESCRIPTION" => "Y",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600",
    ]
);
?>

<?php require(\$_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>
PHP;

        return $content;
    }

    /**
     * Рекурсивное удаление директории
     */
    private static function RemoveDirectory($dir)
    {
        if (!file_exists($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? self::RemoveDirectory($path) : unlink($path);
        }

        return rmdir($dir);
    }
}
