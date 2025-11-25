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

$buildExecutionID = $executionID ? $executionID : (isset($noMultipleExecutionID) ? $noMultipleExecutionID : 0);
$hideExecution    = false;
if(isset($noMultipleExecutionID)) $hideExecution = true;
if($app->tab == 'execution' && $executionID) $hideExecution = true;

formPanel
(
    set::title($lang->testtask->create),

    on::change('[name=product]', isset($executionID) ? 'loadProductRelated' : 'loadTestReports(this.value)'),
    on::change('[name=execution]', 'loadExecutionRelated'),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->testtask->product),
        set::className(!isset($executionID) || !empty($product->shadow) ? 'hidden' : ''),
        set::name('product'),
        set::value($productID),
        set::control('picker'),
        set::items($products)
    ),
    $hideExecution ? formHidden('execution', isset($noMultipleExecutionID) ? $noMultipleExecutionID : $executionID) : formGroup
    (
        set::width('1/2'),
        set::label($lang->testtask->execution),
        set::name('execution'),
        set::value($executionID),
        set::control('picker'),
        set::items($executions)
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
                set::value($build)
            ),
            hasPriv('build', 'create') ? span
            (
                set::className('input-group-addon', !empty($buildExecutionID) && empty($builds) ? '' : 'hidden'),
                a
                (
                    setID('buildCreateLink'),
                    set('href', createLink('build', 'create', "executionID=$buildExecutionID&productID={$productID}&projectID={$projectID}")),
                    set('data-toggle', 'modal'),
                    $lang->build->create
                )
            ) : null,
            hasPriv('build', 'create') ? span
            (
                set::className('input-group-addon', !empty($buildExecutionID) && empty($builds) ? '' : 'hidden'),
                a
                (
                    set('href', 'javascript:void(0)'),
                    set('class', 'refresh'),
                    on::click("loadExecutionBuilds($buildExecutionID)"),
                    $lang->refresh
                )
            ) : null
        )
    ),
    formGroup
    (
        setID('typeBox'),
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
    formRow
    (
        formGroup
        (
            set::label($lang->testtask->name),
            set::required(true),
            input
            (
                set::name('name'),
                set::required(true)
            )
        ),
        formGroup
        (
            set::label($lang->testtask->pri),
            set::labelWidth('60px'),
            set::width('40'),
            priPicker
            (
                set::name('pri'),
                set::items($lang->testtask->priList),
                set::value(3)
            )
        )
    ),
    formGroup
    (
        set::label($lang->testtask->desc),
        set::control(array('control' => 'editor', 'templateType' => 'testtask')),
        set::name('desc'),
        set::rows(10)
    ),
    formGroup
    (
        set::label($lang->testtask->files),
        fileSelector()
    ),
    formGroup
    (
        set::label($lang->testtask->mailto),
        mailto(set::items($users))
    ),
    $app->tab == 'project' ? formHidden('project', $projectID) : null
);

render();
