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
}
