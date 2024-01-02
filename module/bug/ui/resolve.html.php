<?php
declare(strict_types=1);
/**
 * The resolve view file of bug module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     bug
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('bugID',     $bug->id);
jsVar('productID', $bug->product);
jsVar('page',      'resolve');
jsVar('released',  $lang->build->released);
jsVar('requiredFields', $config->bug->resolve->requiredFields);

$createBuild = '';
if(common::hasPriv('build', 'create'))
{
    $createBuild = formGroup
    (
        setID('createBuildBox'),
        set::width('1/3'),
        checkbox
        (
            setID('createBuild'),
            set::name('createBuild'),
            set::rootClass('ml-4 items-center'),
            set::text($lang->bug->createBuild)
        )
    );
}

modalHeader();

/* zin: Define the form in main content. */
formPanel
(
    formGroup
    (
        set::width('1/3'),
        set::label($lang->bug->resolution),
        set::required(true),
        picker
        (
            set::name('resolution'),
            set::items($lang->bug->resolutionList),
            on::change('setDuplicate')
        )
    ),
    formRow
    (
        setClass('hidden'),
        setID('duplicateBugBox'),
        formGroup
        (
            set::width('1/3'),
            set::label($lang->bug->duplicateBug),
            picker
            (
                set::name('duplicateBug'),
                set::items(array()),
                set::placeholder($lang->bug->placeholder->duplicate),
                set::value('')
            ),
            set::required(true)
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/3'),
            setID('newBuildExecutionBox'),
            setClass('hidden'),
            set::label(!empty($execution) && $execution->type == 'kanban' ? $lang->bug->kanban : $lang->build->execution),
            set::required(true),
            picker
            (
                set::name('buildExecution'),
                set::items($executions),
                set::value($bug->execution)
            )
        ),
        formGroup
        (
            set::width('1/3'),
            setID('resolvedBuildBox'),
            set::label($lang->bug->resolvedBuild),
            set::required(strpos(",{$config->bug->resolve->requiredFields},", ',resolvedBuild,') !== false),
            picker
            (
                set::name('resolvedBuild'),
                set::value(''),
                set::items($builds)
            )
        ),
        formGroup
        (
            set::width('1/3'),
            setClass('hidden'),
            setID('newBuildBox'),
            set::required(true),
            inputGroup
            (
                formGroup
                (
                    set::label($lang->bug->resolvedBuild),
                    set::required(true),
                    input
                    (
                        set::name('buildName'),
                        set::value('')
                    )
                )
            )
        ),
        $createBuild
    ),
    formGroup
    (
        set::width('1/3'),
        set::label($lang->bug->resolvedDate),
        set::control('datePicker'),
        set::name('resolvedDate'),
        set::value(helper::now())
    ),
    formGroup
    (
        set::width('1/3'),
        set::name('assignedTo'),
        set::label($lang->bug->assignedTo),
        set::value($bug->assignedTo),
        set::items($users)
    ),
    formGroup
    (
        set::label($lang->bug->files),
        upload()
    ),
    formGroup
    (
        set::label($lang->comment),
        set::name('comment'),
        set::control('editor'),
        set::rows(6)
    )
);
hr();
history();

render();
