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
     * 获取待处理各项数据。
     * Get work count.
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

    /**
     * 获取收费版待处理各项数据。
     * Get work count not in open edition.
     *
     * @param  array  $count
     * @param  int    $recTotal
     * @param  string $recPerPage
     * @param  string $pageID
     * @access public
     * @return array
     */
    public function showWorkCountNotInOpenTest(array $count = [], int $recTotal = 0, int $recPerPage = 20, int $pageID = 1): array
    {
        global $tester;
        $tester->app->rawModule = 'my';
        $tester->app->rawMethod = 'work';

        $tester->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $method = $this->myZenTest->getMethod('showWorkCountNotInOpen');
        $method->setAccessible(true);

        $count = $method->invokeArgs($this->myZenTest->newInstance(), [$count, $pager]);

        if(dao::isError()) return dao::getError();
        return $count;
    }
}
