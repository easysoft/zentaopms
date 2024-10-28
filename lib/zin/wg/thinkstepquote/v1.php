<?php
declare(strict_types=1);
namespace zin;

class thinkStepQuote extends wg
{
    protected static array $defineProps = array
    (
        'step?: object',           // 整个步骤的对象
        'questionType?: string',   // 问题类型
        'quoteQuestions?: array',  // 引用问题
        'setOption?: bool=false',  // 选项配置方式
        'quoteTitle?: string',     // 列标题
        'citation?: int=1',        // 引用方式
        'selectColumn?: string',   // 选择列
    );

    protected function build(): array
    {
        global $lang, $app;
        $app->loadLang('thinkstep');
        list($step, $setOption, $quoteTitle, $quoteQuestions, $citation, $selectColumn, $questionType) = $this->prop(array('step', 'setOption', 'quoteTitle', 'quoteQuestions', 'citation', 'selectColumn', 'questionType'));
        if($step)
        {
            $setOption    = isset($step->options->setOption) ? $step->options->setOption : false;
            $defaultQuote = !empty($quoteQuestions) ? $quoteQuestions[0]->id : null;
            $quoteTitle   = isset($step->options->quoteTitle) ? $step->options->quoteTitle : $defaultQuote;
            $citation     = isset($step->options->citation) ? $step->options->citation : 1;
            $selectColumn = isset($step->options->selectColumn) ? $step->options->selectColumn : null;
        }
        $quoteQuestionsItems = array();
        if(!empty($quoteQuestions))
        {
            foreach($quoteQuestions as $item)
            {
                $quoteQuestionsItems[] = array('text' => $item->index . '. ' . $item->title, 'value' => $item->id);
            }
        }

        return array
        (
            formRow
            (
                formGroup
                (
                    setClass('w-66'),
                    set::label($questionType == 'checkbox' ? $lang->thinkstep->label->setOption : $lang->thinkstep->label->columnSetOption),
                    radioList
                    (
                        set::name('options[setOption]'),
                        set::inline(true),
                        set::value($setOption),
                        set::items($lang->thinkstep->setOptionList),
                        set::disabled(empty($quoteQuestions)),
                        on::change()
                            ->const('questionType', $questionType)
                            ->do("questionType == 'checkbox' ? changeCheckboxSetOption(target) : changeMulticolumnSetOption(target)")
                    )
                ),
                icon
                (
                    setClass('mt-9 text-gray-400 cursor-pointer ml-1 text-base pt-0.5'),
                    toggle::tooltip(array('placement' => 'top', 'title' => empty($quoteQuestions) ? $lang->thinkstep->tips->quoteTitle : $lang->thinkstep->tips->setOption, 'max-width' => '220px', 'className' => 'text-gray border border-gray-300', 'type' => 'white')),
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
                    on::inited()->call('changeQuoteTitle'),
                    bind::change('changeQuoteTitle()')
                )
            ),
            formRow
            (
                setClass('think-quote quote-citation gap-0', $setOption == 0 ? 'hidden' : ''),
                setdata('citation', $citation),
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
                    set::labelHint($questionType == 'multicolumn' ? $lang->thinkstep->tips->selectColumn : null),
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
        );
    }
}
