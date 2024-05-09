<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'datalist' . DS . 'v1.php';

class taskLifeInfo extends wg
{
    protected static array $defineProps = array
    (
        'task'      => '?object',   // 当前需求。
        'users'     => '?array'     // 用户列表。
    );

    protected function getItems(): array
    {
        global $lang;

        $task = $this->prop('task', data('task'));
        if(!$task) return array();

        $users     = $this->prop('users', data('users'));
        $items     = array();

        $items[$lang->task->openedBy] =
            $task->openedBy ? zget($users, $task->openedBy, $task->openedBy) . $lang->at . $task->openedDate : '';
        $items[$lang->task->finishedBy] =
            $task->finishedBy ? zget($users, $task->finishedBy, $task->finishedBy) . $lang->at . $task->finishedDate : '';
        $items[$lang->task->canceledBy] =
            $task->canceledBy ? zget($users, $task->canceledBy, $task->canceledBy) . $lang->at . $task->canceledDate : '';
        $items[$lang->task->closedBy] =
            $task->closedBy ? zget($users, $task->closedBy, $task->closedBy) . $lang->at . $task->closedDate : '';
        $items[$lang->task->closedReason] =
            $task->closedReason ? $lang->task->reasonList[$task->closedReason] : '';
        $items[$lang->task->lastEdited] =
            $task->lastEditedBy ? zget($users, $task->lastEditedBy, $task->lastEditedBy) . $lang->at . $task->lastEditedDate : '';

        return $items;
    }

    protected function build()
    {
        return new datalist
        (
            set::className('task-life-info'),
            set::items($this->getItems())
        );
    }
}
