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
        formRowGroup(set::className('programParams hidden'), set::title($lang->upgrade->dataMethod), set::items(array())),
        div
        (
            set::className('programParams hidden py-4'),
            radioList(set::className('my-2'), set::name('projectType'), set::inline('true'), set::items($this->lang->upgrade->projectType), set::value($data->projectType), set('data-on', 'change'), set('data-call', 'changeProjectType')),
            div(set::className('createProjectTip text-gray ' . ($data->projectType == 'project' ? '' : 'hidden')), html($lang->upgrade->createProjectTip)),
            div(set::className('createExecutionTip text-gray ' . ($data->projectType == 'execution' ? '' : 'hidden')), html($lang->upgrade->createExecutionTip))
        ),
        formRowGroup(set::title($data->systemMode == 'light' ? $lang->upgrade->setProject : $lang->upgrade->setProgram), set::items(array())),
        div
        (
            set::className('programForm mt-4 form-grid'),
            formGroup
            (
                set::className('programName'),
                set::label($lang->upgrade->programName),
                set::required(true),
                inputGroup
                (
                    picker(set::id('programs'), set::name('programs'), set::items($data->programs), set::value($data->programID), set('data-on', 'change'), set('data-call', 'changePrograms'), set::className('hidden')),
                    input(set::name('programName')),
                    span
                    (
                        set('class', 'input-group-addon'),
                        checkbox(set::name('newProgram'), set::text($lang->upgrade->newProgram), set::value('0'), set::checked(true), set('data-on', 'change'), set('data-call', 'changeNewProgram'))
                    ),
                    input(set::className('hidden'), set::name('programID'))
                )
            ),
            formGroup
            (
                set::className($data->systemMode == 'light' ? 'programStatus hidden' : 'programStatus'),
                set::label($lang->program->common . $lang->program->status),
                inputGroup
                (
                    picker(set::id('programStatus'), set::name('programStatus'), set::items($lang->program->statusList), set::value('wait'))
                )
            ),
            formGroup
            (
                set::className('projectName hidden'),
                set::label($lang->upgrade->projectName),
                inputGroup
                (
                    input(set::name('projectName'), set::value(isset($data->sprintName) ? $data->sprintName : '')),
                    picker(set::id('projects'), set::name('projects'), set::items($data->projects), set::className('picker-field hidden')),
                    span
                    (
                        set('class', 'input-group-addon ' . (count($data->projects) ? '' : 'hidden')),
                        checkbox(set::name('newProject'), set::text($lang->upgrade->newProgram), set::value('0'), set::checked(true), set('data-on', 'change'), set('data-call', 'changeNewProject'))
                    )
                )
            ),
            formGroup
            (
                set::className('programParams hidden projectStatus'),
                set::label($lang->project->status),
                inputGroup
                (
                    picker(set::id('projectStatus'), set::name('projectStatus'), set::items($lang->project->statusList), set::className('picker-field'))
                )
            ),
            formGroup
            (
                set::className('lineName'),
                set::label($lang->upgrade->line),
                inputGroup
                (
                    picker(set::id('lines'), set::name('lines'), set::items($data->lines), set::className('hidden')),
                    input(set::name('lineName'), set::value(isset($data->lineName) ? $data->lineName : '')),
                    span
                    (
                        set('class', 'input-group-addon ' . (count($data->lines) ? '' : 'hidden')),
                        checkbox(set::name('newLine'), set::text($lang->upgrade->newProgram), set::value('0'), set::checked(true), set('data-on', 'change'), set('data-call', 'changeNewLine'))
                    )
                )
            ),
            formGroup
            (
                set::className('programParams hidden'),
                set::label($lang->project->PM),
                inputGroup
                (
                    picker(set::id('PM'), set::name('PM'), set::items($data->users), set::className('picker-field'))
                )
            ),
            formGroup
            (
                set::className('programParams hidden'),
                set::label($lang->project->dateRange),
                set::required(true),
                inputGroup
                (
                    datePicker(set::id('begin'), set::name('begin'), set::value(date('Y-m-d'))),
                    span(set::className('input-group-addon'), $lang->project->to),
                    datePicker(set::id('end'), set::name('end')),
                    span(set::className('input-group-addon'), checkbox(set::name('longTime'), set::value('1'), set::text($lang->project->longTime), set('data-on', 'change'), set('data-call', 'changeLongTime')))
                )
            ),
            formGroup
            (
                set::className('programParams hidden'),
                set::label($lang->project->acl),
                radioList(set::name('programAcl'), set::items($lang->program->aclList), set::value('open')),
                radioList(set::name('projectAcl'), set::className('hidden'), set::items($lang->project->subAclList), set::value('open'))
            )
        ),
        center(set::className('form-actions mt-4'), btn(set::btnType('submit'), set::className('primary'), $lang->save))
    );
};
