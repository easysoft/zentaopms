<?php
namespace zin;

class clsInitSearchForm {

    public static function formConfig()
    {
        $config = new stdClass();
        $config->action = createLink('search', 'buildQuery');
        $config->method = 'post';

        return $config;
    }

    public static function formFields($fieldParams, $fieldsMap)
    {
        $fields = array();

        foreach($fieldParams as $name => $param)
        {
            $field = new stdClass();
            $field->label        = isset($fieldsMap[$name]) ? $fieldsMap[$name] : '';
            $field->name         = $name;
            $field->control      = $param['control'];
            $field->operator     = $param['operator'];
            $field->defaultValue = '';
            $field->placeholder  = '';
            $field->values       = $param['values'];

            $fields[] = $field;
        }

        return $fields;
    }

    public static function formOperators($operators)
    {
        $ops = array();

        foreach($operators as $val => $title)
        {
            $op = new stdClass();
            $op->value = $val;
            $op->title = $title;

            $ops[] = $op;
        }

        return $ops;
    }

    public static function formAndOrs($andOrs)
    {
        $result = array();

        foreach($andOrs as $val => $title)
        {
            $item = new stdClass();
            $item->value = $val;
            $item->title = $title;

            $result[] = $item;
        }

        return $result;
    }

    public static function formSaveSearch($module)
    {
        global $lang;

        $result = new stdClass();
        $result->text     = $lang->search->saveCondition;
        $result->hasPriv  = hasPriv('search', 'saveQuery');
        $result->config   = array(
            'data-toggle' => 'modal',
            'data-url'    => createLink('search', 'saveQuery', array('module' => $module)),
        );

        return $result;
    }

    public static function formSavedQuery($queries, $account)
    {
        $result = array();
        if(empty($queries)) return $result;

        $hasPriv = hasPriv('search', 'deleteQuery');
        foreach($queries as $query)
        {
            if(!is_object($query)) continue;

            $item = new stdClass();
            $item->id      = $query->id;
            $item->title   = $query->title;
            $item->account = $query->account;
            $item->hasPriv = ($hasPriv && $account == $query->account);

            $result[] = $item;
        }

        return $result;
    }
}

$opts = new stdClass();
$opts->formConfig      = clsInitSearchForm::formConfig();
$opts->fields          = clsInitSearchForm::formFields($fieldParams, $fields);
$opts->operators       = clsInitSearchForm::formOperators($lang->search->operators);
$opts->andOr           = clsInitSearchForm::formAndOrs($lang->search->andor);
$opts->saveSearch      = clsInitSearchForm::formSaveSearch($module);
$opts->savedQuery      = clsInitSearchForm::formSavedQuery($queries, $this->app->user->account);
$opts->groupName       = array($lang->search->group1, $lang->search->group2);
$opts->savedQueryTitle = $lang->search->savedQuery;
$opts->applyQueryURL   = $actionURL;
$opts->deleteQueryURL  = createLink('search', 'deleteQuery', 'queryID=myQueryID');
$opts->formSession     = $formSession;
$opts->module          = $module;
$opts->actionURL       = $actionURL;
$opts->groupItems      = $groupItems;
$opts->onDeleteQuery   = jsRaw('window.onDeleteQuery');

if(empty($opts->savedQuery)) unset($opts->savedQuery);

zui::searchform(set($opts), set::_to('#searchFormPanel'), set::className('shadow'));

jsVar('onDeleteQueryURL', $opts->deleteQueryURL);
jsVar('options',          isset($options) ? $options : null);
jsVar('canSaveQuery',     !empty($_SESSION[$module . 'Query']));
jsVar('formSession',      $_SESSION[$module . 'Form']);
jsVar('onMenuBar',        $onMenuBar);

js($pageJS);

render('fragment');
