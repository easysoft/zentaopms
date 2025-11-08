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
}
