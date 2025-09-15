<?php
declare(strict_types = 1);
class miscTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('misc');
        $this->objectZen   = $tester->loadZen('misc');
    }

    /**
     * Test hello method.
     *
     * @access public
     * @return mixed
     */
    public function helloTest()
    {
        $result = $this->objectZen->hello();
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
        $result = $this->objectZen->encodeStatistics($statistics);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}