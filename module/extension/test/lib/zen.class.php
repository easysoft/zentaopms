<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class extensionZenTest extends baseTest
{
    protected $moduleName = 'extension';
    protected $className  = 'zen';

    /**
     * Test backupDB method.
     *
     * @param  string $extension 插件代号
     * @access public
     * @return string|false
     */
    public function backupDBTest(string $extension)
    {
        $result = $this->invokeArgs('backupDB', [$extension]);
        if(dao::isError()) return dao::getError();
        /* 如果返回了文件路径，只返回文件名部分便于测试 */
        if($result && is_string($result) && strpos($result, '/') !== false)
        {
            return basename($result);
        }
        return $result;
    }

    /**
     * Test checkCompatible method.
     *
     * @param  string $extension
     * @param  object $condition
     * @param  string $ignoreCompatible
     * @param  string $ignoreLink
     * @param  string $installType
     * @access public
     * @return bool
     */
    public function checkCompatibleTest(string $extension, object $condition, string $ignoreCompatible, string $ignoreLink, string $installType)
    {
        $result = $this->invokeArgs('checkCompatible', [$extension, $condition, $ignoreCompatible, $ignoreLink, $installType]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkConflicts method.
     *
     * @param  object $condition
     * @param  array  $installedExts
     * @access public
     * @return bool
     */
    public function checkConflictsTest(object $condition, array $installedExts)
    {
        $result = $this->invokeArgs('checkConflicts', [$condition, $installedExts]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkDepends method.
     *
     * @param  object $condition
     * @param  array  $installedExts
     * @access public
     * @return bool
     */
    public function checkDependsTest(object $condition, array $installedExts)
    {
        $result = $this->invokeArgs('checkDepends', [$condition, $installedExts]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
