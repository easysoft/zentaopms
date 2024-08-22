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

    protected function buildFormItem(): array
    {
        global $lang, $app;
        $app->loadLang('thinkstep');
        $formItems = parent::buildFormItem();

        list($step, $questionType, $required, $fields) = $this->prop(array('step', 'questionType', 'required', 'fields'));
        $requiredItems = $lang->thinkstep->requiredList;

        $requiredCols = array();
        if($step)
        {
            $required = $step->options->required;
            if(!empty($step->options->fields)) $step->options->fields = is_string($step->options->fields) ? explode(', ', $step->options->fields) : array_values((array)$step->options->fields);
            $fields       = $step->options->fields ?? array('', '', '', '');
            $requiredCols = $step->options->requiredCols ?? array();
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
                    setClass('required-options'),
                    picker
                    (
                        set::name('options[requiredCols][]'),
                        set::items($requiredCols),
                        set::multiple(true)
                    )
                ),
            )
        );
        return $formItems;
    }
}
