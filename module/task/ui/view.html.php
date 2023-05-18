<?php
declare(strict_types=1);
namespace zin;
global $lang;

/**
 * Detail title wg.
 *
 * @param array $props type: array('title' => string, 'className' => ?string).
 * @return wg
 */
function detailTitle(array $props): wg
{
    extract($props);
    $className = isset($className) ? $className : null;

    return div
    (
        setClass($className),
        $title
    );
}

/**
 * Detail subtitle wg.
 *
 * @param array $props type: array('title' => string, 'className' => ?string).
 * @return wg
 */
function detailSubtitle(array $props): wg
{
    extract($props);
    $className = isset($className) ? $className : null;

    return div
    (
        setClass('detail-subtitle', $className),
        $title
    );
}

/**
 * Detail description wg.
 *
 * @param array $props type: array('desc' => string, 'className' => ?string).
 * @return wg
 */
function detailDesc(array $props): wg
{
    extract($props);
    $className = isset($className) ? $className : null;

    return div
    (
        setClass('detail-desc', $className),
        $desc
    );
}

/**
 * Entity ID label wg.
 *
 * @param array $props type: array('id' => string|int, 'className' => ?string).
 * @return wg
 */
function idLabel(array $props): wg
{
    extract($props);
    $className = isset($className) ? $className : null;

    return label
    (
        setClass('justify-center rounded-full', $className),
        setStyle('height', '14px'),
        setStyle('padding', '0 6px'),
        $id
    );
}

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
        setClass($className),
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
 * Detail section wg.
 *
 * @param wg|array $wg wg or wg array.
 * @return wg
 */
function detailSection(wg|array $wg): wg
{
    return div
    (
        setClass('p-4'),
        $wg
    );
}

/**
 * Description heading wg.
 *
 * @param array $props type: array('title' => string, 'className' => ?string).
 * @return wg
 */
function descHeading(array $props): wg
{
    extract($props);
    $className = isset($className) ? $className : null;

    return div
    (
        setClass('desc-heading', $className),
        "[$title]"
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

function storySection(array $props): wg
{
    extract($props);
    return div
    (
        setClass('my-4'),
        descHeading(array('title' => $title, 'className' => $titleClass)),
        detailDesc(array('desc' => $desc)),
    );
}

div
(
    setClass('detail-header flex justify-between mb-3'),
    div
    (
        setClass('flex', 'items-center'),
        backBtn(array('url' => '')),
        idLabel(array('id' => $task->id, 'className' => 'ml-4 mr-2')),
        detailTitle(array('title' => $task->name, 'className' => 'text-lg text-title')),
    ),
    createTaskBtn(array('url' => ''))
);

div
(
    setClass('detail-body', 'canvas shadow rounded', 'flex'),
    div
    (
        setClass('detail-main grow'),
        detailSection
        (
            array(
                detailTitle(array('title' => $lang->task->legendDesc, 'className' => 'mb-3 mt-1 text-subtitle')),
                detailDesc(array('desc' => empty($task->desc) ? $lang->noData : $task->desc)),
            )
        ),
        detailSection
        (
            array(
                detailTitle(array('title' => $lang->task->story, 'className' => 'text-subtitle')),
                div
                (
                    setClass('my-3'),
                    idLabel(array('id' => $task->storyID, 'className' => 'mr-2')),
                    detailSubtitle(array('title' => $task->storyTitle, 'className' => 'text-base text-story-title inline-block')),
                ),
                div
                (
                    storySection(array('title' => $lang->story->legendSpec, 'titleClass' => 'mb-1 text-base', 'desc' => empty($task->storySpec) && empty($task->storyFiles) ? $lang->noData : $task->storySpec)),
                    storySection(array('title' => $lang->task->storyVerify, 'titleClass' => 'mb-1 text-base', 'desc' => empty($task->storyVerify) ? $lang->noData : $task->storyVerify))
                ),
            )
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
                            $lang->task->pri        => priNum(set::pri(1)),
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

render();
