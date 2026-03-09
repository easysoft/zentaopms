<?php
declare(strict_types=1);
/**
 * The browse view file of ppm module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     ppm
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('repoID', $repoID);

/* Show source project column if the user browse the Merge Requests of all the repos. */
if(empty($repoID))
{
    $sourceProject['repoName']['name']  = 'repoID';
    $sourceProject['repoName']['title'] = $lang->repo->common;
    $sourceProject['repoName']['type']  = 'text';
    $sourceProject['repoName']['hint']  = true;
    $sourceProject['repoName']['map']   = $repoPairs;

    $offset = array_search('sourceBranch', array_keys($config->ppm->dtable->fieldList));

    $config->ppm->dtable->fieldList = array_slice($config->ppm->dtable->fieldList, 0, $offset, true) + $sourceProject + array_slice($config->ppm->dtable->fieldList, $offset, NULL, true);
}

$mrs = initTableData($ppmList, $config->ppm->dtable->fieldList, $this->ppm);

featureBar
(
    set::current($mode != 'status' ? $mode : $param),
    set::linkParams("repoID={$repoID}&mode=status&param={key}&objectID={$objectID}")
);

toolBar
(
    hasPriv($app->rawModule, 'create') ? item(
        set::text($lang->ppm->create),
        set::icon('plus'),
        set::className('btn primary'),
        set::url(createLink($app->rawModule, 'create', "repoID=" . ($repoID ? $repoID : key($repoList)) . "&objectID={$objectID}")),
        set('data-app', $app->tab)
    ) : null
);

dtable
(
    set::userMap($users),
    set::cols($config->ppm->dtable->fieldList),
    set::data($mrs),
    set::sortLink(createLink($app->rawModule, 'browse', "repoID={$repoID}&mode={$mode}&param={$param}&objectID={$objectID}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")),
    set::orderBy($orderBy),
    set::footPager(usePager())
);
