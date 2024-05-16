<?php
declare(strict_types=1);
namespace zin;

requireWg('thinkQuestion');

/**
 * 单选题型部件类
 * The thinkRadio widget class
 */
class thinkRadio extends thinkQuestion
{
    protected static array $defineProps = array
    (
        'enableOther?: bool',
        'fields?: array',
    );

    protected function buildDetail(): array
    {
        global $lang;
        $detailWg = parent::buildDetail();
        $step     = $this->prop('step');
        $answer   = $step->answer;
        if(!empty($step->options->fields)) $step->options->fields = is_string($step->options->fields) ? explode(', ', $step->options->fields) : array_values((array)$step->options->fields);

        $fields = $step->options->fields ?? array();
        $items  = array();
        foreach($fields as $field) $items[] = array('text' => $field, 'value' => $field);
        if($step->options->enableOther) $items[] = array('text' => $lang->thinkwizard->step->other, 'value' => 'other', 'isOther' => '1', 'showText' => isset($answer->other) ? $answer->other : '');

        $detailWg[] = thinkBaseCheckbox
        (
            set::type($step->options->questionType),
            set::items($items),
            set::name($step->options->questionType == 'radio' ? 'result' : 'result[]'),
            set::value(isset($answer->result) ? $answer->result : ''),
        );
        return $detailWg;
    }

    protected function buildFormItem(): array
    {
        global $lang;
        $formItems = parent::buildFormItem();

        list($step, $question, $required, $enableOther, $fields) = $this->prop(array('step', 'question', 'required', 'enableOther', 'fields'));
        $requiredItems = $lang->thinkwizard->step->requiredList;
        if($step)
        {
            $step->options->fields = (array)$step->options->fields;
            $enableOther = $step->options->enableOther;
            $required    = $step->options->required;
            $fields      = $step->options->fields ? array_values($step->options->fields) : array();
        }

        $formItems[] = array(
            formHidden('options[questionType]', $question),
            formGroup
            (
                set::label($lang->thinkwizard->step->label->option),
                thinkOptions
                (
                    set::name('options[fields]'),
                    set::data($fields),
                    set::otherName('options[enableOther]'),
                    set::enableOther($enableOther)
                )
            ),
            formGroup
            (
                setStyle(array('display' => 'flex')),
                set::label($lang->thinkwizard->step->label->required),
                radioList
                (
                    set::name('options[required]'),
                    set::inline(true),
                    set::value($required),
                    set::items($requiredItems),
                    $question == 'checkbox' ? bind::change('changeIsRequired(event)') : null
                )
            ),
            $this->children()
        );
        return $formItems;
    }
}
