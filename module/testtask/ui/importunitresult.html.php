<?php
declare(strict_types=1);
/**
 * The import unit result view file of testtask module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     testtask
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::title($lang->testtask->importUnitResult),

    on::change('#execution', 'loadExecutionRelated'),

    formRow
    (
        input
        (
            setClass('hidden'),
            set::name('product'),
            set::value($productID)
        ),
        formGroup
        (
            set::label($lang->testtask->execution),
            set::width('1/2'),
            picker
            (
                setID('execution'),
                set::name('execution'),
                set::items($executions),
            )
        ),
        formGroup
        (
            set::label($lang->testtask->build),
            set::width('1/2'),
            set::required(true),
            inputGroup
            (
                picker
                (
                    set::name('build'),
                    set::items($builds)
                ),
                span
                (
                    setClass('input-group-addon' . (!empty($builds) ? ' hidden' : '')),
                    a
                    (
                        set('data-toggle', 'modal'),
                        $lang->build->create
                    )
                )
            )
        )
    ),
    formGroup
    (
        set::label($lang->job->frame),
        set::width('1/2'),
        picker
        (
            set::name('frame'),
            set::items($lang->job->frameList)
        )
    ),
    formGroup
    (
        set::label($lang->testtask->beginAndEnd),
        set::width('1/2'),
        inputGroup
        (
            datePicker
            (
                set::name('begin'),
                set::value(date('Y-m-d')),
                on::change('suitEndDate')
            ),
            $lang->testtask->to,
            datePicker
            (
                set::name('end'),
                set::value(date('Y-m-d', time() + 24 * 3600))
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->testtask->resultFile),
            set::width('1/2'),
            set::required(true),
            fileInput
            (
                set::name('resultFile')
            )
        ),
        div
        (
            $lang->testtask->unitXMLFormat,
            setStyle('height', '32px'),
            setStyle('line-height', '32px'),
            setStyle('padding-left', '8px')
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->testtask->name),
            set::name('name'),
            set::value(sprintf($lang->testtask->titleOfAuto, date('Y-m-d H:i:s')))
        ),
        formGroup
        (
            set::label($lang->testtask->owner),
            set::width('1/3'),
            picker
            (
                set::name('owner'),
                set::items($users),
                set::value($this->app->user->account)
            )
        ),
        formGroup
        (
            set::label($lang->testtask->pri),
            set::width('180px'),
            priPicker
            (
                zui::width('80px'),
                set::name('pri'),
                set::items($lang->testtask->priList),
                set::value(3)
            )
        )
    ),
    formGroup
    (
        set::label($lang->testtask->desc),
        editor
        (
            set::name('desc'),
            set::rows(10)
        )
    ),
    formGroup
    (
        set::label($lang->testtask->mailto),
        mailto(set::items($users))
    )
);

render();
