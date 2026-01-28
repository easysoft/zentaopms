<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class hostModelTest extends baseTest
{
    protected $moduleName = 'host';
    protected $className  = 'model';

    /**
     * Test getPairs method.
     *
     * @param  string $moduleIdList
     * @param  string $status
     * @access public
     * @return array
     */
    public function getPairsTest($moduleIdList = '', $status = '')
    {
        $result = $this->instance->getPairs($moduleIdList, $status);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test processTreemap method.
     *
     * @param  array $datas
     * @access public
     * @return array
     */
    public function processTreemapTest($datas = array())
    {
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('processTreemap');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance, $datas);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getTreeModules method.
     *
     * @param  int   $rootID
     * @param  array $hosts
     * @access public
     * @return array
     */
    public function getTreeModulesTest($rootID = 0, $hosts = array())
    {
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('getTreeModules');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance, $rootID, $hosts);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test checkFormData method.
     *
     * @param  object $formData
     * @access public
     * @return mixed
     */
    public function checkFormDataTest($formData)
    {
        $method = $this->hostZenTest->getMethod('checkFormData');
        $method->setAccessible(true);

        dao::$errors = array();
        $result = $method->invokeArgs($this->hostZenTest->newInstance(), array($formData));

        if(dao::isError())
        {
            return dao::getError();
        }

        return $result;
    }
}