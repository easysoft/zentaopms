<?php
declare(strict_types=1);
/**
 * The thinkStep widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yu Zhang<zhangyu@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */

namespace zin;

/**
 * 思引师基础表单。
 * thinmory table completion forms.
 *
 * @author Yu Zhang
 */

class thinkTableCompletion extends wg
{
    protected static array $defineProps = array(
        'isRequired?: bool',         // 是否必填
        'isRequiredName: string',    // 是否必填对应的name
        'requiredRows?: number',     // 必填行数
        'requiredRowsName: string',  // 必填行数对应的name
        'rowsTitle?: array',         // 行标题
        'rowsName: string',          // 行标题对应的name
        'isSupportAdd: bool',        // 是否支持用户添加行
        'isSupportAddName: string',  // 是否支持用户添加行的name
        'usersAddRows: number',      // 可添加行数
        'canAddRows: number',        // 可添加行数
        'canAddRowsName: string',    // 可添加行数对应的name
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    private function buildRequiredControl(): wg
    {
        global $lang;
        list($isRequired, $isRequiredName, $requiredRows, $requiredRowsName) = $this->prop(array('isRequired', 'isRequiredName', 'requiredRows', 'requiredRowsName'));
        return formRow
        (
            formGroup
            (
                setClass('w-1/2'),
                set::label($lang->thinkmodel->isRequired),
                radioList
                (
                    set::name($isRequiredName),
                    set::inline(true),
                    set::items($lang->thinkmodel->selectList),
                    set::value($isRequired ? $isRequired : 0)
                )
            ),
            formGroup
            (
                setClass('w-1/2'),
                set::label($lang->thinkmodel->requiredRows),
                input
                (
                    set::name($requiredRowsName),
                    set::value($requiredRows),
                    set::placeholder($lang->thinkmodel->inputPlaceholder),
                )
            )
        );
    }

    private function buildisSupportAddControl(): wg
    {
        global $lang;
        list($isSupportAdd, $isSupportAddName, $canAddRows, $canAddRowsName) = $this->prop(array('isSupportsAdd', 'isSupportAddName', 'canAddRows', 'canAddRowsName'));
        return formRow
        (
            formGroup
            (
                setClass('w-1/2'),
                set::label($lang->thinkmodel->isSupportAdd),
                radioList
                (
                    set::name($isSupportAddName),
                    set::inline(true),
                    set::items($lang->thinkmodel->selectList),
                    set::value($isSupportAdd ? $isSupportAdd : 0)
                )
            ),
            formGroup
            (
                setClass('w-1/2'),
                set::label($lang->thinkmodel->canAddRows),
                input
                (
                    set::name($canAddRows),
                    set::value($canAddRowsName),
                    set::placeholder($lang->thinkmodel->inputPlaceholder),
                )
            )
        );
    }

    private function buildRowsTitleControl(): wg
    {
        global $lang;
        list($rowsTitle, $rowsName) = $this->prop(array('rowsTitle', 'rowsName'));
        return formRow
        (
            formGroup
            (
                set::width('full'),
                set::label($lang->thinkmodel->rowsTitle),
                div
                (
                    setClass('flex rows-item'),
                    div
                    (
                        setClass('rows-index'),
                        1
                    ),
                    input
                    (
                        set::name($rowsName),
                        set::placeholder($lang->thinkmodel->rowsTitlePlaceholder)
                    ),
                    div
                    (
                        setClass('rows-icon flex justify-center items-center'),
                        icon('plus', setClass('text-sm')),
                    ),
                    div
                    (
                        setClass('rows-icon flex justify-center items-center'),
                        icon('trash', setClass('text-sm')),
                    ),
                    div
                    (
                        setClass('rows-icon flex justify-center items-center'),
                        icon('move', setClass('text-sm')),
                    )
                )
            )
        );
    }

    protected function build()
    {
        return div
        (
            $this->buildRequiredControl(),
            $this->buildisSupportAddControl(),
            $this->buildRowsTitleControl(),
        );
    }
}
