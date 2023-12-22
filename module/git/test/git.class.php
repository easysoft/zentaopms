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

    /**
     * Test saveCommits method.
     *
     *  @param  int    $repoID
     *  @param  string $param   linked|job|empty
     *  @access public
     *  @return object|null|array
     */
    public function saveCommitsTest(int $repoID, string $param): object|null|array
    {
        $repo = $this->gitModel->loadModel('repo')->getByID($repoID);

        $logs = array();
        $jobs = array();

        $noLink = new stdclass();
        $noLink->revision  = 'abc12';
        $noLink->committer = 'test';
        $noLink->author    = 'test';
        $noLink->date      = '2023-01-01 00:00:00';
        $noLink->time      = '2023-01-01 00:00:00';
        $noLink->msg       = 'test';
        $noLink->comment   = 'test';
        $noLink->change    = array();

        $linked = new stdclass();
        $linked->revision  = 'abc123';
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

        $res = $this->gitModel->saveCommits($repo, 'master', $logs, 1, $jobs, array(), false);
        if(!$res) return dao::getError();

        return $this->gitModel->loadModel('repo')->getByID($repoID);
    }
}
