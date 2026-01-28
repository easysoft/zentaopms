<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class miscModelTest extends baseTest
{
    protected $moduleName = 'misc';
    protected $className  = 'model';

    /**
     * Test hello method.
     *
     * @access public
     * @return mixed
     */
    public function helloTest()
    {
        $result = $this->instance->hello();
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
        $result = $this->instance->encodeStatistics($statistics);
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
        $result = $this->instance->checkOneClickPackage();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getRemind method.
     *
     * @access public
     * @return mixed
     */
    public function getRemindTest()
    {
        $result = $this->instance->getRemind();
        if(dao::isError()) return dao::getError();

        return $result;
    }
}