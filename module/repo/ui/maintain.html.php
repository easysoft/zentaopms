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

$createItem      = array('text' => $lang->repo->createAction, 'url' => createLink('repo', 'create'));
$createRepoItem  = array('text' => $lang->repo->createRepoAction, 'url' => createLink('repo', 'createRepo'));
$batchCreateItem = array('text' => $lang->repo->batchCreate, 'url' => createLink('repo', 'import'));

foreach($repoList as $repo)
{
    $jobID       = 0;
    $repo->exec   = 'disabled';
    $repo->report = 'disabled';
    if(isset($sonarRepoList[$repo->id]))
    {
        $repo->exec = '';
        $repo->job = $sonarRepoList[$repo->id]->id;
        if(in_array($repo->job, $successJobs)) $repo->report = '';
    }

    $productNames = array();
    $productList  = explode(',', str_replace(' ', '', $repo->product));
    foreach($productList as $productID)
    {
        if(!isset($products[$productID])) continue;
        $productNames[] = zget($products, $productID, $productID);
    }
    $repo->productNames = implode('，', $productNames);

    $projectNames = array();
    $projectList  = explode(',', str_replace(' ', '', $repo->projects));
    foreach($projectList as $projectID)
    {
        if(!isset($projects[$projectID])) continue;
        $projectNames[] = zget($projects, $projectID, $projectID);
    }
    $repo->projectNames = implode('，', $projectNames);

    if(is_object($repo->lastSubmitTime)) $repo->lastSubmitTime = $repo->lastSubmitTime->time;
}

$config->repo->dtable->fieldList['name']['link']                   = $this->createLink('repo', 'browse', "repoID={id}&branchID=&objectID={$objectID}");
$config->repo->dtable->fieldList['actions']['list']['edit']['url'] = $this->createLink('repo', 'edit', "repoID={id}&objectID={$objectID}");

if(empty($config->repo->maintain->showRepoPath))
{
    unset($config->repo->dtable->fieldList['path']);
    $config->repo->dtable->fieldList['product']['width']    = '0.2';
    $config->repo->dtable->fieldList['scm']['width']        = '0.2';
    $config->repo->dtable->fieldList['lastSubmit']['width'] = '0.2';
}

/* Set 'repo-visit' action as one open method, so any user can use it. */
if(empty($config->repo->maintain->disableVisit)) $config->logonMethods[] = 'repo.visit';

$repos         = initTableData($repoList, $config->repo->dtable->fieldList, $this->repo);
$queryMenuLink = createLink('repo', 'maintain', "objectID=$objectID&orderBy=&recTotal={$pager->recTotal}&pageID={$pager->pageID}&type=bySearch&param={queryID}");

/* Process data which the function initTableData() not provided. */
foreach($repos as $repo)
{
    if(!empty($repo->actions[0]['name']) && $repo->actions[0]['name'] != 'visit') break;

    /* Set the url and check status for visiting the repo. */
    $repo->actions[0]['disabled'] = strpos($repo->path, 'http') === false;
    $repo->actions[0]['url']      = $repo->path;
    if(in_array($repo->SCM, array('Gogs', 'Gitea')))
    {
        $resp = $this->loadModel('pipeline')->getByID((int)$repo->serviceHost);
        if(!empty($resp->url))
        {
            $repo->actions[0]['disabled'] = false;
            $repo->actions[0]['url']      = $resp->url . '/' . $repo->serviceProject;
        }
    }
}

\zin\featureBar
(
    set::current('all'),
    set::queryMenuLinkCallback(array(fn($key) => str_replace('{queryID}', (string)$key, $queryMenuLink))),
    li(searchToggle(set::module('repo'), set::open($type == 'bySearch')))
);

toolBar
(
    hasPriv('repo', 'createRepo') && $serverPairs ? item(set($createRepoItem + array
    (
        'icon'  => 'plus',
        'class' => 'btn primary'
    ))) : null,
    !hasPriv('repo', 'create') && hasPriv('repo', 'import') && $serverPairs ? item(set($batchCreateItem + array
    (
        'icon'  => 'plus',
        'class' => 'btn primary'
    ))) : null,
    !hasPriv('repo', 'import') && hasPriv('repo', 'create') ? item(set($createItem + array
    (
        'icon'  => 'plus',
        'class' => 'btn primary'
    ))) : null,
    hasPriv('repo', 'import') && hasPriv('repo', 'create') ? btnGroup
    (
        btn(setClass('btn primary'), set::icon('plus'), set::url(createLink('repo', 'create')), $lang->repo->createAction),
        $serverPairs ? dropDown
        (
            btn(setClass('btn primary dropdown-toggle'),
            setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
            set::placement('bottom-end'),
            set::items(array($createItem, $batchCreateItem))
        ) : null
    ) : null,
);

dtable
(
    set::cols($config->repo->dtable->fieldList),
    set::data($repos),
    set::sortLink(createLink('repo', 'maintain', "objectID=$objectID&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&pageID={$pager->pageID}")),
    set::orderBy($orderBy),
    set::footPager(usePager())
);

render();
