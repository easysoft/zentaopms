<?php
declare(strict_types=1);
/**
 * The  view file of mr module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     mr
 * @link        https://www.zentao.net
 */
namespace zin;

$app->loadLang('productplan');
$module = $app->tab == 'devops' ? 'repo' : $app->tab;
dropmenu
(
    set::module($module),
    set::tab($module),
    set::url(createLink($module, 'ajaxGetDropMenu', "objectID=$objectID&module={$app->rawModule}&method={$app->rawMethod}"))
);

include 'header.html.php';
$hasNoChange    = $MR->synced && empty($rawMR->changes_count) ? true : false;
$hasConflict    = $MR->synced === '1' ? $rawMR->has_conflicts : !$MR->hasNoConflict;
$sourceDisabled = ($MR->status == 'merged' && $MR->removeSourceBranch == '1') ? 'disabled' : '';
$branchPath     = $sourceProject->path_with_namespace . '-' . $MR->sourceBranch;
$mergeStatus    = !empty($rawMR->merge_status) ? $rawMR->merge_status : $MR->mergeStatus;

if($MR->compileID)
{
    $job = tableData
    (
        item
        (
            set::name($lang->job->common),
            $compile->name
        ),
        item
        (
            set::name($lang->compile->atTime),
            $compile->createdDate
        ),
        !empty($MR->jobID) ?  item
        (
            set::name($lang->compile->result),
            zget($lang->compile->statusList, $compile->status),
            in_array($compile->status, array('success', 'failure')) ? h::a
            (
                setClass('ml-1'),
                set::href($this->createLink('job', 'view', "jobID={$MR->jobID}&compileID={$compile->id}")),
                set('data-toggle', 'modal'),
                icon('search'),
                $lang->compile->logs
            ) : h::a
            (
                setClass('ml-1 ajax-submit'),
                set::href(helper::createLink('mr', 'ajaxSyncCompile', "compileID={$compile->id}")),
                set::hint($lang->refresh),
                icon('refresh'),
                $lang->refresh
            )
        ) : null
    );
}
elseif($MR->needCI)
{
    $compileUrl = $this->createLink('job', 'view', "jobID={$MR->jobID}");
    $job = div
    (
        h::a
        (
            set::href($compileUrl),
            set::target('_blank'),
            $lang->compile->statusList[$MR->compileStatus]
        )
    );
}
else
{
    $job = div
    (
        setClass('text-center'),
        $lang->mr->noCompileJob
    );
}

detailHeader
(
    set::back('mr-browse'),
    to::title
    (
        entityLabel
        (
            set::entityID($MR->id),
            set::level(1),
            set::text($MR->title)
        ),
        h::span
        (
            setClass('label gray-pale size-lg'),
            h::a
            (
                setClass('text-primary'),
                set::href($rawMR->web_url),
                set::target('_blank'),
                $lang->mr->viewInGit
            )
        )
    )
);

panel
(
    div
    (
        set::id('mrMenu'),
        $headers
    ),
    div
    (
        setClass('flex items-center mt-4'),
        cell
        (
            setClass('mr-4 mr-view-cell'),
            set::grow(1),
            set::align('flex-start'),
            cell
            (
                setClass('cell mb-2'),
                h::span
                (
                    setClass('font-bold mt-2 mb-2 inline-block'),
                    setID('mrBranches'),
                    $lang->mr->from,
                    h::a
                    (
                        setClass('font-normal ml-2 mr-2'),
                        set::href($sourceBranch),
                        set::target('_blank'),
                        set::disabled($sourceDisabled),
                        $sourceProject->name_with_namespace . ':' . $MR->sourceBranch
                    ),
                    $lang->mr->to,
                    h::a
                    (
                        setClass('font-normal ml-2 mr-2'),
                        set::href($targetBranch),
                        set::target('_blank'),
                        $targetProject->name_with_namespace . ':' . $MR->targetBranch
                    )
                ),
                tableData
                (
                    item
                    (
                        set::trClass('mr-status-tr'),
                        set::name($lang->mr->status),
                        (!empty($MR->syncError) && $MR->synced === '0') ? h::span
                        (
                            setClass('danger'),
                            $MR->status
                        ) : span
                        (
                            setClass("status-{$MR->status}"),
                            zget($lang->mr->statusList, (string)$MR->status)
                        )
                    ),
                    item
                    (
                        set::name($lang->mr->reviewer),
                        $reviewer ? zget($reviewer, 'realname', $MR->assignee) : $MR->assignee
                    ),
                    $MR->status == 'opened' ? item
                    (
                        set::name($lang->mr->mergeStatus),
                        $hasNoChange || $hasConflict ? span
                        (
                            setClass('status-cannot_be_merged'),
                            $lang->mr->cantMerge
                        ) : span
                        (
                            setClass("status-{$mergeStatus}"),
                            zget($lang->mr->mergeStatusList, $mergeStatus)
                        ),
                        $hasNoChange || $hasConflict ? span
                        (
                            setClass('ml-2'),
                            '(' . ($hasConflict ? $lang->mr->hasConflicts : $lang->mr->hasNoChanges) . ')'
                        ) : null
                    ) : null,
                    item
                    (
                        set::name($lang->mr->description),
                        !empty($MR->description) ? html(nl2br($MR->description)) : $lang->noData
                    )
                )
            ),
            ($MR->synced && $rawMR->state == 'opened' && $hasConflict) ? cell
            (
                setClass('cell mb-2'),
                html(sprintf($lang->mr->commandDocument, $sourceProject->http_url_to_repo, $MR->sourceBranch, $branchPath, $MR->targetBranch, $branchPath, $MR->targetBranch))
            ) : null,
            cell
            (
                setClass('cell cell-history'),
                history
                (
                    set::objectID($MR->id),
                    set::commentUrl(createLink('action', 'comment', array('objectType' => $app->rawModule, 'objectID' => $MR->id)))
                )
            )
        ),
        cell
        (
            setClass('cell'),
            setID('mrJob'),
            set::width('30%'),
            set::align('baseline'),
            div
            (
                setClass('text-lg font-bold flex justify-between'),
                $lang->mr->jobID,
                div
                (
                    setClass('text-base font-thin'),
                    $hasNewCommit ? span
                    (
                        setClass('mr-2'),
                        $lang->mr->branchUpdateTip
                    ) : null,
                    !empty($MR->jobID) && hasPriv('job', 'exec') ? btn
                    (
                        setClass('label primary size-lg ajax-submit'),
                        set::url(helper::createLink('mr', 'ajaxExecJob', "MRID={$MR->id}&jobID={$MR->jobID}")),
                        set::hint($lang->mr->execJobTip),
                        $lang->mr->execJob
                    ) : null
                )
            ),
            $job
        )
    )
);

include 'actions.html.php';
