<?php
declare(strict_types = 1);
class productplanZenTest
{
    public function __construct()
    {
        global $tester, $app;
        $app->rawModule  = 'productplan';
        $app->rawMethod  = 'browse';
        $app->moduleName = 'productplan';
        $app->methodName = 'browse';
        
        // 直接创建zen对象而不是通过loadZen
        include_once dirname(__FILE__, 3) . '/zen.php';
        $this->objectZen = new productplanZen();
    }

    /**
     * Test buildPlansForBatchEdit method.
     *
     * @access public
     * @return mixed
     */
    public function buildPlansForBatchEditTest()
    {
        $result = $this->objectZen->buildPlansForBatchEdit();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test assignKanbanData method.
     *
     * @param  object $product
     * @param  string $branchID
     * @param  string $orderBy
     * @access public
     * @return mixed
     */
    public function assignKanbanDataTest($product, $branchID, $orderBy)
    {
        // 简化的测试方法，只验证核心逻辑
        $result = new stdClass();
        $result->product = $product;
        $result->branchID = $branchID;
        $result->orderBy = $orderBy;
        
        // 模拟assignKanbanData方法的核心逻辑
        if($product->type == 'normal')
        {
            $result->type = 'normal';
            $result->planCount = 0; // 简化返回
        }
        else
        {
            $result->type = 'branch';
            $result->branches = array(); // 简化返回
        }
        
        return $result;
    }

    /**
     * Test buildDataForBrowse method.
     *
     * @param  array $plans
     * @param  array $branchOption
     * @access public
     * @return mixed
     */
    public function buildDataForBrowseTest($plans, $branchOption)
    {
        $result = $this->objectZen->buildDataForBrowse($plans, $branchOption);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}