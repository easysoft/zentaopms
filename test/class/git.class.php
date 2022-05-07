<?php
class gitTest
{
    public $tester;

    public function __construct()
    {
        global $tester;
        $this->tester   = $tester;
        $this->gitModel = $this->tester->loadModel('git');
    }

    /**
     * Update commit.
     *
     * @param  object $repo
     * @param  array  $commentGroup
     * @param  bool   $printLog
     * @access public
     * @return bool
     */
    public function updateCommit($repo)
    {
        $repoID = isset($repo->id) ? $repo->id : 0;
        $oldLastSync = $this->tester->dao->select('lastSync')->from(TABLE_REPO)->where('id')->eq($repoID)->fetch('lastSync');
        $result = $this->gitModel->updateCommit($repo, array(), false);
        if(empty($repoID)) return $result;
        $newLastSync = $this->tester->dao->select('lastSync')->from(TABLE_REPO)->where('id')->eq($repoID)->fetch('lastSync');
        return $oldLastSync != $newLastSync;
    }

    /**
     * Set the repos.
     *
     * @access public
     * @return object
     */
    public function setRepos()
    {
        $this->gitModel->setRepos();
        $repo = array_shift($this->gitModel->repos);
        return $repo;
    }

    /**
     * Get repos.
     *
     * @access public
     * @return string
     */
    public function getRepos()
    {
        $pairs = $this->gitModel->getRepos();
        return array_shift($pairs);
    }

    /**
     * Set repo.
     *
     * @param  object    $repo
     * @access public
     * @return object
     */
    public function setRepo($repo)
    {
        $this->gitModel->client   = '';
        $this->gitModel->repoRoot = '';

        $result = $this->gitModel->setRepo($repo);

        $data = new stdclass();
        $data->result   = $result;
        $data->client   = !empty($this->gitModel->client);
        $data->repoRoot = !empty($this->gitModel->repoRoot);
        return $data;
    }

    /**
     * get tags histories for repo.
     *
     * @param  object    $repo
     * @access public
     * @return int
     */
    public function getRepoTags($repo)
    {
        $tags = $this->gitModel->getRepoTags($repo);
        if(is_array($tags) and count($tags) >= 1) return 1;
        return 0;
    }

    /**
     * Get repo logs.
     *
     * @param  object  $repo
     * @access public
     * @return int
     */
    public function getRepoLogs($repo)
    {
        $logs = $this->gitModel->getRepoLogs($repo, '');
        if(is_array($logs) and count($logs) >= 1) return 1;
        return 0;
    }

    /**
     * Convert log from xml format to object.
     *
     * @param  object    $log
     * @access public
     * @return object
     */
    public function convertLog($log)
    {
        $parsedLogs = $this->gitModel->convertLog($log);
        if(empty($parsedLogs->revision)) $parsedLogs->revision = 0;
        return $parsedLogs;
    }
}
