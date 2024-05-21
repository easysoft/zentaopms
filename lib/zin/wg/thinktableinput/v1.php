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
        'defaultFields: array',       // 默认行标题
    );

    public static function getPageJS(): string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function buildDetail(): array
    {
        global $lang;
        $detailWg = parent::buildDetail();
        list($step, $fields, $isSupportAdd, $canAddRows) = $this->prop(array('step', 'fields', 'isSupportAdd', 'canAddRows'));
        if($step)
        {
            if(!empty($step->options->fields)) $step->options->fields = is_string($step->options->fields) ? explode(', ', $step->options->fields) : array_values((array)$step->options->fields);
            $fields       = $step->options->fields ?? array();
            $isSupportAdd = $step->options->isSupportAdd;
            $canAddRows   = $step->options->canAddRows;
        }

        jsVar('canAddRows', $canAddRows);

        $tableInputItems = array();
        foreach($fields as $index => $item)
        {
            $tableInputItems[] = formGroup
            (
                setClass('flex items-center'),
                div
                (
                    setClass('text-right mr-2 w-1/5 text-ellipsis line-clamp-2 rows-group'),
                    $item
                ),
                textarea
                (
                    set::rows('2'),
                    set::name('result[' . $index . ']'),
                    setClass('mt-2 w-4/5'),
                    set::placeholder($lang->thinkrun->pleaseInput)
                ),
                div
                (
                    setClass('flex'),
                    setStyle(array('min-width' => '40px')),
                    ($index == count($fields) - 1 && $isSupportAdd) ? icon
                    (
                        'plus',
                        setClass('mr-1 ghost btn-add ml-2 text-sm text-primary add-rows'),
                        on::click('addRow'),
                        toggle::tooltip(array('placement' => 'bottom-end', 'title' => sprintf($lang->thinkrun->addTips, $canAddRows)))
                    ) : null,
                )
            );
        }

        $detailWg[] = array(
            $tableInputItems,
            formGroup
            (
                setClass('flex rows-group rows-template flex-nowrap items-center hidden'),
                textarea
                (
                    set::rows('2'),
                    setClass('mt-2 w-1/5'),
                    set::name('fileds[]'),
                    set::placeholder($lang->thinkrun->pleaseInput)
                ),
                textarea
                (
                    set::rows('2'),
                    setClass('mt-2 w-4/5 ml-2'),
                    set::name('result[]'),
                    set::placeholder($lang->thinkrun->pleaseInput)
                ),
                div
                (
                    setClass('flex'),
                    setStyle(array('min-width' => '40px')),
                    icon
                    (
                        'plus',
                        setClass('mr-1 ghost btn-add ml-2 text-sm text-primary add-rows'),
                        on::click('addRow'),
                        toggle::tooltip(array('placement' => 'bottom-end', 'title' => sprintf($lang->thinkrun->addTips, $canAddRows)))
                    ),
                    icon
                    (
                        setClass('ghost btn-delete text-sm text-primary ml-1'),
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
