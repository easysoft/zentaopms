<?php
declare(strict_types=1);
/**
 * The activate view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

$space = common::checkNotCN() ? ' ' : '';
formPanel
(
    set::title($lang->execution->activate . $space . $lang->executionCommon),
    set::headingClass('status-heading'),
    set::titleClass('form-label .form-grid'),
    set::shadow(false),
    set::actions(array('submit')),
    set::submitBtnText($lang->execution->activate . $space . $lang->executionCommon),
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
            set::label($lang->execution->beginAndEnd),
            inputGroup
            (
                input
                (
                    set::control('date'),
                    set::name('begin'),
                    set::value($newBegin),
                ),
                $lang->execution->to,
                input
                (
                    set::control('date'),
                    set::name('end'),
                    set::value($newEnd),
                ),
            )
        ),
        formGroup
        (
            set::width('1/2'),
            setClass('items-center'),
            checkbox(
                set::name('readjustTask'),
                set::text($lang->execution->readjustTask),
                set::value(1),
                set::rootClass('ml-4'),
            )
        ),
    ),
    formGroup
    (
        set::label($lang->comment),
        editor
        (
            set::name('comment'),
            set::rows('6'),
        ),
    ),
);

h::hr(set::class('mt-6'));

history();
/* ====== Render page ====== */
render('modalDialog');
