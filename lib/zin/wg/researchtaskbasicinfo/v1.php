<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'datalist' . DS . 'v1.php';

class researchtaskBasicInfo extends wg
{
    protected static array $defineProps = array
    (
        'task'       => '?object',   // 当前任务。
        'product'    => '?object',   // 当前产品。
        'execution'  => '?object',   // 当前执行。
        'users'      => '?array',    // 用户信息。
        'users'      => '?array',    // 用户列表。
        'statusText' => '?string',   // 状态信息。
    );

    protected function getItems(): array
    {
        global $lang, $config;

        $task = $this->prop('task', data('task'));
        if(!$task) return array();

        $execution  = $this->prop('execution', data('execution'));
        $users      = $this->prop('users', data('users'));
        $statusText = $this->prop('statusText', $task->status);

        $items = array();
        if($execution->multiple)
        {
            $items[$lang->researchtask->execution] = array('control' => 'text', 'text' => $execution->name, 'title' => $execution->name);
        }

        $items[$lang->researchtask->assignedTo] = $task->assignedTo ? zget($users, $task->assignedTo) . $lang->at . formatTime($task->assignedDate) : '';

        $items[$lang->researchtask->status] = array
        (
            'control' => 'status',
            'class'   => 'task-status',
            'status'  => $task->status,
            'text'    => $statusText
        );

        $items[$lang->researchtask->progress] = "$task->progress %";

        $items[$lang->researchtask->pri] = array
        (
            'control' => 'pri',
            'pri'     => $task->pri,
            'text'    => $lang->researchtask->priList
        );

        return $items;
    }

    protected function build()
    {
        return new datalist
        (
            set::className('task-basic-info'),
            set::items($this->getItems())
        );
    }
}
