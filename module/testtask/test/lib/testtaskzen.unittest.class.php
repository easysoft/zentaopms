<?php
declare(strict_types = 1);
class testtaskZenTest
{
    public $testtaskZenTest;
    public $tester;

    public function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $tester->app->setModuleName('testtask');
        $tester->loadModel('testtask');

        $this->testtaskZenTest = initReference('testtask');
    }

    /**
     * Test setMenu method.
     *
     * @param  int $productID
     * @param  mixed $branch
     * @param  int $projectID
     * @param  int $executionID
     * @param  string $tab
     * @access public
     * @return mixed
     */
    public function setMenuTest($productID = 1, $branch = 0, $projectID = 0, $executionID = 0, $tab = 'qa')
    {
        global $app;
        $originalTab = $app->tab;
        $app->tab = $tab;

        try {
            $method = $this->testtaskZenTest->getMethod('setMenu');
            $method->setAccessible(true);

            $result = $method->invokeArgs($this->testtaskZenTest->newInstance(), array((int)$productID, $branch, (int)$projectID, (int)$executionID));
            if(dao::isError()) return dao::getError();

            return $result;
        } finally {
            $app->tab = $originalTab;
        }
    }

    /**
     * Test getBrowseBranch method.
     *
     * @param  string $branch
     * @param  string $productType
     * @access public
     * @return string
     */
    public function getBrowseBranchTest($branch = '', $productType = 'normal')
    {
        $method = $this->testtaskZenTest->getMethod('getBrowseBranch');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->testtaskZenTest->newInstance(), array($branch, $productType));
        if(dao::isError()) return dao::getError();

        return $result;
    }
}