<?php
declare(strict_types=1);
namespace zin;

/**
 * 工作流触发条件组件。
 * The workflowCondition widget class.
 *
 * @author Yuting Wang
 */
class workflowCondition extends wg
{
    protected static array $defineProps = array
    (
        'data?: object',
        'datasources?: array',
        'fields?: array',
        'module?: string',
        'hasVarName?: bool=true'
    );

    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function build()
    {
        global $app, $lang, $config;

        $app->control->loadModel('workflowhook');

        $data        = $this->prop('data');
        $datasources = $this->prop('datasources');
        $fields      = $this->prop('fields');
        $module      = $this->prop('module');
        $hasVarName  = $this->prop('hasVarName');

        $sqlConditionDatasources = $datasources;
        unset($sqlConditionDatasources['formula']);

        $dataConditionDatasources = $datasources;
        unset($dataConditionDatasources['form']);
        unset($dataConditionDatasources['record']);
        unset($dataConditionDatasources['formula']);

        $conditionHTML = array();
        $conditionHTML[] = formRow
        (
            div(setClass('form-label required'), span(setClass('text'), $lang->workflowhook->type)),
            div
            (
                setClass('form-group-warpper'),
                setData(array('name' => 'conditionType')),
                picker
                (
                    set::name('conditionType'),
                    set::items($lang->workflowhook->typeList),
                    set::value(!empty($data->conditionType) ? $data->conditionType : 'data'),
                    set::required(true),
                    setData(array('on' => 'change', 'call' => 'changeConditionType'))
                )
            )
        );
        $conditionHTML[] = formRow
        (
            setClass('mt-2', !empty($data->conditionType) && $data->conditionType != 'data' ? 'hidden' : ''),
            div
            (
                setClass('form-group-warpper'),
                setData(array('name' => 'conditionsBox')),
                workflowFieldCondition(set::title($lang->workflowhook->field), set::name('conditions'), set::hasLogicalOperator(true), set::datasources($dataConditionDatasources), set::fields($fields), set::module($module), set::data(!empty($data->conditionType) && !empty($data->conditions) && $data->conditionType == 'data' ? $data->conditions : array()))
            )
        );
        $conditionHTML[] = formRow
        (
            setClass('mt-2', !empty($data->conditionType) && $data->conditionType == 'sql' ? '' : 'hidden'),
            div(setClass('form-label required'), span(setClass('text'), $lang->workflowhook->sql)),
            div
            (
                setClass('form-group-warpper'),
                setData(array('name' => 'sql')),
                textarea
                (
                    set::name('sql'),
                    set::rows(5),
                    set::placeholder($lang->workflowhook->placeholder->sql),
                    set::value(!empty($data->conditions->sql) ? $data->conditions->sql : '')
                )
            )
        );
        $conditionHTML[] = $hasVarName ? formRow
        (
            setClass('mt-2', !empty($data->conditionType) && $data->conditionType == 'sql' ? '' : 'hidden'),
            div
            (
                setClass('form-group-warpper'),
                setData(array('name' => 'sqlsBox')),
                workflowFieldCondition(set::title($lang->workflowhook->varName), set::name('sqls'), set::hasLogicalOperator(false), set::datasources($sqlConditionDatasources), set::fields($fields), set::module($module), set::data(!empty($data->conditionType) && $data->conditionType == 'sql' && !empty($data->conditions->sqlVars) ? $data->conditions->sqlVars : array()))
            )
        ) : null;
        $conditionHTML[] = formRow
        (
            setClass('mt-2', !empty($data->conditionType) && $data->conditionType == 'sql' ? '' : 'hidden'),
            div(setClass('form-label required'), span(setClass('text'), $lang->workflowhook->result)),
            div
            (
                setClass('form-group-warpper'),
                setData(array('name' => 'sqlResult')),
                picker
                (
                    set::name('sqlResult'),
                    set::items($lang->workflowhook->resultList),
                    set::value(!empty($data->conditions->sqlResult) ? $data->conditions->sqlResult : 'empty'),
                    set::required(true)
                )
            )
        );
        return $conditionHTML;
    }
}
