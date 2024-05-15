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
        'requiredName?: string="required"',
        'optionName?: string="fields"',
        'otherName?: string="enableOther"',
        'enableOther?: bool',
        'fields?: array',
    );


    public static function getPageJS(): string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function buildDeatil(): array
    {
        global $lang;
        $detailWg = parent::buildDeatil();
        $step     = $this->prop('step');
        $options  = json_decode($step->options);
        $answer   = json_decode($step->answer);
        $fields   = $options->fields ? explode(', ', $options->fields) : array();
        $items    = array();
        foreach($fields as $field) $items[] = array('text' => $field, 'value' => $field);
        if($options->enableOther) $items[] = array('text' => $lang->thinkwizard->step->other, 'value' => 'other', 'isOther' => '1', 'showText' => isset($answer->other) ? $answer->other : '');

        $detailWg[] = thinkBaseCheckbox
        (
            set::type($options->questionType),
            set::items($items),
            set::name($options->questionType == 'radio' ? 'result' : 'result[]'),
            set::value(isset($answer->result) ? $answer->result : ''),
        );
        return $detailWg;
    }

    protected function buildFormItem(): array
    {
        global $lang;
        $formItems = parent::buildFormItem();

        list($step, $requiredName, $optionName, $otherName, $required, $enableOther, $fields) = $this->prop(array('step', 'requiredName', 'optionName', 'otherName', 'required', 'enableOther', 'fields'));
        $requiredItems = $lang->thinkwizard->step->requiredList;
        if($step)
        {
            $enableOther = $step->enableOther;
            $required    = $step->required;
            $fields      = explode(', ', $step->fields);
        }

        $formItems[] = array(
            formGroup
            (
                set::label($lang->thinkwizard->step->label->option),
                thinkOptions
                (
                    set::name($optionName),
                    set::data($fields),
                    set::otherName($otherName),
                    set::enableOther($enableOther)
                )
            ),
            formGroup
            (
                setStyle(array('display' => 'flex')),
                set::label($lang->thinkwizard->step->label->required),
                radioList
                (
                    set::name($requiredName),
                    set::inline(true),
                    set::value($required),
                    set::items($requiredItems),
                    bind::change('changeIsRequired(event)')
                )
            ),
            $this->children()
        );
        return $formItems;
    }
}
