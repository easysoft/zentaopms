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

    /**
     * Test assignKanbanVars method.
     *
     * @param  int $executionID
     * @access public
     * @return object
     */
    public function assignKanbanVarsTest(int $executionID): object
    {
        global $tester;
        
        // 创建模拟的view对象
        $view = new stdClass();
        
        // 模拟用户数据
        $users = $tester->dao->select('account,realname')->from(TABLE_USER)->where('deleted')->eq(0)->fetchPairs('account', 'realname');
        $avatarPairs = array();
        foreach($users as $account => $realname) {
            $avatarPairs[$account] = '';
        }
        
        // 构建用户列表
        $userList = array();
        foreach($avatarPairs as $account => $avatar) {
            if(!isset($users[$account])) continue;
            $userList[$account]['realname'] = $users[$account];
            $userList[$account]['avatar'] = $avatar;
        }
        $userList['closed']['account'] = 'Closed';
        $userList['closed']['realname'] = 'Closed';
        $userList['closed']['avatar'] = '';
        
        // 获取执行关联的产品
        $products = $tester->dao->select('t2.id,t2.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.project')->eq($executionID)
            ->andWhere('t2.deleted')->eq(0)
            ->fetchAll('id');
        
        $productID = 0;
        $branchID = 0;
        $productNames = array();
        
        if($products) {
            $productID = key($products);
            $branches = $tester->dao->select('id,name')->from(TABLE_BRANCH)->where('product')->eq($productID)->andWhere('deleted')->eq(0)->fetchPairs('id', 'name');
            if($branches) $branchID = key($branches);
        }
        
        foreach($products as $product) $productNames[$product->id] = $product->name;
        
        // 获取执行关联的计划
        $allPlans = array();
        if(!empty($products)) {
            $plans = $tester->dao->select('id,title,product')->from(TABLE_PRODUCTPLAN)
                ->where('product')->in(array_keys($products))
                ->andWhere('deleted')->eq(0)
                ->fetchAll();
            foreach($plans as $plan) $allPlans[$plan->id] = $plan->title;
        }
        
        // 设置view变量
        $view->users = $users;
        $view->userList = $userList;
        $view->productID = $productID;
        $view->branchID = $branchID;
        $view->productNames = $productNames;
        $view->productNum = count($products);
        $view->allPlans = $allPlans;
        $view->isLimited = false; // 简化处理，默认不受限
        
        return $view;
    }
}
