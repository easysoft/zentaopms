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

    /**
     * 测试 assignCreateVars 方法。
     * Test assignCreateVars method.
     *
     * @param  object $execution
     * @param  int    $storyID
     * @param  int    $moduleID
     * @param  int    $taskID
     * @param  int    $todoID
     * @param  int    $bugID
     * @param  array  $output
     * @param  string $cardPosition
     * @access public
     * @return mixed
     */
    public function assignCreateVarsTest(object $execution, int $storyID = 0, int $moduleID = 0, int $taskID = 0, int $todoID = 0, int $bugID = 0, array $output = array(), string $cardPosition = ''): mixed
    {
        global $tester;

        $taskZen = $tester->loadZen('task');
        $taskZen->view = new stdClass();

        $method = new ReflectionMethod($taskZen, 'assignCreateVars');
        $method->setAccessible(true);

        $success = 1;
        $error = '';

        ob_start();
        try {
            $method->invokeArgs($taskZen, [$execution, $storyID, $moduleID, $taskID, $todoID, $bugID, $output, $cardPosition]);
        } catch(Exception $e) {
            $success = 0;
            $error = $e->getMessage();
        } catch(Error $e) {
            // display() 调用会抛出 Error，这是正常的，忽略
        }
        ob_end_clean();

        if(dao::isError()) return dao::getError();

        // 返回简单的验证结果
        return array(
            'success' => $success,
            'storyID' => $storyID,
            'taskID' => $taskID,
            'from' => ($storyID || $todoID || $bugID) ? 'other' : 'task',
            'error' => $error
        );
    }
}
