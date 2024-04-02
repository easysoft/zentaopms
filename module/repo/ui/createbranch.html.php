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

to::header(false);
to::main(false);

jsVar('module', $objectType);
jsVar('linkParams', "objectID={$objectID}&repoID=%s");
modalHeader
(
    set::title($lang->repo->codeBranch),
    set::titleClass('panel-title text-lg')
);

$branchDom = array();
foreach($linkedBranches as $branchRepo => $branchName)
{
    $branchDom[] = h::tr
    (
        h::td(zget($repoPairs, $branchRepo, '')),
        h::td($branchName),
        common::hasPriv($objectType, 'unlinkBranch') ? h::td(
            a
            (
                setClass('btn ghost toolbar-item square size-sm text-primary ajax-submit'),
                setData(array(
                    'url'     => createLink($objectType, 'unlinkBranch', "objectID={$objectID}&repoID={$branchRepo}&branch=" . helper::safe64Encode($branchName)),
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
        setClass('panel-title text-lg'),
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

$canCreate ? formPanel
(
    set::title($lang->repo->createBranchAction),
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
        set::id('from'),
        set::label($lang->repo->branchFrom),
        set::required(true),
        picker
        (
            set::name('branchFrom'),
            set::required(true),
            set::popPlacement('bottom'),
            set::items($branches)
        )
    ),
    formGroup
    (
        set::name('branchName'),
        set::label($lang->repo->branchName),
        set::required(true)
    ),
    set::actions(array('submit'))
) : null;
