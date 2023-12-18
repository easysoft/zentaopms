<?php
namespace zin;

/* Build operators. */
$operators = array();
foreach($lang->search->operators as $value => $text) $operators[] = array('value' => $value, 'text' => $text);

/* Build conditions. */
$conditions = array();
foreach($fieldParams as $name => $param)
{
    $condition = new stdClass();
    $condition->text            = isset($fields[$name]) ? $fields[$name] : '';
    $condition->name            = $name;
    $condition->control         = $param['control'];
    $condition->defaultOperator = $param['operator'];
    $condition->placeholder     = '';

    if(isset($param['class']) && in_array('date', explode(' ', $param['class'])))
    {
        $condition->control = 'date';
    }

    if(is_array($param['values']))
    {
        $condition->items = array();
        foreach($param['values'] as $value => $text)
        {
            if(empty($text)) continue;
            $condition->items[] = array('value' => $value, 'text' => $text);
        }
    }

    $conditions[] = $condition;
}

/* Build default data. */
$defaultData = array();
if(is_array($formSession))
{
    $index = 1;
    foreach($formSession as $item)
    {
        if(isset($item['field']))
        {
            $defaultData['field' . $index] = $item['field'];
            if(isset($item['operator'])) $defaultData['operator' . $index] = $item['operator'];
            if(isset($item['andOr']))    $defaultData['andOr' . $index] = $item['andOr'];
            if(isset($item['value']))    $defaultData['value' . $index] = $item['value'];
            $index++;
        }
        elseif(isset($item['groupAndOr']))
        {
            $defaultData['groupAndOr'] = $item['groupAndOr'];
        }
    }
}

/* Build saved query list. */
$canSaveQuery = !empty($_SESSION[$module . 'Query']);
$canDeleteQuery = hasPriv('search', 'deleteQuery');
$deleteQueryConfirm = $canDeleteQuery ? $lang->search->confirmDelete : null;
$savedQueryList = array();
if(is_array($queries))
{
    foreach($queries as $query)
    {
        if(!is_object($query)) continue;
        $savedQueryList[] = array('id' => $query->id, 'text' => $query->title);
    }
}

/* Build search form setting. */
$setting = new stdClass();
$setting->actionURL           = $actionURL;
$setting->operators           = $operators;
$setting->conditions          = $conditions;
$setting->defaultData         = $defaultData;
$setting->groupTitles         = array($lang->search->group1, $lang->search->group2);
$setting->andText             = $lang->search->andor['and'];
$setting->orText              = $lang->search->andor['or'];
$setting->searchBtnText       = $lang->search->common;
$setting->resetBtnText        = $lang->search->reset;
$setting->canSaveQuery        = $canSaveQuery;
$setting->canDeleteQuery      = $canDeleteQuery;
$setting->savedQueryList      = $savedQueryList;
$setting->deleteQueryConfirm  = $deleteQueryConfirm;
$setting->saveQueryPanelTitle = $lang->search->savedQuery;
$setting->saveQueryBtnText    = $lang->search->saveCondition;
$setting->onMenuBar           = $onMenuBar;
$setting->formName            = $formName;

/**
 * 渲染 JSON 字符串并发送到客户端。
 * Render setting data to string and send to client.
 */
renderJson($setting);
