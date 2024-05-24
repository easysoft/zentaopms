<?php
declare(strict_types=1);
namespace zin;

requireWg('thinkQuestion');
/**
 * 思引师表格填空部件类。
 * thinmory tableInput widget class.
 */
class thinkTableInput extends thinkQuestion
{
    protected static array $defineProps = array(
        'requiredRows?: number=1',    // 必填行数
        'fields?: array',             // 行标题
        'isSupportAdd?: bool',        // 是否支持用户添加行
        'canAddRows: number=1',       // 可添加行数
    );

    public static function getPageJS(): string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    public static function getPageCSS(): ?string
    {
        $baseCss = file_get_contents(dirname(__FILE__, 2) . DS . 'thinkstepbase' . DS . 'css' . DS . 'v1.css');
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css') . $baseCss;
    }

    protected function buildDetail(): array
    {
        global $lang;
        $detailWg = parent::buildDetail();
        list($step, $fields, $isSupportAdd, $canAddRows) = $this->prop(array('step', 'fields', 'isSupportAdd', 'canAddRows'));
        if($step)
        {
            if(!empty($step->options->fields)) $step->options->fields = is_string($step->options->fields) ? explode(', ', $step->options->fields) : array_values((array)$step->options->fields);
            if(!empty($step->options->customFields)) $step->options->customFields = is_string($step->options->customFields) ? explode(', ', $step->options->customFields) : array_values((array)$step->options->customFields);
            $customFields = $step->options->customFields ?? array();
            $fields       = $step->options->fields ?? array();
            $isSupportAdd = $step->options->isSupportAdd;
            $canAddRows   = $step->options->canAddRows;
            $answer       = $step->answer;

            $result = isset($answer->result) && !empty($answer->result) ? $answer->result : array();
            $result = is_array($result) ? $result : get_object_vars($result);
        }
        jsVar('canAddRows', (int)$canAddRows);
        jsVar('fieldsCount', count($fields));
        jsVar('deleteTip', $lang->thinkrun->deleteTip);

        $tableInputItems = array();
        foreach($fields as $index => $item)
        {
            $tableInputItems[] = formGroup
            (
                setClass('flex items-center'),
                div
                (
                    setClass('text-right mr-2 w-1/5 line-clamp-2'),
                    $item,
                    set::title($item)
                ),
                textarea
                (
                    set::rows('2'),
                    set::name('result[' . $index . ']'),
                    setClass('mt-2 w-4/5'),
                    set::value(!empty($result) && isset($result[$index]) ? $result[$index] : ''),
                    set::placeholder($lang->thinkrun->pleaseInput)
                ),
                div
                (
                    setClass('flex'),
                    setStyle(array('min-width' => '40px')),
                    ($index == count($fields) - 1 && $isSupportAdd) ? icon
                    (
                        'plus',
                        setClass('mr-1 btn-add ml-2 text-sm text-primary add-rows'),
                        on::click('addRow'),
                        set::title(sprintf($lang->thinkrun->addTips, $canAddRows)),
                    ) : null,
                )
            );
        }

        if(!empty($customFields))
        {
            foreach($customFields as $index => $item)
            {
                $resultIndex = count($fields) + $index;
                $tableInputItems[] = formGroup
                (
                    setClass('flex rows-group flex-nowrap items-center'),
                    textarea
                    (
                        set::rows('2'),
                        setClass('mt-2 w-1/5'),
                        setID('customFields'),
                        set::name('customFields[' . $index + 1 . ']'),
                        set::value($item),
                        set::placeholder($lang->thinkrun->placeholder->rowTitle)
                    ),
                    textarea
                    (
                        set::rows('2'),
                        setClass('mt-2 w-4/5 ml-2'),
                        setID('result'),
                        set::name('result[' . $resultIndex .']'),
                        set::value($result[$resultIndex] ?? ''),
                        set::placeholder($lang->thinkrun->placeholder->rowContent)
                    ),
                    div
                    (
                        setClass('flex'),
                        setStyle(array('min-width' => '40px')),
                        icon
                        (
                            'plus',
                            setClass('mr-1 btn-add ml-2 text-sm text-primary add-rows'),
                            on::click('addRow'),
                            set::title(sprintf($lang->thinkrun->addTips, $canAddRows)),
                        ),
                        icon
                        (
                            setClass('btn-delete text-sm text-primary ml-1'),
                            'trash'
                        )
                    )
                );
            }
        }

        $detailWg[] = array(
            $tableInputItems,
            formGroup
            (
                setClass('flex rows-template flex-nowrap items-center hidden'),
                textarea
                (
                    set::rows('2'),
                    setID('customFields'),
                    setClass('mt-2 w-1/5'),
                    set::value(''),
                    set::placeholder($lang->thinkrun->placeholder->rowTitle)
                ),
                textarea
                (
                    set::rows('2'),
                    setID('result'),
                    setClass('mt-2 w-4/5 ml-2'),
                    set::value(''),
                    set::placeholder($lang->thinkrun->placeholder->rowContent)
                ),
                div
                (
                    setClass('flex'),
                    setStyle(array('min-width' => '40px')),
                    icon
                    (
                        'plus',
                        setClass('mr-1 btn-add ml-2 text-sm text-primary add-rows'),
                        on::click('addRow'),
                        set::title(sprintf($lang->thinkrun->addTips, $canAddRows)),
                    ),
                    icon
                    (
                        setClass('btn-delete text-sm text-primary ml-1'),
                        'trash'
                    )
                )
            )
        );
        return $detailWg;
    }

    protected function buildFormItem(): array
    {
        global $lang, $app;
        $app->loadLang('thinkstep');
        $formItems = parent::buildFormItem();

        list($step, $required, $requiredRows, $isSupportAdd, $canAddRows, $fields) = $this->prop(array('step','required', 'requiredRows', 'isSupportAdd', 'canAddRows', 'fields'));
        if($step)
        {
            $required = $step->options->required;
            if(!empty($step->options->fields)) $step->options->fields = is_string($step->options->fields) ? explode(', ', $step->options->fields) : array_values((array)$step->options->fields);
            $fields = $step->options->fields ?? array();
            $requiredRows = $step->options->requiredRows;
            $isSupportAdd = $step->options->isSupportAdd;
            $canAddRows   = $step->options->canAddRows;

        }

        $formItems[] = array (
            formHidden('options[questionType]', 'tableInput'),
            formRow
            (
                setClass('mb-3'),
                formGroup
                (
                    setClass('w-1/2'),
                    set::label($lang->thinkstep->label->required),
                    radioList
                    (
                        set::name('options[required]'),
                        set::inline(true),
                        set::items($lang->thinkstep->requiredList),
                        set::value($required ? $required : 0),
                        bind::change('changeIsRequired(event)')
                    )
                ),
                formGroup
                (
                    setClass('w-1/2 required-rows', $required ? '' : 'hidden'),
                    set::label($lang->thinkstep->label->requiredRows),
                    set::labelClass('required'),
                    input
                    (
                        set::type('number'),
                        set::name('options[requiredRows]'),
                        set::value($requiredRows),
                        set::placeholder($lang->thinkstep->inputContent),
                        set::min(1),
                        on::input('changeInput')
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
                    radioList
                    (
                        set::name('options[isSupportAdd]'),
                        set::inline(true),
                        set::items($lang->thinkstep->requiredList),
                        set::value($isSupportAdd ? $isSupportAdd : 0),
                        bind::change('changeSupportAdd(event)')
                    )
                ),
                formGroup
                (
                    setClass('w-1/2 can-add-rows', $isSupportAdd ? '' : 'hidden'),
                    set::label($lang->thinkstep->label->canAddRows),
                    set::labelClass('required'),
                    input
                    (
                        set::type('number'),
                        set::name('options[canAddRows]'),
                        set::value($canAddRows),
                        set::placeholder($lang->thinkstep->inputContent),
                        set::min(1),
                        on::input('changeInput')
                    )
                )
            ),
            formGroup
            (
                set::width('full'),
                set::label($lang->thinkstep->label->rowsTitle),
                thinkoptions
                (
                    set(array(
                        'showOther' => false,
                        'data'      => $fields,
                        'name'      => 'options[fields]',
                    ))
                ),
            ),
            $this->children()
        );
        return $formItems;
    }
}
