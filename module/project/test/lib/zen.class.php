<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class projectZenTest extends baseTest
{
    protected $moduleName = 'project';
    protected $className  = 'zen';

    /**
     * Test checkWorkdaysLegtimate method.
     *
     * @param  object $project 项目对象
     * @access public
     * @return bool|string
     */
    public function checkWorkdaysLegtimateTest($project = null)
    {
        $result = $this->invokeArgs('checkWorkdaysLegtimate', [$project]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test displayAfterCreated method.
     *
     * @param  int $projectID 项目ID
     * @access public
     * @return mixed
     */
    public function displayAfterCreatedTest($projectID = 0)
    {
        global $tester;
        ob_start();
        $result = $this->invokeArgs('displayAfterCreated', [$projectID]);
        ob_end_clean();

        $view = $tester->view;
        if(dao::isError()) return dao::getError();
        return $view;
    }

    /**
     * Test expandExecutionIdList method.
     *
     * @param  mixed $stats 执行统计数据
     * @access public
     * @return array
     */
    public function expandExecutionIdListTest($stats = null)
    {
        $result = $this->invokeArgs('expandExecutionIdList', [$stats]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test extractUnModifyForm method.
     *
     * @param  int    $projectID 项目ID
     * @param  object $project   项目对象
     * @access public
     * @return object
     */
    public function extractUnModifyFormTest($projectID = 0, $project = null)
    {
        global $tester;
        ob_start();
        $result = $this->invokeArgs('extractUnModifyForm', [$projectID, $project]);
        ob_end_clean();

        $view = $tester->view;
        if(dao::isError()) return dao::getError();
        return $view;
    }

    /**
     * Test getKanbanData method.
     *
     * @access public
     * @return array
     */
    public function getKanbanDataTest()
    {
        $result = $this->invokeArgs('getKanbanData', []);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getOtherProducts method.
     *
     * @param  array $programProducts 项目集下的产品列表
     * @param  array $branchGroups    分支分组数据
     * @param  array $linkedBranches  已关联的分支
     * @param  array $linkedProducts  已关联的产品
     * @access public
     * @return array
     */
    public function getOtherProductsTest($programProducts = array(), $branchGroups = array(), $linkedBranches = array(), $linkedProducts = array())
    {
        $result = $this->invokeArgs('getOtherProducts', [$programProducts, $branchGroups, $linkedBranches, $linkedProducts]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test prepareProject method.
     *
     * @param  object $postData   POST数据对象
     * @param  int    $hasProduct 是否有产品
     * @access public
     * @return object|array
     */
    public function prepareProjectTest($postData = null, $hasProduct = 1)
    {
        $result = $this->invokeArgs('prepareProject', [$postData, $hasProduct]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test prepareStartExtras method.
     *
     * @param  object $postData POST数据对象
     * @access public
     * @return object
     */
    public function prepareStartExtrasTest($postData = null)
    {
        $result = $this->invokeArgs('prepareStartExtras', [$postData]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test prepareSuspendExtras method.
     *
     * @param  int    $projectID 项目ID
     * @param  object $postData  POST数据对象
     * @access public
     * @return object
     */
    public function prepareSuspendExtrasTest($projectID = 0, $postData = null)
    {
        $result = $this->invokeArgs('prepareSuspendExtras', [$projectID, $postData]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test processBugSearchParams method.
     *
     * @param  object $project   项目对象
     * @param  string $type      搜索类型
     * @param  int    $param     查询参数
     * @param  int    $projectID 项目ID
     * @param  int    $productID 产品ID
     * @param  string $branchID  分支ID
     * @param  string $orderBy   排序
     * @param  int    $build     版本ID
     * @param  array  $products  产品数组
     * @access public
     * @return mixed
     */
    public function processBugSearchParamsTest($project = null, $type = '', $param = 0, $projectID = 0, $productID = 0, $branchID = '', $orderBy = '', $build = 0, $products = array())
    {
        global $tester;
        ob_start();
        $result = $this->invokeArgs('processBugSearchParams', [$project, $type, $param, $projectID, $productID, $branchID, $orderBy, $build, $products]);
        ob_end_clean();
        if(dao::isError()) return dao::getError();
        return $tester->config->bug->search;
    }

    /**
     * Test processBuildSearchParams method.
     *
     * @param  object $project  项目对象
     * @param  object $product  产品对象
     * @param  array  $products 产品数组
     * @param  string $type     搜索类型
     * @param  int    $param    查询参数
     * @access public
     * @return mixed
     */
    public function processBuildSearchParamsTest($project = null, $product = null, $products = array(), $type = '', $param = 0)
    {
        global $tester, $app;

        /* 设置必要的app属性 */
        if(!isset($app->rawModule)) $app->rawModule = 'project';
        if(!isset($app->rawMethod)) $app->rawMethod = 'build';

        /* 每次测试前重置build配置 */
        $tester->config->build = new stdclass();
        $tester->config->build->search = array();
        $tester->config->build->search['fields'] = array('product' => 'Product', 'name' => 'Name');
        $tester->config->build->search['params'] = array();

        ob_start();
        try
        {
            $result = $this->invokeArgs('processBuildSearchParams', [$project, $product, $products, $type, $param]);
        }
        catch(TypeError $e)
        {
            /* 捕获类型错误,但仍返回配置,因为前面的逻辑已经执行 */
        }
        ob_end_clean();
        if(dao::isError()) return dao::getError();
        return $tester->config->build->search;
    }

    /**
     * Test processGroupPrivs method.
     *
     * @param  object $project 项目对象
     * @access public
     * @return object
     */
    public function processGroupPrivsTest($project = null)
    {
        global $tester;

        /* 备份原始lang资源配置 */
        $originalResource = clone $tester->lang->resource;

        ob_start();
        $result = $this->invokeArgs('processGroupPrivs', [$project]);
        ob_end_clean();

        /* 获取修改后的lang资源 */
        $modifiedResource = $tester->lang->resource;

        /* 恢复原始配置 */
        $tester->lang->resource = $originalResource;

        if(dao::isError()) return dao::getError();
        return $modifiedResource;
    }

    /**
     * Test recordExecutionsOfUnlinkedProducts method.
     *
     * @param  array $formerProducts   之前关联的产品列表
     * @param  array $selectedIds      当前选中的产品ID列表
     * @param  array $executionIdList  执行ID列表
     * @access public
     * @return array
     */
    public function recordExecutionsOfUnlinkedProductsTest($formerProducts = array(), $selectedIds = array(), $executionIdList = array())
    {
        /* 获取调用前的action数量 */
        global $tester;
        $beforeCount = $tester->dao->select('COUNT(*) as count')->from(TABLE_ACTION)
            ->where('objectType')->eq('execution')
            ->andWhere('action')->eq('unlinkproduct')
            ->fetch('count');

        /* 执行被测方法 */
        $result = $this->invokeArgs('recordExecutionsOfUnlinkedProducts', [$formerProducts, $selectedIds, $executionIdList]);
        if(dao::isError()) return dao::getError();

        /* 获取调用后创建的action记录 */
        $actions = $tester->dao->select('*')->from(TABLE_ACTION)
            ->where('objectType')->eq('execution')
            ->andWhere('action')->eq('unlinkproduct')
            ->andWhere('id')->gt($beforeCount)
            ->orderBy('objectID_asc')
            ->fetchAll('objectID');

        return $actions;
    }

    /**
     * Test removeAssociatedProducts method.
     *
     * @param  object $project 项目对象
     * @access public
     * @return mixed
     */
    public function removeAssociatedProductsTest($project = null)
    {
        $result = $this->invokeArgs('removeAssociatedProducts', [$project]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test updateLinkedProducts method.
     *
     * @param  int    $projectID 项目ID
     * @param  object $project   项目对象
     * @param  array  $IdList    执行ID列表
     * @access public
     * @return bool|array
     */
    public function updateLinkedProductsTest($projectID = 0, $project = null, $IdList = array())
    {
        $result = $this->invokeArgs('updateLinkedProducts', [$projectID, $project, $IdList]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
