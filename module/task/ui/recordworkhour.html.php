<?php
declare(strict_types=1);
/**
 * The batchCreate view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('confirmRecord', $lang->task->confirmRecord);

to::header
(
    $lang->task->addEffort,
    entityLabel
    (
        set::level(1),
        set::text($task->name),
        set::entityID($task->id),
        set::reverse(true),
        setClass('clip w-1/2')
    ),
    span
    (
        setClass('flex gap-x-2 mr-3'),
        $lang->task->estimate,
        span
        (
            setClass('label secondary-pale'),
            $task->estimate . $lang->task->suffixHour
        )
    ),
    span
    (
        setClass('flex gap-x-2'),
        $lang->task->consumed,
        span
        (
            setClass('label warning-pale'),
            span
            (
                setID('totalConsumed'),
                $task->consumed
            ),
            $lang->task->suffixHour
        )
    )
);

if($efforts)
{
    /* 多人串行任务工时分两部分. */
    if(!empty($task->team) and $task->mode == 'linear')
    {
        include './linearefforts.html.php';
    }
    else
    {
        $effortRows = array();
        foreach($efforts as $effort)
        {
            $effortRows[] = h::tr
            (
                h::td($effort->id),
                h::td($effort->date),
                h::td(zget($users, $effort->account)),
                h::td(html($effort->work)),
                h::td($effort->consumed . ' H'),
                h::td($effort->left . ' H'),
                h::td
                (
                    common::hasPriv('task', 'editEffort') ? a
                    (
                        setClass('btn ghost toolbar-item square size-sm text-primary'),
                        set::href(createLink('task', 'editEffort', "id={$effort->id}")),
                        set('data-toggle', 'modal'),
                        icon('edit'),
                    ) : null,
                    common::hasPriv('task', 'deleteWorkhour') ? a
                    (
                        setClass('btn ghost toolbar-item square size-sm ajax-submit text-primary'),
                        set('data-confirm', $lang->task->confirmDeleteEffort),
                        set::href(createLink('task', 'deleteWorkhour', "id={$effort->id}")),
                        icon('trash')
                    ) : null
                )
            );
        }
        div
        (
            setClass('table-title'),
            $lang->task->committed
        );
        h::table
        (
            setClass('table condensed bordered'),
            h::tr
            (
                h::th
                (
                    width('32px'),
                    $lang->idAB
                ),
                h::th
                (
                    width('100px'),
                    $lang->task->date
                ),
                h::th
                (
                    width('100px'),
                    $lang->task->recordedBy
                ),
                h::th($lang->task->work),
                h::th
                (
                    width('60px'),
                    $lang->task->consumedHours
                ),
                h::th
                (
                    width('60px'),
                    $lang->task->leftAB
                ),
                h::th
                (
                    width('80px'),
                    $lang->actions
                )
            ),
            $effortRows
        );
    }
}

if(!$this->task->canOperateEffort($task))
{
    $notice = '';
    if(!isset($task->members[$app->user->account]))
    {
        $notice = html(sprintf($lang->task->deniedNotice, '<strong>' . $lang->task->teamMember . '</strong>', $lang->task->logEfforts));
    }
    elseif($task->assignedTo != $app->user->account and $task->mode == 'linear')
    {
        $notice = html(sprintf($lang->task->deniedNotice, '<strong>' . $task->assignedToRealName . '</strong>', $lang->task->logEfforts));
    }

    div
    (
        setClass('alert with-icon'),
        icon('exclamation-sign text-gray text-4xl'),
        div
        (
            setClass('content'),
            $notice
        )
    );
}
else
{
    formBatchPanel
    (
        set::title($lang->task->addEffort),
        set::shadow(!isAjaxRequest('modal')),
        set::actions(array('submit')),
        set::maxRows(3),
        formBatchItem
        (
            set::name('id'),
            set::label($lang->idAB),
            set::control('index'),
            set::width('32px')
        ),
        formBatchItem
        (
            set::required(true),
            set::name('date'),
            set::label($lang->task->date),
            set::width('120px'),
            set::control('date'),
            set::value(helper::today())
        ),
        formBatchItem
        (
            set::name('work'),
            set::label($lang->task->work),
            set::width('auto'),
            set::control('textarea')
        ),
        formBatchItem
        (
            set::required(true),
            set::name('consumed'),
            set::label($lang->task->consumedHours),
            set::width('80px'),
            set::control
            (
                array(
                    'type' => 'inputControl',
                    'suffix' => $lang->task->suffixHour,
                    'suffixWidth' => 20
                )
            )
        ),
        formBatchItem
        (
            set::required(true),
            set::name('left'),
            set::label($lang->task->leftAB),
            set::width('80px'),
            set::control
            (
                array(
                    'type' => 'inputControl',
                    'suffix' => $lang->task->suffixHour,
                    'suffixWidth' => 20
                )
            )
        )
    );
}

render();
