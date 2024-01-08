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
                array
                (
                    'entityID' => $task->id,
                    'level' => 1
                )
            ),
            $task->parent > 0 ?
            span
            (
                setClass('text'),
                set::title($task->name),
                span(setClass('label gray-pale rounded-xl'), $lang->task->childrenAB),
                a(set::href(createLink('task', 'view', "taskID={$task->parent}")), $task->parentName),
                span('/'),
                span(setStyle(array('color' => $task->color)), $task->name)
            ) : span(setStyle(array('color' => $task->color)), $task->name)
        )
    ),
    !isAjaxRequest('modal') && common::hasPriv('task', 'create', $task) ? to::suffix(btn(set::icon('plus'), set::url(createLink('task', 'create', "executionID={$task->execution}")), set::type('primary'), $lang->task->create)) : null
);

/* Construct suitable actions for the current task. */
if(common::hasPriv('repo', 'createBranch') && empty($task->linkedBranch)) $hasRepo = $this->loadModel('repo')->getRepoPairs('execution', $task->execution, false);

$operateMenus = array();
foreach($config->task->view->operateList['main'] as $operate)
{
    if($operate == 'createBranch')
    {
        if(empty($hasRepo) || !common::hasPriv('repo', $operate) || !empty($task->linkedBranch) || !common::canModify('execution', $execution)) continue;
    }
    else
    {
        if(!common::hasPriv('task', $operate, $task)) continue;
        if(!$this->task->isClickable($task, $operate)) continue;

        if($operate == 'batchCreate') $config->task->actionList['batchCreate']['text'] = $lang->task->children;
    }

    $operateMenus[] = $config->task->actionList[$operate];
}

/* Construct common actions for task. */
$commonActions = array();
foreach($config->task->view->operateList['common'] as $operate)
{
    if(!common::hasPriv('task', $operate, $task)) continue;
    if($operate == 'view' && $task->parent <= 0) continue;

    $settings = $config->task->actionList[$operate];
    if($operate != 'view') $settings['text'] = '';

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
            )
        );
    }
}

/* Set the module name of the task. */
$moduleTitle = '';
$moduleItems = array();
if(empty($modulePath))
{
    $moduleTitle .= '/';
    $moduleItems[] = span('/');
}
else
{
    if($product)
    {
        $moduleTitle   .= $product->name  . '/';
        $moduleItems[]  = span(a(set::href(createLink('product', 'browse', "productID=$product->id")), $product->name));
        $moduleItems[]  = icon('angle-right');
    }
    foreach($modulePath as $key => $module)
    {
        $moduleTitle   .= $module->name;
        $moduleItems[]  = a(set::href(createLink('execution', 'task', "executionID=$task->execution&browseType=byModule&param=$module->id")), $module->name) ?? span($module->name);
        if(isset($modulePath[$key + 1]))
        {
            $moduleTitle   .= '/';
            $moduleItems[]  = icon('angle-right');
        }
    }
}

$canViewMR = common::hasPriv('mr', 'view');
$linkedMR  = array();
foreach($linkMRTitles as $MRID => $linkMRTitle)
{
    $linkedMR[] = $canViewMR ? a
    (
        set::href(createLink('mr', 'view', "MRID=$MRID")),
        "#$MRID $linkMRTitle",
        setData(array('app' => $app->tab))
    ) : div("#$MRID $linkMRTitle");
}

$canViewRevision = common::hasPriv('repo', 'revision');
$linkedCommits   = array();
foreach($linkCommits as $commit)
{
    if(empty($commit->revision)) continue;

    $revision        = substr($commit->revision, 0, 10);
    $linkedCommits[] = $canViewRevision ? a
    (
        set::href(createLink('repo', 'revision', "repoID={$commit->repo}&objectID={$task->execution}&revision={$commit->revision}")),
        "#$revision",
        setData(array('app' => $app->tab))
    ) : div($revision . ' ' . $commit->comment);
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
                    set::text($fromBug->title)
                ),
                item
                (
                    set::title($lang->bug->steps),
                    empty($fromBug->steps) ? $lang->noData : html($fromBug->steps)
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
                    set::text($task->storyTitle)
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
                )
            )
        ) : null,
        $task->children ?
        section
        (
            set::title($lang->task->children),
            dtable
            (
                set::cols(array_values($config->task->dtable->children->fieldList)),
                set::userMap($users),
                set::data($children),
                set::checkable(false)
            )
        ) : null
    ),
    $task->files ? fileList
    (
        set::files($task->files)
    ) : null,
    history(),
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
                        a
                        (
                            set::href(createLink('execution', 'view', "executionID=$execution->id")),
                            $execution->name
                        )
                    ),
                    item
                    (
                        set::name($lang->task->module),
                        set::title($moduleTitle),
                        $moduleItems
                    ),
                    item
                    (
                        set::name($lang->task->story),
                        a
                        (
                            setData(
                                array
                                (
                                    'toggle' => 'modal',
                                    'size'   => 'lg'
                                )
                            ),
                            set::href(createLink('story', 'view', "id={$task->story}")),
                            set::title($task->storyTitle),
                            $task->storyTitle
                        ),
                        $task->needConfirm ? span
                        (
                            setClass('ml-1'),
                            '(' . $lang->story->changed,
                            a
                            (
                                setClass('mx-1 rounded primary-pale p-1'),
                                set::href(createLink('task', 'confirmStoryChange', "taskID={$task->id}")),
                                $lang->confirm
                            ),
                            ')'
                        ) : null
                    ),
                    item
                    (
                        set::name($lang->task->fromBug),
                        !empty($fromBug) ? a
                        (
                            setData
                            (
                                array
                                (
                                    'toggle' => 'modal',
                                    'size'   => 'lg'
                                )
                            ),
                            set::href(createLink('bug', 'view', "id={$task->fromBug}")),
                            set::title($fromBug->title),
                            $fromBug->title
                        ) : null
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
                        priLabel
                        (
                            setClass('align-sub'),
                            $task->pri,
                            set::text($lang->task->priList)
                        )
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
                        $task->canceledBy ? zget($users, $task->canceledBy, $task->canceledBy) . $lang->at . $task->canceledDate : ''
                    ),
                    item
                    (
                        set::name($lang->task->closedBy),
                        $task->closedBy ? zget($users, $task->closedBy, $task->closedBy) . $lang->at . $task->closedDate : ''
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
                    )
                )
            ),
            $task->team ? tabPane
            (
                set::key('legend-team'),
                set::title($lang->task->team),
                h::table
                (
                    setClass('table condensed bordered'),
                    setID('team'),
                    h::thead
                    (
                        h::tr
                        (
                            h::th
                            (
                                $lang->task->team,
                                set::width('80px')
                            ),
                            h::th
                            (
                                $lang->task->estimateAB,
                                set::width('60px')
                            ),
                            h::th
                            (
                                $lang->task->consumedAB,
                                set::width('60px')
                            ),
                            h::th
                            (
                                $lang->task->leftAB,
                                set::width('60px')
                            ),
                            h::th
                            (
                                $lang->task->statusAB,
                                set::width('80px')
                            )
                        )
                    ),
                    h::tbody($teams)
                )
            ) : null
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
                        round($task->consumed, 2) . $lang->workingHour
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
                        helper::isZeroDate($task->realStarted) ? '' : substr($task->realStarted, 0, 19)
                    ),
                    item
                    (
                        set::name($lang->task->deadline),
                        $task->deadline
                    )
                )
            ),
            tabPane
            (
                set::key('legend-misc'),
                set::title($lang->task->legendMisc),
                tableData
                (
                    empty($task->linkedBranch) ? null : item
                    (
                        set::name($lang->task->relatedBranch),
                        current($task->linkedBranch)
                    ),
                    item
                    (
                        set::name($lang->task->linkMR),
                        div($linkedMR)
                    ),
                    item
                    (
                        set::name($lang->task->linkCommit),
                        div($linkedCommits)
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
