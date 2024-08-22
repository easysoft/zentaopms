<?php
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
    );

    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function buildDetail(): array
    {
        global $lang;
        $detailWg = parent::buildDetail();
        list($step, $mode) = $this->prop(array('step', 'mode'));
        if($mode != 'detail') return array();
        jsVar('maxCountPlaceholder', $lang->thinkstep->placeholder->maxCount);

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

        list($step, $questionType, $required, $enableOther, $fields, $setOption, $quoteTitle, $quoteQuestions, $citation) = $this->prop(array('step', 'questionType', 'required', 'enableOther', 'fields', 'setOption', 'quoteTitle', 'quoteQuestions', 'citation'));
        $requiredItems = $lang->thinkstep->requiredList;
        if($step)
        {
            $enableOther = $step->options->enableOther ?? 0;
            $required    = $step->options->required;
            $setOption   = isset($step->options->setOption) ? $step->options->setOption : false;
            $quoteTitle  = isset($step->options->quoteTitle) ? $step->options->quoteTitle : null;
            if(!empty($step->options->fields)) $step->options->fields = is_string($step->options->fields) ? explode(', ', $step->options->fields) : array_values((array)$step->options->fields);
            $fields = $step->options->fields ?? array();
        }

        $formItems[] = array(
            formHidden('options[questionType]', $questionType),
            $questionType == 'checkbox' ? array
            (
                formRow
                (
                    formGroup
                    (
                        setClass('w-66'),
                        set::label($lang->thinkstep->label->setOption),
                        radioList
                        (
                            set::name('options[setOption]'),
                            set::inline(true),
                            set::value($setOption),
                            set::items($lang->thinkstep->setOptionList),
                            bind::change('changeOption(event)')
                        )
                    ),
                    icon
                    (
                        setClass('mt-10 text-gray-400 cursor-pointer ml-1'),
                        toggle::tooltip(array('placement' => 'top', 'title' => $lang->thinkstep->tips->setOption, 'width' => '220px')),
                        'help'
                    )
                ),
                formGroup
                (
                    setClass('think-quote-title', $setOption == 0 ? 'hidden' : ''),
                    set::label($lang->thinkstep->label->quoteTitle),
                    set::labelClass('required'),
                    picker
                    (
                        set(array
                        (
                            'name'        => 'options[quoteTitle]',
                            'placeholder' => $lang->thinkstep->placeholder->quoteTitle,
                            'items'       => $quoteQuestions,
                            'value'       => $quoteTitle
                        ))
                    )
                ),
                formGroup
                (
                    setClass('think-quote-title', $setOption == 0 ? 'hidden' : ''),
                    set::label($lang->thinkstep->label->citation),
                    set::labelClass('required'),
                    radioList
                    (
                        set::name('options[citation]'),
                        set::inline(true),
                        set::value($citation),
                        set::items($lang->thinkstep->citationList)
                    )
                )
            ) : null,
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
