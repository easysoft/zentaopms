<?php
class taskTao extends taskModel
{
    /**
     * Compute progress of a task.
     *
     * @param  object   $task
     * @access private
     * @return int
     */
    protected function computeTaskProgress(object $task): float
    {
        if($task->consumed == 0 and $task->left == 0)
        {
            $progress = 0;
        }
        elseif($task->consumed != 0 and $task->left == 0)
        {
            $progress = 100;
        }
        else
        {
            $progress = round($task->consumed / ($task->consumed + $task->left), 2) * 100;
        }

        return $progress;
    }

    /**
     * Compute progress of task list, include its' children.
     *
     * @param  array     $tasks
     * @access private
     * @return object[]
     */
    protected function computeTasksProgress(array $tasks): array
    {
        foreach($tasks as $task)
        {
            $task->progress = $this->getTaskProgress($task);

            if(empty($task->children)) continue;
            foreach($task->children as $child)
            {
                $child->progress = $this->getTaskProgress($child);
            }
        }

        return $tasks;
    }
}
