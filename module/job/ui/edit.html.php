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

jsVar('frameList', $lang->job->frameList);
jsVar('repoPairs', $repoPairs);
jsVar('gitlabRepos', $gitlabRepos);
jsVar('engine', $job->engine);
jsVar('job', $job);
jsVar('dirChange', $lang->job->dirChange);
jsVar('buildTag', $lang->job->buildTag);

if($job->customParam)
{
    $customParam = array();
    $i = 1;
    foreach(json_decode($job->customParam) as $paramName => $paramValue)
    {
        $isCustom = zget($lang->job->paramValueList, $paramValue, '') ? false : true;
        $customParam[] =  inputGroup
        (
            $lang->job->paramName,
            input
            (
                setStyle('width', '50%'),
                setClass('form-control'),
                set::id("paramName{$i}"),
                set::name('paramName[]'),
                set::value($paramName),
            ),
            $lang->job->paramValue,
            select
            (
                setStyle('width', '25%'),
                setClass('paramValue ' . ($isCustom ? 'hidden' : '')),
                set::id("paramValue{$i}"),
                set::name('paramValue[]'),
                !$isCustom ? set::value($paramValue) : null,
                set::items($lang->job->paramValueList),
                $isCustom ? set::disabled(true) : null,
            ),
            input
            (
                setStyle('width', '25%'),
                setClass('form-control paramValue ' . (!$isCustom ? 'hidden' : '')),
                set::id("paramValueInput{$i}"),
                set::name('paramValue[]'),
                $isCustom ? set::value($paramValue) : null,
                !$isCustom ? set::disabled(true) : null,
            ),
            span
            (
                setClass('input-group-addon'),
                checkbox
                (
                    setClass('custom'),
                    set::id("custom{$i}"),
                    set::name('custom'),
                    set::text($lang->job->custom),
                    set::checked($isCustom),
                ),
            ),
            span
            (
                setClass('input-group-addon'),
                h::a
                (
                    setClass('add-param'),
                    set::href('javascript:void(0)'),
                    icon('plus'),
                ),
            ),
            span
            (
                setClass('input-group-addon'),
                a
                (
                    setClass('delete-param'),
                    set::href('javascript:void(0)'),
                    icon('close'),
                ),
            ),
        );
        $i++;
    }
}

if($this->session->repoID)
{
    $repoName = $this->dao->select('name')->from(TABLE_REPO)->where('id')->eq($this->session->repoID)->fetch('name');
    dropmenu(set::objectID($this->session->repoID), set::text($repoName), set::tab('repo'));
}

formPanel
(
    set::title($lang->job->edit),
    set::labelWidth('10em'),
    on::click('.add-param', 'addItem'),
    on::click('.delete-param', 'deleteItem'),
    on::click('.custom', 'setValueInput'),
    formGroup
    (
        set::name('name'),
        set::label($lang->job->name),
        set::value($job->name),
        set::required(true),
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->job->engine),
            set::control('static'),
            set::value(zget($lang->job->engineList, $job->engine, '')),
        ),
        formGroup
        (
            setClass('hidden'),
            set::name('engine'),
            set::value($job->engine),
        ),
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->job->repo),
            set::required(true),
            set::name('repo'),
            set::items($repoPairs),
            set::value($job->repo),
            on::change('changeRepo'),
        ),
        formGroup
        (
            $job->engine == 'jenkins' ? setClass('reference hidden') : setClass('reference hidden'),
            set::labelWidth('5em'),
            set::label($lang->job->branch),
            set::required(true),
            set::name('reference'),
            set::items($refList),
            set::value(isset($job->reference) ? $job->reference : ''),
        ),
    ),
    formGroup
    (
        set::name('product'),
        set::label($lang->job->product),
        set::items($products),
        set::value($job->product),
    ),
    formGroup
    (
        set::name('frame'),
        set::label($lang->job->frame),
        set::items(array()),
        on::change('changeFrame'),
    ),
    formRow
    (
        formGroup
        (
            set::name('triggerType'),
            set::label($lang->job->triggerType),
            set::items($lang->job->triggerTypeList),
            set::value($job->triggerType),
            on::change('changeTriggerType'),
        ),
    ),
    formRow
    (
        setClass('svn-fields hidden'),
        formGroup
        (
            set::name('svnDir[]'),
            set::label($lang->job->svnDir),
            set::control('select'),
        ),
    ),
    formRow
    (
        setClass('sonarqube hidden'),
        formGroup
        (
            set::name('sonarqubeServer'),
            set::label($lang->job->sonarqubeServer),
            set::items(array('' => '') +$sonarqubeServerList),
            set::value($job->sonarqubeServer),
            set::required(true),
            on::change('changeSonarqubeServer'),
        ),
    ),
    formRow
    (
        set::id('sonarProject'),
        setClass('sonarqube hidden'),
        formGroup
        (
            set::name('projectKey'),
            set::label($lang->job->projectKey),
            set::items(array()),
            set::required(true),
        ),
    ),
    formRow
    (
        setClass('comment-fields hidden'),
        formGroup
        (
            set::name('comment'),
            set::label($lang->job->comment),
            set::value($job->comment),
            set::required(true),
        ),
        h::span
        (
            setClass('leading-8 ml-2'),
            html($lang->job->commitEx),
        ),
    ),
    formRow
    (
        setClass('custom-fields hidden'),
        formGroup
        (
            set::label(''),
            set::name('atDay'),
            set::control('checkListInline'),
            set::items($lang->datepicker->dayNames),
            set::value($job->atDay),
        ),
    ),
    formRow
    (
        setClass('custom-fields hidden'),
        formGroup
        (
            set::label(''),
            inputGroup
            (
                $lang->job->atTime,
                timePicker
                (
                    set::name('atTime'),
                    set::value($job->atTime),
                ),
            ),
        ),
    ),
    $job->engine == 'jenkins' ? formRow
    (
        setClass('hidden'),
        set::id('jenkinsServerTR'),
        formGroup
        (
            set::label($lang->job->jkHost),
            set::required(true),
            inputGroup
            (
                picker
                (
                    set::required(true),
                    set::name('jkServer'),
                    set::items($jenkinsServerList),
                    set::value($job->server),
                    on::change('changeJenkinsServer'),
                ),
                $lang->job->pipeline,
                input
                (
                    set::name('jkTask'),
                    set::type('hidden'),
                    set::value($job->rawPipeline),
                ),
                dropmenu
                (
                    setStyle('width', '200px'),
                    set::id('pipelineDropmenu'),
                    set::text($job->pipeline ? $job->pipeline : $lang->job->selectPipeline),
                    $job->pipeline ? set::url($this->createLink('jenkins', 'ajaxGetJenkinsTasks', "jenkinsID={$job->server}")) : set::data(array('' => '')),
                ),
            ),
        ),
    ) : null,
    formRow
    (
        set::id('paramDiv'),
        formGroup
        (
            set::label($lang->job->customParam),
            $customParam,
            inputGroup
            (
                $lang->job->paramName,
                input
                (
                    setStyle('width', '50%'),
                    setClass('form-control'),
                    set::name('paramName[]'),
                ),
                $lang->job->paramValue,
                select
                (
                    setStyle('width', '25%'),
                    setClass('paramValue'),
                    set::name('paramValue[]'),
                    set::items($lang->job->paramValueList),
                ),
                input
                (
                    setStyle('width', '25%'),
                    setClass('form-control hidden paramValue'),
                    set::name('paramValue[]'),
                    set::disabled(true),
                ),
                span
                (
                    setClass('input-group-addon'),
                    checkbox
                    (
                        setClass('custom'),
                        set::name('custom'),
                        set::text($lang->job->custom),
                    ),
                ),
                span
                (
                    setClass('input-group-addon'),
                    h::a
                    (
                        setClass('add-param'),
                        set::href('javascript:void(0)'),
                        icon('plus'),
                    ),
                ),
                span
                (
                    setClass('input-group-addon'),
                    a
                    (
                        setClass('delete-param'),
                        set::href('javascript:void(0)'),
                        icon('close'),
                    ),
                ),
            ),
        ),
    ),
);

render();

