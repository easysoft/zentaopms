<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'idlabel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'statuslabel' . DS . 'v1.php';

class executionTaskList extends wg
{
    protected static array $defineProps = array
    (
        'tasks'      => 'array',       // 任务列表。
        'executions' => 'array',       // 执行列表。
        'onRenderItem' => '?callable'  // 渲染需求对象的回调函数。
    );

    protected function getItems()
    {
        global $lang;

        $tasks        = $this->prop('tasks', array());
        $executions   = $this->prop('executions', array());
        $onRenderItem = $this->prop('onRenderItem', array());
        $items        = array();
        $isInModal    = isInModal();
        $canViewTask  = hasPriv('task', 'view');

        foreach($tasks as $task)
        {
            if(!isset($task->execution) || !isset($executions[$task->execution])) continue;
            $execution   = $executions[$task->execution];
            $executionID = $execution->id;

            if(!isset($items[$executionID]))
            {
                $executionLink = (isset($execution->type) && $execution->type == 'kanban' && $isInModal) ? null : (empty($execution->multiple) ? createLink('project', 'view', "projectID=$task->project") : createLink('execution', 'view', "executionID=$executionID"));

                $items[$executionID] = array
                (
                    'icon'  => 'run',
                    'title' => $execution->name,
                    'hint'  => $execution->name,
                    'url'   => $executionLink,
                    'items' => array()
                );
            }

            $item = array
            (
                'title'       => $task->name,
                'hint'        => $task->name,
                'leading'     => array('html' => wg(idLabel::create($task->id))->render()),
                'content'     => array('html' => wg(statusLabel::create($task->status, $lang->task->statusList[$task->status]))->render()),
                'url'         => $canViewTask ? createLink('task', 'view', "taskID=$task->id") : null,
                'data-toggle' => $canViewTask ? 'modal' : null,
                'data-size'   => $canViewTask ? 'lg' : null,
            );

            if(is_callable($onRenderItem)) $item = $onRenderItem($item, $task);

            $items[$executionID]['items'][] = $item;

            $items[$executionID]['content'] = array('html' => '<span class="label gray-pale rounded-full size-sm">' . count($items[$executionID]['items']) . '</span>');
        }

        foreach($executions as $executionID => $execution)
        {
            if(isset($items[$executionID]) || !$execution->multiple) continue;

            $executionLink = (isset($execution->type) && $execution->type == 'kanban' && $isInModal) ? null : createLink('execution', 'view', "executionID=$executionID");
            $items[$executionID] = array
            (
                'icon'    => 'run',
                'title'   => $execution->name,
                'hint'    => $execution->name,
                'url'     => $executionLink,
                'content' => array('html' => '<span class="label gray-pale rounded-full size-sm">0</span>'),
                'items'   => array()
            );
        }

        return array_values($items);
    }

    protected function build()
    {
        return zui::nestedList
        (
            set::className('execution-task-list'),
            set::defaultNestedShow(),
            set::items($this->getItems())
        );
    }
}
