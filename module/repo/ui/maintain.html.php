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

jsVar('orderBy',  $orderBy);
jsVar('sortLink', createLink('repo', 'maintain', "objectID=$objectID&orderBy={orderBy}&recTotal={$pager->recTotal}&pageID={$pager->pageID}"));

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
    if($productList)
    {
        foreach($productList as $productID)
        {
            if(!isset($products[$productID])) continue;
            $productNames[] = zget($products, $productID, $productID);
        }
        $repo->productNames = implode('，', $repo->productNames);
    }

    $repo->projectNames = array();
    $projectList  = explode(',', str_replace(' ', '', $repo->projects));
    if($projectList)
    {
        foreach($projectList as $projectID)
        {
            if(!isset($projects[$projectID])) continue;
            $projectNames[] = zget($projects, $projectID, $projectID);
        }
        $repo->projectNames = implode('，', $repo->projectNames);
    }

    if(is_object($repo->lastSubmitTime)) $repo->lastSubmitTime = $repo->lastSubmitTime->time;
}

$config->repo->dtable->fieldList['name']['link']                     = $this->createLink('repo', 'browse', "repoID={id}&branchID=&objectID={$objectID}");
$config->repo->dtable->fieldList['actions']['list']['edit']['url']   = $this->createLink('repo', 'edit', "repoID={id}&objectID={$objectID}");
$config->repo->dtable->fieldList['actions']['list']['delete']['url'] = $this->createLink('repo', 'delete', "repoID={id}&objectID={$objectID}&confirm=yes");

$repos = initTableData($repoList, $config->repo->dtable->fieldList, $this->repo);
$queryMenuLink = createLink('repo', 'maintain', "objectID=$objectID&orderBy=&recTotal={$pager->recTotal}&pageID={$pager->pageID}&type=bySearch&param={queryID}");

\zin\featureBar
(
    set::current('all'),
    set::queryMenuLinkCallback(fn($key) => str_replace('{queryID}', (string)$key, $queryMenuLink)),
    li(searchToggle(set::module('repo'), set::open($type == 'bySearch'))),
);

toolBar
(
    hasPriv('repo', 'createRepo') ? item(set($createRepoItem + array
    (
        'icon'  => 'plus',
        'class' => 'btn primary',
    ))) : null,
    !hasPriv('repo', 'create') && hasPriv('repo', 'import') ? item(set($batchCreateItem + array
    (
        'icon'  => 'plus',
        'class' => 'btn primary',
    ))) : null,
    !hasPriv('repo', 'import') && hasPriv('repo', 'create') ? item(set($createItem + array
    (
        'icon'  => 'plus',
        'class' => 'btn primary',
    ))) : null,
    hasPriv('repo', 'import') && hasPriv('repo', 'create') ? btnGroup
    (
        btn(setClass('btn primary'), set::icon('plus'), set::url(createLink('repo', 'create')), $lang->repo->createAction),
        dropDown
        (
            btn(setClass('btn primary dropdown-toggle'),
            setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
            set::placement('bottom-end'),
            set::items(array($createItem, $batchCreateItem)),
        ),
    ) : null,
);

dtable
(
    set::cols($config->repo->dtable->fieldList),
    set::data($repos),
    set::sortLink(jsRaw('createSortLink')),
    set::footPager(usePager())
);

render();
