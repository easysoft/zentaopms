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
        list($step, $mode, $isRun, $quoteQuestions, $isResult) = $this->prop(array('step', 'mode', 'isRun', 'quoteQuestions', 'isResult'));
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

        $detailWg[] = thinkBaseCheckbox
        (
            set::type($step->options->questionType),
            set::items($items),
            set::name('result[]'),
            set::value($step->options->questionType == 'radio' ? ($result[0] ?? '') : $result),
            set::disabled(($isQutoCheckbox && !$isRun)|| $isResult)
        );
        return $detailWg;
    }

    protected function buildFormItem(): array
    {
        global $lang, $app;
        $app->loadLang('thinkstep');

        $formItems = parent::buildFormItem();
        list($step, $questionType, $required, $enableOther, $fields, $setOption, $quoteTitle, $quoteQuestions, $citation, $selectColumn) = $this->prop(array('step', 'questionType', 'required', 'enableOther', 'fields', 'setOption', 'quoteTitle', 'quoteQuestions', 'citation', 'selectColumn'));

        $requiredItems = $lang->thinkstep->requiredList;
        if($step)
        {
            $enableOther  = $step->options->enableOther ?? 0;
            $required     = $step->options->required;
            $setOption    = isset($step->options->setOption) ? $step->options->setOption : false;
            $defaultQuote = !empty($quoteQuestions) ? $quoteQuestions[0]->id : null;
            $quoteTitle   = isset($step->options->quoteTitle) ? $step->options->quoteTitle : $defaultQuote;
            $citation     = isset($step->options->citation) ? $step->options->citation : 1;
            $selectColumn = isset($step->options->selectColumn) ? $step->options->selectColumn : null;
            if(!empty($step->options->fields)) $step->options->fields = is_string($step->options->fields) ? explode(', ', $step->options->fields) : array_values((array)$step->options->fields);
            $fields = !empty($step->options->fields) ? $step->options->fields :  array('', '', '');
        }
        jsVar('citation', !empty($step->options->citation) ? $step->options->citation : 1);

        $quoteQuestionsItems = array();
        if(!empty($quoteQuestions))
        {
            foreach($quoteQuestions as $item)
            {
                $quoteQuestionsItems[] = array('text' => $item->title, 'value' => $item->id);
            }
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
                            on::change()
                                ->const('maxCountPlaceholder', $lang->thinkstep->placeholder->maxCount)
                                ->const('inputContent', $lang->thinkstep->placeholder->inputContent)
                                ->do("
                                    $('.think-options-field').toggleClass('hidden', target.value == 1);
                                    $('.think-quote').toggleClass('hidden', target.value == 0);
                                    if(target.value == 1)
                                    {
                                        $('.min-count input').val(1).attr('disabled', 'disabled');
                                        $('.max-count input').val('').attr('placeholder', maxCountPlaceholder).attr('disabled', 'disabled');
                                    }
                                    else
                                    {
                                        $('.min-count input').val('').removeAttr('disabled');
                                        $('.max-count input').attr('placeholder', inputContent).removeAttr('disabled');
                                    }
                                    $('.text-danger').remove();
                                    $('.has-error').removeClass('has-error');
                                ")
                        )
                    ),
                    icon
                    (
                        setClass('mt-9 text-gray-400 cursor-pointer ml-1 text-base pt-0.5'),
                        toggle::tooltip(array('placement' => 'top', 'title' => $lang->thinkstep->tips->setOption, 'width' => '220px', 'className' => 'text-gray border border-gray-300', 'type' => 'white')),
                        'help'
                    )
                ),
                formGroup
                (
                    setClass('think-quote', $setOption == 0 ? 'hidden' : ''),
                    set::label($lang->thinkstep->label->quoteTitle),
                    set::labelClass('required'),
                    picker
                    (
                        setdata('quote-questions', $quoteQuestions),
                        setdata('selectColumn', $selectColumn),
                        set(array
                        (
                            'class'       => 'options-quote-title',
                            'name'        => 'options[quoteTitle]',
                            'placeholder' => $lang->thinkstep->placeholder->quoteTitle,
                            'items'       => $quoteQuestionsItems,
                            'value'       => !empty($quoteTitle) && !empty($quoteQuestionsItems) ? $quoteTitle : '',
                            'disabled'    => empty($quoteQuestions),
                            'title'       => empty($quoteQuestions) ? $lang->thinkstep->tips->quoteTitle : null,
                            'required'    => true,
                        )),
                        bind::change('changeQuoteTitle(event)')
                    )
                ),
                formRow
                (
                    setClass('think-quote quote-citation', $setOption == 0 ? 'hidden' : ''),
                    formGroup
                    (
                        setClass('citation'),
                        set::label($lang->thinkstep->label->citation),
                        set::labelClass('required'),
                        radioList
                        (
                            set::name('options[citation]'),
                            set::inline(true),
                            set::value($citation),
                            set::items($lang->thinkstep->citationList)
                        )
                    ),
                    formGroup
                    (
                        setClass('multicolumn-citation w-1/2', $citation != 3 ? 'hidden' : ''),
                        set::label($lang->thinkstep->label->citation),
                        set::labelClass('required'),
                        radioList
                        (
                            set::name('options[citation]'),
                            set::inline(true),
                            set::value($citation),
                            set::items($lang->thinkstep->multiCitationList)
                        )
                    ),
                    formGroup
                    (
                        setClass('select-column', $citation != 3 ? 'hidden' : ''),
                        set::label($lang->thinkstep->label->selectColumn),
                        set::labelClass('required'),
                        picker(
                            set(array(
                                'name'        => 'options[selectColumn]',
                                'placeholder' => $lang->thinkstep->placeholder->quoteTitle,
                                'required'    => true,
                                'items'       => array(),
                                'value'       => $selectColumn
                            ))
                        )
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
