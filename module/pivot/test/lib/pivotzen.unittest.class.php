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
}