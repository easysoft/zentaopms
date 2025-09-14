<?php
class metricZenTest
{
    public $metricZenTest;
    public $tester;
    
    function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $tester->app->setModuleName('metric');
        $tester->loadModel('metric');

        $this->metricZenTest = initReference('metric');
    }

    /**
     * Test buildMetricForCreate method.
     *
     * @access public
     * @return mixed
     */
    public function buildMetricForCreateZenTest()
    {
        $method = $this->metricZenTest->getMethod('buildMetricForCreate');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array());
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test buildMetricForEdit method.
     *
     * @access public
     * @return mixed
     */
    public function buildMetricForEditZenTest()
    {
        $method = $this->metricZenTest->getMethod('buildMetricForEdit');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array());
        if(dao::isError()) return dao::getError();
        return $result;
    }
}