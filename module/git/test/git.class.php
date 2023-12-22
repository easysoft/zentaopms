<?php
class gitTest
{
    public $gitModel;

    public function __construct()
    {
        global $tester;
        $this->gitModel = $tester->loadModel('git');
    }

    /**
     * Test updateCommit method.
     *
     *  @param  int    $repoID
     *  @access public
     *  @return object|false
     */
    public function updateCommitTest(int $repoID): object|false
    {
        $repo = $this->gitModel->loadModel('repo')->getByID($repoID);
        $this->gitModel->updateCommit($repo, array(), false);

        return $this->gitModel->dao->select('*')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->orderBy('id_desc')->fetch();
    }

    /**
     * Get repo logs.
     *
     * @param  int    $repoID
     * @param  string $branch
     * @access public
     * @return array
     */
    public function getRepoLogs(int $repoID, string $branch): array
    {
        $repo = $this->gitModel->loadModel('repo')->getByID($repoID);
        return $this->gitModel->getRepoLogs($repo, $branch);
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
        $this->gitModel->run();
        ob_get_clean();
        if(dao::isError()) return dao::getError();

        return $this->gitModel->dao->select('*')->from(TABLE_REPOHISTORY)->where('id')->eq(2)->fetch();
    }
}
