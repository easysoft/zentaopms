<?php
declare(strict_types=1);
/**
 * The create view file of job module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     job
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('frameList', $lang->job->frameList);
jsVar('repoPairs', $repoPairs);
jsVar('gitlabRepos', $gitlabRepos);
jsVar('dirChange', $lang->job->dirChange);
jsVar('buildTag', $lang->job->buildTag);

if($this->session->repoID)
{
    $repoName = $this->dao->select('name')->from(TABLE_REPO)->where('id')->eq($this->session->repoID)->fetch('name');
    dropmenu(set::objectID($this->session->repoID), set::text($repoName), set::tab('repo'));
}

formPanel
(
    set::title($lang->job->create),
    set::labelWidth('10em'),
    on::click('.add-param', 'addItem'),
    on::click('.delete-param', 'deleteItem'),
    on::click('.custom', 'setValueInput'),
    set::actionsClass('w-2/3'),
    formGroup
    (
        set::name('name'),
        set::label($lang->job->name),
        set::required(true),
        set::width('1/2')
    ),
    formRow
    (
        formGroup
        (
            set::name('engine'),
            set::width('1/2'),
            set::label($lang->job->engine),
            set::required(true),
            set::items(array('' => '') + $lang->job->engineList),
            set::value(''),
            on::change('changeEngine')
        ),
        h::span
        (
            set::id('gitlabServerTR'),
            setClass('hidden leading-8 ml-2'),
            html($lang->job->engineTips->success)
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->job->repo),
            set::required(true),
            set::name('repo'),
            set::items($repoPairs),
            set::width('1/2'),
            on::change('changeRepo')
        ),
        formGroup
        (
            setClass('reference hidden'),
            set::labelWidth('5em'),
            set::label($lang->job->branch),
            set::required(true),
            set::name('reference'),
            set::width('1/2'),
            set::items(array())
        )
    ),
    formGroup
    (
        set::name('product'),
        set::label($lang->job->product),
        set::width('1/2'),
        set::items(array())
    ),
    formGroup
    (
        set::name('frame'),
        set::label($lang->job->frame),
        set::items(array()),
        set::width('1/2'),
        on::change('changeFrame')
    ),
    formRow
    (
        formGroup
        (
            set::name('triggerType'),
            set::width('1/2'),
            set::label($lang->job->triggerType),
            set::items($lang->job->triggerTypeList),
            on::change('changeTriggerType')
        )
    ),
    formRow
    (
        setClass('svn-fields hidden'),
        formGroup
        (
            set::id('svnDirBox'),
            set::width('1/2'),
            set::label($lang->job->svnDir),
            set::control('static'),
        )
    ),
    formRow
    (
        setClass('sonarqube hidden'),
        formGroup
        (
            set::name('sonarqubeServer'),
            set::label($lang->job->sonarqubeServer),
            set::items(array('' => '') +$sonarqubeServerList),
            set::value(''),
            set::width('1/2'),
            set::required(true),
            on::change('changeSonarqubeServer')
        )
    ),
    formRow
    (
        set::id('sonarProject'),
        setClass('sonarqube hidden'),
        formGroup
        (
            set::name('projectKey'),
            set::label($lang->job->projectKey),
            set::width('1/2'),
            set::items(array()),
            set::required(true)
        )
    ),
    formRow
    (
        setClass('comment-fields hidden'),
        formGroup
        (
            set::name('comment'),
            set::width('1/2'),
            set::label($lang->job->comment),
            set::required(true)
        ),
        h::span
        (
            setClass('leading-8 ml-2'),
            html($lang->job->commitEx)
        )
    ),
    formRow
    (
        setClass('custom-fields hidden'),
        formGroup
        (
            set::label(''),
            set::name('atDay[]'),
            set::control('checkListInline'),
            set::items($lang->datepicker->dayNames)
        )
    ),
    formRow
    (
        setClass('custom-fields hidden'),
        formGroup
        (
            set::label(''),
            set::width('1/2'),
            inputGroup
            (
                $lang->job->atTime,
                timePicker
                (
                    set::name('atTime')
                )
            )
        )
    ),
    formRow
    (
        setClass('hidden'),
        set::id('jenkinsServerTR'),
        formGroup
        (
            set::label($lang->job->jkHost),
            set::required(true),
            set::width('1/2'),
            inputGroup
            (
                picker
                (
                    set::name('jkServer'),
                    set::items($jenkinsServerList),
                    on::change('changeJenkinsServer')
                ),
                $lang->job->pipeline,
                input
                (
                    set::name('jkTask'),
                    set::type('hidden')
                ),
                dropmenu
                (
                    setStyle('width', '150px'),
                    set::id('pipelineDropmenu'),
                    set::popPlacement('top'),
                    set::text($lang->job->selectPipeline),
                    set::url($this->createLink('jenkins', 'ajaxGetJenkinsTasks'))
                )
            )
        )
    ),
    formRow
    (
        set::id('paramDiv'),
        formGroup
        (
            set::label($lang->job->customParam),
            set::width('2/3'),
            inputGroup
            (
                $lang->job->paramName,
                input
                (
                    setStyle('width', '50%'),
                    setClass('form-control paramName'),
                    set::name('paramName[]')
                ),
                $lang->job->paramValue,
                select
                (
                    setStyle('width', '25%'),
                    setClass('paramValue'),
                    set::name('paramValue[]'),
                    set::items($lang->job->paramValueList)
                ),
                input
                (
                    setStyle('width', '25%'),
                    setClass('form-control hidden paramValue'),
                    set::name('paramValue[]'),
                    set::disabled(true)
                ),
                span
                (
                    setClass('input-group-addon'),
                    checkbox
                    (
                        setClass('custom'),
                        set::name('custom'),
                        set::text($lang->job->custom)
                    )
                ),
                span
                (
                    setClass('input-group-addon'),
                    h::a
                    (
                        setClass('add-param'),
                        set::href('javascript:void(0)'),
                        icon('plus')
                    )
                ),
                span
                (
                    setClass('input-group-addon'),
                    a
                    (
                        setClass('delete-param'),
                        set::href('javascript:void(0)'),
                        icon('close')
                    )
                )
            )
        )
    )
);

render();

