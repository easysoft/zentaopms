<?php
declare(strict_types=1);
/**
 * The browse view file of mr module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     mr
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('repoID', $repoID);

dropmenu(set::url(createLink('execution', 'ajaxGetDropMenu', "objectID=$objectID&module={$app->rawModule}&method={$app->rawMethod}")));

foreach($MRList as $index => $MR)
{
    if(!isset($repoList[$MR->repoID]))
    {
        unset($MRList[$index]);
        continue;
    }

    $repo = $repoList[$MR->repoID];

    /* The user whether has the permission of delete and edit does not require the judge of the permission from the project of the server. */
    $MR->canDelete = hasPriv($app->rawModule, 'delete') ? '' : 'disabled'; /* The value can be '' or 'disabled', 'disabled' means that user can NOT do this. */
    $MR->canEdit   = hasPriv($app->rawModule, 'edit')   ? '' : 'disabled';

    if($MR->status == 'merged' || $MR->status == 'closed')
    {
        $MR->mergeStatus    = $MR->status;
        $MR->approvalStatus = '-';
    }
    else
    {
        $MR->approvalStatus = empty($MR->approvalStatus) ? $lang->mr->approvalStatusList['notReviewed'] : $lang->mr->approvalStatusList[$MR->approvalStatus];
    }

    $MR->repoName = $repo->name;
}

/* Show source project column if the user browse the Merge Requests of all the repos. */
if(empty($repoID))
{
    $sourceProject['repoName']['name']     = 'repoName';
    $sourceProject['repoName']['title']    = $lang->repo->common;
    $sourceProject['repoName']['type']     = 'text';
    $sourceProject['repoName']['hint']     = '{sourceProject}';

    $offset = array_search('sourceBranch', array_keys($config->mr->dtable->fieldList));

    $config->mr->dtable->fieldList = array_slice($config->mr->dtable->fieldList, 0, $offset, true) + $sourceProject + array_slice($config->mr->dtable->fieldList, $offset, NULL, true);
}

$MRs = initTableData($MRList, $config->mr->dtable->fieldList, $this->mr);

featureBar
(
    set::current($mode != 'status' ? $mode : $param),
    set::linkParams("repoID={$repoID}&mode=status&param={key}&objectID={$objectID}")
);

toolBar
(
    hasPriv($app->rawModule, 'create') ? item(
        set::text($lang->mr->create),
        set::icon('plus'),
        set::className('btn primary'),
        set::url(createLink($app->rawModule, 'create', "repoID=" . ($repoID ? $repoID : key($repoList)) . "&objectID={$objectID}")),
        set('data-app', $app->tab)
    ) : null
);

dtable
(
    set::userMap($users),
    set::cols($config->mr->dtable->fieldList),
    set::data($MRs),
    set::sortLink(createLink($app->rawModule, 'browse', "repoID={$repoID}&mode={$mode}&param={$param}&objectID={$objectID}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")),
    set::orderBy($orderBy),
    set::footPager(usePager())
);

render();
