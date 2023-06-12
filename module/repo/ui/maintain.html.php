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
}

$config->repo->dtable->fieldList['name']['link']                     = $this->createLink('repo', 'browse', "repoID={id}&branchID=&objectID={$objectID}");
$config->repo->dtable->fieldList['actions']['list']['edit']['url']   = $this->createLink('repo', 'edit', "repoID={id}&objectID={$objectID}");
$config->repo->dtable->fieldList['actions']['list']['delete']['url'] = $this->createLink('repo', 'delete', "repoID={id}&objectID={$objectID}");

$repos = initTableData($repoList, $config->repo->dtable->fieldList, $this->repo);

dtable
(
    set::cols($config->repo->dtable->fieldList),
    set::data($repos),
    set::footerPager(usePager()),
    set::sortLink(helper::createLink('repo', 'maintain', "objectID={$objectID}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
);

render();

