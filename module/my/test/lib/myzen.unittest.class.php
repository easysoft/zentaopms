<?php
class myZenTest
{
    public $myZenTest;
    public $tester;
    function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $tester->app->setModuleName('my');
        $this->objectModel = $tester->loadModel('my');

        $this->myZenTest = initReference('my');
    }

    /**
     * 获取批量创建需求的表单字段。
     * Get form fields for batch create.
     *
     * @param  int    $recTotal
     * @param  string $recPerPage
     * @param  string $pageID
     * @access public
     * @return array
     */
    public function showWorkCountTest(int $recTotal = 0, int $recPerPage = 20, int $pageID = 1): array
    {
        global $tester;
        $tester->app->rawModule = 'my';
        $tester->app->rawMethod = 'work';

        $method = $this->myZenTest->getMethod('showWorkCount');
        $method->setAccessible(true);

        $count = $method->invokeArgs($this->myZenTest->newInstance(), [$recTotal, $recPerPage, $pageID]);

        if(dao::isError()) return dao::getError();
        return $count;
    }
}
