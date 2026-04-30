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
        'module?: string'
    );

    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function build()
    {
        global $app, $lang, $config;

        list($title, $hasLogicalOperator, $name, $datasources, $fields, $module) = $this->prop(array('title', 'hasLogicalOperator', 'name', 'datasources', 'fields', 'module'));

        $fieldItems = array();
        foreach($fields as $code => $label) $fieldItems[] = array('text' => $label, 'value' => $code);

        jsVar('fieldItems', $fieldItems);
        jsVar('module', $module);
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

        $items = array();
        $items[] = array('label' => $title, 'name' => $hasLogicalOperator ? 'inputGroup' : "{$name}[field]", 'control' => $fieldControl, 'width' => '250px');
        $items[] = array('label' => '',     'name' => "{$name}[operator]",                                   'control' => 'picker', 'items' => $config->workflowhook->operatorList, 'value' => 'equal');
        $items[] = array('label' => '',     'name' => "{$name}[paramType]",                                  'control' => array('control' => 'picker', 'data-on' => 'change', 'data-call' => 'changeFields', 'data-params' => 'event'), 'items' => $datasources, 'value' => 'custom');
        $items[] = array('label' => '',     'name' => "{$name}[param]",                                      'control' => 'input');

        return formBatch
        (
            set::name($name),
            set::minRows(1),
            set::tagName('div'),
            set::actions(array()),
            set::actionsText(''),
            set::items($items),
            set::onRenderRow(jsRaw('renderRowData'))
        );
    }
}
