<?php
namespace zin;

/* Build operators. */
$operators = array();
foreach($lang->search->operators as $value => $text) $operators[] = array('value' => $value, 'text' => $text);

/* Build conditions. */
$conditions = array();
foreach($fieldParams as $name => $param)
{
    if(!isset($fields[$name])) continue;

    $condition = new stdClass();
    $condition->text            = $fields[$name];
    $condition->name            = $name;
    $condition->control         = $param['control'];
    $condition->defaultOperator = $param['operator'];
    $condition->placeholder     = '';
    $condition->controlProps    = isset($config->search->controlProps[$module][$name]) ? (array)$config->search->controlProps[$module][$name] : array();
    if($condition->control == 'select' && !isset($condition->controlProps['maxItemsCount'])) $condition->controlProps['maxItemsCount'] = 200;


    if(isset($param['class']) && in_array('date', explode(' ', $param['class'])))
    {
        $condition->control = 'date';
    }

    if(isset($param['class']) && in_array('datetime', explode(' ', $param['class'])))
    {
        $condition->control = 'datetime';
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
    $index          = 1;
    $conditionIndex = 0;
    foreach($formSession as $item)
    {
        if(isset($item['field']))
        {
            if(!isset($fields[$item['field']]))
            {
                $defaultData['field' . $index] = $conditions[$conditionIndex]->name;
                $conditionIndex ++;
            }
            else
            {
                $defaultData['field' . $index] = $item['field'];
                if(isset($item['operator'])) $defaultData['operator' . $index] = $item['operator'];
                if(isset($item['andOr']))    $defaultData['andOr' . $index] = $item['andOr'];
                if(isset($item['value']))    $defaultData['value' . $index] = $item['value'];
            }
            $index++;
        }
        elseif(isset($item['groupAndOr']))
        {
            $defaultData['groupAndOr'] = $item['groupAndOr'];
        }
    }
}

/* Build saved query list. */
$canSaveQuery = !empty($_SESSION[$module . 'Query']) && common::hasPriv('search', 'saveQuery');
$canDeleteQuery = hasPriv('search', 'deleteQuery');
$deleteQueryConfirm = $canDeleteQuery ? $lang->search->confirmDelete : null;
$savedQueryList = array();
if(is_array($queries))
{
    foreach($queries as $query)
    {
        if(!is_object($query)) continue;
        $savedQueryList[] = array('id' => $query->id, 'text' => $query->title, 'noDelete' => $query->account != $app->user->account);
    }
}

/* Build date period list. */
$datePeriods = array();
$dpText      = $lang->datepicker->dpText;
$datePeriods[] = array('type' => 'heading',     'text' => $dpText->TEXT_DATE, 'className' => 'whitespace-nowrap');
$datePeriods[] = array('value' => '$lastWeek',  'text' => $dpText->TEXT_PREV_WEEK);
$datePeriods[] = array('value' => '$thisWeek',  'text' => $dpText->TEXT_THIS_WEEK);
$datePeriods[] = array('value' => '$yesterday', 'text' => $dpText->TEXT_YESTERDAY);
$datePeriods[] = array('value' => '$today',     'text' => $dpText->TEXT_TODAY);
$datePeriods[] = array('value' => '$lastMonth', 'text' => $dpText->TEXT_PREV_MONTH);
$datePeriods[] = array('value' => '$thisMonth', 'text' => $dpText->TEXT_THIS_MONTH);

/* Build search form setting. */
$setting = new stdClass();
$setting->actionURL           = $actionURL;
$setting->operators           = $operators;
$setting->conditions          = $conditions;
$setting->defaultData         = $defaultData;
$setting->groupItemCount      = $config->search->groupItems;
$setting->groupTitles         = array($lang->search->group1, $lang->search->group2);
$setting->andText             = $lang->search->andor['and'];
$setting->orText              = $lang->search->andor['or'];
$setting->searchBtnText       = $lang->search->common;
$setting->resetBtnText        = $lang->search->reset;
$setting->canSaveQuery        = $canSaveQuery ? $this->createLink('search', 'saveQuery', "module=$module&onMenuBar=$onMenuBar") : false;
$setting->canDeleteQuery      = $canDeleteQuery;
$setting->savedQueryList      = $savedQueryList;
$setting->deleteQueryConfirm  = $deleteQueryConfirm;
$setting->saveQueryPanelTitle = $lang->search->savedQuery;
$setting->saveQueryBtnText    = $lang->search->saveCondition;
$setting->onMenuBar           = $onMenuBar;
$setting->formName            = $formName;
$setting->datePeriods         = $datePeriods;

/**
 * 渲染 JSON 字符串并发送到客户端。
 * Render setting data to string and send to client.
 */
renderJson($setting);
