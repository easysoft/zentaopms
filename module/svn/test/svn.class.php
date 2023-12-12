<?php
class svnTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('svn');
    }

    /**
     * Test run method.
     *
     * @access public
     * @return object|bool
     */
    public function runTest(): object|bool
    {
        ob_start();
        $this->objectModel->run();
        ob_get_clean();
        if(dao::isError()) return dao::getError();

        return $this->objectModel->dao->select('*')->from(TABLE_REPOHISTORY)->where('id')->eq(2)->fetch();
    }

    public function getRepoLogsTest(int $version): object|bool
    {
        $this->objectModel->setRepos();
        ob_start();
        $repo = $this->objectModel->repos[1];
        $logs = $this->objectModel->getRepoLogs($repo, $version);
        $error = ob_get_clean();

        if($error) return $error;
        return $logs[count($logs) - 1];

    }
}
