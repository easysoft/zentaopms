<?php
class pivotZenTest
{
    public $pivotZenTest;
    public $tester;

    function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $tester->app->setModuleName('pivot');
        $tester->loadModel('pivot');

        $this->pivotZenTest = initReference('pivot');
    }

    /**
     * Test processProductsForProductSummary method.
     *
     * @param  array $products
     * @access public
     * @return array
     */
    public function processProductsForProductSummaryTest(array $products): array
    {
        $method = $this->pivotZenTest->getMethod('processProductsForProductSummary');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->pivotZenTest->newInstance(), [$products]);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}