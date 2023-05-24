<?php
declare(strict_types=1);
namespace zin;
global $lang;

detailHeader
(
    to::title(entityLabel(set(array('entityID' => $task->id, 'level' => 1, 'text' => $task->name)))),
    to::suffix(btn(set::icon('plus'), set::url(''), set::type('primary'), $lang->task->create))
);

$sectionSubtitle = function() use($task)
{
    return entityLabel
    (
        setClass('my-3'),
        set::entityID($task->storyID),
        set::level(3),
        set::text($task->storyTitle),
    );
};

$legendBasicData = function() use($lang, $task, $execution)
{
    return tableData
    (
        set::items
        (
            array(
                array('name' => $lang->task->execution,  'value' => $execution->name),
                array('name' => $lang->task->module,     'value' => ''),
                array('name' => $lang->task->assignedTo, 'value' => $task->assignedTo ? $task->assignedToRealName : $lang->noData),
                array('name' => $lang->task->type,       'value' => zget($this->lang->task->typeList, $task->type, $task->type)),
                array('name' => $lang->task->status,     'value' => $this->processStatus('task', $task)),
                array('name' => $lang->task->progress,   'value' => $task->progress . ' %'),
                array('name' => $lang->task->pri,        'value' => array('wg' => 'priLabel', 'pri' => '1'))
            )
        )
    );
};

$legendLifeData = function() use($lang, $task, $users)
{
    return tableData
    (
        set::items
        (
            array(
                array('name' => $lang->task->openedBy,     'value' => $task->openedBy ? zget($users, $task->openedBy, $task->openedBy) . $lang->at . $task->openedDate : $lang->noData),
                array('name' => $lang->task->finishedBy,   'value' => $task->finishedBy ? zget($users, $task->finishedBy, $task->finishedBy) . $lang->at . $task->finishedDate : $lang->noData),
                array('name' => $lang->task->canceledBy,   'value' => $task->canceledBy ? zget($users, $task->canceledBy, $task->canceledBy) . $lang->at . $task->canceledDate : $lang->noData),
                array('name' => $lang->task->closedBy,     'value' => $task->closedBy ? zget($users, $task->closedBy, $task->closedBy) . $lang->at . $task->closedDate : $lang->noData),
                array('name' => $lang->task->closedReason, 'value' => $task->closedReason ? $lang->task->reasonList[$task->closedReason] : $lang->noData),
                array('name' => $lang->task->lastEdited,   'value' => $task->lastEditedBy ? zget($users, $task->lastEditedBy, $task->lastEditedBy) . $lang->at . $task->lastEditedDate : $lang->noData)
            )
        ),
    );
};

$legendEffortData = function() use($lang, $task)
{
    return tableData
    (
        set::items
        (
            array(
                array('name' => $lang->task->estimate,    'value' => $task->estimate . $lang->workingHour),
                array('name' => $lang->task->consumed,    'value' => round($task->consumed, 2) . $lang->workingHour),
                array('name' => $lang->task->left,        'value' => $task->left . $lang->workingHour),
                array('name' => $lang->task->estStarted,  'value' => $task->estStarted),
                array('name' => $lang->task->realStarted, 'value' => helper::isZeroDate($task->realStarted) ? '' : substr($task->realStarted, 0, 19)),
                array('name' => $lang->task->deadline,    'value' => $task->deadline),
            )
        )
    );
};

$legendMiscData = function() use($lang, $task, $users)
{
    return tableData
    (
        set::items
        (
            array(
                array('name' => $lang->task->linkMR,     'value' => $task->openedBy ? zget($users, $task->openedBy, $task->openedBy) . $lang->at . $task->openedDate : $lang->noData),
                array('name' => $lang->task->linkCommit, 'value' => $task->finishedBy ? zget($users, $task->finishedBy, $task->finishedBy) . $lang->at . $task->finishedDate : $lang->noData),
            )
        )
    );
};

$sectionListItems = array(
    array('title' => $lang->task->legendDesc, 'content' => empty($task->desc) ? $lang->noData : $task->desc),
    array(
        'title' => $lang->task->story,
        'content' => array(
            array('title' => $lang->story->legendSpec, 'content' => empty($task->storySpec) && empty($task->storyFiles) ? $lang->noData : $task->storySpec),
            array('title' => $lang->task->storyVerify, 'content' => empty($task->storyVerify) ? $lang->noData : $task->storyVerify)
        ),
        'useHtml' => true,
        'subtitle' => $sectionSubtitle
    )
);

detailBody
(
    sectionList
    (
        set::items($sectionListItems),
        history(set(array('actions' => $actions, 'users' => $users, 'methodName' => $methodName)))
    ),
    detailSide
    (
        tabs
        (
            set::items
            (
                array(
                    array('id' => 'legend-basic', 'label' => $lang->task->legendBasic, 'data' => $legendBasicData, 'active' => true),
                    array('id' => 'legend-life',  'label' => $lang->task->legendLife,  'data' => $legendLifeData),
                )
            )
        ),
        tabs
        (
            set::items
            (
                array(
                    array('id' => 'legend-effort', 'label' => $lang->task->legendEffort, 'data' => $legendEffortData, 'active' => true),
                    array('id' => 'legend-misc',   'label' => $lang->task->legendMisc,   'data' => $legendMiscData)
                )
            )
        )
    )
);

commentDialog(set::name('comment'));

render();
