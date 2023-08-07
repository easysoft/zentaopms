<?php
declare(strict_types=1);
/**
 * The createprogram mode view file of upgrade module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     upgrade
 * @link        https://www.zentao.net
 */
namespace zin;

$createProgram = function($data)
{
    global $lang;
    if($data->systemMode == 'light') unset($lang->project->subAclList['program']);

    return div
    (
        formRowGroup(set::title($lang->upgrade->dataMethod)),
        div
        (
            set::class('programParams py-4'),
            radioList(set::class('my-2'), set::name('projectType'), set::inline('true'), set::items($this->lang->upgrade->projectType, set::value($data->projectType))),
            div(set::class('createProjectTip text-gray ' . ($data->projectType == 'project' ? '' : 'hidden')), html($lang->upgrade->createProjectTip)),
            div(set::class('createExecutionTip text-gray ' . ($data->projectType == 'execution' ? '' : 'hidden')), html($lang->upgrade->createExecutionTip))
        ),
        formRowGroup(set::title($data->systemMode == 'light' ? $lang->upgrade->setProject : $lang->upgrade->setProgram,)),
        div
        (
            set::class('programForm'),
            div
            (
                formGroup
                (
                    inputGroup
                    (
                        span(set::class('form-label w-24'), html("<span class='prj-exist hidden'>{$lang->upgrade->existProject}</span><span class='prj-no-exist'>{$lang->upgrade->programName}</span>")),
                        picker(set::className('hidden'), set::name('programs'), set::items($data->programs), set::value($data->programID)),
                        input(set::name('programName')),
                        span
                        (
                            set('class', 'input-group-addon'),
                            checkbox(set::name('newProgram'), set::text($lang->upgrade->newProgram), set::value(0), set::checked(true))
                        ),
                        input(set::class('hidden'), set::name('programID'))
                    )
                )
            ),
            div
            (
                set::class($systemMode == 'light' ? 'hidden' : ''),
                span(set::class('form-label w-24'), $lang->program->common . $lang->program->status),
                div(picker(set::name('programStatus'), set::items($lang->program->statusList)))
            ),
            div
            (
                set::class('projectName'),
                div($lang->upgrade->existProject . $lang->upgrade->projectName),
                inputGroup
                (
                    picker(set::name('projects'), set::items($data->projects)),
                    input(set::name('projectName'), set::value(isset($data->sprintName) ? $data->sprintName : '')),
                    span
                    (
                        set('class', 'input-group-addon'),
                        checkbox(set::name('newProject'), set::text($lang->upgrade->newProgram), set::value(0), set::checked(true))
                    )
                )
            ),
            div
            (
                set::class('programParams projectStatus'),
                div($lang->project->status),
                div(picker(set::name('projectStatus'), set::items($lang->project->statusList)))
            ),
            div
            (
                set::class('lineName'),
                div($lang->upgrade->existLine . $lang->upgrade->line),
                inputGroup
                (
                    picker(set::name('lines'), set::items($data->lines)),
                    input(set::name('lineName'), set::value(isset($data->lineName) ? $data->lineName : '')),
                    span
                    (
                        set('class', 'input-group-addon'),
                        checkbox(set::name('newLine'), set::text($lang->upgrade->newProgram), set::value(0), set::checked(true))
                    )
                )
            ),
            div
            (
                set::class('programParams'),
                div($lang->project->PM),
                div(picker(set::name('PM'), set::items($data->users)))
            ),
            div
            (
                set::class('programParams'),
                div($lang->project->dateRange),
                inputGroup
                (
                    datePicker(set::name('begin'), set::value(date('Y-m-d'))),
                    span(set::class('input-group-addon'), $lang->project->to),
                    datePicker(set::name('end')),
                    span(set::class('input-group-addon'), checkbox(set::name('longTime'), set::value('1'), set::text($lang->project->longTime))),
                )
            ),
            div
            (
                set::class('programParams'),
                div($lang->project->acl),
                div(radioList(set::name('programAcl'), set::items($lang->program->aclList), set::value('open'))),
                div(radioList(set::name('projectAcl'), set::items($lang->project->subAclList), set::value('open'))),
            ),
        ),
        center(set::class('form-actions'), btn(set::type('submit'), set::class('primary'), $lang->save))
    );
};
