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
     *  @return object|false
     */
    public function updateCommitTest(int $repoID): object|false
    {
        $repo = $this->objectModel->loadModel('repo')->getByID($repoID);
        $this->objectModel->updateCommit($repo, array(), false);

        return $this->objectModel->dao->select('*')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->orderBy('id_desc')->fetch();
    }

    /**
     * Test saveCommits method.
     *
     *  @param  int    $repoID
     *  @param  string $param   linked|job|empty
     *  @access public
     *  @return object|null
     */
    public function saveCommitsTest(int $repoID, string $param): object|null
    {
        $repo = $this->objectModel->loadModel('repo')->getByID($repoID);

        $logs = array();
        $jobs = array();

        $noLink = new stdclass();
        $noLink->revision  = 2;
        $noLink->committer = 'test';
        $noLink->author    = 'test';
        $noLink->date      = '2023-01-01 00:00:00';
        $noLink->time      = '2023-01-01 00:00:00';
        $noLink->msg       = 'test';
        $noLink->comment   = 'test';
        $noLink->change    = array();

        $linked = new stdclass();
        $linked->revision  = 3;
        $linked->committer = 'test';
        $linked->author    = 'test';
        $linked->date      = '2023-01-02 00:00:00';
        $linked->time      = '2023-01-02 00:00:00';
        $linked->msg       = '* Code for task #1.';
        $linked->comment   = '* Code for task #1.';
        $linked->change    = array();
        if(strpos($param, 'linked') !== false) $logs[] = $linked;
        if(strpos($param, 'nolink') !== false) $logs[] = $noLink;

        $job = new stdclass();
        $job->id      = 1;
        $job->comment = 'task';
        if(strpos($param, 'job') !== false) $jobs[] = $job;

        $commit = new stdclass();
        $commit->commit = 1;
        $res = $this->objectModel->saveCommits($repo, $logs, $commit, $jobs, false);
        if(!$res) return dao::getError();

        return $this->objectModel->loadModel('repo')->getByID($repoID);
    }
}
