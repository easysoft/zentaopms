<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'datalist' . DS . 'v1.php';

class taskEffortInfo extends wg
{
    protected static array $defineProps = array
    (
        'task'=> '?object'   // 当前任务。
    );

    protected function getItems(): array
    {
        global $lang;

        $task = $this->prop('task', data('task'));
        if(!$task) return array();

        $items = array();

        $items[$lang->task->estimate] =
            $task->estimate . ' ' . $lang->task->suffixHour;
        $items[$lang->task->consumed] =
            round($task->consumed, 2) . ' ' . $lang->task->suffixHour;
        $items[$lang->task->left] =
            $task->left . ' ' . $lang->task->suffixHour;
        $items[$lang->task->estStarted] =
            helper::isZeroDate($task->estStarted) ? '' : $task->estStarted;
        $items[$lang->task->realStarted] =
            helper::isZeroDate($task->realStarted) ? '' : substr($task->realStarted, 0, 19);
        $items[$lang->task->deadline]['content'] =
            helper::isZeroDate($task->deadline) ? '' : $task->deadline;
        if(isset($task->delay)) $items[$lang->task->deadline]['children'] = label(html(sprintf($lang->task->delayWarning, $task->delay)), setClass('danger-pale circle'));

        return $items;
    }

    protected function build()
    {
        return new datalist
        (
            set::className('task-effort-info'),
            set::items($this->getItems())
        );
    }
}
