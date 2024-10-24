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
        'setOption?: bool=false',
        'quoteTitle?: string',
        'quoteQuestions?: array',
        'citation?: int=1',
        'selectColumn?: string',
        'isResult?: bool = false',
    );

    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function buildDetail(): array
    {
        global $lang;
        $detailWg = parent::buildDetail();
        list($step, $mode, $isRun, $isResult, $quotedQuestions) = $this->prop(array('step', 'mode', 'isRun', 'isResult', 'quotedQuestions'));
        if($mode != 'detail') return array();

        $answer   = $step->answer;
        $result   = isset($answer->result) ? $answer->result : array();
        if(!empty($step->options->fields)) $step->options->fields = is_string($step->options->fields) ? explode(', ', $step->options->fields) : array_values((array)$step->options->fields);

        $fields = $step->options->fields ?? array();
        $items  = array();

        if(!empty($fields)) foreach($fields as $field) $items[] = array('text' => $field, 'value' => $field);
        if(empty($fields) && !$isRun) $items[] = array('text' => $lang->thinkstep->optionReference, 'disabledPrefix' => true);
        if(!empty($step->options->enableOther)) $items[] = array('text' => $lang->other, 'value' => 'other', 'isOther' => '1', 'other' => isset($answer->other) ? $answer->other : '');
        $isQutoCheckbox = $step->options->questionType == 'checkbox' && !empty($step->options->setOption) && $step->options->setOption == 1;
        $viewDisabled   = $isQutoCheckbox && !$isRun;
        $runDisabled    = $isRun && !empty($quotedQuestions) && !empty($answer->result);

        $detailWg[] = thinkBaseCheckbox
        (
            set::type($step->options->questionType),
            set::items($items),
            set::name('result[]'),
            set::value($step->options->questionType == 'radio' ? ($result[0] ?? '') : $result),
            set::disabled($viewDisabled || $isResult || $runDisabled)
        );
        return $detailWg;
    }

    protected function buildFormItem(): array
    {
        global $lang, $app;
        $app->loadLang('thinkstep');

        $formItems = parent::buildFormItem();
        list($step, $questionType, $required, $enableOther, $fields, $setOption, $quoteQuestions, $quotedQuestions) = $this->prop(array('step', 'questionType', 'required', 'enableOther', 'fields', 'setOption', 'quoteQuestions', 'quotedQuestions'));
        $requiredItems = $lang->thinkstep->requiredList;
        if($step)
        {
            if(!empty($step->options->fields)) $step->options->fields = is_string($step->options->fields) ? explode(', ', $step->options->fields) : array_values((array)$step->options->fields);
            $enableOther = $step->options->enableOther ?? 0;
            $required    = $step->options->required;
            $setOption   = isset($step->options->setOption) ? $step->options->setOption : false;
            $fields      = !empty($step->options->fields) ? $step->options->fields :  array('', '', '');
        }

        $quoteQuestionsItems = array();

        jsVar('maxCountPlaceholder', $lang->thinkstep->placeholder->maxCount);
        jsVar('inputContent', $lang->thinkstep->placeholder->inputContent);

        $formItems[] = array(
            formHidden('options[questionType]', $questionType),
            $questionType == 'checkbox' ? thinkStepQuote(set::step($step), set::questionType($questionType), set::quoteQuestions($quoteQuestions)) : null,
            formGroup
            (
                setClass('think-options-field', ($questionType === 'checkbox' && $setOption == 1) ? 'hidden' : ''),
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
                setClass('step-required'),
                setStyle(array('display' => 'flex')),
                set::label($lang->thinkstep->label->required),
                set::labelHint(!empty($quotedQuestions) ? $lang->thinkstep->tips->required : null),
                radioList
                (
                    set::name('options[required]'),
                    set::inline(true),
                    set::value($required),
                    set::items($requiredItems),
                    set::disabled(!empty($quotedQuestions)),
                    $questionType == 'checkbox' ? on::change()->toggleClass('.selectable-rows', 'hidden', 'target.value == 0') : null
                )
            ),
            $this->children()
        );
        return $formItems;
    }
}
