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
jsVar('triggerList', $lang->job->triggerTypeList);
jsVar('repoList', $repoList);
jsVar('pageRepoID', $repoID);

$engine = key($lang->job->engineList);
if($repo)
{
    dropmenu(set::objectID($repoID), set::text($repo->name), set::tab('repo'));
    if(isset($lang->job->engineList[strtolower($repo->SCM)]))
    {
        $engine = strtolower($repo->SCM);
    }
    else
    {
        $engine = 'gitlab';
    }
}

formPanel
(
    set::title($lang->job->create),
    setClass('job-form'),
    set::labelWidth('10em'),
    on::click('.add-param', 'addItem'),
    on::click('.delete-param', 'deleteItem'),
    on::click('.custom', 'setValueInput'),
    on::click('select.paramValue', 'changeCustomField'),
    on::change('[name=sonarqubeServer]')->call('window.changeSonarqubeServer'),
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
            set::items($lang->job->engineList),
            set::value($engine),
            on::change('window.changeEngine')
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
            set::items(array()),
            set::width('1/2'),
            on::change('window.changeRepo')
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
        set::id('productBox'),
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
        setClass('sonarqube hidden'),
        formGroup
        (
            set::name('sonarqubeServer'),
            set::label($lang->job->sonarqubeServer),
            set::width('1/2'),
            set::items($sonarqubeServerList),
            set::required(true),
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
    )
);
