<?php
class fileZenTest
{
    public $fileZenTest;
    public $tester;
    function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $tester->app->setModuleName('file');
        $tester->loadModel('file');

        $this->fileZenTest = initReference('file');
    }

    /**
     * Test getDownloadMode method.
     *
     * @param  object $file  文件对象
     * @param  string $mouse 鼠标操作类型
     * @access public
     * @return string
     */
    public function getDownloadModeZenTest($file = null, string $mouse = ''): string
    {
        $method = $this->fileZenTest->getMethod('getDownloadMode');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->fileZenTest->newInstance(), array($file, $mouse));
        if(dao::isError()) return dao::getError();
        return $result;
    }
}