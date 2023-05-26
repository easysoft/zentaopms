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

$createBuild = '';
if(common::hasPriv('build', 'create'))
{
    $createBuild = formGroup
    (
        set::id('createBuildBox'),
        set::width('90px'),
        checkbox
        (
            set::id('createBuild'),
            set::name('createBuild'),
            set::rootClass('ml-4 items-center'),
            set::text($lang->bug->createBuild),
        )
    );
}

/* zin: Define the form in main content. */
formPanel
(
    set::title($lang->bug->resolve),
    set::headingClass('status-heading'),
    to::headingActions
    (
        entityLabel
        (
            setClass('my-3 gap-x-3'),
            set::level(1),
            set::text($bug->title),
            set::entityID($bug->id),
            set::reverse(true),
        )
    ),
    formGroup
    (
        set::width('1/3'),
        set::name('resolution'),
        set::label($lang->bug->resolution),
        set::value(''),
        set::items($lang->bug->resolutionList),
        on::change('setDuplicate'),
    ),
    formRow
    (
        setClass('hidden'),
        set::id('duplicateBugBox'),
        formGroup
        (
            set::width('1/3'),
            set::name('duplicateBug'),
            set::label($lang->bug->duplicateBug),
            set::items(array()),
            set::placeholder($lang->bug->duplicateTip),
            set::value(''),
            set::required(true),
        ),
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::id('newBuildExecutionBox'),
            setClass('hidden'),
            set::label(!empty($execution) && $execution->type == 'kanban' ? $lang->bug->kanban : $lang->build->execution),
            select
            (
                set::name('buildExecution'),
                set::items($executions),
                set::value($bug->execution),
            ),
        ),
        formGroup
        (
            set::width('1/2'),
            set::id('resolvedBuildBox'),
            set::label($lang->bug->resolvedBuild),
            inputGroup
            (
                select
                (
                    set::name('resolvedBuild'),
                    set::value(''),
                    set::items($builds),
                    set::required(true),
                ),
            ),
        ),
        formGroup
        (
            set::width('1/2'),
            inputGroup
            (
                formGroup
                (
                    set::id('newBuildBox'),
                    set::label($lang->bug->resolvedBuild),
                    setClass('hidden'),
                    input
                    (
                        set::name('buildName'),
                        set::value(''),
                        set::required(true)
                    ),
                ),
                $createBuild,
            ),
        ),
    ),
    formGroup
    (
        set::width('1/3'),
        set::label($lang->bug->resolvedDate),
        input
        (
            set::name('resolvedDate'),
            set::type('date'),
            set::value(helper::now()),
            set::required(true)
        ),
    ),
    formGroup
    (
        set::width('1/3'),
        set::name('assignedTo'),
        set::label($lang->bug->assignedTo),
        set::value($bug->assignedTo),
        set::items($users),
    ),
    formGroup
    (
        set::name('files[]'),
        set::label($lang->bug->files),
        set::control('file')
    ),
    formGroup
    (
        set::label($lang->comment),
        set::name('comment'),
        set::control('editor'),
        set::rows(6),
    ),
);
history();

render('modalDialog');
