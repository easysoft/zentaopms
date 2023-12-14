<?php
declare(strict_types=1);
/**
 * The treetask view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

div
(
    setClass('section-list', 'canvas', 'pt-4', 'pb-6', 'px-4', 'mb-4'),
    div
    (
        setClass('flex items-center flex-nowrap mb-4'),
        label
        (
            setClass('flex-none rounded-full dark-outline'),
            $task->id
        ),
        $task->parent > 0 ?  label
        (
            setClass('flex-none rounded-full dark-outline ml-2'),
            $this->lang->task->childrenAB
        ) : null,
        !empty($task->team) ?  label
        (
            setClass('flex-none rounded-full dark-outline ml-2'),
            $this->lang->task->multipleAB
        ) : null,
        span
        (
            setClass('text-md font-bold mx-2 clip'),
            (isset($task->parentName) ? $task->parentName . '/' : '') . $task->name
        ),
        label
        (
            setClass('flex-none rounded-full status-' . $task->status),
            $this->processStatus('task', $task)
        )
    ),
    div
    (
        setClass('flex items-center flex-wrap mb-4'),
        div
        (
            setClass('w-1/3'),
            $lang->task->estimate,
            span
            (
                setClass('ml-2 font-bold'),
                $task->estimate . ' ' . $lang->execution->workHourUnit
            )
        ),
        div
        (
            setClass('w-1/3'),
            $lang->task->consumedAB,
            span
            (
                setClass('ml-2 font-bold'),
                round($task->consumed, 2) . ' ' . $lang->execution->workHourUnit
            )
        ),
        div
        (
            setClass('w-1/3'),
            $lang->task->leftAB,
            span
            (
                setClass('ml-2 font-bold'),
                $task->left . ' ' . $lang->execution->workHourUnit
            )
        ),
        div
        (
            setClass('w-full mt-4'),
            $lang->task->type,
            span
            (
                setClass('ml-2 font-bold'),
                $lang->task->typeList[$task->type]
            )
        ),
        helper::isZeroDate($task->deadline) ? null : div
        (
            setClass('w-full mt-4'),
            $lang->task->deadline,
            span
            (
                setClass('ml-2 font-bold'),
                $task->deadline
            ),
            isset($task->delay) ? label
            (
                setClass('flex-none rounded-full danger-pale ml-2'),
                html(sprintf($lang->task->delayWarning, $task->delay))
            ) : null
        )
    ),
    btngroup
    (
        setID('actionButtons'),
        setClass('mb-4'),
        ($task->status == 'wait' && hasPriv('task', 'finish')) ? btn
        (
            setClass('text-primary'),
            set::icon('checked'),
            set::hint($lang->task->finish),
            set::url(createLink('task', 'finish', array('taskID' => $task->id))),
            set::disabled(!$this->task->isClickable($task, 'finish')),
            set('data-toggle', 'modal')
        ) : null,
        ($task->status == 'wait' && hasPriv('task', 'start')) ? btn
        (
            setClass('text-primary ml-2'),
            set::icon('play'),
            set::hint($lang->task->start),
            set::url(createLink('task', 'start', array('taskID' => $task->id))),
            set::disabled(!$this->task->isClickable($task, 'start')),
            set('data-toggle', 'modal')
        ) : null,
        ($task->status == 'pause' && hasPriv('task', 'restart')) ? btn
        (
            setClass('text-primary'),
            set::icon('restart'),
            set::hint($lang->task->restart),
            set::url(createLink('task', 'restart', array('taskID' => $task->id))),
            set::disabled(!$this->task->isClickable($task, 'restart')),
            set('data-toggle', 'modal')
        ) : null,
        (($task->status == 'done' || $task->status == 'cancel' || $task->status == 'closed') && hasPriv('task', 'close')) ? btn
        (
            setClass('text-primary'),
            set::icon('off'),
            set::hint($lang->task->close),
            set::url(createLink('task', 'close', array('taskID' => $task->id))),
            set::disabled(!$this->task->isClickable($task, 'close')),
            set('data-toggle', 'modal')
        ) : null,
        ($task->status == 'doing' && hasPriv('task', 'finish')) ? btn
        (
            setClass('text-primary'),
            set::icon('checked'),
            set::hint($lang->task->finish),
            set::url(createLink('task', 'finish', array('taskID' => $task->id))),
            set::disabled(!$this->task->isClickable($task, 'finish')),
            set('data-toggle', 'modal')
        ) : null,
        hasPriv('task', 'recordWorkhour') ? btn
        (
            setClass('text-primary ml-2'),
            set::icon('time'),
            set::hint($lang->task->recordWorkhour),
            set::url(createLink('task', 'recordWorkhour', array('taskID' => $task->id))),
            set::disabled(!$this->task->isClickable($task, 'recordWorkhour')),
            set('data-toggle', 'modal')
        ) : null,
        hasPriv('task', 'edit') ? btn
        (
            setClass('text-primary ml-2'),
            set::icon('edit'),
            set::hint($lang->task->edit),
            set::url(createLink('task', 'edit', array('taskID' => $task->id))),
            set::disabled(!$this->task->isClickable($task, 'edit')),
            set('data-app', $app->tab)
        ) : null,
        ((empty($task->team) || empty($task->children)) && hasPriv('task', 'batchCreate') && $config->vision != 'lite') ? btn
        (
            setClass('text-primary ml-2'),
            set::icon('split'),
            set::hint($lang->task->batchCreate),
            set::url(createLink('task', 'batchCreate', "execution={$task->execution}&storyID={$task->story}&moduleID={$task->module}&askID={$task->id}&ifame=0")),
            set::disabled(!$this->task->isClickable($task, 'batchCreate')),
            set('data-app', $app->tab)
        ) : null
    ),
    section
    (
        set::title($lang->task->legendDesc),
        set::content(empty($task->desc) ? $lang->noData : $task->desc),
        set::useHtml(true)
    )
);

history
(
    set::commentBtn(true),
    set::commentUrl(createLink('action', 'comment', array('objectType' => 'task', 'objectID' => $task->id)))
);
