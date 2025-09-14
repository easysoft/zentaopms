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

    /**
     * Test checkExtension method.
     *
     * @param  string $extension
     * @param  string $ignoreCompatible
     * @param  string $ignoreLink
     * @param  string $overrideFile
     * @param  string $overrideLink
     * @param  string $installType
     * @access public
     * @return bool
     */
    public function checkExtensionTest(string $extension, string $ignoreCompatible, string $ignoreLink, string $overrideFile, string $overrideLink, string $installType)
    {
        try
        {
            $extensionZen = $this->objectModel->loadZen('extension');
            $reflection = new ReflectionClass($extensionZen);
            $method = $reflection->getMethod('checkExtension');
            $method->setAccessible(true);
            
            $result = $method->invokeArgs($extensionZen, array($extension, $ignoreCompatible, $ignoreLink, $overrideFile, $overrideLink, $installType));
            if(dao::isError()) return dao::getError();

            return $result;
        }
        catch(Exception $e)
        {
            return false;
        }
    }

    /**
     * Test checkFileConflict method.
     *
     * @param  string $extension
     * @access public
     * @return object
     */
    public function checkFileConflictTest(string $extension)
    {
        try
        {
            $extensionZen = $this->objectModel->loadZen('extension');
            $reflection = new ReflectionClass($extensionZen);
            $method = $reflection->getMethod('checkFileConflict');
            $method->setAccessible(true);
            
            $result = $method->invokeArgs($extensionZen, array($extension));
            if(dao::isError()) return dao::getError();

            return $result;
        }
        catch(Exception $e)
        {
            $errorResult = new stdClass();
            $errorResult->result = 'fail';
            $errorResult->error = 'Exception: ' . $e->getMessage();
            return $errorResult;
        }
    }

    /**
     * Test copyHookFiles method.
     *
     * @param  string $extension
     * @access public
     * @return mixed
     */
    public function copyHookFilesTest(string $extension)
    {
        try
        {
            $zen = initReference('extension');
            $func = $zen->getMethod('copyHookFiles');
            $zenInstance = $zen->newInstance();
            $zenInstance->extension = $this->objectModel;
            
            $result = $func->invokeArgs($zenInstance, array($extension));
            if(dao::isError()) return dao::getError();

            return $result === null ? '~~' : $result;
        }
        catch(Exception $e)
        {
            return 'Exception: ' . $e->getMessage();
        }
    }

    /**
     * Test installExtension method.
     *
     * @param  string $extension
     * @param  string $type
     * @param  string $upgrade
     * @access public
     * @return bool
     */
    public function installExtensionTest(string $extension, string $type, string $upgrade)
    {
        try
        {
            $extensionZen = $this->objectModel->loadZen('extension');
            $reflection = new ReflectionClass($extensionZen);
            $method = $reflection->getMethod('installExtension');
            $method->setAccessible(true);
            
            $result = $method->invokeArgs($extensionZen, array($extension, $type, $upgrade));
            if(dao::isError()) return dao::getError();

            return $result;
        }
        catch(Exception $e)
        {
            return false;
        }
    }

    /**
     * Test extractPackage method.
     *
     * @param  string $extension
     * @access public
     * @return object
     */
    public function extractPackageTest(string $extension)
    {
        try
        {
            $extensionZen = $this->objectModel->loadZen('extension');
            $reflection = new ReflectionClass($extensionZen);
            $method = $reflection->getMethod('extractPackage');
            $method->setAccessible(true);
            
            $result = $method->invokeArgs($extensionZen, array($extension));
            if(dao::isError()) return dao::getError();

            return $result;
        }
        catch(Exception $e)
        {
            $errorResult = new stdClass();
            $errorResult->result = 'fail';
            $errorResult->error = 'Exception: ' . $e->getMessage();
            return $errorResult;
        }
    }

    /**
     * Test copyPackageFiles method.
     *
     * @param  string $extension
     * @access public
     * @return array
     */
    public function copyPackageFilesTest(string $extension)
    {
        try
        {
            $extensionZen = $this->objectModel->loadZen('extension');
            $reflection = new ReflectionClass($extensionZen);
            $method = $reflection->getMethod('copyPackageFiles');
            $method->setAccessible(true);
            
            $result = $method->invokeArgs($extensionZen, array($extension));
            if(dao::isError()) return dao::getError();

            return is_array($result) ? $result : array();
        }
        catch(Exception $e)
        {
            return array();
        }
    }

    /**
     * Test backupDB method.
     *
     * @param  string $extension
     * @access public
     * @return string|false
     */
    public function backupDBTest(string $extension)
    {
        try
        {
            $extensionZen = $this->objectModel->loadZen('extension');
            $reflection = new ReflectionClass($extensionZen);
            $method = $reflection->getMethod('backupDB');
            $method->setAccessible(true);
            
            $result = $method->invokeArgs($extensionZen, array($extension));
            if(dao::isError()) return dao::getError();

            return $result;
        }
        catch(Exception $e)
        {
            return false;
        }
    }
}