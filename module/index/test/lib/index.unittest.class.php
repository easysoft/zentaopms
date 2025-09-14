<?php
declare(strict_types = 1);
class indexZenTest
{
    public $indexZenTest;
    public $tester;

    public function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $tester->app->setModuleName('index');
        $tester->loadModel('index');

        $this->indexZenTest = initReference('index');
    }

    /**
     * Test checkShowFeatures method.
     *
     * @access public
     * @return mixed
     */
    public function checkShowFeaturesTest()
    {
        $method = $this->indexZenTest->getMethod('checkShowFeatures');
        $method->setAccessible(true);
        $indexInstance = $this->indexZenTest->newInstance();
        
        $result = $method->invoke($indexInstance);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getViewMethodForAssetLib method.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @access public
     * @return mixed
     */
    public function getViewMethodForAssetLibTest(int $objectID, string $objectType)
    {
        $reflection = new ReflectionClass('indexZen');
        $method = $reflection->getMethod('getViewMethodForAssetLib');
        $method->setAccessible(true);
        
        $indexZen = new indexZen();
        $result = $method->invoke($indexZen, $objectID, $objectType);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}