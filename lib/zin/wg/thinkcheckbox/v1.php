<?php
declare(strict_types=1);
namespace zin;

requireWg('thinkRadio');

/**
 * 多选题型部件类
 * The thinkCheckbox widget class
 */
class thinkCheckbox extends thinkRadio
{
    protected static array $defineProps = array
    (
        'minCountName?: string="minCount"',
        'maxCountName?: string="maxCount"',
        'minCount?: string',
        'maxCount?: string',
    );

    public static function getPageJS(): string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function buildFormItem(): array
    {
        global $lang;
        $formItems = parent::buildFormItem();

        list($step, $minCountName, $minCount, $maxCountName, $maxCount, $required) = $this->prop(array('step', 'minCountName', 'minCount', 'maxCountName', 'maxCount', 'required'));
        if($step) $required = $step->required;
        $className = 'selectable-rows' . (empty($required) ? ' hidden' : '');

        $formItems[] = formRow
        (
            setClass('gap-4'),
            formGroup
            (
                set::label($lang->thinkwizard->step->label->minCount),
                setClass($className),
                input
                (
                    set::placeholder($lang->thinkwizard->step->inputContent),
                    set::type('number'),
                    set::name($minCountName),
                    set::value($minCount),
                ),
            ),
            formGroup
            (
                set::label($lang->thinkwizard->step->label->maxCount),
                setClass($className),
                input
                (
                    set::placeholder($lang->thinkwizard->step->inputContent),
                    set::type('number'),
                    set::name($maxCountName),
                    set::value($maxCount)
                )
            )
        );
        $formItems[] = $this->children();
        return $formItems;
    }
}
