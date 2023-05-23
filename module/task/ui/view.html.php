<?php
declare(strict_types=1);
namespace zin;
global $lang;

/**
 * Goback button wg.
 *
 * @param array $props type: array('url' => string, 'className' => ?string).
 * @return wg
 */
function backBtn(array $props): wg
{
    global $lang;

    extract($props);
    $className = isset($className) ? $className : null;

    return btn
    (
        set::icon('back'),
        set::url($url),
        set::type('secondary'),
        setClass($className, 'mr-4'),
        $lang->goback
    );
}

/**
 * Create task button wg.
 *
 * @param array $props type: array('url' => string, className => ?string).
 * @return wg
 */
function createTaskBtn(array $props): wg
{
    global $lang;

    extract($props);
    $className = isset($className) ? $className : null;

    return btn
    (
        set::icon('plus'),
        set::url($url),
        set::type('primary'),
        setClass($className),
        $lang->task->create
    );
}

/**
 * Vertical data table wg.
 * @param array $data
 */
function vtable(array $data): wg
{
    $trs = array();
    foreach ($data as $key => $value)
    {
        $trs[] = h::tr
        (
            h::th($key),
            h::td($value)
        );
    }

    return h::table
    (
        setClass('table-data'),
        h::tbody($trs)
    );
}

div
(
    setClass('detail-header flex justify-between mb-3'),
    div
    (
        setClass('flex', 'items-center'),
        backBtn(array('url' => '')),
        entityLabel
        (
            set::entityID($task->id),
            set::level(1),
            set::text($task->name),
        )
    ),
    createTaskBtn(array('url' => ''))
);

div
(
    setClass('detail-body', 'canvas shadow rounded', 'flex'),
    div
    (
        setClass('detail-main grow'),
        section
        (
            set::title($lang->task->legendDesc),
            set::content(empty($task->desc) ? $lang->noData : $task->desc),
        ),
        section
        (
            set::title($lang->task->story),
            to::subTitle
            (
                entityLabel
                (
                    setClass('my-3'),
                    set::entityID($task->storyID),
                    set::level(3),
                    set::text($task->storyTitle),
                )
            ),
            set::content
            (
                array(
                    array(
                        'title' => $lang->story->legendSpec,
                        'content' => empty($task->storySpec) && empty($task->storyFiles) ? $lang->noData : $task->storySpec
                    ),
                    array(
                        'title' => $lang->task->storyVerify,
                        'content' => empty($task->storyVerify) ? $lang->noData : $task->storyVerify
                    )
                )
            ),
            set::useHtml(true)
        ),
        history
        (
            set::actions($actions),
            set::users($users),
            set::methodName($methodName),
        )
    ),
    div(
        setClass('detail-side px-5 py-4 flex-none surface'),
        tabs
        (
            set::items
            (
                array(
                    array('id' => 'legend-basic', 'label' => $lang->task->legendBasic, 'active' => true),
                    array('id' => 'legend-life', 'label' => $lang->task->legendLife),
                )
            ),
            div
            (
                setClass('tab-content'),
                tabPane
                (
                    setID('legend-basic'),
                    set::isActive(true),
                    vtable
                    (
                        array(
                            $lang->task->execution  => $execution->name,
                            $lang->task->module     => '',
                            $lang->task->assignedTo => $task->assignedTo ? $task->assignedToRealName : $lang->noData,
                            $lang->task->type       => zget($this->lang->task->typeList, $task->type, $task->type),
                            $lang->task->status     => $this->processStatus('task', $task),
                            $lang->task->progress   => $task->progress . ' %',
                            $lang->task->pri        => priLabel(1),
                        )
                    )
                ),
                tabPane
                (
                    setID('legend-life'),
                    vtable
                    (
                        array(
                            $lang->task->openedBy     => $task->openedBy ? zget($users, $task->openedBy, $task->openedBy) . $lang->at . $task->openedDate : $lang->noData,
                            $lang->task->finishedBy   => $task->finishedBy ? zget($users, $task->finishedBy, $task->finishedBy) . $lang->at . $task->finishedDate : $lang->noData,
                            $lang->task->canceledBy   => $task->canceledBy ? zget($users, $task->canceledBy, $task->canceledBy) . $lang->at . $task->canceledDate : $lang->noData,
                            $lang->task->closedBy     => $task->closedBy ? zget($users, $task->closedBy, $task->closedBy) . $lang->at . $task->closedDate : $lang->noData,
                            $lang->task->closedReason => $task->closedReason ? $lang->task->reasonList[$task->closedReason] : $lang->noData,
                            $lang->task->lastEdited   => $task->lastEditedBy ? zget($users, $task->lastEditedBy, $task->lastEditedBy) . $lang->at . $task->lastEditedDate : $lang->noData
                        )
                    )
                ),
            )
        ),
        tabs
        (
            set::items
            (
                array(
                    array('id' => 'legend-effort', 'label' => $lang->task->legendEffort, 'active' => true),
                    array('id' => 'legend-misc', 'label' => $lang->task->legendMisc),
                )
            ),
            div
            (
                setClass('tab-content'),
                tabPane
                (
                    setID('legend-effort'),
                    set::isActive(true),
                    vtable
                    (
                        array(
                            $lang->task->estimate    => $task->estimate . $lang->workingHour,
                            $lang->task->consumed    => round($task->consumed, 2) . $lang->workingHour,
                            $lang->task->left        => $task->left . $lang->workingHour,
                            $lang->task->estStarted  => $task->estStarted,
                            $lang->task->realStarted => helper::isZeroDate($task->realStarted) ? '' : substr($task->realStarted, 0, 19),
                            $lang->task->deadline    => $task->deadline,
                        )
                    )
                ),
                tabPane
                (
                    setID('legend-misc'),
                    vtable
                    (
                        array(
                            $lang->task->linkMR     => $task->openedBy ? zget($users, $task->openedBy, $task->openedBy) . $lang->at . $task->openedDate : $lang->noData,
                            $lang->task->linkCommit => $task->finishedBy ? zget($users, $task->finishedBy, $task->finishedBy) . $lang->at . $task->finishedDate : $lang->noData,
                        )
                    )
                ),
            )
        )
    )
);

commentDialog(set::name('comment'));

render();
