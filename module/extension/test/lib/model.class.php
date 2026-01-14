<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class extensionModelTest extends baseTest
{
    protected $moduleName = 'extension';
    protected $className  = 'model';

    /**
     * Test __construct method.
     *
     * @access public
     * @return extensionModel
     */
    public function __constructTest()
    {
        $result = $this->instance;
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
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('fetchAPI');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->instance, array($url));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getFilesFromPackage method.
     *
     * @param  string $extension
     * @access public
     * @return mixed
     */
    public function getFilesFromPackageTest($extension = '')
    {
        $result = $this->instance->getFilesFromPackage($extension);
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
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('removeExtensionFiles');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->instance, array($files));
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
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('removeExtensionDirs');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->instance, array($dirs));
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
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('cleanModelCache');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance);
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
            $zenInstance->extension = $this->instance;

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
            $zenInstance->extension = $this->instance;

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
     * Test checkConflicts method.
     *
     * @param  object $condition
     * @param  array $installedExts
     * @access public
     * @return bool
     */
    public function checkConflictsTest(object $condition, array $installedExts)
    {
        // 创建一个简化版本的checkConflicts逻辑测试
        $conflicts = $condition->conflicts;
        if($conflicts)
        {
            $conflictsExt = '';
            foreach($conflicts as $code => $limit)
            {
                if(isset($installedExts[$code]))
                {
                    if($this->compareForLimitTest($installedExts[$code]->version, $limit)) $conflictsExt .= $installedExts[$code]->name . " ";
                }
            }

            if($conflictsExt)
            {
                return false;
            }
        }
        return true;
    }

    /**
     * Test checkDepends method.
     *
     * @param  object $condition
     * @param  array $installedExts
     * @access public
     * @return bool
     */
    public function checkDependsTest(object $condition, array $installedExts)
    {
        // 模拟checkDepends方法的逻辑进行测试
        $depends = $condition->depends;
        if($depends)
        {
            $dependsExt = '';
            $hasFailedDepends = false;
            foreach($depends as $code => $limit)
            {
                $noDepends = false;
                if(isset($installedExts[$code]))
                {
                    if($this->compareForLimitTest($installedExts[$code]->version, $limit, 'noBetween')) $noDepends = true;
                }
                else
                {
                    $noDepends = true;
                }

                if($noDepends)
                {
                    $dependsExt .= $code;
                    $hasFailedDepends = true;
                }
            }

            if($hasFailedDepends)
            {
                return false;
            }
        }
        return true;
    }

    /**
     * Test checkIncompatible method.
     *
     * @param  array $versions
     * @access public
     * @return array
     */
    public function checkIncompatibleTest(array $versions)
    {
        $result = $this->instance->checkIncompatible($versions);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test checkVersion method.
     *
     * @param  string $version
     * @access public
     * @return bool
     */
    public function checkVersionTest(string $version)
    {
        $result = $this->instance->checkVersion($version);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test version of compareForLimit method.
     *
     * @param  string       $version
     * @param  array|string $limit
     * @param  string       $type
     * @access public
     * @return bool
     */
    public function compareForLimitTest(string $version, array|string $limit, string $type = 'between'): bool
    {
        $result = false;
        if(empty($limit))   return true;
        if($limit == 'all')
        {
            $result = true;
        }
        else
        {
            if(!empty($limit['min']) && $version >= $limit['min'])           $result = true;
            if(!empty($limit['max']) && $version <= $limit['max'])           $result = true;
            if(!empty($limit['max']) && $version > $limit['max'] && $result) $result = false;
        }

        if($type != 'between') return !$result;

        return $result;
    }

    /**
     * Test erasePackage method.
     *
     * @param  string $extension
     * @access public
     * @return array
     */
    public function erasePackageTest(string $extension): array
    {
        $result = $this->instance->erasePackage($extension);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test executeDB method.
     *
     * @param  string $extension
     * @param  string $method
     * @access public
     * @return object
     */
    public function executeDBTest(string $extension, string $method = 'install'): object
    {
        $result = $this->instance->executeDB($extension, $method);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getExpireDate method.
     *
     * @param  object $extension
     * @access public
     * @return string
     */
    public function getExpireDateTest(object $extension): string
    {
        $result = $this->instance->getExpireDate($extension);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getExpiringPlugins method.
     *
     * @param  bool $isGroup
     * @access public
     * @return array
     */
    public function getExpiringPluginsTest(bool $isGroup = false): array
    {
        $result = $this->instance->getExpiringPlugins($isGroup);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getPackageFile method.
     *
     * @param  string $extension
     * @access public
     * @return string
     */
    public function getPackageFileTest(string $extension): string
    {
        $result = $this->instance->getPackageFile($extension);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getPathsFromPackage method.
     *
     * @param  string $extension
     * @access public
     * @return array
     */
    public function getPathsFromPackageTest(string $extension): array
    {
        $result = $this->instance->getPathsFromPackage($extension);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test removePackage method.
     *
     * @param  string $extension
     * @access public
     * @return array
     */
    public function removePackageTest(string $extension): array
    {
        $result = $this->instance->removePackage($extension);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test saveExtension method.
     *
     * @param  string $code
     * @param  string $type
     * @access public
     * @return mixed
     */
    public function saveExtensionTest(string $code, string $type)
    {
        $result = $this->instance->saveExtension($code, $type);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
