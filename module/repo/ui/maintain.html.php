<?php
declare(strict_types=1);
/**
 * The maintain view file of repo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     repo
 * @link        https://www.zentao.net
 */
namespace zin;

$createRepoURL = createLink('repo', 'createRepo', $inSpace ? "objectID=0&spaceID={$spaceID}" : '');
$importURL     = createLink('repo', 'import', $inSpace ? "spaceID={$spaceID}" : '');

$createRepoItem = array('text' => $lang->repo->createRepoAction, 'url' => $createRepoURL, 'class' => 'btn primary', 'icon' => 'plus');
$importItem     = array('text' => $lang->repo->import, 'url' => $importURL, 'class' => 'primary', 'icon' => 'download');

foreach($repoList as $repo)
{
    $repo->spaceID  = $repo->spaceID ? $repo->spaceID : '';

    $productNames = array();
    $productList  = explode(',', str_replace(' ', '', $repo->product));
    foreach($productList as $productID)
    {
        if(!isset($products[$productID])) continue;
        $productNames[] = zget($products, $productID, $productID);
    }
    $repo->productNames = implode('，', $productNames);

}

$config->repo->dtable->fieldList['name']['link']                   = $this->createLink('repo', 'browse', "repoID={id}&branchID=&objectID={$objectID}");
$config->repo->dtable->fieldList['actions']['list']['edit']['url'] = $this->createLink('repo', 'edit', "repoID={id}&objectID={$objectID}&spaceID={$spaceID}");
$config->repo->dtable->fieldList['space']['map']                   = $spaces;
if($inSpace) unset($config->repo->dtable->fieldList['space']);

if(empty($config->repo->maintain->showRepoPath))
{
    unset($config->repo->dtable->fieldList['path']);
    $config->repo->dtable->fieldList['product']['width']    = '0.2';
}

/* Set 'repo-visit' action as one open method, so any user can use it. */
if(empty($config->repo->maintain->disableVisit)) $config->logonMethods[] = 'repo.visit';

$repos         = initTableData($repoList, $config->repo->dtable->fieldList, $this->repo);
$queryMenuLink = createLink('repo', 'maintain', "inSpace={$inSpace}&objectID=$objectID&space={$spaceID}&orderBy=&recTotal={$pager->recTotal}&pageID={$pager->pageID}&type=bySearch&param={queryID}");

/* Process data which the function initTableData() not provided. */
foreach($repos as $repo)
{
    /* 镜像代码库屏蔽"执行扫描/扫描问题"两个操作项。 */
    if(!empty($repo->mirror) && !empty($repo->actions))
    {
        $repo->actions = array_values(array_filter($repo->actions, function($action)
        {
            return empty($action['name']) || !in_array($action['name'], array('scanExec', 'scanIssue'), true);
        }));
    }

    if(!empty($repo->actions[0]['name']) && $repo->actions[0]['name'] != 'visit') continue;
}

$spaceItems = array();
$spaceItems[] = array('text' => $lang->repo->allSpace, 'url' => createLink('repo', 'maintain', "inSpace=0&space=0&objectID={$objectID}"));
foreach($spaces as $id => $spaceName)
{
    $spaceItems[] = array('text' => $spaceName, 'url' => createLink('repo', 'maintain', "inSpace=0&space={$id}&objectID={$objectID}"));
}

featureBar
(
    $inSpace ? null : to::before
    (
        dropdown
        (
            to('trigger', btn(zget($spaces, $spaceID, $lang->repo->allSpace), setID('spaceDropdown'), setClass('ghost text-ellipsis text-left'))),
            set::items($spaceItems)
        )
    ),
    set::current('all'),
    set::queryMenuLinkCallback(array(fn($key) => str_replace('{queryID}', (string)$key, $queryMenuLink))),
    li(searchToggle(set::module('repo'), set::open($type == 'bySearch')))
);

//toolBar
//(
//    hasPriv('repo', 'createRepo') ? item(set($createRepoItem + array
//    (
//        'icon'  => 'plus',
//        'class' => 'btn primary'
//    ))) : null,
//    !hasPriv('repo', 'create') && hasPriv('repo', 'import') ? item(set($batchCreateItem + array
//    (
//        'icon'  => 'plus',
//        'class' => 'btn primary'
//    ))) : null,
//    !hasPriv('repo', 'import') && hasPriv('repo', 'create') ? item(set($createItem + array
//    (
//        'icon'  => 'plus',
//        'class' => 'btn primary'
//    ))) : null,
//    hasPriv('repo', 'import') && hasPriv('repo', 'create') ? btnGroup
//    (
//        btn(setClass('btn primary'), set::icon('plus'), set::url(createLink('repo', 'create')), $lang->repo->createAction),
//        dropDown
//        (
//            btn(setClass('btn primary dropdown-toggle'),
//            setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
//            set::placement('bottom-end'),
//            set::items(array($createItem, $batchCreateItem))
//        )
//    ) : null,
//);

toolbar
(
    hasPriv('repo', 'createRepo') ? item(set($createRepoItem)) : null,
    hasPriv('repo', 'import') ? item(set($importItem)) : null
);

dtable
(
    set::cols($config->repo->dtable->fieldList),
    set::data($repos),
    set::sortLink(createLink('repo', 'maintain', "inSpace={$inSpace}&space={$spaceID}&objectID=$objectID&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&pageID={$pager->pageID}")),
    set::orderBy($orderBy),
    set::footPager(usePager())
);
