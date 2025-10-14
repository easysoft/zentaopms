<?php
declare(strict_types = 1);
class pivotZenTest
{
    public function __construct()
    {
        $this->objectZen = initReference('pivot');
    }

    /**
     * Test processProductsForProductSummary method.
     *
     * @param  array $products
     * @access public
     * @return mixed
     */
    public function processProductsForProductSummaryTest($products = null)
    {
        $method = $this->objectZen->getMethod('processProductsForProductSummary');
        $method->setAccessible(true);
        $instance = $this->objectZen->newInstance();
        $result = $method->invoke($instance, $products);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}