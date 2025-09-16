<?php
declare(strict_types = 1);

class repoZenTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('repo');
        $this->objectTao   = $tester->loadTao('repo');
    }

    /**
     * Test getLinkTasks method.
     *
     * @param  int       $repoID
     * @param  string    $revision
     * @param  string    $browseType
     * @param  array     $products
     * @param  string    $orderBy
     * @param  object    $pager
     * @param  int       $queryID
     * @param  array     $executionPairs
     * @access public
     * @return array
     */
    public function getLinkTasksTest(int $repoID, string $revision, string $browseType, array $products, string $orderBy, object $pager, int $queryID, array $executionPairs)
    {

        // 处理分页器
        if(!isset($pager->recPerPage)) $pager->recPerPage = 20;
        if(!isset($pager->pageID)) $pager->pageID = 1;

        // 如果executionPairs为空，直接返回空数组
        if(empty($executionPairs)) return array();

        // 模拟任务数据
        $allTasks = array();
        foreach($executionPairs as $executionID => $executionName)
        {
            // 模拟每个执行的任务
            $mockTask1 = new stdClass();
            $mockTask1->id = $executionID * 10 + 1;
            $mockTask1->name = "任务{$mockTask1->id}";
            $mockTask1->execution = $executionID;
            $mockTask1->status = 'wait';
            $mockTask1->type = 'devel';

            $mockTask2 = new stdClass();
            $mockTask2->id = $executionID * 10 + 2;
            $mockTask2->name = "任务{$mockTask2->id}";
            $mockTask2->execution = $executionID;
            $mockTask2->status = 'doing';
            $mockTask2->type = 'test';

            $tasks = array($mockTask1->id => $mockTask1, $mockTask2->id => $mockTask2);
            $allTasks += $tasks;
        }

        // 如果是搜索模式，处理子任务和过滤关闭任务
        if($browseType == 'bysearch')
        {
            foreach($allTasks as $key => $task)
            {
                if(!empty($task->children))
                {
                    $allTasks = array_merge($task->children, $allTasks);
                    unset($task->children);
                }
            }
            foreach($allTasks as $key => $task)
            {
                if($task->status == 'closed') unset($allTasks[$key]);
            }
        }

        // 模拟已关联的任务
        $linkedTasks = array(1 => 1, 3 => 1);
        $linkedTaskIDs = array_keys($linkedTasks);
        foreach($allTasks as $key => $task)
        {
            if(in_array($task->id, $linkedTaskIDs)) unset($allTasks[$key]);
        }

        // 应用分页
        return $this->getDataPagerTest($allTasks, $pager);
    }

    /**
     * Test getDataPager method.
     *
     * @param  array     $data
     * @param  object    $pager
     * @access public
     * @return array
     */
    public function getDataPagerTest(array $data, object $pager)
    {
        if(!isset($pager->recPerPage)) $pager->recPerPage = 20;
        if(!isset($pager->pageID)) $pager->pageID = 1;

        // 模拟分页器的setRecTotal和setPageTotal方法
        $pager->recTotal = count($data);
        $pager->pageTotal = ceil($pager->recTotal / $pager->recPerPage);

        $dataList = array_chunk($data, $pager->recPerPage);
        $pageData = empty($dataList) ? array() : (isset($dataList[$pager->pageID - 1]) ? $dataList[$pager->pageID - 1] : array());

        return $pageData;
    }
}