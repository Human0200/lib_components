<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class FeaturesComponent extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $arParams['TITLE'] = $arParams['TITLE'] ?? 'Особенности проекта';
        $arParams['TAGLINE_ROW1'] = $arParams['TAGLINE_ROW1'] ?? 'Одной из сложных, но ключевых задач проекта стало внедрение сложного процесса согласования участия в тендерах.';
        $arParams['TAGLINE_ROW2'] = $arParams['TAGLINE_ROW2'] ?? 'полный набор инструментов Битрикс24 для работы';
        $arParams['FEATURES_LIST'] = $arParams['FEATURES_LIST'] ?? "Параметры автоматической фильтрации сделок, которые помогли оценивать тендеры на основе бюджета, сроков и условий.\nМногоступенчатый процесс согласования, который включал несколько участников и этапов, с возможностью возврата заявки на доработку и обязательным контролем на всех уровнях.\nУведомления и задачи для ответственных лиц, что обеспечило четкую координацию всех участников процесса и своевременное выполнение задач";
        
        // Преобразуем текст с переносами строк в массив
        $arParams['FEATURES_ARRAY'] = explode("\n", $arParams['FEATURES_LIST']);
        $arParams['FEATURES_ARRAY'] = array_map('trim', $arParams['FEATURES_ARRAY']);
        $arParams['FEATURES_ARRAY'] = array_filter($arParams['FEATURES_ARRAY']);
        
        return $arParams;
    }

    public function executeComponent()
    {
        $this->includeComponentTemplate();
    }
}
?>