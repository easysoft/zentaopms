<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class pivotZenTest extends baseTest
{
    protected $moduleName = 'pivot';
    protected $className  = 'zen';

    /**
     * Test processProductsForProductSummary method.
     *
     * @param  array $products
     * @access public
     * @return mixed
     */
    public function processProductsForProductSummaryTest($products = null)
    {
        $result = $this->invokeArgs('processProductsForProductSummary', [$products]);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test bugAssign method.
     *
     * @access public
     * @return mixed
     */
    public function bugAssignTest()
    {
        $this->invokeArgs('bugAssign');
        if(dao::isError()) return dao::getError();

        return $this->instance->view->bugs ?? array();
    }

    /**
     * Test bugCreate method.
     *
     * @param  string $begin
     * @param  string $end
     * @param  int    $product
     * @param  int    $execution
     * @access public
     * @return mixed
     */
    public function bugCreateTest($begin = '', $end = '', $product = 0, $execution = 0)
    {
        $this->invokeArgs('bugCreate', [$begin, $end, $product, $execution]);
        if(dao::isError()) return dao::getError();

        return array(
            'bugs'       => $this->instance->view->bugs ?? array(),
            'begin'      => $this->instance->view->begin ?? '',
            'end'        => $this->instance->view->end ?? '',
            'product'    => $this->instance->view->product ?? 0,
            'execution'  => $this->instance->view->execution ?? 0,
        );
    }

    /**
     * Test getBuiltinMenus method.
     *
     * @param  int    $dimensionID
     * @param  object $currentGroup
     * @access public
     * @return mixed
     */
    public function getBuiltinMenusTest($dimensionID = 0, $currentGroup = null)
    {
        $result = $this->invokeArgs('getBuiltinMenus', [$dimensionID, $currentGroup]);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getDefaultMethodAndParams method.
     *
     * @param  int $dimensionID
     * @param  int $groupID
     * @access public
     * @return mixed
     */
    public function getDefaultMethodAndParamsTest(int $dimensionID = 0, int $groupID = 0)
    {
        $result = $this->invokeArgs('getDefaultMethodAndParams', [$dimensionID, $groupID]);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getDrill method.
     *
     * @param  int    $pivotID
     * @param  string $version
     * @param  string $colName
     * @param  string $status
     * @access public
     * @return mixed
     */
    public function getDrillTest(int $pivotID = 0, string $version = '1', string $colName = '', string $status = 'published')
    {
        $result = $this->invokeArgs('getDrill', [$pivotID, $version, $colName, $status]);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getFilterOptionUrl method.
     *
     * @param  array  $filter
     * @param  string $sql
     * @param  array  $fieldSettings
     * @access public
     * @return mixed
     */
    public function getFilterOptionUrlTest(array $filter = array(), string $sql = '', array $fieldSettings = array())
    {
        $result = $this->invokeArgs('getFilterOptionUrl', [$filter, $sql, $fieldSettings]);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getMenuItems method.
     *
     * @param  array $menus
     * @access public
     * @return mixed
     */
    public function getMenuItemsTest(array $menus = array())
    {
        $result = $this->invokeArgs('getMenuItems', [$menus]);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getSidebarMenus method.
     *
     * @param  int $dimensionID
     * @param  int $groupID
     * @access public
     * @return mixed
     */
    public function getSidebarMenusTest(int $dimensionID = 0, int $groupID = 0)
    {
        $result = $this->invokeArgs('getSidebarMenus', [$dimensionID, $groupID]);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test productSummary method.
     *
     * @param  string     $conditions
     * @param  int|string $productID
     * @param  string     $productStatus
     * @param  string     $productType
     * @access public
     * @return mixed
     */
    public function productSummaryTest(string $conditions = '', int|string $productID = 0, string $productStatus = 'normal', string $productType = 'normal')
    {
        $this->invokeArgs('productSummary', [$conditions, $productID, $productStatus, $productType]);
        if(dao::isError()) return dao::getError();

        return array(
            'products' => $this->instance->view->products ?? array(),
            'filters'  => $this->instance->view->filters ?? array(),
            'title'    => $this->instance->view->title ?? ''
        );
    }

    /**
     * Test projectDeviation method.
     *
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return mixed
     */
    public function projectDeviationTest(string $begin = '', string $end = '')
    {
        $this->invokeArgs('projectDeviation', [$begin, $end]);
        if(dao::isError()) return dao::getError();

        return array(
            'executions' => $this->instance->view->executions ?? array(),
            'begin'      => $this->instance->view->begin ?? '',
            'end'        => $this->instance->view->end ?? '',
            'title'      => $this->instance->view->title ?? ''
        );
    }

    /**
     * Test setNewMark method.
     *
     * @param  object $pivot
     * @param  object $firstAction
     * @param  array  $builtins
     * @access public
     * @return object
     */
    public function setNewMarkTest($pivot = null, $firstAction = null, $builtins = array())
    {
        $this->invokeArgs('setNewMark', [$pivot, $firstAction, $builtins]);
        if(dao::isError()) return dao::getError();

        return $pivot;
    }

    /**
     * Test workload method.
     *
     * @param  string $begin
     * @param  string $end
     * @param  int    $days
     * @param  float  $workhour
     * @param  int    $dept
     * @param  string $assign
     * @access public
     * @return mixed
     */
    public function workloadTest(string $begin = '', string $end = '', int $days = 0, float $workhour = 0, int $dept = 0, string $assign = 'assign')
    {
        $this->invokeArgs('workload', [$begin, $end, $days, $workhour, $dept, $assign]);
        if(dao::isError()) return dao::getError();

        return array(
            'workload'   => $this->instance->view->workload ?? array(),
            'begin'      => $this->instance->view->begin ?? '',
            'end'        => $this->instance->view->end ?? '',
            'days'       => $this->instance->view->days ?? 0,
            'workhour'   => $this->instance->view->workhour ?? 0,
            'dept'       => $this->instance->view->dept ?? 0,
            'assign'     => $this->instance->view->assign ?? '',
            'title'      => $this->instance->view->title ?? ''
        );
    }
}
