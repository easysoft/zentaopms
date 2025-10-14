<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class batchCreateTaskTester extends tester
{
    /**
     * Batch create tasks.
     *
     * @param  string    $task
     * @access public
     * @return object
     *
     */
    public function batchCreateTask($task)
    {
        $form = $this->initForm('task', 'batchCreate', array('executionID' => '2'), 'appIframe-execution');
        $form->dom->name->setValue($task->name);
        $form->dom->assignedTo->picker('user1');
        $form->dom->estimate->setValue($task->estimate);
        $form->dom->savebtn->click();
        $form->wait(1);

        return $this->success('成功批量创建任务');
    }


    /**
     * Batch create tasks with empty task names.
     *
     * @param  string    $task
     * @access public
     * @return object
     *
     */
    public function batchCreateNameBlankTask($task)
    {
        $form = $this->initForm('task', 'batchCreate', array('executionID' => '2'), 'appIframe-execution');
        $form->dom->name->setValue($task->name);
        $form->dom->savebtn->click();
        $form->wait(1);

        return $this->failed('保存成功');
    }
}
