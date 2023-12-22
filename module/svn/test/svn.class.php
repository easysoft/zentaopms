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

    /**
     * Test getRepoLogs method.
     *
     *  @param  int    $version
     *  @access public
     *  @return object|bool|null
     */
    public function getRepoLogsTest(int $version): object|bool|null
    {
        $this->objectModel->setRepos();
        ob_start();
        $repo = $this->objectModel->repos[1];
        $logs = $this->objectModel->getRepoLogs($repo, $version);
        $error = ob_get_clean();

        if($error) return $error;
        return $logs[count($logs) - 1];

    }

    /**
     * Test updateCommit method.
     *
     *  @param  int    $repoID
     *  @access public
     *  @return object|null
     */
    public function updateCommitTest(int $repoID): object|null
    {
        $repo = $this->objectModel->loadModel('repo')->getByID($repoID);
        $res  = $this->objectModel->updateCommit($repo, array(), false);
        if(!$res) return null;

        return $this->objectModel->dao->select('*')->from(TABLE_REPOHISTORY)->orderBy('id_desc')->fetch();
    }
}
