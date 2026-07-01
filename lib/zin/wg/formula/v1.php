<?php
declare(strict_types=1);
namespace zin;

/**
 * 公式部件类, 由带data-name属性的按钮触发setFormula函数来调用。
 * The formula widget class.
 *
 * @author Yuting Wang
 */
class formula extends wg
{
    protected static array $defineProps = array
    (
        'flow?: object'
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function build()
    {
        global $app, $lang, $config;

        $flow = $this->prop('flow');

        $modules      = array($flow->module => $flow->name);
        $numberFields = $app->control->loadModel('workflowfield')->getNumberFields($flow->module);
        $moduleFields = array($flow->module => $numberFields);

        $targetBlocks = array();

        $mainLinks = array();
        foreach($numberFields as $fieldCode => $target)
        {
            if(isset($field) && !empty($field->field) && $field->field == $fieldCode) continue;

            $displayTarget = $flow->name . '_' . $target;
            $mainLinks[] = a
            (
                setClass('btn btn-expression'),
                set::href('javascript:;'),
                setData(array('type' => 'target', 'module' => $flow->module, 'field' => $fieldCode, 'text' => $displayTarget, 'on' => 'click', 'call' => 'clickExpression', 'params' => 'event')),
                $displayTarget
            );
        }
        $targetBlocks[] = div(set('module', $flow->module), $mainLinks);

        if(!$flow->parent)
        {
            $subTables = $app->control->loadModel('workflow')->getPairs($flow->module);
            foreach($subTables as $subModule => $tableName)
            {
                $subFields = $app->control->workflowfield->getNumberFields($subModule, true);

                $modules[$subModule]      = $tableName;
                $moduleFields[$subModule] = $subFields;

                $subLinks = array();
                foreach($subFields as $fieldCode => $fieldName)
                {
                    foreach($lang->workflowfield->formula->functions as $function => $label)
                    {
                        $displayTarget = sprintf($label, $tableName, $fieldName);
                        $subLinks[] = a
                        (
                            setClass('btn btn-expression'),
                            set::href('javascript:;'),
                            setData(array('type' => 'target', 'module' => $subModule, 'field' => $fieldCode, 'function' => $function, 'text' => $displayTarget, 'on' => 'click', 'call' => 'clickExpression', 'params' => 'event')),
                            $displayTarget
                        );
                    }
                }
                $targetBlocks[] = div(set('module', $subModule), $subLinks);
            }
        }

        jsVar('modules', $modules);
        jsVar('moduleFields', $moduleFields);
        jsVar('errorMessage', $lang->workflowfield->formula->error);
        jsVar('functions', $lang->workflowfield->formula->functions);

        $operatorLinks = array();
        foreach($config->workflowfield->formula->operators as $operator => $label)
        {
            $operatorLinks[] = a
            (
                setClass('btn btn-expression'),
                set::href('javascript:;'),
                setData(array('type' => 'operator', 'operator' => $operator, 'text' => $label, 'on' => 'click', 'call' => 'clickExpression', 'params' => 'event')),
                $label
            );
        }

        $numberLinks = array();
        foreach($config->workflowfield->formula->numbers as $number)
        {
            $numberLinks[] = a
            (
                setClass('btn btn-expression'),
                set::href('javascript:;'),
                setData(array('type' => 'number', 'value' => (string)$number, 'text' => (string)$number, 'on' => 'click', 'call' => 'clickExpression', 'params' => 'event')),
                (string)$number
            );
        }

        $expressionHTML = array();
        $expressionHTML[] = h::importJs($config->webRoot . 'js/math.js');
        $expressionHTML[] = div
        (
            setID('expressionDIV'),
            setClass('hidden'),
            div(setClass('expression'), span(setClass('item-name'), $lang->workflowfield->formula->common), span('=')),
            div
            (
                setClass('clear-expression'),
                a(setClass('clear-last'), set::href('javascript:;'), setData(array('on' => 'click', 'call' => 'clearLast', 'params' => 'event')), $lang->workflowfield->formula->clearLast),
                a(setClass('clear-all'),  set::href('javascript:;'), setData(array('on' => 'click', 'call' => 'clearAll',  'params' => 'event')), $lang->workflowfield->formula->clearAll)
            ),
            div(setClass('detail'), div(setClass('detail-heading'), $lang->workflowfield->formula->target), div(setClass('detail-content'), $targetBlocks)),
            div(setClass('detail'), div(setClass('detail-heading'), $lang->workflowfield->formula->operator), array_merge($operatorLinks, array(div(setClass('detail-content'))))),
            div(setClass('detail'), div(setClass('detail-heading'), $lang->workflowfield->formula->numbers), div(setClass('detail-content'), $numberLinks)),
            div
            (
                setClass('form-actions text-center'),
                input(setClass('hidden'), set::name('expressionData'), set::value('[]')),
                a(setClass('btn primary save-expression'), set::href('javascript:;'), setData(array('on' => 'click', 'call' => 'saveFormula',   'params' => 'event')), $lang->save),
                a(setClass('btn cancel-expression'),       set::href('javascript:;'), setData(array('on' => 'click', 'call' => 'cancelFormula', 'params' => 'event')), $lang->cancel)
            )
        );
        return $expressionHTML;
    }
}
