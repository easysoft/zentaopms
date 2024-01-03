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
        formRowGroup(setClass('programParams hidden'), set::title($lang->upgrade->dataMethod), set::items(array())),
        div
        (
            setClass('programParams hidden py-4'),
            radioList(setClass('my-2'), set::name('projectType'), set::inline('true'), set::items($this->lang->upgrade->projectType), set::value($data->projectType), setData(array('on' => 'change', 'call' => 'changeProjectType'))),
            div(setClass('createProjectTip text-gray ' . ($data->projectType == 'project' ? '' : 'hidden')), html($lang->upgrade->createProjectTip)),
            div(setClass('createExecutionTip text-gray ' . ($data->projectType == 'execution' ? '' : 'hidden')), html($lang->upgrade->createExecutionTip))
        ),
        formRowGroup(set::title($data->systemMode == 'light' ? $lang->upgrade->setProject : $lang->upgrade->setProgram), set::items(array())),
        div
        (
            setClass('programForm mt-4 form-horz'),
            formGroup
            (
                setClass('programName'),
                set::label($lang->upgrade->programName),
                set::required(true),
                inputGroup
                (
                    picker(setID('programs'), set::name('programs'), set::items($data->programs), set::value($data->programID), setData(array('on' => 'change', 'call' => 'changePrograms')), setClass('hidden')),
                    input(set::name('programName')),
                    span
                    (
                        setClass('input-group-addon'),
                        checkbox(set::name('newProgram'), set::text($lang->upgrade->newProgram), set::value('0'), set::checked(true), setData(array('on' => 'change', 'call' => 'changeNewProgram')))
                    ),
                    input(setClass('hidden'), set::name('programID'))
                )
            ),
            formGroup
            (
                setClass($data->systemMode == 'light' ? 'programStatus hidden' : 'programStatus'),
                set::label($lang->program->common . $lang->program->status),
                inputGroup
                (
                    picker(setID('programStatus'), set::name('programStatus'), set::items($lang->program->statusList), set::value('wait'))
                )
            ),
            formGroup
            (
                setClass('projectName hidden'),
                set::label($lang->upgrade->projectName),
                inputGroup
                (
                    input(set::name('projectName'), set::value(isset($data->sprintName) ? $data->sprintName : '')),
                    picker(setID('projects'), set::name('projects'), set::items($data->projects), setClass('picker-field hidden')),
                    span
                    (
                        setClass('input-group-addon ' . (count($data->projects) ? '' : 'hidden')),
                        checkbox(set::name('newProject'), set::text($lang->upgrade->newProgram), set::value('0'), set::checked(true), setData(array('on' => 'change', 'call' => 'changeNewProject')))
                    )
                )
            ),
            formGroup
            (
                setClass('programParams hidden projectStatus'),
                set::label($lang->project->status),
                inputGroup
                (
                    picker(setID('projectStatus'), set::name('projectStatus'), set::items($lang->project->statusList), setClass('picker-field'))
                )
            ),
            formGroup
            (
                setClass('lineName'),
                set::label($lang->upgrade->line),
                inputGroup
                (
                    picker(setID('lines'), set::name('lines'), set::items($data->lines), setClass('hidden')),
                    input(set::name('lineName'), set::value(isset($data->lineName) ? $data->lineName : '')),
                    span
                    (
                        setClass('input-group-addon ' . (count($data->lines) ? '' : 'hidden')),
                        checkbox(set::name('newLine'), set::text($lang->upgrade->newProgram), set::value('0'), set::checked(true), setData(array('on' => 'change', 'call' => 'changeNewLine')))
                    )
                )
            ),
            formGroup
            (
                setClass('programParams hidden'),
                set::label($lang->project->PM),
                inputGroup
                (
                    picker(setID('PM'), set::name('PM'), set::items($data->users), setClass('picker-field'))
                )
            ),
            formGroup
            (
                setClass('programParams hidden'),
                set::label($lang->project->dateRange),
                set::required(true),
                inputGroup
                (
                    datePicker(setID('begin'), set::name('begin'), set::value(date('Y-m-d'))),
                    span(setClass('input-group-addon'), $lang->project->to),
                    datePicker(setID('end'), set::name('end')),
                    span(setClass('input-group-addon'), checkbox(set::name('longTime'), set::value('1'), set::text($lang->project->longTime), setData(array('on' => 'change', 'call' => 'changeLongTime'))))
                )
            ),
            formGroup
            (
                setClass('programParams hidden'),
                set::label($lang->project->acl),
                radioList(set::name('programAcl'), set::items($lang->program->aclList), set::value('open')),
                radioList(set::name('projectAcl'), setClass('hidden'), set::items($lang->project->subAclList), set::value('open'))
            )
        ),
        center(setClass('form-actions mt-4'), btn(set::btnType('submit'), setClass('primary'), $lang->save))
    );
};
