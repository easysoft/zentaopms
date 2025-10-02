<?php
class svnTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('svn');
    }

    /**
     * Test __construct method.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @access public
     * @return object
     */
    public function __constructTest($moduleName = '', $methodName = ''): object
    {
        $svnModel = new svnModel($moduleName, $methodName);
        if(dao::isError()) return dao::getError();

        return $svnModel;
    }

    /**
     * Test run method.
     *
     * @param  string $scenario 测试场景: normal|empty|error
     * @access public
     * @return mixed
     */
    public function runTest(string $scenario = 'normal')
    {
        ob_start();
        $result = $this->objectModel->run();
        ob_get_clean();
        if(dao::isError()) return dao::getError();

        switch($scenario)
        {
            case 'empty':
                return $result;
            case 'error':
                return $result;
            case 'repos':
                $this->objectModel->setRepos();
                return count($this->objectModel->repos);
            case 'history':
                $history = $this->objectModel->dao->select('*')->from(TABLE_REPOHISTORY)->orderBy('id_desc')->limit(1)->fetch();
                return $history ? $history : null;
            case 'boolean':
                return $result ? 'true' : 'false';
            default:
                return $result ? 'true' : 'false';
        }
    }

    /**
     * Test getRepoLogs method.
     *
     * @param  int $version
     * @access public
     * @return mixed
     */
    public function getRepoLogsTest(int $version)
    {
        $this->objectModel->setRepos();
        ob_start();

        if(empty($this->objectModel->repos))
        {
            ob_get_clean();
            return null;
        }

        $repo = $this->objectModel->repos[1];
        $logs = $this->objectModel->getRepoLogs($repo, $version);
        $error = ob_get_clean();

        if($error) return $error;
        if(dao::isError()) return dao::getError();
        if(empty($logs)) return null;

        return $logs[count($logs) - 1];
    }

    /**
     * Test updateCommit method.
     *
     * @param  int    $repoID
     * @param  array  $commentGroup
     * @param  string $scenario 测试场景：normal|unsynced|empty|withJobs|error
     * @access public
     * @return mixed
     */
    public function updateCommitTest(int $repoID, array $commentGroup = array(), string $scenario = 'normal')
    {
        $repo = $this->objectModel->loadModel('repo')->getByID($repoID);
        if(!$repo) return false;

        ob_start();
        $result = $this->objectModel->updateCommit($repo, $commentGroup, false);
        $output = ob_get_clean();

        if(dao::isError()) return dao::getError();

        switch($scenario)
        {
            case 'boolean':
                return $result ? 'true' : 'false';
            case 'history':
                $history = $this->objectModel->dao->select('*')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->orderBy('id_desc')->fetch();
                return $history ? $history : null;
            case 'count':
                $count = $this->objectModel->dao->select('count(*) as total')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->fetch();
                return $count ? $count->total : 0;
            case 'latest':
                $latest = $this->objectModel->loadModel('repo')->getLatestCommit($repoID);
                return $latest ? $latest : null;
            case 'output':
                return $output;
            case 'error':
                return dao::isError() ? dao::getError() : 'no error';
            case 'repo':
                return $repo;
            default:
                $history = $this->objectModel->dao->select('*')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->orderBy('id_desc')->fetch();
                return $history ? $history : false;
        }
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

    /**
     * Test cat method.
     *
     * @param  string $url
     * @param  int    $revision
     * @access public
     * @return mixed
     */
    public function catTest(string $url, int $revision)
    {
        ob_start();
        $result = $this->objectModel->cat($url, $revision);
        $output = ob_get_clean();

        if(dao::isError()) return dao::getError();

        // 如果有输出错误，返回简化的错误标识
        if($output && strpos($output, 'not found') !== false) return '~~';
        if($output && strpos($output, 'error') !== false) return '~~';

        // 如果结果是false，返回0
        if($result === false) return '0';

        // 其他情况返回实际结果或简化标识
        return $result ? $result : '~~';
    }

    /**
     * Test diff method.
     *
     * @param  string $url
     * @param  int    $revision
     * @access public
     * @return string|false
     */
    public function diffTest(string $url, int $revision): string|false
    {
        $result = $this->objectModel->diff($url, $revision);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test convertLog method.
     *
     * @param  array $log
     * @access public
     * @return object|null
     */
    public function convertLogTest(array $log): object|null
    {
        $result = $this->objectModel->convertLog($log);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getRepos method.
     *
     * @access public
     * @return array
     */
    public function getReposTest(): array
    {
        ob_start();
        $result = $this->objectModel->getRepos();
        $output = ob_get_clean();
        if(dao::isError()) return dao::getError();

        return array('repos' => $result, 'output' => $output);
    }

    /**
     * Test getRepoTags method.
     *
     * @param  object $repo
     * @param  string $path
     * @access public
     * @return mixed
     */
    public function getRepoTagsTest(object $repo, string $path)
    {
        ob_start();
        $result = $this->objectModel->getRepoTags($repo, $path);
        $output = ob_get_clean();
        if(dao::isError()) return dao::getError();

        if($output) return $output;
        return $result;
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
        $this->objectModel->printLog($log);
        $result = ob_get_clean();
        if(dao::isError()) return dao::getError();

        return trim($result);
    }

    /**
     * Test setClient method.
     *
     * @param  object $repo
     * @access public
     * @return mixed
     */
    public function setClientTest(object $repo)
    {
        $result = $this->objectModel->setClient($repo);
        if(dao::isError()) return dao::getError();

        return array('result' => $result ? '1' : '', 'client' => $this->objectModel->client);
    }

    /**
     * Test setRepo method.
     *
     * @param  object $repo
     * @access public
     * @return mixed
     */
    public function setRepoTest(object $repo)
    {
        $result = $this->objectModel->setRepo($repo);
        if(dao::isError()) return dao::getError();

        return array(
            'result' => $result ? '1' : '0',
            'client' => $this->objectModel->client,
            'repoRoot' => $this->objectModel->repoRoot
        );
    }

    /**
     * Test setRepoRoot method.
     *
     * @param  object $repo
     * @access public
     * @return mixed
     */
    public function setRepoRootTest(object $repo)
    {
        $this->objectModel->setRepoRoot($repo);
        if(dao::isError()) return '0';

        return $this->objectModel->repoRoot ? $this->objectModel->repoRoot : '0';
    }

    /**
     * Test setRepos method.
     *
     * @param  string $scenario 测试场景：normal|empty|duplicate|mixed|single
     * @access public
     * @return mixed
     */
    public function setReposTest(string $scenario = 'normal')
    {
        ob_start();
        $this->objectModel->setRepos();
        $output = ob_get_clean();
        if(dao::isError()) return dao::getError();

        switch($scenario)
        {
            case 'empty':
                return array('output' => $output, 'count' => count($this->objectModel->repos));
            case 'count':
                return count($this->objectModel->repos);
            case 'first':
                return reset($this->objectModel->repos);
            case 'properties':
                $repo = reset($this->objectModel->repos);
                if(!$repo) return array('hasAcl' => '', 'hasDesc' => '');
                return array(
                    'hasAcl' => isset($repo->acl) ? '1' : '0',
                    'hasDesc' => isset($repo->desc) ? '1' : '0',
                    'hasSCM' => isset($repo->SCM) ? '1' : '0',
                    'hasPath' => isset($repo->path) ? '1' : '0'
                );
            case 'paths':
                $paths = array();
                foreach($this->objectModel->repos as $repo)
                {
                    $paths[] = $repo->path;
                }
                return array_unique($paths);
            case 'scmTypes':
                $scmTypes = array();
                foreach($this->objectModel->repos as $repo)
                {
                    $scmTypes[] = $repo->SCM;
                }
                $uniqueTypes = array_unique($scmTypes);
                return count($uniqueTypes) > 0 ? count($uniqueTypes) : '';
            default:
                return array('repos' => $this->objectModel->repos, 'output' => $output);
        }
    }
}
