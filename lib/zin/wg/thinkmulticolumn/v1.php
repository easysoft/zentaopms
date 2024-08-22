<?php
declare(strict_types=1);
namespace zin;

requireWg('thinkQuestion');

class thinkMulticolumn extends thinkQuestion
{
    protected static array $defineProps = array
    (
        'enableOther?: bool',
        'fields?: array',
    );

    protected function buildFormItem(): array
    {
        global $lang, $app;
        $app->loadLang('thinkstep');
        $formItems = parent::buildFormItem();

        list($step, $questionType, $required, $enableOther, $fields) = $this->prop(array('step', 'questionType', 'required', 'enableOther', 'fields'));
        $requiredItems = $lang->thinkstep->requiredList;

        if($step)
        {
            $enableOther = $step->options->enableOther ?? 0;
            $required    = $step->options->required;
            if(!empty($step->options->fields)) $step->options->fields = is_string($step->options->fields) ? explode(', ', $step->options->fields) : array_values((array)$step->options->fields);
            $fields = $step->options->fields ?? array('', '', '', '');
        }

        $formItems[] = array(
            formHidden('options[questionType]', $questionType),
            formGroup
            (
                set::label($lang->thinkstep->label->columnTitle),
                setStyle(array('padding-bottom' => 'calc(4 * var(--space))')),
                thinkMatrixOptions(set::colName('options[fields]'), set::cols($fields))
            ),
        );
        return $formItems;
    }
}
