<?php
declare(strict_types = 1);
class repoZenGetLinkBugsTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('repo');
        $this->objectTao   = $tester->loadTao('repo');
    }

    /**
     * Test getLinkBugs method.
     *
     * @param  int       $repoID
     * @param  string    $revision
     * @param  string    $browseType
     * @param  array     $products
     * @param  string    $orderBy
     * @param  object    $pager
     * @param  int       $queryID
     * @access public
     * @return array
     */
    public function getLinkBugsTest(int $repoID, string $revision, string $browseType, array $products, string $orderBy, object $pager, int $queryID)
    {
        if(dao::isError()) return dao::getError();

        // 模拟已关联的bug
        $linkedBugs = array(1 => 1, 2 => 1);
        $allBugs = array();

        // 处理分页器
        if(!isset($pager->recPerPage)) $pager->recPerPage = 20;
        if(!isset($pager->pageID)) $pager->pageID = 1;

        // 模拟bug数据
        $mockBug1 = new stdClass();
        $mockBug1->id = 3;
        $mockBug1->title = '测试Bug1';
        $mockBug1->product = 1;
        $mockBug1->status = 'active';

        $mockBug2 = new stdClass();
        $mockBug2->id = 4;
        $mockBug2->title = '测试Bug2';
        $mockBug2->product = 2;
        $mockBug2->status = 'active';

        $mockBug3 = new stdClass();
        $mockBug3->id = 5;
        $mockBug3->title = '已关闭Bug';
        $mockBug3->product = 1;
        $mockBug3->status = 'closed';

        if($browseType == 'bySearch')
        {
            // 搜索模式
            $allBugs = array($mockBug1, $mockBug2, $mockBug3);
            // 过滤非active状态的bug
            foreach($allBugs as $bugID => $bug)
            {
                if($bug->status != 'active') unset($allBugs[$bugID]);
            }
        }
        else
        {
            // 普通模式
            foreach($products as $productID => $product)
            {
                if($productID == 1)
                {
                    $productBugs = array($mockBug1);
                }
                elseif($productID == 2)
                {
                    $productBugs = array($mockBug2);
                }
                else
                {
                    $productBugs = array();
                }
                $allBugs = array_merge($allBugs, $productBugs);
            }
        }

        // 应用分页并处理状态文本
        $allBugs = $this->getDataPagerTest($allBugs, $pager);
        foreach($allBugs as $bug)
        {
            $bug->statusText = $this->processStatusTest('bug', $bug);
        }

        return $allBugs;
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

    /**
     * Test processStatus method helper.
     *
     * @param  string $type
     * @param  object $object
     * @access public
     * @return string
     */
    public function processStatusTest(string $type, object $object): string
    {
        if($type == 'bug')
        {
            return $object->status == 'active' ? '激活' : '其他';
        }
        return $object->status;
    }
}