<?php
declare(strict_types=1);
/**
 * The browseBranch view file of repo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@easycorp.ltd>
 * @package     repo
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('searchUrl', createLink('repo', 'browsebranch', "repoID={$repo->id}&objectID={$objectID}&label={$label}&showArchived={$showArchived}&keyword=%s"));
$module = $app->tab == 'devops' ? 'repo' : $app->tab;
dropmenu
(
    set::module($module),
    set::tab($module),
    set::url(createLink($module, 'ajaxGetDropMenu', "objectID=$objectID&module={$app->rawModule}&method={$app->rawMethod}"))
);

featureBar
(
    (in_array($app->tab, array('project', 'execution')) && count($repoPairs) > 1) ? to::leading(dropmenu
    (
        set::id('repoDropmenu'),
        set::text($repo->name),
        set::objectID($repo->id),
        set::url(createLink('repo', 'ajaxGetDropMenu', "repoID={$repo->id}&module=repo&method=browsebranch&projectID={$objectID}"))
    )) : null,
    set::current($label),
    set::labelCount(false),
    set::linkParams("repoID={$repoID}&objectID={$objectID}&label={key}&showArchived=active&keyword={$keyword}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"),
    div
    (
        setClass('flex branch-search'),
        input
        (
            set::placeholder(sprintf($lang->repo->searchTips, strtolower($lang->repo->branch))),
            set::name('keyword'),
            set::value(base64_decode($keyword))
        ),
        btn
        (
            setClass('primary ml-2'),
            $lang->searchAB,
            on::click('searchList')
        )
    )
);

toolBar
(
    /* 镜像代码库（$repo->mirror == 1）不允许创建分支，隐藏入口。 */
    (empty($repo->mirror) && hasPriv('repo', 'createBranch')) ? item(set(array
    (
        'text'  => $lang->repo->createBranchAction,
        'icon'  => 'plus',
        'class' => 'btn primary',
        'url'   => createLink('repo', 'createBranch', 'objectID=' . data('objectID') . '&repoID=' . data('repoID')),
        'data-toggle'=>'modal'
    ))) : null
);

$config->repo->dtable->branch->fieldList['committer']['map'] = $users;
if(!hasPriv('repo', 'revision')) unset($config->repo->dtable->branch->fieldList['commitID']['link']);

/* 镜像代码库（$repo->mirror == 1）隐藏分支类型、规则控制、操作三列。 */
if(!empty($repo->mirror))
{
    unset($config->repo->dtable->branch->fieldList['type']);
    unset($config->repo->dtable->branch->fieldList['rule']);
    unset($config->repo->dtable->branch->fieldList['actions']);
}
$branchList = initTableData($branchList, $config->repo->dtable->branch->fieldList);
$urlParams = array(
    'repoID'       => $repo->id,
    'objectID'     => $objectID,
    'label'        => $label,
    'showArchived' => $showArchived,
    'keyword'      => urlencode($keyword),
    'orderBy'      => '{name}_{sortType}',
    'recTotal'     => $pager->recTotal,
    'recPerPage'   => $pager->recPerPage,
    'pageID'       => $pager->pageID
);
dtable
(
    set::cols($config->repo->dtable->branch->fieldList),
    set::data($branchList),
    set::orderBy($orderBy),
    set::sortLink(createLink('repo', 'browsebranch', $urlParams)),
    set::footPager(usePager('pager', 'noTotalCount'))
);
