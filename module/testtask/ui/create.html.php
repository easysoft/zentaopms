<?php
declare(strict_types=1);
/**
 * The create view file of testtask module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     testtask
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('projectID', $projectID);
jsVar('multiple', isset($moMultipleExecutionID) ? false : true);

formPanel
(
    set::title($lang->testtask->create),

    on::change('#product', isset($executionID) ? 'loadProductRelated' : 'loadTestReports(this.value)'),
    on::change('#execution', 'loadExecutionRelated'),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->testtask->product),
        set::className(!isset($executionID) || !empty($product->shadow) ? 'hidden' : ''),
        set::name('product'),
        set::value($product->id),
        set::control('picker'),
        set::items($products)
    ),
    isset($noMultipleExecutionID) ? input
    (
        set::type('hidden'),
        set::name('execution'),
        set::value($noMultipleExecutionID)
    ) : formRow
    (
        set::className(($app->tab == 'execution' && $executionID) ? 'hidden' : ''),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->testtask->execution),
            set::name('execution'),
            set::value($executionID),
            set::control('picker'),
            set::items($executions)
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->testtask->build),
        set::required(true),
        inputGroup
        (
            set::seg(true),
            picker
            (
                setID('build'),
                on::change('setExecutionByBuild'),
                set::name('build'),
                set::items($builds),
                set::required(true),
                set::value($build)
            ),
            span
            (
                set::className('input-group-addon', !empty($executionID) && empty($builds) ? '' : 'hidden'),
                a
                (
                    set('href', createLink('build', 'create', "executionID=$executionID&productID={$product->id}&projectID={$projectID}")),
                    set('data-toggle', 'modal'),
                    $lang->build->create
                )
            ),
            span
            (
                set::className('input-group-addon', !empty($executionID) && empty($builds) ? '' : 'hidden'),
                a
                (
                    set('href', 'javascript:void(0)'),
                    set('class', 'refresh'),
                    on::click("loadExecutionBuilds($executionID)"),
                    $lang->refresh
                )
            )
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->testtask->type),
        picker
        (
            set::multiple(true),
            set::name('type[]'),
            set::items($lang->testtask->typeList)
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->testtask->owner),
        set::name('owner'),
        set::control('picker'),
        set::items($users)
    ),
    formgroup
    (
        set::width('1/2'),
        set::label($lang->testtask->members),
        picker
        (
            setid('members'),
            set::name('members[]'),
            set::items($users),
            set::multiple(true)
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->testtask->begin),
        set::required(true),
        inputGroup
        (
            datePicker
            (
                set::id('beginDate'),
                set::name('begin'),
                set::required(true),
                on::change('suitEndDate')
            ),
            $lang->testtask->end,
            datePicker
            (
                set::id('endDate'),
                set::name('end'),
                set::required(true)
            )
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->testtask->status),
        set::name('status'),
        set::required(true),
        set::control('picker'),
        set::items($lang->testtask->statusList)
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->testtask->testreport),
        set::name('testreport'),
        set::control('picker'),
        set::items($testreports)
    ),
    formGroup
    (
        set::label($lang->testtask->name),
        set::required(true),
        inputGroup
        (
            input
            (
                zui::width('1/2'),
                set::name('name'),
                set::required(true)
            ),
            $lang->testtask->pri,
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
        set::control('editor'),
        set::name('desc'),
        set::rows(10)
    ),
    formGroup
    (
        set::label($lang->testtask->files),
        upload()
    ),
    formGroup
    (
        set::label($lang->testtask->mailto),
        picker
        (
            set::multiple(true),
            set::name('mailto[]'),
            set::items($users)
        )
    )
);

render();
