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

        list($step, $minCount, $maxCount, $required) = $this->prop(array('step', 'minCount', 'maxCount', 'required'));
        if($step)
        {
            $required = $step->options->required;
            $minCount = $step->options->minCount;
            $maxCount = $step->options->maxCount;
        }
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
                    set::name('options[minCount]'),
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
                    set::name('options[maxCount]'),
                    set::value($maxCount)
                )
            )
        );
        $formItems[] = $this->children();
        return $formItems;
    }
}
