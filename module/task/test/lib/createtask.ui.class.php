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

        return $this->success('成功创建开发任务');
    }

    /**
     * Create a design task.
     *
     * @param  string    $task
     * @access public
     * @return object
     *
     */
    public function createDesignTask($task)
    {
        $form = $this->initForm('task', 'create', array('executionID' => '2'), 'appIframe-execution');
        $form->dom->type->picker('设计');
        $form->dom->name->setValue($task->name);
        $form->dom->assignedTo->picker('user2');
        $form->dom->estimate->setValue($task->estimate);
        $form->dom->desc->setValue($task->desc);
        $form->dom->getElement('//*[@id="form-task-create"]/div[26]/button[1]')->click();
        $form->wait(1);

        return $this->success('成功创建设计任务');
    }

    /**
     * Create a name blank task.
     *
     * @param  string    $task
     * @access public
     * @return object
     *
     */
    public function createNameBlankTask($task)
    {
        $form = $this->initForm('task', 'create', array('executionID' => '2'), 'appIframe-execution');
        $form->dom->name->setValue($task->name);
        $form->dom->getElement('//*[@id="form-task-create"]/div[26]/button[1]')->click();
        $form->wait(1);

        return $this->failed('任务名称不能为空');
    }

    /**
     * Create a affair task.
     *
     * @param  string    $task
     * @access public
     * @return object
     *
     */
    public function createAffairTask($task)
    {
        $form = $this->initForm('task', 'create', array('executionID' => '2'), 'appIframe-execution');
        $form->dom->type->picker('事务');
        $form->dom->name->setValue($task->name);
        $form->dom->assignedTo->click();
        $form->dom->getElement('//*[@class="pick-container"]/div//footer//button[1]')->click();
        $form->dom->estimate->setValue($task->estimate);
