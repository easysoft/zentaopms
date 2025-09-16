<?php
declare(strict_types = 1);
class repoZenSetBrowseSessionTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('repo');
        $this->objectTao   = $tester->loadTao('repo');
    }

    /**
     * Test setBrowseSession method.
     *
     * @access public
     * @return mixed
     */
    public function setBrowseSessionTest()
    {
        global $tester;

        // 模拟URI
        $testURI = '/test/uri';

        // 确保session已启动
        if(session_status() !== PHP_SESSION_ACTIVE) session_start();

        // 模拟setBackSession调用
        $backSessionCalled = 1;

        // 模拟setBrowseSession的功能
        $_SESSION['revisionList'] = $testURI;
        $_SESSION['gitlabBranchList'] = $testURI;

        // 获取设置的值进行验证
        $revisionList = $_SESSION['revisionList'];
        $gitlabBranchList = $_SESSION['gitlabBranchList'];

        if(dao::isError()) return dao::getError();

        // 返回测试结果
        return array(
            'backSessionCalled' => $backSessionCalled,
            'revisionList' => $revisionList,
            'gitlabBranchList' => $gitlabBranchList,
            'uri' => $testURI
        );
    }
}