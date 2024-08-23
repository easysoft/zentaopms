<?php
declare(strict_types=1);
namespace zin;

requireWg('thinkQuestion');

class thinkMulticolumn extends thinkQuestion
{
    protected static array $defineProps = array
    (
        'fields?: array',
    );

    public static function getPageJS(): string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function buildFormItem(): array
    {
        global $lang, $app;
        $app->loadLang('thinkstep');
        $formItems = parent::buildFormItem();

        list($step, $questionType, $required, $fields, $supportAdd, $canAddRows, $requiredCols) = $this->prop(array('step', 'questionType', 'required', 'fields', 'supportAdd', 'canAddRows', 'requiredCols'));
        $requiredItems = $lang->thinkstep->requiredList;

        $requiredOptions = array();
        if($step)
        {
            $required = isset($step->options->required) ? $step->options->required : 1;
            if(!empty($step->options->fields)) $step->options->fields = is_string($step->options->fields) ? explode(', ', $step->options->fields) : array_values((array)$step->options->fields);
            $fields       = $step->options->fields;
            $requiredCols = $required && isset($step->options->requiredCols) ? $step->options->requiredCols : '';
            $supportAdd   = $step->options->supportAdd;
            $canAddRows   = $supportAdd ? $step->options->canAddRows: '';
            foreach($fields as $key => $field) $requiredOptions[] = array('value' => $key + 1, 'text' => $field);
        }

        $formItems[] = array(
            formHidden('options[questionType]', $questionType),
            formGroup
            (
                set::label($lang->thinkstep->label->columnTitle),
                setStyle(array('padding-bottom' => 'calc(4 * var(--space))')),
                thinkMatrixOptions(set::colName('options[fields]'), set::cols($fields))
            ),
            formRow
            (
                setClass('mb-3'),
                formGroup
                (
                    set::width('1/2'),
                    set::label($lang->thinkstep->label->required),
                    radioList
                    (
                        set::name('options[required]'),
                        set::inline(true),
                        set::value($required),
                        set::items($requiredItems),
                        on::change()->toggleClass('.required-options', 'hidden', 'target.value == 0')
                    )
                ),
                formGroup
                (
                    set::width('1/2'),
                    set::label($lang->thinkstep->label->requiredCol),
                    set::required(true),
                    setClass('required-options'),
                    picker
                    (
                        set::name('options[requiredCols][]'),
                        set::items($requiredOptions),
                        set::value($requiredCols),
                        set::multiple(true)
                    )
                )
            ),
            formRow
            (
                setClass('mb-3'),
                formGroup
                (
                    setClass('w-1/2'),
                    set::label($lang->thinkstep->label->isSupportAdd),
                    set::labelHint($lang->thinkstep->tips->supportAdd),
                    radioList
                    (
                        set::name('options[supportAdd]'),
                        set::inline(true),
                        set::items($lang->thinkstep->requiredList),
                        set::value(is_null($supportAdd) ? 1 : $supportAdd),
                        on::change()->toggleClass('.can-add-rows', 'hidden', 'target.value == 0')
                    )
                ),
                formGroup
                (
                    setClass('w-1/2 can-add-rows', (is_null($supportAdd) || $supportAdd) ? '' : 'hidden'),
                    set::label($lang->thinkstep->label->canAddRows),
                    set::required(true),
                    input
                    (
                        set::type('number'),
                        set::name('options[canAddRows]'),
                        set::value($canAddRows),
                        set::placeholder($lang->thinkstep->placeholder->inputContent),
                        set::min(1),
                        on::input('changeRows')
                    )
                )
            )
        );
        return $formItems;
    }
}
