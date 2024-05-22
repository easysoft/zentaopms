<?php
declare(strict_types=1);
/**
 * The create branch view file of repo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     repo
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('module', $objectType);
jsVar('linkParams', "objectID={$objectID}&repoID=%s");
jsVar('branchLang', $lang->repo->branch);
jsVar('tagLang', $lang->repo->tag);
modalHeader
(
    set::title($lang->repo->codeBranch),
    set::titleClass('panel-title text-lg')
);

$branchDom = array();
foreach($linkedBranches as $branch)
{
    $branchName = helper::safe64Encode(base64_encode($branch->BType));
    $branchDom[] = h::tr
    (
        h::td(a
        (
            set::href(createLink('repo', 'browse', "repoID={$branch->BID}")),
            zget($allRepos, $branch->BID, '')
        )),
        h::td(a(
            set::href(createLink('repo', 'browse', "repoID={$branch->BID}&branch={$branchName}")),
            $branch->BType
        )),
        common::hasPriv($objectType, 'unlinkBranch') ? h::td(
            a
            (
                setClass('btn ghost toolbar-item square size-sm text-primary ajax-submit'),
                setData(array(
                    'url'     => createLink($objectType, 'unlinkBranch'),
                    'data'    => "{\"branch\": \"$branch->BType\", \"objectID\": $objectID, \"repoID\": $branch->BID}",
                    'confirm' => sprintf($lang->repo->notice->unlinkBranch, $lang->{$objectType}->common)
                )),
                set::title($lang->repo->unlink),
                icon('unlink')
            )
        ) : null
    );
}
empty($linkedBranches) ? null : div
(
    div
    (
        setClass('panel-title text-md'),
        $lang->repo->createdBranch
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
                $lang->repo->branchName
            ),
            common::hasPriv($objectType, 'unlinkBranch') ? h::th
            (
                width('60px'),
                $lang->actions
            ) : null
        ),
        $branchDom
    )
);

if(empty($linkedBranches) && !$canCreate)
{
    div
    (
        setClass('canvas text-center py-2'),
        p
        (
            setClass('py-2 my-2'),
            span
            (
                setClass('text-gray'),
                $lang->noData
            )
        )
    );
}

$canCreate ? formPanel
(
    setID('branchCreateForm'),
    set::title($lang->repo->createBranchAction),
    set::titleClass('panel-title text-md'),
    formGroup
    (
        setID('repoID'),
        set::label($lang->repo->codeRepo),
        set::required(true),
        picker
        (
            set::required(true),
            set::name('codeRepo'),
            set::items($repoPairs),
            set::value($repoID),
            set::popPlacement('bottom'),
            on::change('window.onRepoChange')
        )
    ),
    formGroup
    (
        setID('branchFrom'),
        set::label($lang->repo->branchFrom),
        set::required(true),
        picker
        (
            set::name('branchFrom'),
            set::required(true),
            set::popPlacement('bottom'),
            set::items($fromList),
            set::value(!empty($fromList[0]['items']) ? $fromList[0]['items'][0]['value'] : '')
        )
    ),
    formGroup
    (
        set::name('branchName'),
        set::label($lang->repo->branchName),
        set::required(true),
        set::value("{$objectType}-{$objectID}")
    ),
    set::actions(array('submit'))
) : null;
