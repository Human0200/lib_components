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
    "leadspace:topbar", 
    ".default", 
    [
        "IMAGE" => "/local/templates/leadspace/assets/images/topbar/01.webp",
        "TAGLINE" => "Описание решения...",
        "TITLE" => "TITLE_PLACEHOLDER",
        "SUBTITLE" => "Полностью готовое решение для ниши",
        "PRICE_OLD" => "",
        "PRICE_NEW" => "",
        "PRICE_CURRENCY" => "",
        "PRICE_NOTE" => "",
        "CARD_1_TEXT" => "Мы знаем с чего начать. Без лишних экспериментов.",
        "CARD_1_NUMBER" => "20+",
        "CARD_1_LABEL" => "установок",
        "CARD_2_TEXT" => "Сэкономим ваше время: начнем с проверенных решений.",
        "CARD_2_PREFIX" => "до",
        "CARD_2_NUMBER" => "7",
        "CARD_2_LABEL" => "дней",
        "CARD_3_TEXT" => "Стоимость с обучением и интеграциями",
        "CARD_3_PREFIX" => "от",
        "CARD_3_NUMBER" => "150",
        "CARD_3_LABEL" => "т.р",
        "BUTTON_1_TEXT" => "заказать внедрение",
        "BUTTON_1_LINK" => "#modal-feedback",
        "BUTTON_1_CURSOR" => "Перейти",
        "BUTTON_2_TEXT" => "Попробовать 7 дней бесплатно",
        "BUTTON_2_LINK" => "#modal-feedback",
        "CACHE_TIME" => "3600",
        "CACHE_TYPE" => "A"
    ],
    false
);
?>

<?php
$APPLICATION->IncludeComponent(
    "leadspace:crm.benefits", 
    ".default", 
    [
        "MARK" => "СRM-система",
        "TAGLINE_ROW_1" => "Ваша CRM уже готова:",
        "TAGLINE_ROW_2" => "разработана с учетом всех особенностей ниши",
        "TITLE" => "СRM-система помогает:",
        "CARD_1_TEXT" => "Автоматизировать коммуникации с клиентами",
        "CARD_1_ICON" => "/local/templates/leadspace/assets/images/icons/Business Conversation.svg",
        "CARD_2_TEXT" => "Повышать эффективность команды",
        "CARD_2_ICON" => "/local/templates/leadspace/assets/images/icons/SvgjsG1681.svg",
        "CARD_3_TEXT" => "Визуализировать данные и генерировать отчёты",
        "CARD_3_ICON" => "/local/templates/leadspace/assets/images/icons/Duplicate Copy.svg",
        "CARD_4_TEXT" => "Выстроить систему работы с клиентской базой",
        "CARD_4_ICON" => "/local/templates/leadspace/assets/images/icons/Business Hierarchy.svg",
        "CARD_5_TEXT" => "Управлять задачами и сроками",
        "CARD_5_ICON" => "/local/templates/leadspace/assets/images/icons/Group 19808.svg",
        "CARD_6_TEXT" => "Анализировать эффективность работы",
        "CARD_6_ICON" => "/local/templates/leadspace/assets/images/icons/Business Growth.svg",
        "CACHE_TIME" => "3600",
        "CACHE_TYPE" => "A"
    ],
    false
);
?>
<?php
$APPLICATION->IncludeComponent(
    "leadspace:whom.cards", 
    ".default", 
    [
        "MARK" => "для кого",
        "TITLE" => "Кому подходит данное решение?",
        "BUTTON_TEXT" => "Попробовать 7 дней бесплатно",
        "BUTTON_LINK" => "#modal-feedback",
        "CARD_1_IMAGE" => "/local/templates/leadspace/assets/images/whom/01.webp",
        "CARD_1_TITLE" => "Небольшим компаниям",
        "CARD_1_TEXT" => "от|1|до|5|сотрудников",
        "CARD_1_BACK_SUBTITLE" => "Численность от 1 до 5 сотрудников",
        "CARD_1_BACK_TITLE" => "Небольшим компаниям",
        "CARD_1_BACK_TEXT" => "Описание...",
        "CARD_2_IMAGE" => "/local/templates/leadspace/assets/images/whom/02.webp",
        "CARD_2_TITLE" => "Средним компаниям",
        "CARD_2_TEXT" => "от|3|до|50|сотрудников",
        "CARD_2_BACK_SUBTITLE" => "Численность от 3 до 50 сотрудников",
        "CARD_2_BACK_TITLE" => "Средним компаниям",
        "CARD_2_BACK_TEXT" => "Описание...",
        "CARD_3_IMAGE" => "/local/templates/leadspace/assets/images/whom/02.webp",
        "CARD_3_TITLE" => "Крупным компаниям",
        "CARD_3_TEXT" => "от|10|до|1000|сотрудников",
        "CARD_3_BACK_SUBTITLE" => "Численность от 10 до 1 000 сотрудников",
        "CARD_3_BACK_TITLE" => "Крупным компаниям",
        "CARD_3_BACK_TEXT" => "Описание...",
        "CACHE_TIME" => "3600",
        "CACHE_TYPE" => "A"
    ],
    false
);
?>

<?$APPLICATION->IncludeComponent(
    "leadspace:bitrix24.tools", 
    ".default", 
    [
        "MARK_TEXT" => "что внутри",
        "TAGLINE_ROW_1" => "Вместе с CRM-решением вы получите",
        "TAGLINE_ROW_2" => "полный набор инструментов Битрикс24 для работы",
        "TITLE" => "Что внутри готового решения?",
        "TOOL_1_NAME" => "ВОРОНКА СДЕЛОК \"ПРОДАЖИ\"",
        "TOOL_1_DESC_1" => "Для систематизации и квалификации заявок клиентов из различных источников создана воронка \"Продажи\".",
        "TOOL_1_DESC_2" => "С помощью открытых линий и настроенных роботов обращения корректно распределяются между сотрудниками.",
        "TOOL_1_DESC_3" => "",
        "TOOL_1_DESC_4" => "",
        "TOOL_1_DESC_5" => "",
        "TOOL_1_IMAGE_1" => "/upload/tools/01.webp",
        "TOOL_1_IMAGE_2" => "/upload/tools/02.webp",
        "TOOL_1_IMAGE_3" => "",
        "TOOL_1_IMAGE_4" => "",
        "TOOL_1_IMAGE_5" => "",
        "TOOL_2_NAME" => "ЗАДАЧИ И ПРОЕКТЫ",
        "TOOL_2_DESC_1" => "Описание инструмента...",
        "TOOL_2_DESC_2" => "",
        "TOOL_2_DESC_3" => "",
        "TOOL_2_DESC_4" => "",
        "TOOL_2_DESC_5" => "",
        "TOOL_2_IMAGE_1" => "/upload/tools/06.webp",
        "TOOL_2_IMAGE_2" => "/upload/tools/07.webp",
        "TOOL_2_IMAGE_3" => "/upload/tools/08.webp",
        "TOOL_2_IMAGE_4" => "",
        "TOOL_2_IMAGE_5" => "",
        "CACHE_TIME" => "3600",
        "CACHE_TYPE" => "A"
    ],
    false
);?>


<?$APPLICATION->IncludeComponent(
    "bitrix:main.include",
    "",
    [
        "AREA_FILE_SHOW" => "file",
        "AREA_FILE_SUFFIX" => "inc",
        "EDIT_TEMPLATE" => "",
        "PATH" => "/local/include/devices.php"
    ]
);?>

<?php
$APPLICATION->IncludeComponent(
    "leadspace:ready.section",
    "",
    [
        "IBLOCK_ID" => "12",
        "IBLOCK_CODE" => "business_integrations",
        "IBLOCK_TYPE" => "content",
        "SERVICES_TITLE" => "100+ готовых интеграций и сервисов!",
        "BUSINESS_TITLE" => "Интеграции с нишевыми сервисами для бизнеса",
        "SHOW_SERVICES" => "Y",
        "SHOW_BUSINESS" => "Y",
        "ITEMS_COUNT" => "6",
        "SORT_BY" => "SORT",
        "SORT_ORDER" => "ASC",
        "CACHE_TIME" => 3600
    ]
);
?>

<?$APPLICATION->IncludeComponent(
    "leadspace:individual.steps", 
    "other", 
    [
        "IBLOCK_ID" => "4",
        "CACHE_TIME" => "3600",
        "TITLE" => "Из чего складывается стоимость внедрения?",
        "CACHE_TYPE" => "A"
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
        "MARK_TEXT" => "Мы не просто продаем — мы помогаем вам использовать Битрикс24",
        "MARK_HIGHLIGHT" => "на полную мощность",
        "TITLE" => "Лицензии Битрикс24",
        "CAPTION" => "В соответствии с политикой 1С-Битрикс24 мы не продаем лицензии выше или ниже установленных цен",
        "FOOTER_TEXT" => "В таблице цены со скидкой 50% для тех, кто покупает подписку впервые",
        "CACHE_TYPE" => "A",
        "ENTERPRISE_OPTION_1_EMPLOYEES" => "250",
        "ENTERPRISE_OPTION_1_PRICE_MONTH" => "33990",
        "ENTERPRISE_OPTION_1_PRICE_YEAR" => "326280",
        "ENTERPRISE_OPTION_2_EMPLOYEES" => "500",
        "ENTERPRISE_OPTION_2_PRICE_MONTH" => "59990",
        "ENTERPRISE_OPTION_2_PRICE_YEAR" => "575880",
        "ENTERPRISE_OPTION_3_EMPLOYEES" => "1000",
        "ENTERPRISE_OPTION_3_PRICE_MONTH" => "99990",
        "ENTERPRISE_OPTION_3_PRICE_YEAR" => "959880",
        "SUBSCRIPTION_CLOUD_OPTION_1_EMPLOYEES" => "250",
        "SUBSCRIPTION_CLOUD_OPTION_1_PRICE_MONTH" => "4995",
        "SUBSCRIPTION_CLOUD_OPTION_1_PRICE_YEAR" => "59940",
        "SUBSCRIPTION_CLOUD_OPTION_2_EMPLOYEES" => "500",
        "SUBSCRIPTION_CLOUD_OPTION_2_PRICE_MONTH" => "9995",
        "SUBSCRIPTION_CLOUD_OPTION_2_PRICE_YEAR" => "119880",
        "SUBSCRIPTION_CLOUD_OPTION_3_EMPLOYEES" => "1000",
        "SUBSCRIPTION_CLOUD_OPTION_3_PRICE_MONTH" => "19995",
        "SUBSCRIPTION_CLOUD_OPTION_3_PRICE_YEAR" => "239880"
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
        "IBLOCK_ID" => 8,
        "MARK" => "отзывы",
        "TITLE" => "Отзывы покупателей",
        "TEXT" => "Нашим готовым решением пользуются уже 20 клиентов\nи мы регулярно получаем от них обратную связь.",
        "CACHE_TIME" => 3600,
    ]
);
?>

<?$APPLICATION->IncludeComponent(
    "leadspace:solutions.list", 
    "other", 
    [
        "IBLOCK_ID" => "3",
        "CACHE_TIME" => "3600",
        "TITLE" => "Посмотреть все готовые решения",
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

<?php
$APPLICATION->IncludeComponent(
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
);
?>

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
