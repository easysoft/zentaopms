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

$hasNoConflict     = $MR->synced === '1' ? $rawMR->has_conflicts : (bool)$MR->hasNoConflict;
$sourceDisabled    = ($MR->status == 'merged' && $MR->removeSourceBranch == '1') ? 'disabled' : '';
$compileNotSuccess = !empty($compile->id) && $compile->status != 'success';
$branchPath        = $sourceProject->path_with_namespace . '-' . $MR->sourceBranch;

$mainActions   = array();
$suffixActions = array();
foreach($config->mr->view->operateList as $operate)
{
    if(!common::hasPriv('mr', $operate == 'reject' ? 'approval' : $operate)) continue;
    if($operate == 'reopen' && (!$MR->synced || $rawMR->state != 'closed')) continue;

    $action = $config->mr->actionList[$operate];
    if($operate === 'edit' || $operate === 'delete')
    {
        $suffixActions[] = $action;
        continue;
    }

    if($operate == 'accept' && ($MR->approvalStatus != 'approved' || $compileNotSuccess)) $action['disabled'] = true;
    if($operate == 'accept' && ($rawMR->state != 'opened' || $rawMR->has_conflicts)) $action['disabled'] = true;

    if(in_array($operate, array('approval', 'reject', 'close', 'edit')))
    {
        if(!$MR->synced || $rawMR->state != 'opened') continue;
        if($operate == 'reject' && $MR->approvalStatus == 'rejected') $action['disabled'] = true;

        if($operate == 'approval')
        {
            if($rawMR->has_conflicts || $compileNotSuccess || $MR->approvalStatus == 'approved') $action['disabled'] = true;
        }
    }

    if($operate == 'delete' && !$projectOwner && !$this->app->user->admin) $action['disabled'] = true;
    if($operate == 'edit' && !$projectEdit && !$this->app->user->admin) $action['disabled'] = true;

    $mainActions[] = $action;
}

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
            ($compileJob && !empty($compileJob->id)) ?  item
            (
                set::name($lang->compile->result),
                zget($lang->compile->statusList, $compile->status),
                h::a
                (
                    setClass('ml-1'),
                    set::href($this->createLink('job', 'view', "jobID={$compileJob->id}&compileID={$compile->id}")),
                    set('data-toggle', 'modal'),
                    icon('search'),
                    $lang->compile->logs
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
        nav
        (
            li
            (
                setClass('nav-item'),
                a($lang->mr->view, setClass('active'))
            ),
            li
            (
                setClass('nav-item'),
                a
                (
                    $lang->mr->viewDiff,
                    set::href(inlink('diff', "MRID={$MR->id}")),
                    set('data-app', $app->tab)
                )
            ),
            li
            (
                setClass('nav-item'),
                a
                (
                    icon($lang->icons['story']),
                    $lang->productplan->linkedStories,
                    set::href(inlink('link', "MRID={$MR->id}&type=story")),
                    set('data-app', $app->tab)
                )
            ),
            li
            (
                setClass('nav-item'),
                a
                (
                    icon($lang->icons['bug']),
                    $lang->productplan->linkedBugs,
                    set::href(inlink('link', "MRID={$MR->id}&type=bug")),
                    set('data-app', $app->tab)
                )
            ),
            li
            (
                setClass('nav-item'),
                a
                (
                    icon('todo'),
                    $lang->mr->linkedTasks,
                    set::href(inlink('link', "MRID={$MR->id}&type=task")),
                    set('data-app', $app->tab)
                )
            )
        )
    ),
    div
        (
            setClass('flex items-center mt-4'),
            cell
            (
                setClass('mr-4'),
                set::grow(1),
                set::align('flex-start'),
                cell
                (
                    setClass('cell mb-2'),
                    h::span
                    (
                        setClass('font-bold mt-2 mb-2 inline-block'),
                        $lang->mr->from,
                        h::a
                        (
                            setClass('font-normal ml-2 mr-2'),
                            set::href($sourceBranch ? zget($sourceBranch, 'web_url', '') : ''),
                            set::target('_blank'),
                            set::disabled($sourceDisabled),
                            $sourceProject->name_with_namespace . ':' . $MR->sourceBranch
                        ),
                        $lang->mr->to,
                        h::a
                        (
                            setClass('font-normal ml-2 mr-2'),
                            set::href($targetBranch ? zget($targetBranch, 'web_url', '') : ''),
                            set::target('_blank'),
                            $targetProject->name_with_namespace . ':' . $MR->targetBranch
                        )
                    ),
                    tableData
                    (
                        item
                        (
                            set::name($lang->mr->status),
                            (!empty($MR->syncError) && $MR->synced === '0') ? h::span
                            (
                                setClass('danger'),
                                $MR->status
                            ) : zget($lang->mr->statusList, (string)$MR->status)
                        ),
                        item
                        (
                            set::name($lang->mr->mergeStatus),
                            ($MR->synced && empty($rawMR->changes_count)) ? span($lang->mr->cantMerge, h::code($lang->mr->noChanges)
                            ) : zget($lang->mr->mergeStatusList, !empty($rawMR->merge_status) ? $rawMR->merge_status : $MR->mergeStatus)
                        ),
                        item
                        (
                            set::name($lang->mr->MRHasConflicts),
                            $hasNoConflict ? $lang->mr->hasConflicts : $lang->mr->hasNoConflict
                        ),
                        item
                        (
                            set::name($lang->mr->description),
                            !empty($MR->description) ? html(nl2br($MR->description)) : $lang->noData
                        )
                    )
                ),
                cell
                (
                    setClass('cell mb-2'),
                    html(sprintf($lang->mr->commandDocument, $sourceProject->http_url_to_repo, $MR->sourceBranch, $branchPath, $MR->targetBranch, $branchPath, $MR->targetBranch))
                ),
                cell
                (
                    setClass('cell cell-history'),
                    history
                    (
                        set::objectID($MR->id),
                        set::commentUrl(createLink('action', 'comment', array('objectType' => 'mr', 'objectID' => $MR->id)))
                    )
                )
            ),
            cell
            (
                setClass('cell'),
                set::width('30%'),
                set::align('baseline'),
                div
                (
                    setClass('text-lg font-bold'),
                    $lang->mr->jobID
                ),
                $job
            )
        )
);

div
(
    setClass('flex justify-center items-center pt-6'),
    floatToolbar
    (
        set::object($MR),
        isAjaxRequest('modal') ? null : to::prefix(backBtn(set::icon('back'), $lang->goback)),
        set::main($mainActions),
        set::suffix($suffixActions)
    )
);
