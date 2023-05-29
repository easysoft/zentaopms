<?php
declare(strict_types=1);
namespace zin;
global $lang;

detailHeader
(
    to::title(entityLabel(set(array('entityID' => $task->id, 'level' => 1, 'text' => $task->name)))),
    to::suffix(btn(set::icon('plus'), set::url(''), set::type('primary'), $lang->task->create))
);
detailBody
(
    sectionList
    (
        item
        (
            set::title($lang->task->legendDesc),
            empty($task->desc) ? $lang->noData : $task->desc
        ),
        item
        (
            set::title($lang->task->story),
            set::content(array(
                array('title' => $lang->story->legendSpec, 'content' => empty($task->storySpec) && empty($task->storyFiles) ? $lang->noData : $task->storySpec),
                array('title' => $lang->task->storyVerify, 'content' => empty($task->storyVerify) ? $lang->noData : $task->storyVerify)
            )),
            set::useHtml(true),
            to::subtitle(entityLabel
            (
                setClass('my-3'),
                set::entityID($task->storyID),
                set::level(3),
                set::text($task->storyTitle),
            ))
        ),
        history(set(array('actions' => $actions, 'users' => $users, 'methodName' => $methodName)))
    ),
    detailSide
    (
        tabs
        (
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
                        set::name($lang->task->assignedTo),
                        $task->assignedTo ? $task->assignedToRealName : $lang->noData
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
                        $task->openedBy ? zget($users, $task->openedBy, $task->openedBy) . $lang->at . $task->openedDate : $lang->noData
                    ),
                    item
                    (
                        set::name($lang->task->finishedBy),
                        $task->finishedBy ? zget($users, $task->finishedBy, $task->finishedBy) . $lang->at . $task->finishedDate : $lang->noData
                    ),
                    item
                    (
                        set::name($lang->task->canceledBy),
                        $task->canceledBy ? zget($users, $task->canceledBy, $task->canceledBy) . $lang->at . $task->canceledDate : $lang->noData,
                    ),
                    item
                    (
                        set::name($lang->task->closedBy),
                        $task->closedBy ? zget($users, $task->closedBy, $task->closedBy) . $lang->at . $task->closedDate : $lang->noData,
                    ),
                    item
                    (
                        set::name($lang->task->closedReason),
                        $task->closedReason ? $lang->task->reasonList[$task->closedReason] : $lang->noData
                    ),
                    item
                    (
                        set::name($lang->task->lastEdited),
                        $task->lastEditedBy ? zget($users, $task->lastEditedBy, $task->lastEditedBy) . $lang->at . $task->lastEditedDate : $lang->noData
                    ),
                )
            ),
        ),
        tabs
        (
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
                        $task->openedBy ? zget($users, $task->openedBy, $task->openedBy) . $lang->at . $task->openedDate : $lang->noData
                    ),
                    item
                    (
                        set::name($lang->task->linkCommit),
                        $task->finishedBy ? zget($users, $task->finishedBy, $task->finishedBy) . $lang->at . $task->finishedDate : $lang->noData
                    )
                )
            )
        )
    )
);

render();
