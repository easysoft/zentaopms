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

    /**
     * Test responseAfterCreate method.
     *
     * @param  int    $metricID
     * @param  string $afterCreate
     * @param  string $from
     * @param  string $location
     * @access public
     * @return mixed
     */
    public function responseAfterCreateZenTest($metricID, $afterCreate, $from, $location = '')
    {
        $method = $this->metricZenTest->getMethod('responseAfterCreate');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array($metricID, $afterCreate, $from, $location));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test responseAfterEdit method.
     *
     * @param  int    $metricID
     * @param  string $afterEdit
     * @param  string $location
     * @access public
     * @return mixed
     */
    public function responseAfterEditZenTest($metricID, $afterEdit, $location = '')
    {
        $method = $this->metricZenTest->getMethod('responseAfterEdit');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array($metricID, $afterEdit, $location));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test prepareDataset method.
     *
     * @param  object $calcGroup
     * @access public
     * @return mixed
     */
    public function prepareDatasetZenTest($calcGroup)
    {
        $method = $this->metricZenTest->getMethod('prepareDataset');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array($calcGroup));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test prepareTree method.
     *
     * @param  string $scope
     * @param  string $stage
     * @param  array  $modules
     * @access public
     * @return mixed
     */
    public function prepareTreeZenTest($scope, $stage, $modules)
    {
        $method = $this->metricZenTest->getMethod('prepareTree');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->metricZenTest->newInstance(), array($scope, $stage, $modules));
        if(dao::isError()) return dao::getError();
        return $result;
    }
}