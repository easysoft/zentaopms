<?php
declare(strict_types=1);
/**
 * The create view file of mr module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     mr
 * @link        https://www.zentao.net
 */
namespace zin;

h::importJs('js/misc/base64.js');
jsVar('hostID', $MR->hostID);
jsVar('projectID', $MR->sourceProject);

$hostName = $this->loadModel('pipeline')->getByID($MR->hostID)->name;
$sourceProject = $host->type == 'gitlab' ? $this->loadModel('gitlab')->apiGetSingleProject($MR->hostID, $MR->sourceProject)->name_with_namespace : $MR->sourceProject;
$targetProject = $host->type == 'gitlab' ? $this->loadModel('gitlab')->apiGetSingleProject($MR->hostID, $MR->targetProject)->name_with_namespace : $MR->targetProject;

formPanel
(
    set::labelWidth('11em'),
    formGroup
    (
        set::label($lang->mr->server),
        set::value($hostName),
        set::control('static'),
    ),
    formGroup
    (
        set::label($lang->mr->sourceProject),
        set::value($sourceProject . $MR->sourceBranch),
        set::control('static'),
    ),
    formRow
    (
        formGroup
        (
            set::width('2/3'),
            set::required(true),
            set::label($lang->mr->targetProject),
            inputGroup
            (
                $targetProject,
                ':',
                ($MR->status == 'merged' or $MR->status == 'closed' or $host->type == 'gogs') ? $MR->targetBranch : select
                (
                    set::name('targetBranch'),
                    set::items($targetBranchList),
                    set::value($MR->targetBranch),
                ),
            )
        ),
    ),
    formGroup
    (
        set::required(true),
        set::name('title'),
        set::label($lang->mr->title),
        set::value($MR->title),
    ),
    formGroup
    (
        set::name('description'),
        set::label($lang->mr->description),
        set::control('textarea'),
        set::value($MR->description),
    ),
    formGroup
    (
        set::required(true),
        set::name('repoID'),
        set::label($lang->devops->repo),
        set::control('picker'),
        set::items($repoList),
        set::value($MR->repoID),
        on::change('onRepoChange'),
    ),
    formGroup
    (
        set::required(true),
        set::name('removeSourceBranch'),
        set::label($lang->mr->removeSourceBranch),
        set::control('checkbox'),
        set::disabled(!$MR->canDeleteBranch),
        set::checked($MR->canDeleteBranch && $MR->removeSourceBranch == '1'),
    ),
    formGroup
    (
        set::name('needCI'),
        set::label($lang->mr->needCI),
        set::control('checkbox'),
        on::change('onNeedCiChange'),
        set::checked($MR->needCI == '1'),
    ),
    formRow
    (
        setClass('hidden'),
        formGroup
        (
            set::required(true),
            set::name('jobID'),
            set::label($lang->job->common),
            set::control('picker'),
            set::items($jobList),
            set::value($MR->jobID),
        ),
    ),
    formGroup
    (
        set::width('1/2'),
        set::name('squash'),
        set::label($lang->mr->squash),
        set::control('checkbox'),
        set::checked($MR->squash == '1'),
    ),
    formGroup
    (
        set::required(true),
        set::name('assignee'),
        set::label($lang->mr->assignee),
        set::control('picker'),
        set::items($users),
        set::value($assignee)
    ),
);

render();
