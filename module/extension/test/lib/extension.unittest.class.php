<?php
declare(strict_types = 1);
class extensionTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('extension');
    }

    /**
     * Test __construct method.
     *
     * @access public
     * @return extensionModel
     */
    public function __constructTest()
    {
        $result = $this->objectModel;
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test fetchAPI method.
     *
     * @param  string $url
     * @access public
     * @return mixed
     */
    public function fetchAPITest($url = '')
    {
        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('fetchAPI');
        $method->setAccessible(true);
        
        $result = $method->invokeArgs($this->objectModel, array($url));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test removeExtensionFiles method.
     *
     * @param  string $files
     * @access public
     * @return array
     */
    public function removeExtensionFilesTest($files = '')
    {
        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('removeExtensionFiles');
        $method->setAccessible(true);
        
        $result = $method->invokeArgs($this->objectModel, array($files));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test removeExtensionDirs method.
     *
     * @param  string $dirs
     * @access public
     * @return array
     */
    public function removeExtensionDirsTest($dirs = '')
    {
        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('removeExtensionDirs');
        $method->setAccessible(true);
        
        $result = $method->invokeArgs($this->objectModel, array($dirs));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test cleanModelCache method.
     *
     * @access public
     * @return bool
     */
    public function cleanModelCacheTest()
    {
        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('cleanModelCache');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->objectModel);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getHookFile method.
     *
     * @param  string $extension
     * @param  string $hook
     * @access public
     * @return string|false
     */
    public function getHookFileTest(string $extension, string $hook)
    {
        $extensionZen = $this->objectModel->loadZen('extension');
        $reflection = new ReflectionClass($extensionZen);
        $method = $reflection->getMethod('getHookFile');
        $method->setAccessible(true);
        
        $result = $method->invokeArgs($extensionZen, array($extension, $hook));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getDependsByDB method.
     *
     * @param  string $extension
     * @access public
     * @return array
     */
    public function getDependsByDBTest(string $extension)
    {
        try
        {
            $zen = initReference('extension');
            $func = $zen->getMethod('getDependsByDB');
            $zenInstance = $zen->newInstance();
            $zenInstance->extension = $this->objectModel;
            
            $result = $func->invokeArgs($zenInstance, array($extension));
            if(dao::isError()) return dao::getError();

            return is_array($result) ? $result : array();
        }
        catch(Exception $e)
        {
            return array();
        }
    }
}