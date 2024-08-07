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
        list($step, $mode) = $this->prop(array('step', 'mode'));
        if($mode != 'detail') return array();

        $answer   = $step->answer;
        $result   = isset($answer->result) ? $answer->result : array();
        if(!empty($step->options->fields)) $step->options->fields = is_string($step->options->fields) ? explode(', ', $step->options->fields) : array_values((array)$step->options->fields);

        $fields = $step->options->fields ?? array();
        $items  = array();
        foreach($fields as $field) $items[] = array('text' => $field, 'value' => $field);
        if(!empty($step->options->enableOther)) $items[] = array('text' => $lang->other, 'value' => 'other', 'isOther' => '1', 'other' => isset($answer->other) ? $answer->other : '');

        $detailWg[] = thinkBaseCheckbox
        (
            set::type($step->options->questionType),
            set::items($items),
            set::name('result[]'),
            set::value($step->options->questionType == 'radio' ? ($result[0] ?? '') : $result),
        );
        return $detailWg;
    }

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
            $fields = $step->options->fields ?? array();
        }

        $formItems[] = array(
            formHidden('options[questionType]', $questionType),
            formGroup
            (
                set::label($lang->thinkstep->label->option),
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
                set::label($lang->thinkstep->label->required),
                radioList
                (
                    set::name('options[required]'),
                    set::inline(true),
                    set::value($required),
                    set::items($requiredItems),
                    $questionType == 'checkbox' ? on::change()->toggleClass('.selectable-rows', 'hidden', 'target.value == 0') : null
                )
            ),
            $this->children()
        );
        return $formItems;
    }
}
