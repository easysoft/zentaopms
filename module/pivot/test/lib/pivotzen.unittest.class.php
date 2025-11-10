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
}