<?php
declare(strict_types = 1);
class miscTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('misc');
    }

    /**
     * Test hello method.
     *
     * @access public
     * @return mixed
     */
    public function helloTest()
    {
        $result = $this->objectModel->hello();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test encodeStatistics method.
     *
     * @param  array $statistics
     * @access public
     * @return mixed
     */
    public function encodeStatisticsTest($statistics = array())
    {
        $result = $this->objectModel->encodeStatistics($statistics);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test checkOneClickPackage method.
     *
     * @access public
     * @return mixed
     */
    public function checkOneClickPackageTest()
    {
        $result = $this->objectModel->checkOneClickPackage();
        if(dao::isError()) return dao::getError();

        return $result;
    }
}