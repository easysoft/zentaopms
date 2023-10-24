<?php
declare(strict_types=1);
/**
 * The browse view file of mr module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang <zenggang@easycorp.ltd>
 * @package     mr
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('orderBy',  $orderBy);
jsVar('sortLink', $sortLink);

dropmenu(set::objectID($repo->id), set::text($repo->name), set::tab('repo'));

foreach($MRList as $MR)
{
    $MR->canDelete = ($app->user->admin or (isset($openIDList[$MR->hostID]) and isset($projects[$MR->hostID][$MR->sourceProject]->owner->id) and $projects[$MR->hostID][$MR->sourceProject]->owner->id == $openIDList[$MR->hostID])) ? '' : 'disabled';
    if($repo->SCM == 'Gitlab')
    {
        $MR->canEdit = (isset($projects[$MR->hostID][$MR->sourceProject]->isDeveloper) and $projects[$MR->hostID][$MR->sourceProject]->isDeveloper == true) ? '' : 'disabled';
    }
    elseif($repo->SCM == 'Gitea')
    {
        $MR->canEdit = (isset($projects[$MR->hostID][$MR->sourceProject]->allow_merge_commits) and $projects[$MR->hostID][$MR->sourceProject]->allow_merge_commits == true) ? '' : 'disabled';
    }
    elseif($repo->SCM == 'Gogs')
    {
        $MR->canEdit = (isset($projects[$MR->hostID][$MR->sourceProject]->permissions->push) and $projects[$MR->hostID][$MR->sourceProject]->permissions->push) ? '' : 'disabled';
    }

    if($repo->SCM == 'Gitlab')
    {
        $MR->sourceProject = isset($projects[$MR->hostID][$MR->sourceProject]) ? $projects[$MR->hostID][$MR->sourceProject]->name_with_namespace . ':' . $MR->sourceBranch : $MR->sourceProject . ':' . $MR->sourceBranch;
        $MR->targetProject = isset($projects[$MR->hostID][$MR->targetProject]) ? $projects[$MR->hostID][$MR->targetProject]->name_with_namespace . ':' . $MR->targetBranch : $MR->targetProject . ':' . $MR->targetBranch;
    }
    else
    {
        $MR->sourceProject = isset($projects[$MR->hostID][$MR->sourceProject]) ? $projects[$MR->hostID][$MR->sourceProject]->full_name . ':' . $MR->sourceBranch : $MR->sourceProject . ':' . $MR->sourceBranch;
        $MR->targetProject = isset($projects[$MR->hostID][$MR->targetProject]) ? $projects[$MR->hostID][$MR->targetProject]->full_name . ':' . $MR->targetBranch : $MR->targetProject . ':' . $MR->targetBranch;
    }

    $MR->mergeStatus = ($MR->status == 'closed' || $MR->status == 'merged') ? zget($lang->mr->statusList, $MR->status) : zget($lang->mr->mergeStatusList, $MR->mergeStatus);

    if($MR->status == 'merged' or $MR->status == 'closed')
    {
        $MR->approvalStatus = '-';
    }
    else
    {
        $MR->approvalStatus = empty($MR->approvalStatus) ? $lang->mr->approvalStatusList['notReviewed'] : $lang->mr->approvalStatusList[$MR->approvalStatus];
    }
}

$MRs = initTableData($MRList, $config->mr->dtable->fieldList, $this->mr);

featureBar
(
    set::current($mode != 'status' ? $mode : $param),
    set::linkParams("repoID={$repoID}&mode=status&param={key}"),
);

toolBar
(
    hasPriv('mr', 'create') ? item(set(array
    (
        'text'  => $lang->mr->create,
        'icon'  => 'plus',
        'class' => 'btn primary',
        'url'   => createLink('mr', 'create'),
    ))) : null,
);

dtable
(
    set::userMap($users),
    set::cols($config->mr->dtable->fieldList),
    set::data($MRs),
    set::sortLink(jsRaw('createSortLink')),
    set::footPager(usePager()),
);

render();

