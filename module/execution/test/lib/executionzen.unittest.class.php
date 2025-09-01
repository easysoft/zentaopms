<?php
class executionZenTest
{
    public $executionZenTest;
    public $tester;
    function __construct()
    {
        global $tester;
        $this->tester      = $tester;
        $this->objectModel = $tester->loadModel('execution');
        $tester->app->setModuleName('execution');

        $this->executionZenTest = initReference('execution');
    }

    /**
     * 将导入的Bug转为任务。
     *
     * @param  string $mode normal|emptyData|errorEstimate|errorDeadline
     * @access public
     * @return array
     */
    public function buildTasksForImportBugTest(string $mode = 'normal'): array
    {
        $method = $this->executionZenTest->getMethod('buildTasksForImportBug');
        $method->setAccessible(true);

        $postData  = array();
        $execution = $this->objectModel->fetchByID(3);
        if($mode != 'emptyData')
        {
            $tasks = $this->objectModel->dao->select('*')->from(TABLE_TASK)->fetchAll('id');
            foreach($tasks as $taskID => $task)
            {
                if($mode == 'errorEstimate') $task->estimate = -1;
                if($mode == 'errorDeadline')
                {
                    $task->deadline   = '2025-08-25';
                    $task->estStarted = '2025-08-26';
                }
                $postData[$taskID] = $task;
            }
        }

        $result = $method->invokeArgs($this->executionZenTest->newInstance(), [$execution, $postData]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
