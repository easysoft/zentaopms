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
        'supportAdd?: bool',          // 是否支持用户添加行
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
        list($step, $fields, $supportAdd, $canAddRows, $mode) = $this->prop(array('step', 'fields', 'supportAdd', 'canAddRows', 'mode'));
        if($mode != 'detail') return array();

        if($step)
        {
            $fields       = $step->options->fields;
            $supportAdd   = $step->options->supportAdd;
            $canAddRows   = $step->options->canAddRows;
            $answer       = $step->answer;
            $result       = isset($answer->result) && !empty($answer->result) ? (array) $answer->result : array();
            $customFields = !empty($answer->customFields) ? get_object_vars($answer->customFields) : array();
        }

        $tableInputItems = array();
        $disabledAdd = !empty($customFields) && (int)$canAddRows <= count($customFields);
        $index = 0;
        foreach($fields as $item)
        {
            $value = !empty($result) && isset($result[$index]) ? $result[$index] : '';
            $tableInputItems[] = formGroup
            (
                setClass('flex items-center'),
                div
                (
                    setClass('text-right mr-2 w-24 line-clamp-2'),
                    $item,
                    set::title($item)
                ),
                div
                (
                    set::title($value),
                    setClass('result-width', $index == 0 ? '' : 'mt-2'),
                    textarea
                    (
                        set::rows('2'),
                        set::name('result[' . $index . ']'),
                        set::value($value),
                        set::placeholder($lang->thinkrun->pleaseInput)
                    )
                ),
                div
                (
                    setClass('flex'),
                    setStyle(array('min-width' => '40px')),
                    ($index == count($fields) - 1 && $supportAdd) ? icon
                    (
                        'plus',
                        setClass('mr-1 btn-add ml-2 text-sm text-primary add-rows cursor-pointer', $disabledAdd ? 'disabled' : ''),
                        setData('canAddRows', $canAddRows),
                        setData('fieldsCount', count($fields)),
                        set::title(sprintf($lang->thinkrun->tips->add, $canAddRows)),
                    ) : null,
                )
            );
            $index ++;
        }
        if(!empty($customFields))
        {
            foreach($customFields as $item)
            {
                $customValue = !empty($result) && isset($result[$index]) ? $result[$index] : '';
                $tableInputItems[] = formGroup
                (
                    setClass('flex rows-group flex-nowrap items-center'),
                    div
                    (
                        set::title($item),
                        setClass('mt-2 w-24'),
                        textarea
                        (
                            set::rows('2'),
                            setID('customFields'),
                            set::name('customFields[' . $index . ']'),
                            set::value($item),
                            set::placeholder($lang->thinkrun->placeholder->rowTitle)
                        )
                    ),
                    div
                    (
                        set::title($customValue),
                        setClass('mt-2 ml-2 result-width'),
                        textarea
                        (
                            set::rows('2'),
                            setID('result'),
                            set::name('result[' . $index .']'),
                            set::value($customValue),
                            set::placeholder($lang->thinkrun->placeholder->rowContent)
                        )
                    ),
                    div
                    (
                        setClass('flex'),
                        setStyle(array('min-width' => '40px')),
                        icon
                        (
                            'plus',
                            setClass('mr-1 btn-add ml-2 text-sm text-primary add-rows', $disabledAdd ? 'disabled' : ''),
                            setData('canAddRows', $canAddRows),
                            setData('fieldsCount', count($fields)),
                            set::title(sprintf($lang->thinkrun->tips->add, $canAddRows)),
                        ),
                        icon
                        (
                            setClass('btn-delete text-sm text-primary ml-1'),
                            setData('canAddRows', $canAddRows),
                            setData('fieldsCount', count($fields)),
                            setData('deleteTip', $lang->thinkrun->tips->delete),
                            'trash'
                        )
                    )
                );
                $index ++;
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
                    setClass('mt-2 w-24'),
                    set::value(''),
                    set::placeholder($lang->thinkrun->placeholder->rowTitle)
                ),
                textarea
                (
                    set::rows('2'),
                    setID('result'),
                    setClass('mt-2 result-width ml-2'),
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
                        setClass('mr-1 btn-add ml-2 text-sm text-primary add-rows cursor-pointer'),
                        setData('canAddRows', $canAddRows),
                        setData('fieldsCount', count($fields)),
                        set::title(sprintf($lang->thinkrun->tips->add, $canAddRows)),
                    ),
                    icon
                    (
                        setClass('btn-delete text-sm text-primary ml-1 cursor-pointer'),
                        setData('canAddRows', $canAddRows),
                        setData('fieldsCount', count($fields)),
                        setData('deleteTip', $lang->thinkrun->tips->delete),
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

        list($step, $required, $requiredRows, $supportAdd, $canAddRows, $fields) = $this->prop(array('step','required', 'requiredRows', 'supportAdd', 'canAddRows', 'fields'));
        if($step)
        {
            $required = $step->options->required;
            if(!empty($step->options->fields)) $step->options->fields = is_string($step->options->fields) ? explode(', ', $step->options->fields) : array_values((array)$step->options->fields);
            $fields = $step->options->fields ?? array();
            $requiredRows = $step->options->requiredRows;
            $supportAdd   = $step->options->supportAdd;
            $canAddRows   = $step->options->canAddRows;
        }

        $formItems[] = array (
            formHidden('options[questionType]', 'tableInput'),
            formRow
            (
                setClass('mb-3'),
                formGroup
                (
                    setClass('w-1/2 step-required'),
                    set::label($lang->thinkstep->label->required),
                    radioList
                    (
                        set::name('options[required]'),
                        set::inline(true),
                        set::items($lang->thinkstep->requiredList),
                        set::value($required),
                        on::change()->toggleClass('.required-rows', 'hidden', 'target.value == 0')
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
                        set::placeholder($lang->thinkstep->placeholder->inputContent),
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
                        set::name('options[supportAdd]'),
                        set::inline(true),
                        set::items($lang->thinkstep->requiredList),
                        set::value($supportAdd ? $supportAdd : 0),
                        on::change()->toggleClass('.can-add-rows', 'hidden', 'target.value == 0')
                    )
                ),
                formGroup
                (
                    setClass('w-1/2 can-add-rows', $supportAdd ? '' : 'hidden'),
                    set::label($lang->thinkstep->label->canAddRows),
                    set::labelClass('required'),
                    input
                    (
                        set::type('number'),
                        set::name('options[canAddRows]'),
                        set::value($canAddRows),
                        set::placeholder($lang->thinkstep->placeholder->inputContent),
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
