<?php
declare(strict_types=1);
/**
 * The putoff view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('weekend', $config->execution->weekend);

$space = common::checkNotCN() ? ' ' : '';
formPanel
(
    set::title($lang->execution->putoff . $space . $lang->executionCommon),
    set::headingClass('status-heading'),
    set::titleClass('form-label .form-grid'),
    set::shadow(false),
    set::actions(array('submit')),
    set::submitBtnText($lang->execution->putoff . $space . $lang->executionCommon),
    to::headingActions
    (
        entityLabel
        (
            setClass('my-3 gap-x-3'),
            set::level(1),
            set::text($execution->name),
            set::entityID($execution->id),
            set::reverse(true),
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->execution->dateRange),
            inputGroup
            (
                input
                (
                    set::control('date'),
                    set::id('begin'),
                    set::name('begin'),
                    set::value($execution->begin),
                    set::placeholder($lang->execution->begin),
                    on::change('computeWorkDays'),
                ),
                $lang->execution->to,
                input
                (
                    set::control('date'),
                    set::id('end'),
                    set::name('end'),
                    set::value($execution->end),
                    set::placeholder($lang->execution->end),
                    on::change('computeWorkDays'),
                ),
            ),
        ),
        formGroup
        (
            set::width('1/2'),
            radioList
            (
                set::name('dayOptions'),
                set::items($lang->execution->endList),
                set::inline(true),
                on::change('computeEndDate'),
            ),
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->execution->days),
            inputControl
            (
                input(set::name('days')),
                to::suffix($lang->execution->day),
                set::suffixWidth(20),
            ),
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->comment),
            editor
            (
                set::name('comment'),
                set::rows('6'),
            )
        )
    )
);
/* ====== Render page ====== */
render('modalDialog');
