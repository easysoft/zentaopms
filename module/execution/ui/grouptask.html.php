<?php
declare(strict_types=1);
/**
 * The group task view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

$items = array();
if(isset($lang->execution->groupFilter[$groupBy]))
{
    foreach($lang->execution->groupFilter[$groupBy] as $filterKey => $name)
    {
        $items[] = li
        (
            setClass('nav-item'),
            a
            (
                ($filterKey == $filter) ? set('class', 'active') : null,
                span(setClass('text'), $name),
                ($filterKey == $filter) ? span(setClass('label size-sm rounded-full white'), $allCount) : null,
                set::href(createLink('execution', 'grouptask', "executionID={$executionID}&groupBy={$groupBy}&filter={$filterKey}")),
                set('data-app', $app->tab)
            )
        );
    }
}
else
{
    $items[] = li
    (
        setClass('nav-item'),
        a
        (
            set('class', 'active'),
            span(setClass('text'), $lang->all),
            span(setClass('label size-sm rounded-full white'), $allCount),
            set::href(createLink('execution', 'grouptask', "executionID={$executionID}&groupBy={$groupBy}")),
            set('data-app', $app->tab)
        )
    );
}

$lang->task->statusList['changed'] = $lang->task->storyChange;
featureBar
(
    !empty($tasks) ? li
    (
        setClass('nav-item feature-actions'),
        a
        (
            setClass('btn ghost group-collapse-all'),
            span(setClass('text'), $lang->execution->treeLevel['root']),
            icon('fold-all')
        )
    ) : null,
    li
    (
        setClass('nav-item hidden feature-actions'),
        a
        (
            setClass('btn ghost group-expand-all'),
            span(setClass('text'), $lang->execution->treeLevel['all']),
            icon('unfold-all')
        )
    ),
    $items
);

$canCreate     = !$isLimited && hasPriv('task', 'create');
$canImportTask = hasPriv('task', 'importTask');
$canImportBug  = hasPriv('task', 'importBug');
if(common::canModify('execution', $execution))
{
    $createLink = $this->createLink('task', 'create', "executionID={$execution->id}") . ($app->tab == 'project' ? '#app=project' : '');
    if(commonModel::isTutorialMode())
    {
        $wizardParams   = helper::safe64Encode("executionID={$execution->id}");
        $taskCreateLink = $this->createLink('tutorial', 'wizard', "module=task&method=create&params=$wizardParams");
    }

    $createItem = array('text' => $lang->task->create, 'url' => $createLink);

    if($canImportTask && $execution->multiple) $importTaskItem = array('text' => $lang->execution->importTask, 'url' => $this->createLink('execution', 'importTask', "execution={$execution->id}"), 'data-app' => $app->tab);
    if($canImportBug && $execution->lifetime != 'ops' && !in_array($execution->attribute, array('request', 'review')))
    {
        $importBugItem = array('text' => $lang->execution->importBug, 'url' => $this->createLink('execution', 'importBug', "execution={$execution->id}"), 'className' => 'importBug', 'data-app' => $app->tab);
    }
}

$importItems = !empty($importTaskItem) && empty($importBugItem) ? array($importTaskItem) : array();
$importItems = empty($importTaskItem) && !empty($importBugItem) ? array($importBugItem) : $importItems;
$importItems = !empty($importTaskItem) && !empty($importBugItem) ? array_filter(array($importTaskItem, $importBugItem)) : $importItems;

toolbar
(
    hasPriv('task', 'report') ? item(set(array
    (
        'text'     => $lang->task->report->common,
        'icon'     => 'bar-chart',
        'class'    => 'ghost',
        'url'      => createLink('task', 'report', "execution={$execution->id}&browseType={$browseType}"),
        'data-app' => $app->tab
    ))) : null,
    hasPriv('task', 'export') ? item(set(array
    (
        'text'        => $lang->export,
        'icon'        => 'export',
        'class'       => 'ghost export',
        'url'         => createLink('task', 'export', "execution={$execution->id}&orderBy={$orderBy}&type={$browseType}"),
        'data-toggle' => 'modal',
        'data-size'   => 'sm'
    ))) : null,
    !empty($importItems) ? dropdown(
        btn(
            setClass('btn ghost dropdown-toggle'),
            set::icon('import'),
            set::text($lang->import),
        ),
        set::items($importItems),
        set::placement('bottom-end')
    ) : null,
    $canCreate && isset($createItem) ? item(set($createItem + array('class' => 'btn primary', 'icon' => 'plus'))) : null
);

$groupList = array();
foreach($lang->execution->groups as $key => $value)
{
    if(empty($key)) continue;
    $link = createLink('execution', 'grouptask', "executionID={$executionID}&groupBy={$key}");
    $groupList[] = array
    (
        'text'     => $value,
        'url'      => $link,
        'active'   => $key == $groupBy,
        'data-app' => $app->tab
    );
}

$thead = function() use($lang, $groupList, $groupBy, $allCount)
{
    return h::tr
    (
        $allCount ? setClass('border-divider') : null,
        h::th
        (
            setClass('c-side text-left has-btn group-menu'),
            dropdown
            (
                btn
                (
                    setClass('ghost btn square btn-default'),
                    $lang->execution->groups[$groupBy]
                ),
                set::items($groupList)
            )
        ),
        h::th
        (
            setClass('c-id'),
            $lang->task->id
        ),
        h::th
        (
            setClass('c-pri'),
            $lang->priAB
        ),
        h::th
        (
            setClass('c-name'),
            $lang->task->name
        ),
        h::th
        (
            setClass('c-status'),
            $lang->task->status
        ),
        h::th
        (
            setClass('c-user'),
            $lang->task->assignedTo
        ),
        h::th
        (
            setClass('c-user'),
            $lang->task->finishedBy
        ),
        h::th
        (
            setClass('c-hours'),
            $lang->task->estimateAB
        ),
        h::th
        (
            setClass('c-hours'),
            $lang->task->consumedAB
        ),
        h::th
        (
            setClass('c-hours'),
            $lang->task->leftAB
        ),
        h::th
        (
            setClass('c-progress'),
            $lang->task->progressAB
        ),
        h::th
        (
            setClass('c-type'),
            $lang->typeAB
        ),
        h::th
        (
            setClass('c-date'),
            $lang->task->deadline
        ),
        h::th
        (
            setClass('c-actions'),
            $lang->actions
        )
    );
};

$tbody = function() use($tasks, $lang, $groupBy, $users, $groupByList, $execution, $members)
{
    global $app;

    $tbody = array();
    $groupIndex = 1;
    foreach($tasks as $groupKey => $groupTasks)
    {
        $groupWait     = 0;
        $groupDone     = 0;
        $groupDoing    = 0;
        $groupClosed   = 0;
        $groupEstimate = 0.0;
        $groupConsumed = 0.0;
        $groupLeft     = 0.0;

        $groupName = $groupKey;
        if($groupBy == 'story') $groupName = empty($groupName) ? $lang->task->noStory : zget($groupByList, $groupKey);
        if($groupBy == 'assignedTo' and $groupName == '') $groupName = $this->lang->task->noAssigned;

        $groupSum = 0;
        foreach($groupTasks as $taskKey => $task)
        {
            if($groupBy == 'story')
            {
                if(!$task->isParent)
                {
                    $groupEstimate += $task->estimate;
                    $groupConsumed += $task->consumed;
                    if($task->status != 'cancel' && $task->status != 'closed') $groupLeft += $task->left;
                }
            }
            else
            {
                if(!$task->isParent)
                {
                    $groupEstimate += $task->estimate;
                    $groupConsumed += $task->consumed;
                    if($groupBy == 'status' || ($task->status != 'cancel' && $task->status != 'closed')) $groupLeft += $task->left;
                }
            }

            if($task->status == 'wait')   $groupWait++;
            if($task->status == 'doing')  $groupDoing++;
            if($task->status == 'done')   $groupDone++;
            if($task->status == 'closed') $groupClosed++;
        }

        $groupSum = count($groupTasks);

        $i = 0;
        foreach($groupTasks as $task)
        {
            $assignedToStyle = $task->assignedTo == $app->user->account ? "style='color:red'" : '';

            $tbody[] = h::tr
            (
                set(array('data-id' => $groupIndex)),
                ($groupIndex > 1 and $i == 0) ? setClass('divider-top') : null,
                $i == 0 ? h::td
                (
                    $groupSum > 4 ? setClass('c-side text-left group-toggle text-top c-side-lg') : setClass('c-side text-left group-toggle text-top'),
                    set::rowspan($groupSum),
                    div
                    (
                        setClass('group-header'),
                        a
                        (
                            setClass('groupBtn'),
                            set::href('###'),
                            icon('caret-down'),
                            $groupName
                        ),
                        div
                        (
                            setClass('groupSummary small'),
                            ($groupBy == 'assignedTo' and isset($members[$task->assignedTo])) ? html(sprintf($lang->execution->memberHoursAB, zget($users, $task->assignedTo), $members[$task->assignedTo]->totalHours)) : null,
                            html(sprintf($lang->execution->groupSummaryAB, $groupSum, $groupWait, $groupDoing, $groupEstimate . $lang->execution->workHourUnit, $groupConsumed . $lang->execution->workHourUnit, $groupLeft . $lang->execution->workHourUnit))
                        )
                    )
                ) : null,
                h::td(sprintf('%03d', (string)$task->id)),
                h::td
                (
                    span
                    (
                        setClass("pri-{$task->pri}"),
                        $task->pri
                    )
                ),
                h::td
                (
                    setClass('c-name'),
                    set('title', $task->name),
                    !empty($task->mode) ? span(setClass('label gray-pale rounded-xl'), $lang->task->multipleAB) : null,
                    (!$task->isParent && $task->parent > 0) ? span(setClass('label gray-pale rounded-xl'), $lang->task->childrenAB) : null,
                    $task->isParent ? span(setClass('label gray-pale rounded-xl'), $lang->task->parentAB) : null,
                    a(set::href(createLink('task', 'view', "task=$task->id")), $task->name, set('data-app', $app->tab))
                ),
                h::td
                (
                    setClass('text-center'),
                    span
                    (
                        setClass("status-{$task->status}"),
                        zget($lang->task->statusList, $task->status, '')
                    )
                ),
                h::td
                (
                    setClass('text-center'),
                    span
                    (
                        set(array('style' => $assignedToStyle)),
                        $task->assignedToRealName
                    )
                ),
                h::td
                (
                    setClass('text-center'),
                    zget($users, $task->finishedBy)
                ),
                h::td
                (
                    setClass('text-right'),
                    helper::formatHours($task->estimate) . $lang->execution->workHourUnit
                ),
                h::td
                (
                    setClass('text-right'),
                    helper::formatHours($task->consumed) . $lang->execution->workHourUnit
                ),
                h::td
                (
                    setClass('text-right'),
                    helper::formatHours($task->left) . $lang->execution->workHourUnit
                ),
                h::td
                (
                    setClass('text-right'),
                    $task->progress . '%'
                ),
                h::td
                (
                    setClass('text-center'),
                    zget($lang->task->typeList, $task->type)
                ),
                h::td
                (
                    setClass('text-center'),
                    isset($task->delay) ? setClass('delayed') : null,
                    (substr((string)$task->deadline, 0, 4) > 0) ? substr((string)$task->deadline, 5, 6) : null
                ),
                common::canModify('execution', $execution) ? h::td
                (
                    setClass('text-center'),
                    common::hasPriv('task', 'assignTo') && common::hasDBPriv($task, 'task', 'assignto') ? btn
                    (
                        set
                        (
                            array
                            (
                                'url'         => createLink('task', 'assignTo', "executionID=$task->execution&taskID=$task->id"),
                                'data-toggle' => 'modal',
                                'class'       => 'btn ghost toolbar-item text-primary square size-sm',
                                'icon'        => 'hand-right',
                                'disabled'    => !empty($task) && $task->status == 'closed'
                            )
                        )
                    ) : null,
                    common::hasPriv('task', 'edit') && common::hasDBPriv($task, 'task', 'edit')? btn
                    (
                        set
                        (
                            array
                            (
                                'url'      => createLink('task', 'edit', "taskID=$task->id"),
                                'class'    => 'btn ghost toolbar-item text-primary square size-sm',
                                'icon'     => 'edit',
                                'data-app' => $app->tab
                            )
                        )
                    ) : null,
                    common::hasPriv('task', 'delete') && common::hasDBPriv($task, 'task', 'delete') ? btn
                    (
                        set
                        (
                            array
                            (
                                'url'          => createLink('task', 'delete', "taskID={$task->id}"),
                                'data-confirm' => $task->isParent ? $lang->task->confirmDeleteParent : $lang->task->confirmDelete,
                                'class'        => 'btn ghost toolbar-item text-primary square size-sm ajax-submit',
                                'icon'         => 'trash'
                            )
                        )
                    ) : null
                )
                : h::td()
            );

            $i ++;
        }

        if($i != 0)
        {
            $tbody[] = h::tr
            (
                $groupIndex > 1 ? setClass('hidden group-toggle group-summary divider-top') : setClass('hidden group-summary group-toggle'),
                set(array('data-id' => $groupIndex)),
                h::td
                (
                    setClass('c-side'),
                    div
                    (
                        setClass('summary-header'),
                        a
                        (
                            setClass('summaryBtn'),
                            set::href('###'),
                            icon('caret-right'),
                            $groupName
                        )
                    )
                ),
                h::td
                (
                    set::colspan(13),
                    div
                    (
                        setClass('table-row segments-list'),
                        ($groupBy == 'assignedTo' and isset($members[$task->assignedTo])) ? html(sprintf($lang->execution->memberHours, zget($users, $task->assignedTo), $members[$task->assignedTo]->totalHours)) : null,
                        ($groupBy == 'assignedTo' and $task->assignedTo and !isset($members[$task->assignedTo])) ? html(sprintf($lang->execution->memberHours, zget($users, $task->assignedTo), '0.0')) : null,
                        ($groupBy == 'assignedTo' and empty($task->assignedTo)) ? div
                        (
                            setClass('table-col'),
                            div
                            (
                                setClass('segments'),
                                div
                                (
                                    setClass('segment'),
                                    div
                                    (
                                        setClass('segment-title'),
                                        $groupName
                                    )
                                )
                            )
                        ) : null,
                        html(sprintf($lang->execution->countSummary, $groupSum, $groupDoing, $groupWait)),
                        html(sprintf($lang->execution->timeSummary, $groupEstimate . $lang->execution->workHourUnit, $groupConsumed . $lang->execution->workHourUnit, $groupLeft . $lang->execution->workHourUnit))
                    )
                )
            );
        }

        $groupIndex ++;
    }

    return $tbody;
};

if($tasks)
{
    div
    (
        set::id('tasksTable'),
        h::table
        (
            setClass('table condensed'),
            h::thead($thead()),
            h::tbody($tbody())
        )
    );
}
else
{
    panel
    (
        div
        (
            setClass('table-empty-tip h-60 flex items-center justify-center'),
            span
            (
                setClass('text-gray'),
                $lang->task->noTask
            ),
            $canCreate ? btn
            (
                set::text($lang->task->create),
                set::icon('plus'),
                set::url(createLink('task', 'create', "execution={$executionID}" . (isset($moduleID) ? "&storyID=&moduleID={$moduleID}" : '')) . ($app->tab == 'project' ? '#app=project' : '')),
                setClass('btn primary-pale border-primary ml-2')
            ) : null
        )
    );
}
