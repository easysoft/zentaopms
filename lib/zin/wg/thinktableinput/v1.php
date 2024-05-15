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
        'required?: bool',                          // 是否必填
        'isRequiredName?: string="required"',       // 是否必填对应的name
        'requiredRows?: number=1',                  // 必填行数
        'requiredRowsName?: string="requiredRows"', // 必填行数对应的name
        'rowsTitle?: array',                        // 行标题
        'rowsTitleName: string="fields"',           // 行标题对应的name
        'isSupportAdd?: bool',                      // 是否支持用户添加行
        'isSupportAddName: string="isSupportAdd"',  // 是否支持用户添加行的name
        'canAddRows: number=1',                     // 可添加行数
        'canAddRowsName: string="canAddRows"',      // 可添加行数对应的name
    );

    public static function getPageJS(): string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    private function buildRequiredControl(): wg
    {
        global $lang;
        list($isRequired, $isRequiredName, $requiredRows, $requiredRowsName) = $this->prop(array('required', 'isRequiredName', 'requiredRows', 'requiredRowsName'));
        return formRow
        (
            setClass('mb-3'),
            formGroup
            (
                setClass('w-1/2'),
                set::label($lang->thinkwizard->step->label->required),
                radioList
                (
                    set::name($isRequiredName),
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
                    set::name($requiredRowsName),
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
        list($isSupportAdd, $isSupportAddName, $canAddRows, $canAddRowsName) = $this->prop(array('isSupportAdd', 'isSupportAddName', 'canAddRows', 'canAddRowsName'));
        return formRow
        (
            setClass('mb-3'),
            formGroup
            (
                setClass('w-1/2'),
                set::label($lang->thinkwizard->step->label->isSupportAdd),
                radioList
                (
                    set::name($isSupportAddName),
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
                    set::name($canAddRowsName),
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
        list($rowsTitle, $rowsTitleName) = $this->prop(array('rowsTitle', 'rowsTitleName'));
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
                        'data'      => $rowsTitle,
                        'name'      => $rowsTitleName,
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
