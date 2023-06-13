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

    $repo->productNames = '';
    $productList  = explode(',', str_replace(' ', '', $repo->product));
    if(isset($productList) and $productList[0])
    {
        foreach($productList as $productID)
        {
            if(!isset($products[$productID])) continue;
            $repo->productNames .= ' ' . zget($products, $productID, $productID);
        }
    }
}

$config->repo->dtable->fieldList['name']['link']                     = $this->createLink('repo', 'browse', "repoID={id}&branchID=&objectID={$objectID}");
$config->repo->dtable->fieldList['actions']['list']['edit']['url']   = $this->createLink('repo', 'edit', "repoID={id}&objectID={$objectID}");
$config->repo->dtable->fieldList['actions']['list']['delete']['url'] = $this->createLink('repo', 'delete', "repoID={id}&objectID={$objectID}");

$repos = initTableData($repoList, $config->repo->dtable->fieldList, $this->repo);

featureBar
(
    h::a
    (
        setClass('btn btn-active-text'),
        $lang->repo->maintain,
        set::href(createLink('repo', 'maintain')),
    ),
);

toolBar
(
    hasPriv('repo', 'import') ? item(set(array
    (
        'text'  => $lang->repo->importAction,
        'icon'  => 'import',
        'class' => 'toolbar-item ghost btn btn-default',
        'url'   => createLink('repo', 'import'),
    ))) : null,
    hasPriv('repo', 'create') ? item(set(array
    (
        'text'  => $lang->repo->createAction,
        'icon'  => 'plus',
        'class' => 'btn primary',
        'url'   => createLink('repo', 'create'),
    ))) : null,
);

dtable
(
    set::cols($config->repo->dtable->fieldList),
    set::data($repos),
    set::footPager(usePager()),
);

render();

