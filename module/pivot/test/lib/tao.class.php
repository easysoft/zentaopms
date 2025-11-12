<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class pivotTaoTest extends baseTest
{
    protected $moduleName = 'pivot';
    protected $className  = 'tao';

    /**
     * Test fetchPivot method.
     *
     * @param  int         $id
     * @param  string|null $version
     * @access public
     * @return object|bool
     */
    public function fetchPivotTest(int $id, ?string $version = null): object|bool
    {
        $result = $this->invokeArgs('fetchPivot', [$id, $version]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test fetchPivotDrills method.
     *
     * @param  int          $pivotID
     * @param  string       $version
     * @param  string|array $fields
     * @access public
     * @return array
     */
    public function fetchPivotDrillsTest(int $pivotID, string $version, string|array $fields): array
    {
        $result = $this->invokeArgs('fetchPivotDrills', [$pivotID, $version, $fields]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getBugGroup method.
     *
     * @param  string $begin
     * @param  string $end
     * @param  int    $product
     * @param  int    $execution
     * @access public
     * @return array
     */
    public function getBugGroupTest(string $begin, string $end, int $product, int $execution): array
    {
        $result = $this->invokeArgs('getBugGroup', [$begin, $end, $product, $execution]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getExecutionList method.
     *
     * @param  string $begin
     * @param  string $end
     * @param  array  $executionIDList
     * @access public
     * @return array
     */
    public function getExecutionListTest(string $begin, string $end, array $executionIDList = array()): array
    {
        $result = $this->invokeArgs('getExecutionList', [$begin, $end, $executionIDList]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getPlanStatusStatistics method.
     *
     * @param  array $products
     * @param  array $plans
     * @param  array $plannedStories
     * @param  array $unplannedStories
     * @access public
     * @return array
     */
    public function getPlanStatusStatisticsTest(array $products, array $plans, array $plannedStories, array $unplannedStories): array
    {
        $this->invokeArgs('getPlanStatusStatistics', array(&$products, $plans, $plannedStories, $unplannedStories));
        if(dao::isError()) return dao::getError();
        return $products;
    }

    /**
     * Test getProductProjects method.
     *
     * @access public
     * @return array
     */
    public function getProductProjectsTest(): array
    {
        $result = $this->invokeArgs('getProductProjects', []);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test processProductPlan method.
     *
     * @param  array  $products
     * @param  string $conditions
     * @access public
     * @return array
     */
    public function processProductPlanTest(array $products, string $conditions): array
    {
        $result = $this->invokeArgs('processProductPlan', array(&$products, $conditions));
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
