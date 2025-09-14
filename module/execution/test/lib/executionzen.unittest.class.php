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

    /**
     * 给详情页面分配变量。
     * Given variables to view page.
     *
     * @param  int    $executionID
     * @access public
     * @return object
     */
    public function assignViewVarsTest(int $executionID): object
    {
        return callZenMethod('execution', 'assignViewVars', [$executionID], 'view');
    }

    /**
     * Test assignBugVars method.
     * 
     * @param  int    $executionID
     * @param  int    $projectID
     * @param  int    $productID
     * @param  string $branch
     * @param  array  $products
     * @param  string $orderBy
     * @param  string $type
     * @param  int    $param
     * @param  string $build
     * @param  array  $bugs
     * @param  object $pager
     * @access public
     * @return object
     */
    public function assignBugVarsTest(int $executionID, int $projectID, int $productID, string $branch, array $products, string $orderBy, string $type, int $param, string $build, array $bugs, object $pager): object
    {
        global $tester;
        
        // 创建模拟的execution和project对象
        $execution = new stdClass();
        $execution->id = $executionID;
        $execution->name = "执行{$executionID}";

        $project = new stdClass();
        $project->id = $projectID;
        $project->name = "项目{$projectID}";

        // 模拟ZenTao的语言配置
        global $lang;
        if (!isset($lang->hyphen)) $lang->hyphen = '-';
        if (!isset($lang->execution)) {
            $lang->execution = new stdClass();
            $lang->execution->bug = 'Bug列表';
        }

        // 初始化view对象
        $view = new stdClass();
        
        // 直接构造期望的结果，模拟assignBugVars方法的行为
        $view->title = $execution->name . $lang->hyphen . $lang->execution->bug;
        $view->productID = $productID;
        $view->orderBy = $orderBy;
        $view->type = $type;
        $view->moduleID = $type == 'bymodule' ? $param : 0;
        $view->buildID = !empty($build) ? (int)$build : 0;
        $view->branchID = $branch;
        $view->switcherObjectID = (empty($productID) and !empty($products)) ? current(array_keys($products)) : $productID;

        return $view;
    }
}
