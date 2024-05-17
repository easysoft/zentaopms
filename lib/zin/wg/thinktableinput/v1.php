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
        'required?: bool',            // 是否必填
        'requiredRows?: number=1',    // 必填行数
        'fields?: array',             // 行标题
        'isSupportAdd?: bool',        // 是否支持用户添加行
        'canAddRows: number=1',       // 可添加行数
        'isRequiredValue?: string'    // 是否必填的值
    );

    public static function getPageJS(): string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function buildDetail(): array
    {
        $detailWg = parent::buildDetail();
        list($step, $fields) = $this->prop(array('step', 'fields'));
        if($step)
        {
            if(!empty($step->options->fields)) $step->options->fields = is_string($step->options->fields) ? explode(', ', $step->options->fields) : array_values((array)$step->options->fields);
            $fields = $step->options->fields ?? array();
        }

        $tableInputItems = array();
        foreach($fields as $item)
        {
            $tableInputItems[] = textarea
            (
                set::rows('3'),
                setClass('mt-2'),
                set::name('result[]'),
                set::placeholder($item)
            );
        };

        $detailWg[] = $tableInputItems;
        return $detailWg;
    }

    protected function buildFormItem(): array
    {
        global $lang;
        $formItems = parent::buildFormItem();

        list($step, $isRequired, $requiredRows, $isSupportAdd, $canAddRows, $fields) = $this->prop(array('step','required', 'requiredRows', 'isSupportAdd', 'canAddRows', 'fields'));
        if($step)
        {
            $isRequired = $step->options->required;
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
                    set::label($lang->thinkwizard->step->label->required),
                    radioList
                    (
                        set::name('options[required]'),
                        set::inline(true),
                        set::items($lang->thinkwizard->step->requiredList),
                        set::value($isRequired ? $isRequired : 0),
                        bind::change('changeIsRequired(event)')
                    )
                ),
                formGroup
                (
                    setClass($isRequired ? 'w-1/2 required-rows': 'w-1/2 required-rows hidden'),
                    set::label($lang->thinkwizard->step->label->requiredRows),
                    set::labelClass('required'),
                    input
                    (
                        set::type('number'),
                        set::name('options[requiredRows]'),
                        set::value($requiredRows),
                        set::placeholder($lang->thinkwizard->step->inputContent),
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
                    set::label($lang->thinkwizard->step->label->isSupportAdd),
                    radioList
                    (
                        set::name('options[isSupportAdd]'),
                        set::inline(true),
                        set::items($lang->thinkwizard->step->requiredList),
                        set::value($isSupportAdd ? $isSupportAdd : 0),
                        bind::change('changeSupportAdd(event)')
                    )
                ),
                formGroup
                (
                    setClass($isSupportAdd ? 'w-1/2 can-add-rows' : 'w-1/2 hidden can-add-rows'),
                    set::label($lang->thinkwizard->step->label->canAddRows),
                    set::labelClass('required'),
                    input
                    (
                        set::type('number'),
                        set::name('options[canAddRows]'),
                        set::value($canAddRows),
                        set::placeholder($lang->thinkwizard->step->inputContent),
                        set::min(1),
                        on::input('changeInput')
                    )
                )
            ),
            formGroup
            (
                set::width('full'),
                set::label($lang->thinkwizard->step->label->rowsTitle),
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
