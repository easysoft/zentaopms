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
        setClass('clip w-1/2'),
    ),
    span
    (
        setClass('flex gap-x-2 mr-3'),
        $lang->task->estimate,
        span
        (
            setClass('label secondary-pale'),
            $task->estimate . $lang->task->suffixHour,
        ),
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
            $lang->task->suffixHour,
        )
    )
);


if($efforts)
{
    div
    (
        setClass('table-title'),
        $lang->task->committed
    );
    $tableData = initTableData($efforts, $config->task->effortTable->fieldList, $this->task);
    dtable
    (
        set::cols(array_values($config->task->effortTable->fieldList)),
        set::data($tableData),
    );
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
        icon('exclamation-sign'),
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
            set::width('32px'),
        ),
        formBatchItem
        (
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
            set::name('consumed'),
            set::label($lang->task->consumed),
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
            set::name('left'),
            set::label($lang->task->left),
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
