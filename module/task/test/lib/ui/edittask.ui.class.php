<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class editTaskTester extends tester
{
    /**
     * Edit a devlop task.
     *
     * @param  string    $task
     * @access public
     * @return object
     *
     */
    public function editDevTask($task)
    {
        $form = $this->initForm('task', 'edit', array('taskID' => '1'), 'appIframe-execution');
        $form->dom->name->setValue($task->name);
        $form->dom->assignedTo->picker($task->assignedTo);
        $form->dom->type->picker($task->type);
        $form->dom->savebtn->click();
        $form->wait(1);

        return $this->success('成功编辑开发任务');
    }
/**
     * Edit a task with name blank.
     *
     * @param  string    $task
     * @access public
     * @return object
     *
     */
    public function editNameBlankTask($task)
    {
        $form = $this->initForm('task', 'edit', array('taskID' => '2'), 'appIframe-execution');
        $form->dom->name->setValue($task->name);
        $form->dom->savebtn->click();
        $form->wait(1);

        return $this->failed('任务名称不能为空');
    }
}
