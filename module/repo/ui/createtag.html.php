<?php
declare(strict_types=1);
/**
 * The create tag view file of repo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     repo
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('module', $objectType);
jsVar('linkParams', "objectID={$objectID}&repoID=%s");
jsVar('tagLang', $lang->repo->tag);
jsVar('branchLang', $lang->repo->branch);
jsVar('commitLang', $lang->repo->info);

if(!$canCreate)
{
    $noticeField = $objectType . 'NotActive';
    div
    (

        setClass('alert with-icon mb-4'),
        icon('exclamation-sign text-warning text-2xl'),
        div
        (
            setClass('content'),
            $lang->repo->notice->$noticeField
        )
    );
}

empty($linkedTags) ? null : div
(
    div
    (
        setClass('panel-title text-md'),
        $lang->repo->createdRepo
    ),
    h::table
    (
        setClass('table condensed bordered mb-4 mt-2 text-center'),
        h::tr
        (
            h::th
            (
                width('100px'),
                $lang->repo->codeRepo
            ),
            h::th
            (
                width('150px'),
                $lang->repo->tagName
            ),
            common::hasPriv($objectType, 'unlinkTag') ? h::th
            (
                width('60px'),
                $lang->actions
            ) : null
        ),
        $tagDom
    )
);

$canCreate ? formPanel
(
    setID('tagCreateForm'),
    set::title($lang->repo->createTagAction),
    set::titleClass('panel-title text-md'),
    $app->tab == 'devops' ?  formGroup
    (
        set::name('devopsCodeRepo'),
        set::label($lang->repo->codeRepo),
        set::required(true),
        set::control('hidden'),
        set::value($repoID)
    ) : null,
    formGroup
    (
        setID('repoID'),
        set::label($lang->repo->codeRepo),
        set::required(true),
        $app->tab == 'devops' ? set::control('static') : null,
        $app->tab == 'devops' ? set::value(zget($repoPairs, $repoID)): picker
        (
            set::required(true),
            set::name('codeRepo'),
            set::items($repoPairs),
            set::value($repoID),
            set::popPlacement('bottom'),
            on::change('window.onRepoChange')
        )
    ),
    formRow(
        formGroup
        (
            setID('tagBranch'),
            set::label($lang->repo->tagFrom),
            set::required(true),
            set::width('1/2'),
            picker
            (
                set::name('tagBranch'),
                set::required(true),
                set::popPlacement('bottom'),
                set::items($this->createLink('repo', 'ajaxGetBranchOptions', "repoID=$repoID")),
                set::display(jsRaw('(value) => value')),
                on::change('window.onBranchChange')
            )
        ),
        formGroup
        (
            setID('tagFrom'),
            set::required(true),
            set::width('1/2'),
            picker
            (
                set::name('tagFrom'),
                set::required(true),
                set::popPlacement('bottom'),
                set::items($this->createLink('repo', 'ajaxGetBranchCommits', "repoID=$repoID&branchID=$branchID&search={search}")),
                set::popWidth('auto'),
                set::popMaxWidth(800),
                set::popMinWidth('100%')
            )
        ),
    ),
    formGroup
    (
        set::name('tagName'),
        set::label($lang->repo->tagName),
        set::required(true)
    ),
    formGroup
    (
        set::name('comment'),
        set::label($lang->repo->remark),
        set::required(true),
        set::control('textarea'),
    ),
    set::actions(array('submit'))
) : null;
