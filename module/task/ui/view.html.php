<?php
declare(strict_types=1);
/**
 * The activate view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */
namespace zin;
global $lang;

detailHeader
(
    to::title
    (
        $task->team ? span
        (
            setClass('label primary-pale'),
            $lang->task->modeList[$task->mode]
        ) : null,
        entityLabel
        (
            set
            (
                array('entityID' => $task->id, 'level' => 1, 'text' => $task->name)
            )
        )
    ),
    !isAjaxRequest('modal') && common::hasPriv('task', 'create') ? to::suffix(btn(set::icon('plus'), set::url(createLink('task', 'create', "executionID={$task->execution}")), set::type('primary'), $lang->task->create)) : null
);

/* Construct suitable actions for the current task. */
$operateMenus = array();
foreach($config->task->view->operateList['main'] as $operate)
{
    if(!common::hasPriv('task', $operate)) continue;
    if(!$this->task->isClickable($task, $operate)) continue;

    if($operate == 'batchCreate') $config->task->actionList['batchCreate']['text'] = $lang->task->children;

    $operateMenus[] = $config->task->actionList[$operate];
}

/* Construct common actions for task. */
$commonActions = array();
foreach($config->task->view->operateList['common'] as $operate)
{
    if(!common::hasPriv('task', $operate)) continue;
    if($operate == 'view' && $task->parent <= 0) continue;

    $settings = $config->task->actionList[$operate];
    $settings['text'] = '';

    $commonActions[] = $settings;
}

if($task->children) $children = initTableData($task->children, $config->task->dtable->children->fieldList, $this->task);
if($task->team)
{
    $teams = array();
    foreach($task->team as $team)
    {
        $teams[] = h::tr
        (
            h::td
            (
                zget($users, $team->account)
            ),
            h::td
            (
                (float)$team->estimate
            ),
            h::td
            (
                (float)$team->consumed
            ),
            h::td
            (
                (float)$team->left
            ),
            h::td
            (
                setClass("status-{$team->status}"),
                zget($lang->task->statusList, $team->status)
            ),
        );
    }
}

detailBody
(
    sectionList
    (
        section
        (
            set::title($lang->task->legendDesc),
            set::content(empty($task->desc) ? $lang->noData : $task->desc),
            set::useHtml(true)
        ),
        $task->fromBug ?
        section
        (
            set::title($lang->task->fromBug),
            sectionCard
            (
                entityLabel
                (
                    set::entityID($task->fromBug),
                    set::text($fromBug->title),
                ),
                item
                (
                    set::title($lang->bug->steps),
                    empty($fromBug->steps) && empty($fromBug->steps) ? $lang->noData : html($fromBug->steps)
                )
            )
        ) : null,
        !$task->fromBug && $task->story ?
        section
        (
            set::title($lang->task->story),
            sectionCard
            (
                entityLabel
                (
                    set::entityID($task->storyID),
                    set::text($task->storyTitle),
                ),
                item
                (
                    set::title($lang->story->legendSpec),
                    empty($task->storySpec) && empty($task->storyFiles) ? $lang->noData : html($task->storySpec)
                ),
                item
                (
                    set::title($lang->task->storyVerify),
                    empty($task->storyVerify) ? $lang->noData : html($task->storyVerify)
                ),
            )
        ) : null,
        $task->children ?
        section
        (
            set::title($lang->task->children),
            dtable
            (
                set::cols(array_values($config->task->dtable->children->fieldList)),
                set::data($children),
                set::checkable(false),
            )
        ) : null,
    ),
    $task->files ? fileList
    (
        set::files($task->files),
    ) : null,
    history(set::commentUrl(createLink('action', 'comment', array('objectType' => 'task', 'objectID' => $task->id))),),
    floatToolbar
    (
        isAjaxRequest('modal') ? null : to::prefix(backBtn(set::icon('back'), $lang->goback)),
        set::main($operateMenus),
        set::suffix($commonActions),
        set::object($task)
    ),
    detailSide
    (
        tabs
        (
            set::collapse(true),
            tabPane
            (
                set::key('legend-basic'),
                set::title($lang->task->legendBasic),
                set::active(true),
                tableData
                (
                    item
                    (
                        set::name($lang->task->execution),
                        $execution->name
                    ),
                    item
                    (
                        set::name($lang->task->module),
                        ''
                    ),
                    item
                    (
                        set::name($lang->task->story),
                        a
                        (
                            set('data-toggle', 'modal'),
                            set('data-size', 'lg'),
                            set::href(createLink('story', 'view', "id={$task->story}")),
                            set::title($task->storyTitle),
                            $task->storyTitle
                        )
                    ),
                    item
                    (
                        set::name($lang->task->fromBug),
                        a
                        (
                            set('data-toggle', 'modal'),
                            set('data-size', 'lg'),
                            set::href(createLink('bug', 'view', "id={$task->fromBug}")),
                            set::title($fromBug->title),
                            $fromBug->title
                        )
                    ),
                    item
                    (
                        set::name($lang->task->assignedTo),
                        $task->assignedToRealName
                    ),
                    item
                    (
                        set::name($lang->task->type),
                        zget($this->lang->task->typeList, $task->type, $task->type)
                    ),
                    item
                    (
                        set::name($lang->task->status),
                        $this->processStatus('task', $task)
                    ),
                    item
                    (
                        set::name($lang->task->progress),
                        $task->progress . ' %'
                    ),
                    item
                    (
                        set::name($lang->task->pri),
                        priLabel(1)
                    )
                )
            ),
            tabPane
            (
                set::key('legend-life'),
                set::title($lang->task->legendLife),
                tableData
                (
                    item
                    (
                        set::name($lang->task->openedBy),
                        $task->openedBy ? zget($users, $task->openedBy, $task->openedBy) . $lang->at . $task->openedDate : ''
                    ),
                    item
                    (
                        set::name($lang->task->finishedBy),
                        $task->finishedBy ? zget($users, $task->finishedBy, $task->finishedBy) . $lang->at . $task->finishedDate : ''
                    ),
                    item
                    (
                        set::name($lang->task->canceledBy),
                        $task->canceledBy ? zget($users, $task->canceledBy, $task->canceledBy) . $lang->at . $task->canceledDate : '',
                    ),
                    item
                    (
                        set::name($lang->task->closedBy),
                        $task->closedBy ? zget($users, $task->closedBy, $task->closedBy) . $lang->at . $task->closedDate : '',
                    ),
                    item
                    (
                        set::name($lang->task->closedReason),
                        $task->closedReason ? $lang->task->reasonList[$task->closedReason] : ''
                    ),
                    item
                    (
                        set::name($lang->task->lastEdited),
                        $task->lastEditedBy ? zget($users, $task->lastEditedBy, $task->lastEditedBy) . $lang->at . $task->lastEditedDate : ''
                    ),
                )
            ),
            $task->team ? tabPane
            (
                set::key('legend-team'),
                set::title($lang->task->team),
                h::table
                (
                    setClass('table table-data'),
                    set::id('team'),
                    h::thead
                    (
                        h::tr
                        (
                            h::th
                            (
                                $lang->task->team,
                                set::width('80px'),
                            ),
                            h::th
                            (
                                $lang->task->estimateAB,
                                set::width('60px'),
                            ),
                            h::th
                            (
                                $lang->task->consumedAB,
                                set::width('60px'),
                            ),
                            h::th
                            (
                                $lang->task->leftAB,
                                set::width('60px'),
                            ),
                            h::th
                            (
                                $lang->task->statusAB,
                                set::width('80px'),
                            ),
                        ),
                    ),
                    h::tbody($teams)
                )
            ) : null,
        ),
        tabs
        (
            set::collapse(true),
            tabPane
            (
                set::key('legend-effort'),
                set::title($lang->task->legendEffort),
                set::active(true),
                tableData
                (
                    item
                    (
                        set::name($lang->task->estimate),
                        $task->estimate . $lang->workingHour
                    ),
                    item
                    (
                        set::name($lang->task->consumed),
                        round($task->consumed, 2) . $lang->workingHour,
                    ),
                    item
                    (
                        set::name($lang->task->left),
                        $task->left . $lang->workingHour
                    ),
                    item
                    (
                        set::name($lang->task->estStarted),
                        $task->estStarted
                    ),
                    item
                    (
                        set::name($lang->task->realStarted),
                        helper::isZeroDate($task->realStarted) ? '' : substr($task->realStarted, 0, 19),
                    ),
                    item
                    (
                        set::name($lang->task->deadline),
                        $task->deadline
                    ),
                )
            ),
            tabPane
            (
                set::key('legend-misc'),
                set::title($lang->task->legendMisc),
                tableData
                (
                    item
                    (
                        set::name($lang->task->linkMR),
                        $task->openedBy ? zget($users, $task->openedBy, $task->openedBy) . $lang->at . $task->openedDate : ''
                    ),
                    item
                    (
                        set::name($lang->task->linkCommit),
                        $task->finishedBy ? zget($users, $task->finishedBy, $task->finishedBy) . $lang->at . $task->finishedDate : ''
                    )
                )
            )
        )
    )
);

if(!isInModal())
{
    floatPreNextBtn
    (
        !empty($preAndNext->pre)  ? set::preLink(createLink('task', 'view', "taskID={$preAndNext->pre->id}"))   : null,
        !empty($preAndNext->next) ? set::nextLink(createLink('task', 'view', "taskID={$preAndNext->next->id}")) : null
    );
}
