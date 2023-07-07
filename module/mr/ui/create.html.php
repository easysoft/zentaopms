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
jsVar('hosts', $hosts);
jsVar('repo', $repo);
jsVar('mrLang', $lang->mr);
jsVar('branchPrivs', array());

formPanel
(
    set::labelWidth('11em'),
    formGroup
    (
        set::name('hostID'),
        set::label($lang->mr->create),
        set::value('gitService'),
        set::items($hostPairs),
        set::required(true),
        on::change('onHostChange'),
    ),
    formRow
    (
        formGroup
        (
            set::required(true),
            set::label($lang->mr->sourceProject),
            inputGroup
            (
                select
                (
                    set::name('sourceProject'),
                    set::id('sourceProject'),
                    on::change('onProjectChange'),
                    on::change('onSourceProjectChange'),
                ),
                $lang->mr->sourceBranch,
                select
                (
                    set::name('sourceBranch'),
                ),
            )
        ),
    ),
    formRow
    (
        formGroup
        (
            set::required(true),
            set::label($lang->mr->targetProject),
            inputGroup
            (
                select
                (
                    set::name('targetProject'),
                ),
                $lang->mr->targetBranch,
                select
                (
                    set::name('targetBranch'),
                ),
            )
        ),
    ),
    formGroup
    (
        set::required(true),
        set::name('title'),
        set::label($lang->mr->title),
    ),
    formGroup
    (
        set::name('description'),
        set::label($lang->mr->description),
        set::control('textarea'),
    ),
    formGroup
    (
        set::required(true),
        set::name('repoID'),
        set::label($lang->devops->repo),
        set::control('select'),
        on::change('onRepoChange'),
    ),
    formGroup
    (
        set::name('removeSourceBranch'),
        set::label($lang->mr->removeSourceBranch),
        set::control('checkbox'),
    ),
    formGroup
    (
        set::name('needCI'),
        set::label($lang->mr->needCI),
        set::control('checkbox'),
        on::change('onNeedCiChange'),
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
        ),
    ),
    formGroup
    (
        set::name('squash'),
        set::label($lang->mr->squash),
        set::control('checkbox'),
    ),
    formGroup
    (
        set::required(true),
        set::name('assignee'),
        set::label($lang->mr->assignee),
        set::control('picker'),
        set::items($users),
    ),
);

render();
