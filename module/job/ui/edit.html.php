<?php
declare(strict_types=1);
/**
 * The edit view file of job module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     job
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('engine', $job->engine);
jsVar('job', $job);
jsVar('repoList', $repoList);
jsVar('triggerList', $lang->job->triggerTypeList);
jsVar('dirs', !empty($dirs) ? $dirs : '');

if($job->engine != 'jenkins') unset($lang->job->frameList['sonarqube']);

if($this->session->repoID)
{
    $repoName = $this->dao->select('name')->from(TABLE_REPO)->where('id')->eq($this->session->repoID)->fetch('name');
    dropmenu(set::objectID($this->session->repoID), set::text($repoName), set::tab('repo'));
}

$repoPairs = array();
foreach($repoList as $repoID => $codeRepo)
{
    if($job->engine == 'jenkins')
    {
        $repoPairs[$repoID] = "[{$codeRepo->SCM}] {$codeRepo->name}";
        continue;
    }

    if(strtolower($codeRepo->SCM) == $job->engine) $repoPairs[$repoID] = "[{$codeRepo->SCM}] {$codeRepo->name}";
}

formPanel
(
    set::title($lang->job->edit),
    setClass('job-form'),
    set::labelWidth('10em'),
    on::click('.dropmenu-list li.tree-item', 'setJenkinsJob'),
    set::actionsClass('w-2/3'),
    formGroup
    (
        set::name('name'),
        set::label($lang->job->name),
        set::value($job->name),
        set::required(true),
        set::width('1/2')
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->job->engine),
            set::name('engine'),
            set::control('static'),
            set::value(zget($lang->job->engineList, $job->engine, ''))
        ),
        formGroup
        (
            setClass('hidden'),
            set::name('engine'),
            set::value($job->engine)
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->job->repo),
            set::required(true),
            set::width('1/2'),
            set::name('repo'),
            set::items($repoPairs),
            set::value($job->repo),
            on::change('changeRepo')
        ),
        formGroup
        (
            setClass('reference hidden'),
            set::labelWidth('5em'),
            set::label($lang->job->branch),
            set::required(true),
            set::name('reference'),
            set::items(!empty($refList) ? $refList : array()),
            set::value(isset($job->reference) ? $job->reference : '')
        )
    ),
    formGroup
    (
        set::id('productBox'),
        set::name('product'),
        set::width('1/2'),
        set::label($lang->job->product),
        set::items($products),
        set::value($job->product)
    ),
    formGroup
    (
        set::name('frame'),
        set::label($lang->job->frame),
        set::items($lang->job->frameList),
        set::value($job->frame),
        set::width('1/2'),
        on::change('changeFrame')
    ),
    $job->engine == 'jenkins' ? formRow
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
                    set::required(true),
                    set::name('jkServer'),
                    set::items($jenkinsServerList),
                    set::value($job->server),
                    on::change('changeJenkinsServer')
                ),
                $lang->job->pipeline,
                input
                (
                    set::name('jkTask'),
                    set::type('hidden'),
                    set::value(zget($job, 'rawPipeline', $job->pipeline))
                ),
                dropmenu
                (
                    setStyle('width', '150px'),
                    set::id('pipelineDropmenu'),
                    set::text(urldecode($job->pipeline ? $job->pipeline : $lang->job->selectPipeline)),
                    $job->pipeline ? set::url($this->createLink('jenkins', 'ajaxGetJenkinsTasks', "jenkinsID={$job->server}")) : set::data(array('' => ''))
                )
            )
        )
    ) : null,
    formRow
    (
        setClass('sonarqube hidden'),
        formGroup
        (
            set::name('sonarqubeServer'),
            set::label($lang->job->sonarqubeServer),
            set::width('1/2'),
            set::items($sonarqubeServerList),
            set::value($job->sonarqubeServer),
            set::required(true),
            on::change('changeSonarqubeServer')
        )
    ),
    formRow
    (
        set::id('sonarProject'),
        setClass('sonarqube', $job->frame == 'sonarqube' && $job->projectKey ? '' : 'hidden'),
        formGroup
        (
            set::name('projectKey'),
            set::width('1/2'),
            set::label($lang->job->projectKey),
            set::items(!empty($sonarqubeProjectPairs) ? $sonarqubeProjectPairs : array()),
            set::value($job->projectKey),
            set::required(true)
        )
    )
);
