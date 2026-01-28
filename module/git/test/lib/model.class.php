<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class gitModelTest extends baseTest
{
    protected $moduleName = 'git';
    protected $className  = 'model';

    /**
     * Test updateCommit method.
     *
     * @param  int    $repoID
     * @param  array  $commentGroup
     * @param  bool   $printLog
     * @access public
     * @return array
     */
    public function updateCommitTest(int $repoID, array $commentGroup = array(), bool $printLog = false): array
    {
        $repo = $this->gitModel->loadModel('repo')->getByID($repoID);
        if(!$repo) return array('error' => 'repo_not_found', 'result' => false);

        ob_start();
        $result = $this->gitModel->updateCommit($repo, $commentGroup, $printLog);
        $output = ob_get_clean();

        if(dao::isError()) return array('error' => dao::getError(), 'result' => false);

        $repoHistory = $this->gitModel->dao->select('*')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->orderBy('id_desc')->fetch();
        $updatedRepo = $this->gitModel->loadModel('repo')->getByID($repoID);

        return array(
            'result' => $result,
            'repo' => $updatedRepo,
            'history' => $repoHistory,
            'output' => trim($output),
            'error' => dao::isError() ? dao::getError() : null
        );
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
     * @return bool
     */
    public function runTest(): bool
    {
        ob_start();
        $result = $this->gitModel->run();
        ob_get_clean();
        if(dao::isError()) return false;

        return $result;
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

    /**
     * Test linkCommit method.
     *
     * @param  array  $designs
     * @param  int    $repoID
     * @param  string $revision
     * @access public
     * @return mixed
     */
    public function linkCommitTest(array $designs, int $repoID, string $revision): mixed
    {
        $log = new stdclass();
        $log->revision = $revision;

        ob_start();
        try {
            $this->gitModel->linkCommit($designs, $repoID, $log);
        } catch (TypeError $e) {
            ob_end_clean();
            return 'has_error';
        }
        $output = ob_get_clean();

        if(dao::isError()) return 'has_error';

        if(!empty($output) && strpos($output, 'alert') !== false) return 'has_error';

        if(empty($designs)) return 'empty';

        $relations = $this->gitModel->dao->select('*')->from(TABLE_RELATION)
            ->where('AType')->eq('design')
            ->andWhere('AID')->in($designs)
            ->andWhere('BType')->eq('commit')
            ->andWhere('relation')->eq('completedin')
            ->fetchAll();

        return count($relations) > 0 ? 'success' : 'no_relation';
    }

    /**
     * Test getRepos method.
     *
     * @access public
     * @return array
     */
    public function getReposTest(): array
    {
        $result = $this->gitModel->getRepos();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getRepoTags method.
     *
     * @param  object $repo
     * @access public
     * @return array|bool
     */
    public function getRepoTagsTest(object $repo): array|bool
    {
        try {
            $result = $this->gitModel->getRepoTags($repo);
            if(dao::isError()) return dao::getError();
            return $result;
        } catch (TypeError $e) {
            return false;
        }
    }

    /**
     * Test printLog method.
     *
     * @param  string $log
     * @access public
     * @return string
     */
    public function printLogTest(string $log): string
    {
        ob_start();
        $this->gitModel->printLog($log);
        $result = trim(ob_get_clean());
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test setClient method.
     *
     * @param  mixed $repo
     * @access public
     * @return mixed
     */
    public function setClientTest($repo = null)
    {
        if($repo === null) return 'null_repo';

        try {
            $this->gitModel->setClient($repo);
            if(dao::isError()) return dao::getError();

            return $this->gitModel->client;
        } catch (TypeError $e) {
            return 'type_error';
        } catch (Error $e) {
            return 'error';
        }
    }

    /**
     * Test setRepo method.
     *
     * @param  object $repo
     * @access public
     * @return array
     */
    public function setRepoTest(object $repo): array
    {
        $result = $this->gitModel->setRepo($repo);
        if(dao::isError()) return array('error' => dao::getError());

        return array(
            'result'   => $result ? '1' : '0',
            'client'   => $this->gitModel->client,
            'repoRoot' => $this->gitModel->repoRoot
        );
    }

    /**
     * Test setRepoRoot method.
     *
     * @param  object $repo
     * @access public
     * @return string
     */
    public function setRepoRootTest(object $repo): string
    {
        $this->gitModel->setRepoRoot($repo);
        if(dao::isError()) return dao::getError();

        return $this->gitModel->repoRoot;
    }

    /**
     * Test setRepos method.
     *
     * @access public
     * @return array
     */
    public function setReposTest(): array
    {
        ob_start();
        $result = $this->gitModel->setRepos();
        $output = ob_get_clean();
        if(dao::isError()) return array('error' => dao::getError());

        $firstRepo = reset($this->gitModel->repos);
        $firstRepoSCM = $firstRepo ? $firstRepo->SCM : '';
        $firstRepoHasAcl = $firstRepo && property_exists($firstRepo, 'acl') ? 'exists' : 'not_exists';
        $firstRepoHasDesc = $firstRepo && property_exists($firstRepo, 'desc') ? 'exists' : 'not_exists';

        return array(
            'result' => $result ? '1' : '0',
            'count'  => count($this->gitModel->repos),
            'firstSCM' => $firstRepoSCM,
            'hasAcl' => $firstRepoHasAcl,
            'hasDesc' => $firstRepoHasDesc,
            'output' => trim($output)
        );
    }
}
