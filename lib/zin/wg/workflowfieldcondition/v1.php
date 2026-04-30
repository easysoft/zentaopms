<?php
declare(strict_types=1);
namespace zin;

/**
 * 工作流字段联动批量表单组件。
 * The workflowFieldCondition widget class.
 *
 * @author Yuting Wang
 */
class workflowFieldCondition extends wg
{
    protected static array $defineProps = array
    (
        'title?: string',
        'name?: string',
        'hasLogicalOperator?: bool',
        'datasources?: array',
        'fields?: array',
        'module?: string',
        'data?: array'
    );

    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function build()
    {
        global $app, $lang, $config;

        list($title, $hasLogicalOperator, $name, $datasources, $fields, $module, $data) = $this->prop(array('title', 'hasLogicalOperator', 'name', 'datasources', 'fields', 'module', 'data'));

        $fieldItems = array();
        foreach($fields as $code => $label) $fieldItems[] = array('text' => $label, 'value' => $code);

        jsVar('fieldItems', $fieldItems);
        jsVar('moduleName', $module);
        jsVar('setFormula', $lang->workflowhook->formula->set);
        jsVar('datasources', $datasources);

        $app->loadLang('workflowhook');

        $fieldControl = array();
        if($hasLogicalOperator)
        {
            $fieldControl = array('control' => 'inputGroup', 'items' => array(array('control' => 'picker', 'name' => "{$name}[logicalOperator]", 'items' => $lang->workflowhook->logicalOperatorList, 'value' => 'and', 'class' => 'mr-4'), array('control' => 'picker', 'name' => "{$name}[field]", 'items' => $fields, 'data-on' => 'change', 'data-call' => 'changeFields', 'data-params' => 'event')));
        }
        else
        {
            $fieldControl = array('control' => 'picker', 'items' => $fields, 'data-on' => 'change', 'data-call' => 'changeFields', 'data-params' => 'event');
        }

        if($name == 'sqls') $fieldControl = array('control' => 'input', 'data-on' => 'change', 'data-call' => 'changeVarName', 'data-params' => 'event');

        $items = array();
        $items[] = array('label' => $title, 'name' => $hasLogicalOperator ? 'inputGroup' : "{$name}[field]", 'control' => $fieldControl, 'width' => '250px');
        $items[] = array('label' => '',     'name' => "{$name}[operator]",                                   'control' => 'picker', 'items' => $config->workflowhook->operatorList, 'value' => 'equal');
        $items[] = array('label' => '',     'name' => "{$name}[paramType]",                                  'control' => array('control' => 'picker', 'data-on' => 'change', 'data-call' => 'changeFields', 'data-params' => 'event'), 'items' => $datasources, 'value' => $name == 'wheres' ? 'record' : 'custom');
        $items[] = array('label' => '',     'name' => "{$name}[param]",                                      'control' => $name == 'wheres' ? 'picker' : 'input', 'items' => $fields);

        if($data)
        {
            foreach($data as $dataItem)
            {
                $fieldCode           = "{$name}[field]";
                $logicalOperatorCode = "{$name}[logicalOperator]";
                $operatorCode        = "{$name}[operatorCode]";
                $paramTypeCode       = "{$name}[paramType]";
                $paramCode           = "{$name}[param]";

                $dataItem->{$fieldCode}           = zget($dataItem, $name != 'sqls' ? 'field' : 'varName', '');
                $dataItem->{$logicalOperatorCode} = zget($dataItem, 'logicalOperator',                     '');
                $dataItem->{$operatorCode}        = zget($dataItem, 'operator',                            '');
                $dataItem->{$paramTypeCode}       = zget($dataItem, 'paramType',                           '');
                $dataItem->{$paramCode}           = zget($dataItem, 'param',                               '');
            }
        }

        return formBatch
        (
            set::name($name),
            set::minRows(1),
            set::tagName('div'),
            set::actions(array()),
            set::actionsText(''),
            set::items($items),
            set::data($data),
            set::onRenderRow(jsRaw('renderRowData'))
        );
    }
}
