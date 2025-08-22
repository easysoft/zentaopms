<?php
class taskZenTest
{
    public $taskZenTest;
    public $tester;
    function __construct()
    {
        global $tester;
        $this->tester      = $tester;
        $this->objectModel = $tester->loadModel('task');
        $tester->app->setModuleName('task');

        $this->taskZenTest = initReference('task');
    }

    /**
     * 检查传入的开始数据是否符合要求。
     * Check if the input post meets the requirements.
     *
     * @param  int        $taskID
     * @param  string     $consumed
     * @param  string     $left
     * @access public
     * @return array|bool
     */
    public function checkStartTest(int $taskID, string $consumed, string $left): array|bool
    {
        $oldTask = $this->objectModel->getByID($taskID);

        $task = clone $oldTask;
        $task->consumed = $consumed;
        $task->left     = $left;

        $method = $this->taskZenTest->getMethod('checkStart');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->taskZenTest->newInstance(), [$oldTask, $task]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * 构造激活的任务数据。
     * Build the task data to activate.
     *
     * @param  int         $taskID
     * @param  string      $left
     * @access public
     * @return object|array
     */
    public function buildTaskForActivateTest(int $taskID, string $left): object|array
    {
        $_POST['left'] = $left;

        $method = $this->taskZenTest->getMethod('buildTaskForActivate');
        $method->setAccessible(true);

        $method->invokeArgs($this->taskZenTest->newInstance(), [$taskID]);
        if(dao::isError()) return dao::getError();
        return $this->objectModel->fetchByID($taskID);
    }
}
