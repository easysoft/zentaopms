<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class hostZenTest extends baseTest
{
    protected $moduleName = 'host';
    protected $className  = 'zen';
    protected $objectModel;

    public function __construct($moduleName = '', $className = '')
    {
        parent::__construct($moduleName, $className);
        $this->objectModel = $this->instance;
    }

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
        $result = $this->objectModel->getPairs($moduleIdList, $status);
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
        $result = $this->invokeArgs('processTreemap', [$datas]);
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
        $result = $this->invokeArgs('getTreeModules', [$rootID, $hosts]);
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
        dao::$errors = array();
        $result = $this->invokeArgs('checkFormData', [$formData]);

        if(dao::isError())
        {
            return dao::getError();
        }

        return $result;
    }
}
