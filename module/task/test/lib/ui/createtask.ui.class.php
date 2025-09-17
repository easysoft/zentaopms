<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
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
        $form->dom->savebtn->click();
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
        $form->dom->savebtn->click();
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
        $form->dom->savebtn->click();
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
        $form->dom->desc->setValue($task->desc);
        $form->dom->savebtn->click();
        $form->wait(1);

        return $this->success('成功创建事务任务');
    }


    /**
     * Create a test task.
     *
     * @param  string    $task
     * @access public
     * @return object
     *
     */
    public function createTestTask($task)
    {
        $form = $this->initForm('task', 'create', array('executionID' => '2'), 'appIframe-execution');
        $form->dom->type->picker('测试');
        $form->dom->selectTestStory->click();
        $form->wait(1);
        $form->dom->name->setValue($task->name);
        $form->dom->assignedTo->picker('user1');
        $form->dom->savebtn->click();
        $form->wait(1);

        return $this->success('成功创建测试任务');
    }

    /**
     * Create a linear task.
     *
     * @param  string    $task
     * @access public
     * @return object
     *
     */
    public function createLinearTask($task)
    {
        $form = $this->initForm('task', 'create', array('executionID' => '2'), 'appIframe-execution');
        $form->dom->type->picker('开发');
        $form->dom->multiple->click();
        $form->dom->getElement('//*[@id="form-task-create"]/div[6]/div/a/span')->click();
        $form->wait(1);

        $team = 'team[1]';
        $form->dom->{$team}->picker('admin');
        $form->dom->{'teamEstimate[1]'}->setValue($task->teamEstimate1);
        $team = 'team[2]';
        $form->dom->{$team}->picker('user2');
        $form->dom->{'teamEstimate[2]'}->setValue($task->teamEstimate2);
        $team = 'team[3]';
        $form->dom->{$team}->picker('user1');
        $form->dom->{'teamEstimate[3]'}->setValue($task->teamEstimate3);
        $form->dom->getElement('//*[@id="teamTable"]/div[2]/button')->click();
        $form->wait(1);

        $form->dom->name->setValue($task->name);
        $form->dom->desc->setValue($task->desc);
        $form->dom->savebtn->click();
        $form->wait(1);

        return $this->success('成功创建多人串行任务');
    }

    /**
     * Create a multi task.
     *
     * @param  string    $task
     * @access public
     * @return object
     *
     */
    public function createMultiTask($task)
    {
        $form = $this->initForm('task', 'create', array('executionID' => '2'), 'appIframe-execution');
        $form->dom->type->picker('开发');
        $form->dom->multiple->click();
        $form->dom->getElement('//*[@id="form-task-create"]/div[6]/div/a/span')->click();
        $form->wait(1);

        $form->dom->mode->picker('多人并行');

        $team = 'team[1]';
        $form->dom->{$team}->picker('admin');
        $form->dom->{'teamEstimate[1]'}->setValue($task->teamEstimate1);
        $team = 'team[2]';
        $form->dom->{$team}->picker('user1');
        $form->dom->{'teamEstimate[2]'}->setValue($task->teamEstimate2);
        $team = 'team[3]';
        $form->dom->{$team}->picker('user2');
        $form->dom->{'teamEstimate[3]'}->setValue($task->teamEstimate3);
        $form->dom->getElement('//*[@id="teamTable"]/div[2]/button')->click();
        $form->wait(1);

        $form->dom->name->setValue($task->name);
        $form->dom->desc->setValue($task->desc);
        $form->dom->savebtn->click();
        $form->wait(1);

        return $this->success('成功创建多人并行任务');
    }
}
