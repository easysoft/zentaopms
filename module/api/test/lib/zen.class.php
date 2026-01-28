<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class apiZenTest extends baseTest
{
    protected $moduleName = 'api';
    protected $className  = 'zen';

    /**
     * Test generateLibsDropMenu method.
     *
     * @param  object $lib
     * @param  int    $version
     * @access public
     * @return array|string
     */
    public function generateLibsDropMenuTest($lib, $version = 0)
    {
        $result = $this->invokeArgs('generateLibsDropMenu', [$lib, $version]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test parseDocSpaceParam method.
     *
     * @param  array     $libs
     * @param  int       $libID
     * @param  string    $type
     * @param  int       $objectID
     * @param  int       $moduleID
     * @param  string    $spaceType
     * @param  int       $release
     * @access public
     * @return array
     */
    public function parseDocSpaceParamTest(array $libs, int $libID, string $type, int $objectID, int $moduleID, string $spaceType, int $release)
    {
        $this->invokeArgs('parseDocSpaceParam', [$libs, $libID, $type, $objectID, $moduleID, $spaceType, $release]);
        if(dao::isError()) return dao::getError();
        return $this->getProperty('view');
    }

    /**
     * Test getMethod method.
     *
     * @param  string $filePath
     * @param  string $ext
     * @access public
     * @return object|array
     */
    public function getMethodTest(string $filePath, string $ext = '')
    {
        $result = $this->invokeArgs('getMethod', [$filePath, $ext]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test request method.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @param  string $action
     * @param  array  $postData
     * @access public
     * @return array
     */
    public function requestTest(string $moduleName, string $methodName, string $action)
    {
        $result = $this->invokeArgs('request', [$moduleName, $methodName, $action]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
