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
        formRowGroup(set::class('programParams hidden'), set::title($lang->upgrade->dataMethod), set::items('')),
        div
        (
            set::class('programParams hidden py-4'),
            radioList(set::class('my-2'), set::name('projectType'), set::inline('true'), set::items($this->lang->upgrade->projectType), set::value($data->projectType), set('data-on', 'change'), set('data-call', 'changeProjectType')),
            div(set::class('createProjectTip text-gray ' . ($data->projectType == 'project' ? '' : 'hidden')), html($lang->upgrade->createProjectTip)),
            div(set::class('createExecutionTip text-gray ' . ($data->projectType == 'execution' ? '' : 'hidden')), html($lang->upgrade->createExecutionTip))
        ),
        formRowGroup(set::title($data->systemMode == 'light' ? $lang->upgrade->setProject : $lang->upgrade->setProgram), set::items('')),
        div
        (
            set::class('programForm mt-4 form-grid'),
            formGroup
            (
                set::class('programName hidden program-exist'),
                set::label($lang->upgrade->existProgram),
                set::required(true),
                inputGroup
                (
                    picker(set::id('programs'), set::name('programs'), set::items($data->programs), set::value($data->programID), set('data-on', 'change'), set('data-call', 'changePrograms')),
                    span
                    (
                        set('class', 'input-group-addon'),
                        checkbox(set::name('newProgram'), set::text($lang->upgrade->newProgram), set::value(1), set::checked(true), set('data-on', 'change'), set('data-call', 'changeNewProgram'))
                    ),
                )
            ),
            formGroup
            (
                set::class('programName program-no-exist'),
                set::label($lang->upgrade->programName),
                set::required(true),
                inputGroup
                (
                    input(set::name('programName')),
                    span
                    (
                        set('class', 'input-group-addon'),
                        checkbox(set::name('newProgram'), set::text($lang->upgrade->newProgram), set::value(0), set::checked(true), set('data-on', 'change'), set('data-call', 'changeNewProgram'))
                    ),
                    input(set::class('hidden'), set::name('programID'))
                )
            ),
            formGroup
            (
                set::class($data->systemMode == 'light' ? 'programStatus hidden' : 'programStatus'),
                set::label($lang->program->common . $lang->program->status),
                inputGroup
                (
                    picker(set::id('programStatus'), set::name('programStatus'), set::items($lang->program->statusList), set::value('wait'))
                )
            ),
            formGroup
            (
                set::class('projectName hidden project-exist'),
                set::label($lang->upgrade->existProject),
                inputGroup
                (
                    picker(set::id('projects'), set::name('projects'), set::items($data->projects), set::class('picker-field')),
                    span
                    (
                        set('class', 'input-group-addon'),
                        checkbox(set::name('newProject'), set::text($lang->upgrade->newProgram), set::value(1), set::checked(true), set('data-on', 'change'), set('data-call', 'changeNewProject'))
                    )
                )
            ),
            formGroup
            (
                set::class('projectName hidden project-no-exist'),
                set::label($lang->upgrade->projectName),
                inputGroup
                (
                    input(set::name('projectName'), set::value(isset($data->sprintName) ? $data->sprintName : '')),
                    span
                    (
                        set('class', 'input-group-addon'),
                        checkbox(set::name('newProject'), set::text($lang->upgrade->newProgram), set::value(0), set::checked(true), set('data-on', 'change'), set('data-call', 'changeNewProject'))
                    )
                )
            ),
            formGroup
            (
                set::class('programParams hidden projectStatus'),
                set::label($lang->project->status),
                inputGroup
                (
                    picker(set::id('projectStatus'), set::name('projectStatus'), set::items($lang->project->statusList), set::class('picker-field'))
                )
            ),
            formGroup
            (
                set::class('lineName hidden line-exist'),
                set::label($lang->upgrade->existLine),
                inputGroup
                (
                    picker(set::id('lines'), set::name('lines'), set::items($data->lines)),
                    span
                    (
                        set('class', 'input-group-addon'),
                        checkbox(set::name('newLine'), set::text($lang->upgrade->newProgram), set::value(1), set::checked(true), set('data-on', 'change'), set('data-call', 'changeNewLine'))
                    )
                )
            ),
            formGroup
            (
                set::class('lineName line-no-exist'),
                set::label($lang->upgrade->line),
                inputGroup
                (
                    input(set::name('lineName'), set::value(isset($data->lineName) ? $data->lineName : '')),
                    span
                    (
                        set('class', 'input-group-addon'),
                        checkbox(set::name('newLine'), set::text($lang->upgrade->newProgram), set::value(0), set::checked(true), set('data-on', 'change'), set('data-call', 'changeNewLine'))
                    )
                )
            ),
            formGroup
            (
                set::class('programParams hidden'),
                set::label($lang->project->PM),
                inputGroup
                (
                    picker(set::id('PM'), set::name('PM'), set::items($data->users), set::class('picker-field'))
                )
            ),
            formGroup
            (
                set::class('programParams hidden'),
                set::label($lang->project->dateRange),
                set::required(true),
                inputGroup
                (
                    datePicker(set::id('begin'), set::name('begin'), set::value(date('Y-m-d'))),
                    span(set::class('input-group-addon'), $lang->project->to),
                    datePicker(set::id('end'), set::name('end')),
                    span(set::class('input-group-addon'), checkbox(set::name('longTime'), set::value('1'), set::text($lang->project->longTime), set('data-on', 'change'), set('data-call', 'changeLongTime'))),
                )
            ),
            formGroup
            (
                set::class('programParams hidden'),
                set::label($lang->project->acl),
                radioList(set::name('programAcl'), set::items($lang->program->aclList), set::value('open')),
                radioList(set::name('projectAcl'), set::class('hidden'), set::items($lang->project->subAclList), set::value('open')),
            )
        ),
        center(set::class('form-actions mt-4'), btn(set::btnType('submit'), set::class('primary'), $lang->save))
    );
};
