<?php
declare(strict_types=1);
namespace zin;

requireWg('thinkNode');
/**
 * 思引师表格填空部件类。
 * thinmory tableInput widget class.
 */
class thinkTableInput extends thinkNode
{
    protected static array $defineProps = array(
        'required?: bool',         // 是否必填
        'requiredRows?: number=1', // 必填行数
        'rowsTitle?: array',       // 行标题
        'isSupportAdd?: bool',     // 是否支持用户添加行
        'canAddRows: number=1',    // 可添加行数
    );

    public static function getPageJS(): string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    private function buildRequiredControl(): wg
    {
        global $lang;
        list($isRequired, $requiredRows) = $this->prop(array('required', 'requiredRows'));
        return formRow
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
        );
    }

    private function buildisSupportAddControl(): wg
    {
        global $lang;
        list($isSupportAdd, $canAddRows) = $this->prop(array('isSupportAdd', 'canAddRows'));
        return formRow
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
        );
    }

    private function buildRowsTitleControl(): wg
    {
        global $lang;
        return formRow
        (
            formGroup
            (
                set::width('full'),
                set::label($lang->thinkwizard->step->label->rowsTitle),
                thinkoptions
                (
                    set(array(
                        'showOther' => false,
                        'data'      => $this->prop('rowsTitle'),
                        'name'      => 'options[fields]',
                    ))
                ),
            )
        );
    }

    protected function buildBody(): array
    {
        $items = parent::buildBody();
        $items[] = $this->buildRowsTitleControl();
        $items[] = $this->buildisSupportAddControl();
        $items[] = $this->buildRequiredControl();

        return $items;
    }
}
