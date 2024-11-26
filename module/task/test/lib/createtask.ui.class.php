<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createTaskTester extends tester
{
    /**
     * Create a devlop task.
     *
     * @param  string    $task
     * @access public
     * @return object
     *
     */
    public function createDevTask($task)
    {
        $form = $this->initForm('task', 'create', array('executionID' => '2'), 'appIframe-execution');
        $form->dom->name->setValue($task->name);
        $form->dom->assignedTo->picker('user1');
        $form->dom->estimate->setValue($task->estimate);
        $form->dom->getElement('//*[@id="form-task-create"]/div[26]/button[1]')->click();
        $form->wait(1);
