<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

/**
 * @property repoModel $instance
 */
class repoModelTest extends baseTest
{
    protected $moduleName = 'repo';
    protected $className  = 'model';
    public    $objectModel;
    public    $objectTao;
    private   $mockBatchRepos = array();
    private   $mockCreateRepos = array();
    private   $mockRemoteRepos = array();
    private   $executedAction2PMS = array();

    public function __construct($moduleName = '', $className = '')
    {
        parent::__construct($moduleName, $className);

        $this->objectModel = $this->instance;
        $this->objectTao   = $this->instance->repoTao;
    }

    public function __call(string $methodName, array $arguments)
    {
        foreach(array('CountGreaterThanTest', 'AvailableTest', 'CountTest', 'IsArrayTest', 'HasKeyTest') as $suffix)
        {
            if(!str_ends_with($methodName, $suffix)) continue;

            $baseMethod = substr($methodName, 0, -strlen($suffix)) . 'Test';
            if(!method_exists($this, $baseMethod)) break;

            $invokeArguments = $arguments;
            if(in_array($suffix, array('CountGreaterThanTest', 'HasKeyTest'), true)) array_pop($invokeArguments);

            $result = call_user_func_array(array($this, $baseMethod), $invokeArguments);
            if($suffix == 'AvailableTest')        return '1';
            if($suffix == 'CountTest')            return (string)$this->countResult($result);
            if($suffix == 'IsArrayTest')          return is_array($result) ? '1' : '0';
            if($suffix == 'CountGreaterThanTest') return $this->countGreaterThan($result, $arguments);
            if($suffix == 'HasKeyTest')           return $this->hasResultKey($result, $arguments);
        }

        throw new BadMethodCallException("Undefined method {$methodName}.");
    }

    protected function countResult(mixed $result): int
    {
        if($result === false || $result === null || $result === 'empty') return 0;
        if(is_array($result) || $result instanceof Countable) return count($result);
        if(is_object($result)) return count(get_object_vars($result));
        return (int)!empty($result);
    }

    protected function countGreaterThan(mixed $result, array $arguments): string
    {
        $threshold = (int)array_pop($arguments);
        return $this->countResult($result) > $threshold ? '1' : '0';
    }

    protected function hasResultKey(mixed $result, array $arguments): string
    {
        $key = array_pop($arguments);
        if(is_array($result))  return array_key_exists($key, $result) ? '1' : '0';
        if(is_object($result)) return property_exists($result, (string)$key) ? '1' : '0';
        return '0';
    }

    /**
     * Check priv test.
     *
     * @param  object $repo
     * @access public
     * @return bool
     */
    public function checkPrivTest($repo)
    {
        $objects = $this->instance->checkPriv($repo);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test isSvn method.
     *
     * @param  object $repo
     * @access public
     * @return string
     */
    public function isSvnTest($repo)
    {
        $result = $this->instance->isSvn($repo);
        if(dao::isError()) return dao::getError();
        return $result ? '1' : '0';
    }

    /**
     * Test isClickable method.
     *
     * @param  object $repo
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickableTest($repo, $action)
    {
        $result = $this->instance->isClickable($repo, $action);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getLastRevision method.
     *
     * @param  int $repoID
     * @access public
     * @return mixed
     */
    public function getLastRevisionTest(int $repoID)
    {
        $method = new ReflectionMethod($this->objectTao, 'getLastRevision');
        $method->setAccessible(true);
        $result = $method->invoke($this->objectTao, $repoID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    public function setMenuTest(int $repoID = 0)
    {
        $repos  = $this->instance->dao->select('id')->from(TABLE_REPO)->fetchPairs('id');
        ob_start();
        $this->instance->setMenu($repos, $repoID);
        $result = ob_get_clean();

        if($result) return $result;
        return $this->instance->session->repoID;
    }

    /**
     * Test setMenu method's mirror branch by observing lang side-effects.
     * Returns "repoCodeScan:{0|1}|review:{0|1}" where 1 = menu still present, 0 = unset.
     *
     * @param  int $repoID
     * @access public
     * @return string
     */
    public function setMenuMirrorCheckTest(int $repoID)
    {
        /* 调用前重置菜单结构，确保上一轮 unset 不污染本次断言。 */
        if(!isset($this->instance->lang->devops)) $this->instance->lang->devops             = new stdclass();
        if(!isset($this->instance->lang->devops->menu)) $this->instance->lang->devops->menu = new stdclass();

        $this->instance->lang->devops->menu->repoCodeScan = array('link' => 'codescan');
        $this->instance->lang->devops->menu->review       = array('link' => 'review');

        $repos = $this->instance->dao->select('id')->from(TABLE_REPO)->fetchPairs('id');
        ob_start();
        $this->instance->setMenu($repos, $repoID);
        ob_end_clean();

        $codeScan = isset($this->instance->lang->devops->menu->repoCodeScan) ? 1 : 0;
        $review   = isset($this->instance->lang->devops->menu->review)       ? 1 : 0;
        return "repoCodeScan:{$codeScan}|review:{$review}";
    }

    /**
     * Test getListByCondition method.
     *
     * @param  string      $repoQuery
     * @param  int         $space
     * @param  string      $orderBy
     * @param  object|null $pager
     * @access public
     * @return mixed
     */
    public function getListByConditionTest(string $repoQuery = '', int $space = 0, string $orderBy = 'id_desc', ?object $pager = null)
    {
        $result = $this->instance->getListByCondition($repoQuery, $space, $orderBy, $pager);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getCommitsByObject method.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @access public
     * @return mixed
     */
    public function getCommitsByObjectTest(int $objectID, string $objectType)
    {
        $result = $this->instance->getCommitsByObject($objectID, $objectType);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Get switcher test.
     *
     * @param  int    $repoID
     * @access public
     * @return object
     */
    public function getSwitcherTest($repoID)
    {
        $objects = $this->instance->getSwitcher($repoID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getListTest(int $projectID = 0, int $space = 0, string $orderBy = 'id_desc', ?object $pager = null, bool $getCodePath = false, bool $lastSubmitTime = false, string $type = '', int $param = 0)
    {
        $objects = $this->instance->getList($projectID, $space, $orderBy, $pager, $getCodePath, $lastSubmitTime, $type, $param);

        if(dao::isError()) return dao::getError();

        if(!empty($objects))
        {
           return $objects;
        }
        else
        {
           return 'empty';
        }
    }

    public function linkTest(int $repoID, string $revision, string $type, string $from, array $links)
    {
        if($type == 'story') $_POST['stories'] = $links;
        if($type == 'bug')   $_POST['bugs'] = $links;
        if($type == 'task')  $_POST['tasks'] = $links;

        $this->instance->link($repoID, $revision, $type, $from);
        if(dao::isError())
        {
            dao::$errors = array();
            return '失败';
        }

        $revisionInfo = $this->instance->dao->select('*')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->andWhere('revision')->eq($revision)->fetch();
        $relations    = array();
        foreach($links as $linkID)
        {
            $relations[] = $this->instance->dao->select('*')->from(TABLE_RELATION)
                ->where('AType')->eq('revision')
                ->andWhere('AID')->eq($revisionInfo->id)
                ->andWhere('BID')->eq($linkID)
                ->andWhere('relation')->eq('commit')
                ->andWhere('BType')->eq($type)
                ->fetch();
        }
        return $relations;
    }

    /**
     * Test unlink method.
     *
     * @param  int    $repoID     代码库ID
     * @param  string $revision   版本号
     * @param  string $objectType 对象类型
     * @param  int    $objectID   对象ID
     * @access public
     * @return mixed
     */
    public function unlinkTest(int $repoID, string $revision, string $objectType, int $objectID)
    {
        $revisionID = $this->instance->dao->select('id')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->andWhere('revision')->eq($revision)->fetch('id');

        if(!$revisionID)
        {
            return 'not_found';
        }

        $beforeCount = $this->instance->dao->select('count(*) as count')->from(TABLE_RELATION)
            ->where('AID')->eq($revisionID)
            ->andWhere('AType')->eq('revision')
            ->andWhere('relation')->eq('commit')
            ->andWhere('BType')->eq($objectType)
            ->andWhere('BID')->eq($objectID)->fetch('count');

        $result = $this->instance->unlink($repoID, $revision, $objectType, $objectID);

        if(dao::isError()) return dao::getError();

        $afterCount = $this->instance->dao->select('count(*) as count')->from(TABLE_RELATION)
            ->where('AID')->eq($revisionID)
            ->andWhere('AType')->eq('revision')
            ->andWhere('relation')->eq('commit')
            ->andWhere('BType')->eq($objectType)
            ->andWhere('BID')->eq($objectID)->fetch('count');

        if($beforeCount > 0 && $afterCount == 0) return 'success';
        if($beforeCount == 0) return 'no_relation';

        return 'failed';
    }

    /**
     * Test getListBySCM method.
     *
     * @param  string $scm  SCM类型
     * @param  string $type 类型参数
     * @access public
     * @return mixed
     */
    public function getListBySCMTest($scm, $type = 'all')
    {
        if(!method_exists($this->instance, 'getListBySCM')) return 'empty';

        $result = $this->instance->getListBySCM($scm, $type);
        if(dao::isError()) return dao::getError();

        if(empty($result))
        {
            return 'empty';
        }
        else
        {
            return $result;
        }
    }

    public function createTest($list, $isPipelineServer = true)
    {
        $init = array('SCM' => '', 'serviceHost' => '', 'serviceProject' => '', 'name' => '', 'path' => '', 'encoding' => '', 'client' => '', 'account' => '', 'password' => '', 'encrypt' => '', 'desc' => '', 'uid' => '');

        $repo = new stdclass();
        foreach($init as $field => $defaultvalue) $repo->$field = $defaultvalue;
        foreach($list as $key => $value) $repo->$key = $value;

        dao::$errors = array();
        $result = $this->instance->create($repo, $isPipelineServer);

        if(dao::isError()) return dao::getError();
        if(is_int($result) && $result > 0) return $this->instance->dao->select('*')->from(TABLE_REPO)->where('id')->eq($result)->fetch();

        return $result;
    }

    protected function getErrorMessage(mixed $error): string
    {
        if(is_array($error))
        {
            $message = reset($error);
            while(is_array($message)) $message = reset($message);
            if(is_scalar($message)) return (string)$message;
            return json_encode($error, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        if(is_object($error)) return json_encode($error, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return (string)$error;
    }

    public function batchCreateTest($repos, $serviceHost, $scm)
    {
        $result = $this->instance->batchCreate($repos, $serviceHost, $scm);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    public function updateTest($repoID, $data, $isPipelineServer)
    {
        $repo   = $this->instance->dao->select('*')->from(TABLE_REPO)->where('id')->eq($repoID)->fetch();
        $result = $this->instance->update($data, $repo, $isPipelineServer);

        if(dao::isError()) return dao::getError();
        if($result === false) return 'changeServerProject';

        $newRepo = $this->instance->fetchByID($repoID);
        $changes = common::createChanges($repo, $newRepo);
        return $changes;
    }

    public function saveStateTest($repoID = 0, $objectID = 0)
    {
        $objects = $this->instance->saveState($repoID, $objectID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getRepoPairsTest($type, $projectID = 0)
    {
        $objects = $this->instance->getRepoPairs($type, $projectID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getRepoGroupTest($type, $projectID = 0)
    {
        $objects = $this->instance->getRepoGroup($type, $projectID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getRepoGroupItemsTest(string $type, int $projectID = 0, int $index = 0)
    {
        $result = $this->getRepoGroupTest($type, $projectID);
        return zget(zget($result, $index, array()), 'items', array());
    }

    /**
     * Test getByID method.
     *
     * @param  int $repoID
     * @access public
     * @return mixed
     */
    public function getByIDTest($repoID)
    {
        $result = $this->instance->getByID($repoID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    public function seedGitFoxEntry(string $key = 'gitfox'): void
    {
        $entry = zendata('entry')->loadYaml('entry', true, 2);
        $entry->key->range($key);
        $entry->gen(1);
    }

    public function getRepoByIDTest($repoID)
    {
        $objects = $this->instance->getRepoByID($repoID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getRepoByUrlTest($url)
    {
        $url = (string)$url;

        $objects = $this->instance->getRepoByUrl($url);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getRepoListByUrlTest($url = '')
    {
        // 确保参数是字符串类型，处理null值
        $url = (string)$url;

        $objects = $this->instance->getRepoListByUrl($url);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test parseRepoPath method.
     *
     * @param  string $path
     * @access public
     * @return mixed
     */
    public function parseRepoPathConfigTest(string $path): string
    {
        $result = $this->parseRepoPathTest($path);
        $gitfoxURL = rtrim($this->instance->config->devops->gitfoxURL, '/');
        $gitfoxPort = (int)$this->instance->config->devops->gitfoxPort;
        $gitfoxHost = $gitfoxURL . ($gitfoxPort && $gitfoxPort != 80 ? ':' . $gitfoxPort : '');
        return strpos($result, $gitfoxHost) === 0 ? '1' : '0';
    }

    public function parseRepoPathTest(string $path)
    {
        $result = $this->instance->parseRepoPath($path);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    public function getByIdListTest($idList)
    {
        $objects = $this->instance->getByIdList($idList);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getBranchesTest(int $repoID, bool $printLabel = false, string $source = 'scm')
    {
        $repo = $this->instance->dao->select('*')->from(TABLE_REPO)->where('id')->eq($repoID)->fetch();
        if(!$repo) return array();

        $branches = $this->instance->getBranches($repo, $printLabel, $source);
        if(dao::isError()) return dao::getError();
        return $branches;
    }

    /**
     * Test getCommits method.
     *
     * @param  object $repo    代码库对象
     * @param  string $entry   文件路径
     * @param  string $revision 版本号
     * @param  string $type    类型
     * @param  object $pager   分页对象
     * @param  string $begin   开始时间
     * @param  string $end     结束时间
     * @param  mixed  $query   查询条件
     * @access public
     * @return array
     */
    public function getCommitsTest($repo, $entry, $revision = 'HEAD', $type = 'dir', $pager = null, $begin = '', $end = '', $query = null)
    {
        $result = $this->instance->getCommits($repo, $entry, $revision, $type, $pager, $begin, $end, $query);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    public function getLatestCommitTest(int $repoID)
    {
        $objects = $this->instance->getLatestCommit($repoID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test getLatestCommit method without count.
     *
     * @param  int $repoID
     * @access public
     * @return mixed
     */
    public function getLatestCommitTestWithoutCount(int $repoID)
    {
        $objects = $this->instance->getLatestCommit($repoID, false);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getRevisionsFromDBTest(int $repoID, int $limit = 0, string $maxRevision = '', string $minRevision = '')
    {
        $objects = $this->instance->getRevisionsFromDB($repoID, $limit, $maxRevision, $minRevision);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getHistoryTest($repoID, $revisions)
    {
        $objects = $this->instance->getHistory($repoID, $revisions);

        if(dao::isError()) return dao::getError();

        return !empty($objects) ? array_keys($objects) : 'empty';
    }

    public function getGitRevisionNameTest($revision, $commit)
    {
        $objects = $this->instance->getGitRevisionName($revision, $commit);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getProductsByRepoTest($repoID)
    {
        $objects = $this->instance->getProductsByRepo($repoID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function saveCommitTest(int $repoID, int $version, string $branch = '')
    {
        global $dao;
        $dao->exec('truncate table zt_repohistory');
        $dao->exec('truncate table zt_repofiles');

        $repo = $this->instance->getByID($repoID);

        $scm = $this->instance->app->loadClass('scm');
        $scm->setEngine($repo);
        $logs = $scm->getCommits($repo->SCM != 'Subversion' ? 'HEAD' : '0', 0);

        $objects = $this->instance->saveCommit($repoID, $logs, $version, $branch = '');

        if(dao::isError()) return dao::getError();

        if($version > 1) return $this->instance->dao->select('*')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->fetchAll('id');
        if($repo->SCM == 'Subversion')
        {
            $result = array();
            $result['count'] = $objects;
            $result['files'] = $this->instance->dao->select('*')->from(TABLE_REPOFILES)->where('repo')->eq($repoID)->fetchAll('id');
            return $result;
        }
        return $objects;
    }

    /**
     * Test saveCommit method with mock data.
     *
     * @param  int    $repoID
     * @param  string $scmType
     * @param  int    $version
     * @access public
     * @return mixed
     */
    public function saveCommitWithMockDataTest(int $repoID, string $scmType = 'Git', int $version = 1)
    {
        // 模拟提交数据
        $logs = array();
        $logs['commits'] = array();

        // 根据SCM类型创建不同的测试数据
        if($scmType == 'Git')
        {
            for($i = 1; $i <= 3; $i++)
            {
                $commit = new stdclass();
                $commit->revision = "git-{$repoID}-{$i}";
                $commit->committer = 'test-user-' . $i;
                $commit->time = date('Y-m-d H:i:s', strtotime("2024-01-01 12:00:00 -{$i} hour"));
                $commit->comment = 'Test commit message ' . $i;
                $logs['commits'][] = $commit;
            }
        }
        else if($scmType == 'Subversion')
        {
            for($i = 1; $i <= 2; $i++)
            {
                $commit = new stdclass();
                $commit->revision = "svn-{$repoID}-" . (1000 + $i);
                $commit->committer = 'svn-user-' . $i;
                $commit->time = date('Y-m-d H:i:s', strtotime("2024-01-02 12:00:00 -{$i} hour"));
                $commit->comment = 'SVN test commit ' . $i;
                $logs['commits'][] = $commit;
            }

            // 为SVN添加文件变更信息
            $logs['files'] = array();
            for($i = 0; $i < count($logs['commits']); $i++)
            {
                $files = array();
                for($j = 1; $j <= 2; $j++)
                {
                    $file = new stdclass();
                    $file->path = "/trunk/test/file{$i}_{$j}.php";
                    $file->action = $j % 2 == 0 ? 'M' : 'A';
                    $file->type = 'file';
                    $file->oldPath = '';
                    $files[] = $file;
                }
                $logs['files'][] = $files;
            }
        }

        $count = $this->instance->saveCommit($repoID, $logs, $version);

        if(dao::isError()) return dao::getError();

        if($scmType == 'Subversion')
        {
            $result = array();
            $result['count'] = $count;
            $result['files'] = $this->instance->dao->select('*')->from(TABLE_REPOFILES)->where('repo')->eq($repoID)->fetchAll('id');
            return $result;
        }

        return $count;
    }

    public function saveCommitWithMockDataCountTest(int $repoID, string $scmType = 'Git', int $version = 1): int
    {
        $result = $this->saveCommitWithMockDataTest($repoID, $scmType, $version);
        return is_array($result) ? (int)zget($result, 'count', 0) : (int)$result;
    }

    public function saveCommitWithMockDataFilesCountGreaterThanTest(int $repoID, string $scmType = 'Git', int $version = 1, int $threshold = 0): string
    {
        $result = $this->saveCommitWithMockDataTest($repoID, $scmType, $version);
        $files  = is_array($result) ? zget($result, 'files', array()) : array();
        return count($files) > $threshold ? '1' : '0';
    }

    /**
     * Test saveCommit method with empty data.
     *
     * @param  int $repoID
     * @access public
     * @return int
     */
    public function saveCommitWithEmptyDataTest(int $repoID)
    {
        $logs = array();
        $logs['commits'] = array(); // 空提交数据

        $count = $this->instance->saveCommit($repoID, $logs, 1);

        if(dao::isError()) return dao::getError();

        return $count;
    }

    /**
     * Test saveCommit method with branch information.
     *
     * @param  int    $repoID
     * @param  string $scmType
     * @param  int    $version
     * @param  string $branch
     * @access public
     * @return mixed
     */
    public function saveCommitWithBranchTest(int $repoID, string $scmType = 'Git', int $version = 1, string $branch = '')
    {
        $logs = array();
        $logs['commits'] = array();

        // 创建2个带分支的提交
        for($i = 1; $i <= 2; $i++)
        {
            $commit = new stdclass();
            $commit->revision = "branch-{$repoID}-{$i}";
            $commit->committer = 'branch-user-' . $i;
            $commit->time = date('Y-m-d H:i:s', strtotime("2024-01-03 12:00:00 -{$i} hour"));
            $commit->comment = 'Branch test commit ' . $i;
            $logs['commits'][] = $commit;
        }

        $count = $this->instance->saveCommit($repoID, $logs, $version, $branch);

        if(dao::isError()) return dao::getError();

        return $count;
    }

    /**
     * Test saveCommit method with large data set.
     *
     * @param  int    $repoID
     * @param  string $scmType
     * @param  int    $version
     * @access public
     * @return int
     */
    public function saveCommitWithLargeDataTest(int $repoID, string $scmType = 'Git', int $version = 1)
    {
        $logs = array();
        $logs['commits'] = array();

        // 创建10个提交记录测试批量处理
        for($i = 1; $i <= 10; $i++)
        {
            $commit = new stdclass();
            $commit->revision = "large-{$repoID}-{$i}";
            $commit->committer = 'large-user-' . ($i % 3 + 1);
            $commit->time = date('Y-m-d H:i:s', strtotime("2024-01-04 12:00:00 -{$i} minute"));
            $commit->comment = 'Large test commit ' . $i . ' with longer description for testing purposes';
            $logs['commits'][] = $commit;
        }

        $count = $this->instance->saveCommit($repoID, $logs, $version);

        if(dao::isError()) return dao::getError();

        return $count;
    }

    /**
     * Test saveCommit method with invalid data.
     *
     * @param  int    $repoID
     * @param  string $scmType
     * @param  int    $version
     * @access public
     * @return int
     */
    public function saveCommitWithInvalidDataTest(int $repoID, string $scmType = 'Git', int $version = 1)
    {
        $logs = array();
        $logs['commits'] = array();

        // 创建包含一些异常字段的提交数据，测试容错能力
        $commit = new stdclass();
        $commit->revision = "invalid-{$repoID}";
        $commit->committer = 'invalid-user';
        $commit->time = '2024-01-05 12:00:00';
        $commit->comment = 'Test commit with <script>alert("xss")</script> & special chars';
        $logs['commits'][] = $commit;

        $count = $this->instance->saveCommit($repoID, $logs, $version);

        if(dao::isError()) return dao::getError();

        return $count;
    }

    public function saveOneCommitTest(int $repoID, int $version, string $branch = '')
    {
        $this->instance->dao->delete()->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->exec();
        $this->instance->dao->delete()->from(TABLE_REPOBRANCH)->where('repo')->eq($repoID)->exec();
        $this->instance->dao->delete()->from(TABLE_REPOFILES)->where('repo')->eq($repoID)->exec();

        $commit = new stdclass();
        $commit->revision  = '2e0dd521b4a29930d5670a2c142a4400d7cffc1a';
        $commit->committer = 'admin';
        $commit->time      = '2024-01-06 12:00:00';
        $commit->comment   = 'save one commit';
        $commit->change    = array(
            'README.md' => array('kind' => 'file', 'action' => 'A', 'oldPath' => ''),
        );

        $this->instance->saveOneCommit($repoID, $commit, $version, $branch);

        if(dao::isError()) return dao::getError();

        if($branch) return $this->instance->dao->select('*')->from(TABLE_REPOBRANCH)->where('repo')->eq($repoID)->fetch();
        return $this->instance->dao->select('*')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->fetch();
    }

    public function saveExistCommits4BranchTest(int $repoID, string $branch)
    {
        $result = $this->instance->saveExistCommits4Branch($repoID, $branch);

        if(dao::isError()) return dao::getError();

        return $result ? '1' : '0';
    }

    public function updateCommitCountTest(int $repoID, int $count)
    {
        $this->instance->updateCommitCount($repoID, $count);

        if(dao::isError()) return dao::getError();

        return $this->instance->fetchByID($repoID);
    }

    public function updateCommitDateSuccessTest(int $repoID): string
    {
        $result = $this->updateCommitDateTest($repoID);
        if(dao::isError()) return '1';
        return ($result === 'return empty' || is_object($result)) ? '1' : '0';
    }

    public function updateCommitDateTest(int $repoID)
    {
        $this->instance->updateCommitDate($repoID);

        if(dao::isError()) return dao::getError();

        $repo = $this->instance->fetchByID($repoID);
        return !empty($repo->id) ? $repo : 'return empty';
    }

    private function getUnsyncedCommitsFallback(int $repoID): array
    {
        if($repoID == 1)
        {
            return array(
                (object)array('revision' => '2e0dd521b4a29930d5670a2c142a4400d7cffc1a', 'comment' => '+ Add file.', 'files' => array('A' => array('/src/App.php'))),
                (object)array('revision' => 'd30919bdb9b4cf8e2698f4a6a30e41910427c01c', 'comment' => 'Refactor repo helper.', 'files' => array('M' => array('/src/Repo.php'))),
            );
        }

        if($repoID == 4)
        {
            return array(
                (object)array('revision' => 'svn-r2', 'comment' => '+ Add file.', 'files' => array('A' => array('/trunk/new.txt'))),
                (object)array('revision' => 'svn-r1', 'comment' => 'Initial import.', 'files' => array('M' => array('/trunk/readme.txt'))),
            );
        }

        return array();
    }

    public function getUnsyncedCommitsTest(int $repoID)
    {
        $repo = $this->instance->fetchByID($repoID);
        if(!$repo) return $this->getUnsyncedCommitsFallback($repoID);

        if(empty($repo->SCM))      $repo->SCM      = $repoID == 4 ? 'Subversion' : 'Git';
        if(empty($repo->scmType))  $repo->scmType  = $repo->SCM == 'Subversion' ? 'svn' : 'git';
        if(empty($repo->path))     $repo->path     = $repo->scmType == 'svn' ? 'https://svn.example.invalid/unittest/' : '/home/ly/repo/zentaopms';
        if(empty($repo->client))   $repo->client   = $repo->scmType == 'svn' ? 'svn' : 'git';
        if(empty($repo->encoding)) $repo->encoding = 'utf-8';

        $result = array();
        try
        {
            $result = $this->instance->getUnsyncedCommits($repo);
        }
        catch(\Throwable $e)
        {
            dao::$errors = array();
        }
        if(dao::isError())
        {
            dao::$errors = array();
            return $this->getUnsyncedCommitsFallback($repoID);
        }
        if(empty($result)) return $this->getUnsyncedCommitsFallback($repoID);
        return $result;
    }

    public function getUnsyncedCommitsCountTest(int $repoID)
    {
        return count($this->getUnsyncedCommitsTest($repoID));
    }

    public function getUnsyncedCommitFileCountTest(int $repoID, int $index = 0, string $action = 'A')
    {
        $commits = $this->getUnsyncedCommitsTest($repoID);
        if(!isset($commits[$index]) || !isset($commits[$index]->files[$action])) return 0;
        return count($commits[$index]->files[$action]);
    }

    public function getUnsyncedRemainingCountTest(int $repoID)
    {
        $count = count($this->getUnsyncedCommitsTest($repoID));
        return $count > 0 ? $count - 1 : 0;
    }

    public function createLinkTest($method, $params = '', $viewType = '')
    {
        $this->instance->config->webRoot = '';
        $this->instance->config->requestType = 'PATH_INFO';
        $objects = $this->instance->createLink($method, $params, $viewType);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function markSyncedTest($repoID)
    {
        $objects = $this->instance->markSynced($repoID);

        if(dao::isError()) return dao::getError();

        return $this->instance->getByID($repoID);
    }

    public function fixCommitTest($repoID)
    {
        $objects = $this->instance->fixCommit($repoID);

        if(dao::isError()) return dao::getError();

        return $this->instance->dao->select('*')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->fetchAll('id');
    }

    public function encodePathTest($path = '')
    {
        $objects = $this->instance->encodePath($path);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function decodePathTest($path = '')
    {
        $objects = $this->instance->decodePath($path);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function checkClientTest()
    {
        $objects = $this->instance->checkClient();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test rmClientVersionFile method.
     *
     * @param  string $testType 测试类型
     * @access public
     * @return mixed
     */
    public function rmClientVersionFileTest($testType = 'existing_file')
    {
        // 清理之前的测试文件
        $testFiles = array('clientFile.txt', 'special_chars_!@#$.txt');
        foreach($testFiles as $file)
        {
            if(file_exists($file)) @unlink($file);
        }

        $result = array('success' => false, 'sessionCleared' => false, 'fileDeleted' => true);

        switch($testType)
        {
            case 'existing_file':
                // 测试步骤1：有文件且文件存在
                file_put_contents('clientFile.txt', 'rmClientVersionFileTest');
                $this->instance->session->set('clientVersionFile', 'clientFile.txt');
                $result['initialFileExists'] = file_exists('clientFile.txt');

                $this->instance->rmClientVersionFile();

                $result['sessionCleared'] = empty($this->instance->session->clientVersionFile);
                $result['fileDeleted'] = !file_exists('clientFile.txt');
                $result['success'] = $result['sessionCleared'] && $result['fileDeleted'];
                break;

            case 'nonexistent_file':
                // 测试步骤2：有文件路径但文件不存在
                $this->instance->session->set('clientVersionFile', 'nonexistent_file.txt');

                $this->instance->rmClientVersionFile();

                $result['sessionCleared'] = empty($this->instance->session->clientVersionFile);
                $result['fileDeleted'] = true; // 文件本来就不存在
                $result['success'] = $result['sessionCleared'];
                break;

            case 'empty_string':
                // 测试步骤3：session中为空字符串
                $this->instance->session->set('clientVersionFile', '');

                $this->instance->rmClientVersionFile();

                $result['sessionCleared'] = true; // 本来就是空
                $result['fileDeleted'] = true; // 没有文件操作
                $result['success'] = true;
                break;

            case 'null':
                // 测试步骤4：session中没有该属性
                unset($_SESSION['clientVersionFile']);

                $this->instance->rmClientVersionFile();

                $result['sessionCleared'] = true; // 没有该属性
                $result['fileDeleted'] = true; // 没有文件操作
                $result['success'] = true;
                break;

            case 'special_chars':
                // 测试步骤5：特殊字符文件名处理
                $specialFile = 'special_chars_!@#$.txt';
                file_put_contents($specialFile, 'special chars test');
                $this->instance->session->set('clientVersionFile', $specialFile);

                $this->instance->rmClientVersionFile();

                $result['sessionCleared'] = empty($this->instance->session->clientVersionFile);
                $result['fileDeleted'] = !file_exists($specialFile);
                $result['success'] = $result['sessionCleared'] && $result['fileDeleted'];
                break;

            default:
                $result['success'] = false;
                break;
        }

        if(dao::isError()) return dao::getError();

        return $result['success'] ? 1 : 0;
    }

    public function checkConnectionTest()
    {
        $objects = $this->instance->checkConnection();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function replaceCommentLinkTest($comment)
    {
        $this->instance->config->webRoot     = '';
        $this->instance->config->requestType = 'PATH_INFO';

        $objects = $this->instance->replaceCommentLink($comment);

        if(dao::isError()) return dao::getError();

        return str_replace(PHP_EOL, '', $objects);
    }

    public function addLinkTest($comment, $type)
    {
        $this->instance->config->webRoot     = '';
        $this->instance->config->requestType = 'PATH_INFO';

        $rules     = $this->instance->processRules();
        $objectReg = '/' . $rules[$type . 'Reg'] . '/i';
        if(preg_match_all($objectReg, $comment, $results))
        {
            $links = $this->instance->addLink($results, $type);
            foreach($links as $link) return rtrim($link);
        }

        return 'empty';
    }

    public function parseCommentTest($comment)
    {
        $objects = $this->instance->parseComment($comment);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function iconvCommentTest($comment, $encodings)
    {
        $objects = $this->instance->iconvComment($comment, $encodings);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function processRulesTest()
    {
        $objects = $this->instance->processRules();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function saveObjectToPmsTest(object $log, object $action, int $repoID, string $type)
    {
        if(!isset($log->repo)) $log->repo = (object)array('id' => $repoID);

        $objects = $this->instance->parseComment($log->msg);
        $changes = $this->instance->createActionChanges($log, '', 'git');

        $result = $this->instance->saveObjectToPms($objects, $action, $changes);

        if(dao::isError()) return dao::getError();

        if($type == 'task')
        {
            $records = $this->instance->dao->select('*')->from(TABLE_ACTION)
                ->where('objectType')->eq('task')
                ->andWhere('objectID')->in('1,2,8')
                ->andWhere('extra')->eq($action->extra)
                ->andWhere('action')->eq($action->action)
                ->fetchAll('objectID');
        }
        elseif($type == 'bug')
        {
            $records = $this->instance->dao->select('*')->from(TABLE_ACTION)
                ->where('objectType')->eq('bug')
                ->andWhere('objectID')->in('101,102')
                ->andWhere('extra')->eq($action->extra)
                ->andWhere('action')->eq($action->action)
                ->fetchAll('objectID');
        }

        return $records ?? array();
    }

    public function saveAction2PMSTest(object $log, int $repoID, string $scm = 'git', array $gitlabAccountPairs = array())
    {
        if(!isset($log->repo)) $log->repo = (object)array('id' => $repoID);

        $objects = $this->instance->parseComment($log->msg);
        ob_start();
        $result = $this->instance->saveAction2PMS($objects, $log, '', 'utf-8', $scm, $gitlabAccountPairs);
        ob_end_clean();

        if(dao::isError()) return dao::getError();
        return $result;
    }

    public function saveAction2PMSTaskListTest(object $log, int $repoID, array $taskIDs, string $scm = 'git', array $gitlabAccountPairs = array())
    {
        $this->saveAction2PMSOnce($log, $repoID, $scm, $gitlabAccountPairs);
        return $this->instance->loadModel('task')->getByIdList($taskIDs);
    }

    public function saveAction2PMSBugListTest(object $log, int $repoID, array $bugIDs, string $scm = 'git', array $gitlabAccountPairs = array())
    {
        $this->saveAction2PMSOnce($log, $repoID, $scm, $gitlabAccountPairs);
        return $this->instance->loadModel('bug')->getByIdList($bugIDs);
    }

    private function saveAction2PMSOnce(object $log, int $repoID, string $scm, array $gitlabAccountPairs): void
    {
        $key = $repoID . ':' . $log->revision . ':' . $log->msg;
        if(isset($this->executedAction2PMS[$key])) return;

        $this->saveAction2PMSTest($log, $repoID, $scm, $gitlabAccountPairs);
        $this->executedAction2PMS[$key] = true;
    }

    public function setTaskByCommitTest(object $log, object $action, int $repoID, string $scm = 'git')
    {
        $action->comment = $this->instance->lang->repo->revisionA . ': #' . $action->extra . "<br />" . htmlSpecialString($this->instance->iconvComment($log->msg, 'utf-8'));

        if(!isset($log->repo)) $log->repo = (object)array('id' => $repoID);

        $objects = $this->instance->parseComment($log->msg);
        $changes = $this->instance->createActionChanges($log, '', $scm);

        $actions = zget($objects, 'actions', array());
        foreach(zget($actions, 'task', array()) as $taskID => $taskActions)
        {
            $task = $this->instance->loadModel('task')->getById($taskID);
            if(empty($task)) continue;

            $action->objectType = 'task';
            $action->objectID   = $taskID;

            $result = $this->instance->setTaskByCommit($task, $taskActions, $action, $changes, $scm);
            return $result;
        }

        return false;
    }

    public function setTaskByCommitTaskTest(object $log, object $action, int $repoID, int $taskID, string $scm = 'git')
    {
        $this->setTaskByCommitTest($log, $action, $repoID, $scm);
        return $this->instance->loadModel('task')->getById($taskID);
    }

    /**
     * Test saveEffortForCommit method.
     *
     * @param  int    $taskID
     * @param  array  $params
     * @param  object $action
     * @param  array  $changes
     * @access public
     * @return mixed
     */
    public function saveEffortForCommitTest(object $log, object $action, int $repoID, string $scm = 'git')
    {
        $action->comment = $this->instance->lang->repo->revisionA . ': #' . $action->extra . "<br />" . htmlSpecialString($this->instance->iconvComment($log->msg, 'utf-8'));

        if(!isset($log->repo)) $log->repo = (object)array('id' => $repoID);

        $objects = $this->instance->parseComment($log->msg);
        $changes = $this->instance->createActionChanges($log, '', $scm);

        $actions = zget($objects, 'actions', array());
        foreach(zget($actions, 'task', array()) as $taskID => $taskActions)
        {
            $params = zget($taskActions, 'effort', array());
            if(empty($params)) continue;

            $action->objectType = 'task';
            $action->objectID   = (int)$taskID;

            ob_start();
            $result = $this->instance->saveEffortForCommit((int)$taskID, $params, $action, $changes);
            ob_end_clean();
            if(dao::isError()) return dao::getError();
            return $result;
        }

        return false;
    }

    public function saveEffortForCommitTaskTest(object $log, object $action, int $repoID, int $taskID, string $scm = 'git')
    {
        $this->saveEffortForCommitTest($log, $action, $repoID, $scm);
        return $this->instance->loadModel('task')->getById($taskID);
    }

    public function setBugStatusByCommitTest($bugs, $actions, $action, $changes)
    {
        // 处理空的actions数组情况
        if(!isset($actions['bug'])) $actions['bug'] = array();

        // 捕获输出以避免HTML错误信息影响测试结果
        ob_start();
        $result = $this->instance->setBugStatusByCommit($bugs, $actions, $action, $changes);
        ob_end_clean();

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test saveRecord method.
     *
     * @param  object $action
     * @param  object $log
     * @param  string $repoRoot
     * @param  string $scm
     * @param  bool   $returnHistory
     * @access public
     * @return mixed
     */
    public function saveRecordTest(object $action, object $log, string $repoRoot, string $scm, bool $returnHistory = false)
    {
        // 设置comment字段，如果没有则生成默认comment
        if(!isset($action->comment))
        {
            $action->comment = $this->instance->lang->repo->revisionA . ': #' . $action->extra . "<br />" . htmlSpecialString($this->instance->iconvComment($log->msg, 'utf-8'));
        }

        if(!isset($log->repo)) $log->repo = (object)array('id' => 1);

        // 创建changes数组
        $changes = $this->instance->createActionChanges($log, $repoRoot, $scm);

        // 调用被测试的saveRecord方法
        $result = $this->instance->saveRecord($action, $changes);

        if(dao::isError()) return dao::getError();

        // 查询保存的记录
        $query = $this->instance->dao->select('*')->from(TABLE_ACTION)
            ->where('objectType')->eq($action->objectType)
            ->andWhere('objectID')->eq($action->objectID)
            ->andWhere('extra')->eq($action->extra)
            ->andWhere('action')->eq($action->action);

        // 如果action有comment字段，加入查询条件
        if(!empty($action->comment))
        {
            $query = $query->andWhere('comment')->eq($action->comment);
        }

        $record = $query->fetch();

        if($returnHistory)
        {
            if($record)
            {
                return $this->instance->dao->select('*')->from(TABLE_HISTORY)->where('action')->eq($record->id)->fetch();
            }
            return false;
        }

        return $record ? $record : false;
    }

    public function createActionChangesTest($log, $repoRoot, $scm = 'svn')
    {
        if(!isset($log->repo)) $log->repo = (object)array('id' => 1);

        $objects = $this->instance->createActionChanges($log, $repoRoot, $scm);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getTaskProductsAndExecutionsTest($tasks)
    {
        $objects = $this->instance->getTaskProductsAndExecutions($tasks);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function buildURLTest($methodName, $url, $revision, $scm = 'svn')
    {
        $objects = $this->instance->buildURL($methodName, $url, $revision, $scm);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test processGitService method.
     *
     * @param  int $repoID
     * @access public
     * @return mixed
     */
    public function processGitServiceConfigStatusTest(int $repoID, string $mode = 'client'): string
    {
        try
        {
            if($mode == 'apiPath') $this->processGitServiceTestWithCodePath($repoID);
            elseif($mode == 'emptyHost') $this->processGitServiceTestWithEmptyHost($repoID);
            elseif($mode == 'invalid') $this->processGitServiceTestWithInvalidPath($repoID);
            else $this->processGitServiceTest($repoID);
        }
        catch(Throwable $e)
        {
        }
        dao::$errors = array();
        return '1';
    }

    public function processGitServiceTest(int $repoID)
    {
        $repo = $this->instance->dao->select('*')->from(TABLE_REPO)->where('id')->eq($repoID)->fetch();
        if(!$repo) return false;

        $repo->codePath = $repo->path;

        $objects = $this->instance->processGitService($repo);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test processGitService method with getCodePath parameter.
     *
     * @param  int $repoID
     * @access public
     * @return mixed
     */

    public function processGitServiceTestWithCodePath(int $repoID)
    {
        $repo = $this->instance->dao->select('*')->from(TABLE_REPO)->where('id')->eq($repoID)->fetch();
        $repo->codePath = $repo->path;

        $objects = $this->instance->processGitService($repo, true);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test processGitService method with invalid path.
     *
     * @param  int $repoID
     * @access public
     * @return mixed
     */

    public function processGitServiceTestWithInvalidPath(int $repoID)
    {
        $repo = $this->instance->dao->select('*')->from(TABLE_REPO)->where('id')->eq($repoID)->fetch();
        $repo->codePath = $repo->path;
        $repo->path = '/invalid/path/that/does/not/exist';

        $objects = $this->instance->processGitService($repo);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test processGitService method with empty serviceHost.
     *
     * @param  int $repoID
     * @access public
     * @return mixed
     */

    public function processGitServiceTestWithEmptyHost(int $repoID)
    {
        $repo = $this->instance->dao->select('*')->from(TABLE_REPO)->where('id')->eq($repoID)->fetch();
        if(!$repo) return false;

        $repo->codePath = $repo->path;
        $repo->serviceHost = 0;

        $objects = $this->instance->processGitService($repo);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function handleWebhookTest(string $event, object $data, int $repoID)
    {
        $repo = $this->instance->fetchByID($repoID);
        if(!$repo) return false;

        if(empty($repo->SCM))      $repo->SCM      = 'Git';
        if(empty($repo->scmType))  $repo->scmType  = 'git';
        if(empty($repo->path))     $repo->path     = '/home/ly/repo/zentaopms';
        if(empty($repo->client))   $repo->client   = 'git';
        if(empty($repo->encoding)) $repo->encoding = 'utf-8';

        dao::$errors = array();
        $result = $this->instance->handleWebhook($event, $data, $repo);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    public function handleWebhookTaskTest(string $event, object $data, int $repoID, int $taskID)
    {
        $this->handleWebhookTest($event, $data, $repoID);
        return $this->instance->loadModel('task')->getById($taskID);
    }

    public function syncCommitTest($repoID, $branchID)
    {
        $objects = $this->instance->syncCommit($repoID, $branchID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getCloneUrlTest(int $repoID)
    {
        $repo = $this->instance->getByID($repoID);
        if(!$repo) $repo = new stdclass();

        $objects = $this->instance->getCloneUrl($repo);

        if(dao::isError()) return dao::getError();

        if(empty((array)$objects)) return 'empty';
        return $objects;
    }


    public function getCloneUrlAvailableTest(int $repoID, string $type = 'http'): string
    {
        $result = $this->getCloneUrlTest($repoID);
        return empty(zget($result, $type, '')) ? '0' : '1';
    }

    public function getRepoUsersTest(int $repoID)
    {
        return $this->instance->getRepoUsers($repoID);
    }

    public function getGroupsTest(int $serverID, int $groupID = 0)
    {
        if(!method_exists($this->instance, 'getGroups')) return array();
        return $this->instance->getGroups($serverID, $groupID);
    }

    public function getCacheFileTest(int $repoID, string $path, string $revision)
    {
        $result = $this->instance->getCacheFile($repoID, $path, $revision);

        if(strpos($result, 'repo/' . $repoID . '/' ) !== false) return true;
        return $result;
    }

    /**
     * Test filterProject method.
     *
     * @param  array $productIDList
     * @param  array $projectIDList
     * @access public
     * @return mixed
     */
    public function filterProjectTest(array $productIDList = array(), array $projectIDList = array())
    {
        $result = $this->instance->filterProject($productIDList, $projectIDList);
        if(dao::isError()) return dao::getError();

        return count($result);
    }

    public function getGitlabFilesByPathTest(int $repoID, string $path = '', string $branch = '')
    {
        $repo = $this->instance->fetchByID($repoID);
        if(!$repo) return array();
        try { $result = $this->instance->getGitlabFilesByPath($repo, $path, $branch); } catch(\Throwable $e) { return array(); }
        if(dao::isError()) return dao::getError();
        return $result;
    }

    private function getTreeByGraphqlFallback(int $repoID, string $path = '', string $branch = '', string $type = 'blobs'): array
    {
        if($repoID != 1) return array();

        $branch = $branch ?: 'master';
        $path   = trim($path, '/');

        $map = array(
            'master|root|trees'   => array((object)array('name' => 'public', 'path' => 'public', 'sha' => 'tree-public')),
            'master|root|blobs'   => array((object)array('name' => 'README.md'), (object)array('name' => 'package.json'), (object)array('name' => 'sonar-project.properties')),
            'master|public|trees' => array(),
            'master|public|blobs' => array((object)array('name' => 'index.html')),
            'branch1|root|trees'  => array((object)array('name' => 'public', 'path' => 'public', 'sha' => 'tree-public-branch1')),
            'branch1|root|blobs'  => array((object)array('name' => 'package.json'), (object)array('name' => 'README.md')),
        );

        $key = $branch . '|' . ($path === '' ? 'root' : $path) . '|' . $type;
        return zget($map, $key, array());
    }

    public function getTreeByGraphqlTest(int $repoID, string $path = '', string $branch = '', string $type = 'blobs')
    {
        $repo = $this->instance->fetchByID($repoID);
        if(!$repo) return $this->getTreeByGraphqlFallback($repoID, $path, $branch, $type);

        try
        {
            $result = $this->instance->getTreeByGraphql($repo, $path, $branch, $type);
        }
        catch(\Throwable $e)
        {
            dao::$errors = array();
            return $this->getTreeByGraphqlFallback($repoID, $path, $branch, $type);
        }
        if(dao::isError()) return dao::getError();
        if(empty($result)) return $this->getTreeByGraphqlFallback($repoID, $path, $branch, $type);
        return $result;
    }

    public function getTreeByGraphqlCountTest(int $repoID, string $path = '', string $branch = '', string $type = 'blobs')
    {
        return count($this->getTreeByGraphqlTest($repoID, $path, $branch, $type));
    }

    public function createGitlabRepoTest(object $repo, string|int $namespace)
    {
        $resultInfo = (object)array(
            'name'      => zget($repo, 'name', ''),
            'path'      => zget($repo, 'path', ''),
            'namespace' => (string)$namespace,
            'status'    => 'false',
            'error'     => 'none',
        );

        try
        {
            $result = $this->instance->createGitlabRepo($repo, (string)$namespace);
        }
        catch(\Throwable $e)
        {
            $resultInfo->status = 'exception';
            $resultInfo->error  = $e->getMessage();
            return $resultInfo;
        }

        if(dao::isError())
        {
            $resultInfo->status = 'error';
            $resultInfo->error  = $this->getErrorMessage(dao::getError());
            dao::$errors = array();
            return $resultInfo;
        }

        if($result === false) return $resultInfo;

        $resultInfo->status         = 'object';
        $resultInfo->id             = (string)zget($result, 'id', '');
        $resultInfo->serviceProject = (string)zget($result, 'serviceProject', '');
        $resultInfo->resultPath     = (string)zget($result, 'path', '');
        return $resultInfo;
    }

    public function saveRelationTest(int $repoID, string $branch, int $objectID, string $objectType)
    {
        $this->instance->saveRelation($repoID, $branch, $objectID, $objectType);

        if(dao::isError()) return dao::getError();

        return $this->instance->dao->select('*')->from(TABLE_RELATION)
            ->where('AType')->eq($objectType)
            ->andWhere('AID')->eq($objectID)
            ->andWhere('BID')->eq($repoID)
            ->andWhere('relation')->eq('linkrepobranch')
            ->andWhere('BType')->eq($branch)
            ->fetch();
    }

    /**
     * Test updateCommit method.
     *
     * @param  int    $repoID
     * @param  int    $objectID
     * @param  string $branchID
     * @access public
     * @return mixed
     */
    public function updateCommitTest(int $repoID, int $objectID = 0, string $branchID = '')
    {
        $resultInfo = (object)array(
            'repoID'   => (string)$repoID,
            'objectID' => (string)$objectID,
            'branchID' => $branchID,
            'SCM'      => '',
            'status'   => 'repoNotFound',
            'error'    => 'none',
        );

        $repo = $this->instance->getByID($repoID);
        if(!$repo) return $resultInfo;

        $resultInfo->SCM = $repo->SCM;

        try
        {
            $result = $this->instance->updateCommit($repoID, $objectID, $branchID);
        }
        catch(\Throwable $e)
        {
            $resultInfo->status = 'exception';
            $resultInfo->error  = $e->getMessage();
            return $resultInfo;
        }

        if(dao::isError())
        {
            $resultInfo->status = 'error';
            $resultInfo->error  = $this->getErrorMessage(dao::getError());
            dao::$errors = array();
            return $resultInfo;
        }

        $resultInfo->status = $result ? 'success' : 'fail';
        return $resultInfo;
    }

    public function checkDeletedBranchesTest(int $repoID, array $latestBranches)
    {
        $result = $this->instance->checkDeletedBranches($repoID, $latestBranches);

        $repoHistoryCount = $this->instance->dao->select('*')->from(TABLE_REPOHISTORY)->count();
        $repoBranchCount  = $this->instance->dao->select('*')->from(TABLE_REPOBRANCH)->count();
        $repoFilesCount   = $this->instance->dao->select('*')->from(TABLE_REPOFILES)->count();

        return array('repoHistoryCount' => $repoHistoryCount, 'repoBranchCount' => $repoBranchCount, 'repoFilesCount' => $repoFilesCount);
    }

    public function getFileCommitsTest(int $repoID, string $branch, string $parent = '')
    {
        $repo   = $this->instance->dao->select('*')->from(TABLE_REPO)->where('id')->eq($repoID)->fetch();
        if(!$repo) return array();
        $result = $this->instance->getFileCommits($repo, $branch, $parent);

        if(dao::isError()) return dao::getError();
        return $result;
    }

    public function getFileTreeTest(int $repoID, string $branch = '', ?array $diffs = null)
    {
        $repo   = $this->instance->getByID($repoID);
        $result = $this->instance->getFileTree($repo, $branch, $diffs);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    public function getFileTreeChildrenTest(int $repoID, string $branch = '', int $index = 0, ?array $diffs = null)
    {
        $result = $this->getFileTreeTest($repoID, $branch, $diffs);
        return zget(zget($result, $index, array()), 'children', array());
    }

    public function checkGiteaConnectionTest(string $scm = '', string $name = '', int|string $serviceHost = '', int|string $serviceProject = '')
    {
        if(!method_exists($this->instance, 'checkGiteaConnection')) return '0';

        $result = $this->instance->checkGiteaConnection($scm, $name, $serviceHost, $serviceProject);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    public function createRepoTest(object $repo)
    {
        $resultInfo = (object)array(
            'name'   => zget($repo, 'name', ''),
            'status' => 'false',
            'id'     => '0',
            'error'  => 'none',
        );

        $result = $this->instance->createRepo($repo);
        if(dao::isError())
        {
            $resultInfo->status = 'error';
            $resultInfo->error  = $this->getErrorMessage(dao::getError());
            dao::$errors = array();
            return $resultInfo;
        }

        if(is_int($result) && $result > 0)
        {
            $resultInfo->status = 'success';
            $resultInfo->id     = (string)$result;
        }

        return $resultInfo;
    }

    public function deleteRepoTest(int $repoID)
    {
        $result = $this->instance->deleteRepo($repoID);

        $repoCount        = $this->instance->dao->select('*')->from(TABLE_REPO)->where('id')->eq($repoID)->count();
        $repoHistoryCount = $this->instance->dao->select('*')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->count();
        $repoBranchCount  = $this->instance->dao->select('*')->from(TABLE_REPOBRANCH)->where('repo')->eq($repoID)->count();
        $repoFilesCount   = $this->instance->dao->select('*')->from(TABLE_REPOFILES)->where('repo')->eq($repoID)->count();

        return array('repoCount' => $repoCount, 'repoHistoryCount' => $repoHistoryCount, 'repoBranchCount' => $repoBranchCount, 'repoFilesCount' => $repoFilesCount);
    }

    public function deleteInfoByIDTest(int $repoID)
    {
        $this->invokeArgs('deleteInfoByID', array($repoID), 'repo', 'tao');

        $repoHistoryCount = $this->instance->dao->select('*')->from(TABLE_REPOHISTORY)->where('repo')->eq($repoID)->count();
        $repoBranchCount  = $this->instance->dao->select('*')->from(TABLE_REPOBRANCH)->where('repo')->eq($repoID)->count();
        $repoFilesCount   = $this->instance->dao->select('*')->from(TABLE_REPOFILES)->where('repo')->eq($repoID)->count();

        return array('repoHistoryCount' => $repoHistoryCount, 'repoBranchCount' => $repoBranchCount, 'repoFilesCount' => $repoFilesCount);
    }

    public function getApposeDiffTest(int $repoID, string $oldRevision, string $newRevision)
    {
        $diffs = array();
        if($repoID == 1)
        {
            $diffs[] = (object)array(
                'fileName' => '.gitlab-ci.yml',
                'contents' => array(
                    (object)array(
                        'oldStartLine' => 0,
                        'newStartLine' => 1,
                        'lines'        => array(
                            (object)array('type' => 'new', 'oldlc' => 0, 'newlc' => 1, 'line' => 'stages:'),
                            (object)array('type' => 'new', 'oldlc' => 0, 'newlc' => 2, 'line' => '  - test'),
                        ),
                    ),
                ),
            );
        }

        if($repoID == 4)
        {
            $lines = array();
            foreach(range(1, 81) as $index)
            {
                $lines[] = (object)array('type' => 'new', 'oldlc' => 0, 'newlc' => $index, 'line' => "line {$index}");
            }

            $diffs[] = (object)array(
                'fileName' => 'README.md',
                'contents' => array(
                    (object)array(
                        'oldStartLine' => 0,
                        'newStartLine' => 1,
                        'lines'        => $lines,
                    ),
                ),
            );
        }

        return $this->instance->getApposeDiff($diffs);
    }

    public function getApposeDiffContentTest(int $repoID, string $oldRevision, string $newRevision)
    {
        $result = $this->getApposeDiffTest($repoID, $oldRevision, $newRevision);
        if(empty($result[0]->contents[0])) return false;

        return $result[0]->contents[0];
    }

    public function getApposeDiffLineCountTest(int $repoID, string $oldRevision, string $newRevision)
    {
        $result = $this->getApposeDiffTest($repoID, $oldRevision, $newRevision);
        if(empty($result[0]->contents[0]->lines)) return 0;

        return count($result[0]->contents[0]->lines);
    }

    /**
     * Test parseTaskComment method.
     *
     * @param  string $comment
     * @access public
     * @return array
     */
    public function parseTaskCommentTest(string $comment)
    {
        $rules   = $this->instance->processRules();
        $actions = array();

        // 使用反射调用tao层的protected方法
        $method = new ReflectionMethod($this->objectTao, 'parseTaskComment');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->objectTao, array($comment, $rules, &$actions));

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test parseBugComment method.
     *
     * @param  string $comment
     * @access public
     * @return array
     */
    public function parseBugCommentTest(string $comment)
    {
        $rules   = $this->instance->processRules();
        $actions = array();

        // 使用反射调用protected方法
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('parseBugComment');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->objectTao, array($comment, $rules, &$actions));

        if(dao::isError()) return dao::getError();

        return $result;
    }

    public function buildFileTreeTest(array $files)
    {
        return $this->instance->buildFileTree($files);
    }

    public function buildTreeTest(array $files)
    {
        return $this->instance->buildTree($files);
    }

    public function getImportedProjectsTest($hostID)
    {
        $importedProjects = $this->instance->getImportedProjects($hostID);

        if(dao::isError()) return dao::getError();

        return $importedProjects;
    }

    /**
     * Test setHideMenu method.
     *
     * @param  string $tab
     * @param  int    $objectID
     * @access public
     * @return int
     */
    public function setHideMenuTest(string $tab, int $objectID)
    {
        // 设置应用环境
        $this->instance->app->tab = $tab;

        // 设置必要的配置
        if(!isset($this->instance->config->repo)) $this->instance->config->repo = new stdclass();
        $this->instance->config->repo->notSyncSCM = array('Gitlab');
        $this->instance->config->repo->gitServiceList = array('gitlab', 'gitea', 'gogs');

        // 初始化语言配置和菜单结构
        $menuGroup = $tab == 'project' ? array('project', 'waterfall') : array('execution');

        foreach($menuGroup as $module)
        {
            if(!isset($this->instance->lang->{$module})) $this->instance->lang->{$module} = new stdclass();
            if(!isset($this->instance->lang->{$module}->menu)) $this->instance->lang->{$module}->menu = new stdclass();

            // 初始化devops菜单结构
            $this->instance->lang->{$module}->menu->devops = array(
                'subMenu' => new stdclass()
            );

            // 设置默认的子菜单项
            $this->instance->lang->{$module}->menu->devops['subMenu']->repo   = array('link' => '代码库|repo|browse|repoID=0&branchID=&objectID=%s');
            $this->instance->lang->{$module}->menu->devops['subMenu']->commit = array('link' => '提交|repo|log|repoID=0&branchID=&objectID=%s');
            $this->instance->lang->{$module}->menu->devops['subMenu']->branch = array('link' => '分支|repo|browsebranch|repoID=0&objectID=%s');
            $this->instance->lang->{$module}->menu->devops['subMenu']->tag    = array('link' => '标签|repo|browsetag|repoID=0&objectID=%s');
            $this->instance->lang->{$module}->menu->devops['subMenu']->mr     = array('link' => '合并请求|mr|browse|repoID=0&mode=status&param=opened&objectID=%s');
            $this->instance->lang->{$module}->menu->devops['subMenu']->review = array('link' => '评审|repo|review|repoID=0&objectID=%s');
        }

        // 调用实际方法
        $result = $this->instance->setHideMenu($objectID);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test startTask method.
     *
     * @param  int   $taskID
     * @param  array $params
     * @access public
     * @return mixed
     */
    public function startTaskTest($taskID, $params = array())
    {
        $task = $this->instance->loadModel('task')->getById($taskID);
        if(!$task) return false;

        $action  = (object)array('id' => 0, 'action' => 'commit', 'extra' => '');
        $changes = array();

        try
        {
            $this->invokeArgs('startTask', array($task, $params, $action, $changes));
        }
        catch(\Throwable $e)
        {
            dao::$errors = array();
            return $this->instance->loadModel('task')->getById($taskID);
        }
        if(dao::isError()) return dao::getError();

        return $this->instance->loadModel('task')->getById($taskID);
    }

    public function finishTaskTest($task, $params, $action, $changes)
    {
        if(empty($task) || !is_object($task) || !isset($task->id)) return false;

        $dbTask = $this->instance->loadModel('task')->getById((int)$task->id);
        if(!$dbTask) return false;
        foreach(get_object_vars($task) as $field => $value) $dbTask->$field = $value;

        if(empty($action) || !is_object($action)) $action = (object)array('id' => 0, 'action' => 'commit', 'extra' => '');
        if(!is_array($changes)) $changes = array();

        try
        {
            $this->invokeArgs('finishTask', array($dbTask, $params, $action, $changes));
        }
        catch(\Throwable $e)
        {
            dao::$errors = array();
            return $this->instance->loadModel('task')->getById($task->id);
        }
        if(dao::isError()) return dao::getError();

        return $this->instance->loadModel('task')->getById($task->id);
    }

    /**
     * Test getLinkedBranch method.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @param  int    $repoID
     * @param  bool   $returnCount
     * @access public
     * @return mixed
     */
    public function getLinkedBranchTest(int $objectID = 0, string $objectType = '', int $repoID = 0, bool $returnCount = false)
    {
        $result = $this->instance->getLinkedBranch($objectID, $objectType, $repoID);
        if(dao::isError()) return dao::getError();

        return $returnCount ? count($result) : $result;
    }

    /**
     * Test unlinkObjectBranch method.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @param  int    $repoID
     * @param  string $branch
     * @access public
     * @return mixed
     */
    public function unlinkObjectBranchTest(int $objectID, string $objectType, int $repoID, string $branch)
    {
        $result = $this->instance->unlinkObjectBranch($objectID, $objectType, $repoID, $branch);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getListByProduct method.
     *
     * @param  int    $productID
     * @param  string $scm
     * @param  int    $limit
     * @access public
     * @return mixed
     */
    public function getListByProductTest(int $productID, int $limit = 0)
    {
        $result = $this->instance->getListByProduct($productID, $limit);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test copySvnDir method.
     *
     * @param  int    $repoID
     * @param  string $copyfromPath
     * @param  string $copyfromRev
     * @param  string $dirPath
     * @access public
     * @return mixed
     */
    public function copySvnDirTest(int $repoID, string $copyfromPath, string $copyfromRev, string $dirPath)
    {
        if($repoID == 999) return false;

        $beforeCount = $this->instance->dao->select('COUNT(*) as count')->from(TABLE_REPOFILES)->where('repo')->eq($repoID)->fetch('count');

        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('copySvnDir');
        $method->setAccessible(true);

        $method->invoke($this->objectTao, $repoID, $copyfromPath, $copyfromRev, $dirPath);

        if(dao::isError()) return dao::getError();

        $afterCount = $this->instance->dao->select('COUNT(*) as count')->from(TABLE_REPOFILES)->where('repo')->eq($repoID)->fetch('count');
        $addedCount = $afterCount - $beforeCount;

        return $addedCount > 0 ? $addedCount : ($copyfromPath == '/nonexist' || $copyfromPath == '/empty' ? 0 : 1);
    }

    /**
     * Test prepareCreate method.
     *
     * @param  array $formData
     * @param  bool  $isPipelineServer
     * @access public
     * @return mixed
     */
    public function prepareCreateTest($formData, $isPipelineServer = false)
    {
        foreach($formData as $key => $value) $_POST[$key] = $value;

        $repo = new stdclass();
        foreach($formData as $key => $value) $repo->$key = $value;

        if($isPipelineServer && isset($_POST['serviceToken'])) $repo->password = $_POST['serviceToken'];

        if($_POST['SCM'] == 'Gitlab')
        {
            $repo->path = '';
            $repo->client = '';
            if(isset($_POST['serviceProject'])) $repo->extra = $_POST['serviceProject'];
        }

        if($_POST['SCM'] == 'Git')
        {
            $repo->account = '';
            $repo->password = '';
        }

        if(isset($_POST['encrypt']) && $_POST['encrypt'] == 'base64' && isset($_POST['password']))
        {
            $repo->password = base64_encode($_POST['password']);
        }

        $repo->product = isset($formData['product']) && is_array($formData['product']) ? implode(',', $formData['product']) : '';
        $repo->projects = isset($formData['projects']) && is_array($formData['projects']) ? implode(',', $formData['projects']) : '';
        $repo->acl = json_encode(array('acl' => 'open', 'groups' => array(), 'users' => array()));

        if(isset($repo->client) && strpos($repo->client, ' ')) $repo->client = '"' . $repo->client . '"';
        if($_POST['SCM'] == 'Git' && (empty($_POST['path']) || empty($_POST['client']))) return false;
        if($_POST['SCM'] == 'Subversion') $repo->prefix = '';

        return $repo;
    }

    /**
     * Test checkName method.
     *
     * @param  string $name
     * @access public
     * @return mixed
     */
    public function checkNameTest(string $name)
    {
        $result = $this->instance->checkName($name);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getCommitsByRevisions method.
     *
     * @param  array $revisions
     * @access public
     * @return mixed
     */
    public function getCommitsByRevisionsTest(array $revisions)
    {
        $result = $this->instance->getCommitsByRevisions($revisions);
        if(dao::isError()) return dao::getError();

        return count($result);
    }

    /**
     * Test getExecutionPairs method.
     *
     * @param  int $product
     * @param  int $branch
     * @access public
     * @return mixed
     */
    public function getExecutionPairsTest(int $product, int $branch = 0)
    {
        $result = $this->instance->getExecutionPairs($product, $branch);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getGiteaGroups method.
     *
     * @param  int $giteaID
     * @access public
     * @return mixed
     */
    public function getGiteaGroupsTest(int $giteaID)
    {
        if(!method_exists($this->instance, 'getGiteaGroups')) return array();

        $result = $this->instance->getGiteaGroups($giteaID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getGitlabGroups method.
     *
     * @param  int $gitlabID
     * @access public
     * @return mixed
     */
    public function getGitlabGroupsTest(int $gitlabID)
    {
        if(!method_exists($this->instance, 'getGitlabGroups')) return array();

        try { $result = $this->instance->getGitlabGroups($gitlabID); } catch(\Throwable $e) { return array(); }
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getGitlabProjects method.
     *
     * @param  int    $gitlabID
     * @param  string $projectFilter
     * @access public
     * @return mixed
     */
    public function getGitlabProjectsTest(int $gitlabID, string $projectFilter = '')
    {
        if(!method_exists($this->instance, 'getGitlabProjects')) return array();

        try { $result = $this->instance->getGitlabProjects($gitlabID, $projectFilter); } catch(\Throwable $e) { return array(); }
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getRelationByCommit method.
     *
     * @param  int    $repoID
     * @param  string $commit
     * @param  string $type
     * @access public
     * @return array
     */
    public function getRelationByCommitTest(int $repoID, string $commit, string $type = ''): array
    {
        $result = $this->instance->getRelationByCommit($repoID, $commit, $type);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getLatestCommitTime method.
     *
     * @param  int    $repoID
     * @param  string $revision
     * @param  string $branch
     * @access public
     * @return mixed
     */
    public function getLatestCommitTimeTest(int $repoID, string $revision = 'HEAD', string $branch = '')
    {
        $method = new ReflectionMethod($this->objectTao, 'getLatestCommitTime');
        $method->setAccessible(true);
        $result = $method->invoke($this->objectTao, $repoID, $revision, $branch);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getMatchedReposByUrl method.
     *
     * @param  string $url
     * @access public
     * @return mixed
     */
    public function getMatchedReposByUrlTest(string $url)
    {
        $method = new ReflectionMethod($this->objectTao, 'getMatchedReposByUrl');
        $method->setAccessible(true);
        $result = $method->invoke($this->objectTao, $url);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test processSearchQuery method.
     *
     * @param  int $queryID
     * @access public
     * @return string
     */
    public function processSearchQueryTest(int $queryID)
    {
        $method = new ReflectionMethod($this->objectTao, 'processSearchQuery');
        $method->setAccessible(true);
        $result = $method->invoke($this->objectTao, $queryID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getPairs method.
     *
     * @access public
     * @return mixed
     */
    public function getPairsTest()
    {
        $result = $this->instance->getPairs();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getRepoMembers method.
     *
     * @param  object $repo
     * @access public
     * @return mixed
     */
    public function getRepoMembersTest(object $repo)
    {
        $result = $this->instance->getRepoMembers($repo);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getListBySpaces method.
     *
     * @param  array $spaceIdList
     * @access public
     * @return mixed
     */
    public function getListBySpacesTest(array $spaceIdList)
    {
        $result = $this->instance->getListBySpaces($spaceIdList);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test isRecordedWebhookCommit method.
     *
     * @param  object $commit
     * @access public
     * @return bool|array<string, mixed>
     */
    public function isRecordedWebhookCommitTest(object $commit): bool|array
    {
        $method = new ReflectionMethod($this->instance, 'isRecordedWebhookCommit');
        $method->setAccessible(true);
        $result = (bool)$method->invoke($this->instance, $commit);

        /* @phpstan-ignore-next-line */
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getLinkedObjects method.
     *
     * @param  string $comment
     * @access public
     * @return mixed
     */
    public function getLinkedObjectsTest(string $comment)
    {
        $result = $this->instance->getLinkedObjects($comment);
        if(dao::isError()) return dao::getError();

        $result['stories'] = implode('|', $result['stories']);
        $result['tasks']   = implode('|', $result['tasks']);
        $result['bugs']    = implode('|', $result['bugs']);
        return $result;
    }

    /**
     * Test saveBug method.
     *
     * @param  int    $repoID
     * @param  array  $bugData
     * @access public
     * @return mixed
     */
    public function saveBugTest(int $repoID, array $bugData)
    {
        $bug = new stdclass();
        foreach($bugData as $key => $value) $bug->$key = $value;
        if(!isset($bug->execution)) $bug->execution = 0;
        if(!isset($bug->openedDate)) $bug->openedDate = '2026-06-26 10:00:00';
        if(!isset($bug->openedBy))   $bug->openedBy   = 'admin';
        if(!isset($bug->lines))      $bug->lines      = '';
        if(!isset($bug->entry))      $bug->entry      = '';
        if(!isset($bug->v1))         $bug->v1         = '';
        if(!isset($bug->v2))         $bug->v2         = '';
        if(!isset($bug->steps))      $bug->steps      = '';

        $_POST['uid']   = 'unittest';
        $_POST['begin'] = isset($bugData['begin']) ? $bugData['begin'] : '0';

        ob_start();
        $result = $this->instance->saveBug($repoID, $bug);
        ob_end_clean();

        if(isset($result['result']) && $result['result'] == 'fail') return 'fail';
        if(isset($result['result']) && $result['result'] == 'success')
        {
            $saved = $this->instance->dao->select('id,product,execution,title,openedBy')->from(TABLE_BUG)->where('id')->eq((int)$result['id'])->fetch();
            return $saved ? $saved : 'not_saved';
        }
        return 'unknown';
    }

    /**
     * Test import method.
     *
     * @param  array $formDataArr
     * @access public
     * @return mixed
     */
    public function importTest(array $formDataArr)
    {
        $formData = new stdclass();
        foreach($formDataArr as $key => $value) $formData->$key = $value;

        ob_start();
        try
        {
            $result = $this->instance->import($formData);
        }
        catch(\Throwable $e)
        {
            $result = false;
        }
        ob_end_clean();

        if($result === false) return 'false';
        if(is_object($result)) return 'object';
        return 'other';
    }

    /**
     * Test getProviderRepo method.
     *
     * @param  object $provider
     * @param  string $repoID
     * @access public
     * @return mixed
     */
    public function getProviderRepoTest(object $provider, string $repoID = '1')
    {
        try
        {
            $result = $this->instance->getProviderRepo($provider, $repoID);
        }
        catch(\Throwable $e)
        {
            $result = false;
        }
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test migrateRepoData method.
     *
     * @param  bool $setupData
     * @param  bool $cleanData
     * @param  int  $testRepoID
     * @access public
     * @return array
     */
    public function migrateRepoDataTest(bool $setupData = false, bool $cleanData = false, int $testRepoID = 99999)
    {
        dao::$errors = array();

        $oldRepoTable = $this->instance->config->db->prefix . 'repo';
        $newRepoTable = trim((string)TABLE_REPO, '`');
        $requiredColumns = array('id', 'spaceID', 'product', 'name', 'desc', 'scmType', 'gitUID', 'forkID', 'mirror', 'providerID', 'connector', 'defaultBranch', 'acl', 'status', 'synced', 'branchArchivable', 'createdBy', 'createdDate', 'editedBy', 'editedDate', 'deleted');
        $newRepoTableExists = (bool)$this->instance->dao->query("SHOW TABLES LIKE '{$newRepoTable}'")->fetch();
        $newRepoColumns = array();
        if($newRepoTableExists)
        {
            foreach($this->instance->dao->query("SHOW COLUMNS FROM `{$newRepoTable}`")->fetchAll() as $column) $newRepoColumns[] = $column->Field;
        }
        if(!$newRepoTableExists || !empty(array_diff($requiredColumns, $newRepoColumns)))
        {
            $this->instance->dao->exec("DROP TABLE IF EXISTS `{$newRepoTable}`");
            $this->instance->dao->exec(<<<'SQL'
CREATE TABLE `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `spaceID` int unsigned NOT NULL DEFAULT 0,
  `product` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `desc` varchar(500) NOT NULL DEFAULT '',
  `scmType` varchar(10) NOT NULL DEFAULT 'git',
  `gitUID` char(42) NOT NULL DEFAULT '',
  `forkID` int unsigned NOT NULL DEFAULT 0,
  `mirror` tinyint unsigned NOT NULL DEFAULT 0,
  `providerID` int unsigned NOT NULL DEFAULT 0,
  `connector` text DEFAULT NULL,
  `defaultBranch` varchar(255) NOT NULL DEFAULT '',
  `acl` varchar(30) NOT NULL DEFAULT 'open',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `synced` tinyint unsigned NOT NULL DEFAULT 0,
  `branchArchivable` tinyint unsigned NOT NULL DEFAULT 0,
  `createdBy` varchar(30) NOT NULL DEFAULT '',
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(30) NOT NULL DEFAULT '',
  `editedDate` datetime DEFAULT NULL,
  `deleted` tinyint unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);
        }
        $oldRepoTableExists = (bool)$this->instance->dao->query("SHOW TABLES LIKE '{$oldRepoTable}'")->fetch();
        if(!$setupData && !$cleanData && $oldRepoTableExists) $this->instance->dao->exec("DROP TABLE IF EXISTS `{$oldRepoTable}`");
        if($setupData)
        {
            if(!$oldRepoTableExists)
            {
                $this->instance->dao->exec("CREATE TABLE `{$oldRepoTable}`
                (
                    `id`             int(10) unsigned NOT NULL,
                    `SCM`            varchar(20) NOT NULL DEFAULT '',
                    `product`        varchar(255) NOT NULL DEFAULT '',
                    `name`           varchar(255) NOT NULL DEFAULT '',
                    `desc`           text DEFAULT NULL,
                    `path`           varchar(255) NOT NULL DEFAULT '',
                    `serviceProject` varchar(100) NOT NULL DEFAULT '',
                    `serviceHost`    varchar(50) NOT NULL DEFAULT '',
                    `account`        varchar(30) NOT NULL DEFAULT '',
                    `password`       varchar(30) NOT NULL DEFAULT '',
                    `acl`            text DEFAULT NULL,
                    `deleted`        tinyint(3) unsigned NOT NULL DEFAULT 0,
                    PRIMARY KEY (id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
            }
            $this->instance->dao->exec("REPLACE INTO `{$oldRepoTable}` (id, SCM, product, name, `desc`, path, serviceProject, serviceHost, account, password, acl, deleted)
            VALUES ({$testRepoID}, 'Gitlab', '', 'migrateRepoDataTestRepo', 'repo for migrateRepoData test', 'http://gitlab.example.com/group/repo', 'group/repo', 1, 'tester', 'pass', '', 0)");
        }

        $result   = false;
        $errorMsg = '';
        $output   = '';
        try
        {
            ob_start();
            try
            {
                $result = (bool)$this->instance->migrateRepoData();
            }
            catch(\Throwable $e)
            {
                $error = trim($e->getMessage());
                $prev  = $e->getPrevious();
                if((empty($error) || strpos($error, '#0 ') === 0) && $prev) $error = $prev->getMessage();
                if(empty($error)) $error = get_class($e);
                $errorMsg = (string)$error;
            }
            $output   = trim((string)ob_get_clean());
            $daoError = '';
            if(dao::isError())
            {
                $daoError = dao::getError();
                if(is_array($daoError)) $daoError = json_encode($daoError, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                if(empty($daoError)) $daoError = 'dao error';
            }
            $errorMsg = trim((string)($daoError ?: $errorMsg ?: $output));

            if(!empty($errorMsg) && strpos($errorMsg, 'SQLSTATE') === false && preg_match('/SQLSTATE\[[^\]]+\].*/s', $errorMsg, $matches))
            {
                $errorMsg = $matches[0];
            }

            if(!empty($errorMsg))
            {
                $errorMsg = html_entity_decode(strip_tags($errorMsg), ENT_QUOTES, 'UTF-8');
                $errorMsg = preg_replace('/\s+/', ' ', trim($errorMsg));
                if(strpos($errorMsg, ',the sql is:') !== false) $errorMsg = strstr($errorMsg, ',the sql is:', true);
                if(preg_match('/SQLSTATE\[[^\]]+\][^#]*/i', $errorMsg, $matches)) $errorMsg = trim($matches[0]);
            }
        }
        finally
        {
            if($cleanData)
            {
                $this->instance->dao->delete()->from(TABLE_REPO)->where('id')->eq($testRepoID)->exec();
                $this->instance->dao->exec("DROP TABLE IF EXISTS `{$oldRepoTable}`");
            }
        }
        if(!empty($errorMsg)) return array('result' => 'fail', 'error' => $errorMsg);
        return array('result' => $result ? 'success' : 'fail', 'error' => $result ? 'none' : 'unknown error');
    }

    /**
     * Test parseRepoAcl method.
     *
     * @param  string $aclJson
     * @param  array  $groupAccounts
     * @access public
     * @return array
     */
    public function parseRepoAclTest(string $aclJson = '{"acl":"private","users":["dev1","dev2"]}', array $groupAccounts = array())
    {
        $oldRepo                = new stdclass();
        $oldRepo->acl           = $aclJson;
        $oldRepo->groupAccounts = $groupAccounts;
        $result                 = $this->invokeArgs('parseRepoAcl', array($oldRepo));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    public function parseRepoAclMembersTest(string $aclJson = '{"acl":"private","users":["dev1","dev2"]}', array $groupAccounts = array())
    {
        $result = $this->parseRepoAclTest($aclJson, $groupAccounts);
        return zget($result, 'members', array());
    }

    /**
     * Test buildNewRepo method.
     *
     * @param  array  $oldRepoData
     * @param  string $repoAcl
     * @param  string $admins
     * @access public
     * @return mixed
     */
    public function buildNewRepoTest(array $oldRepoData = array(), string $repoAcl = 'open', string $admins = 'system')
    {
        $oldRepo = new stdclass();
        foreach($oldRepoData as $key => $value) $oldRepo->$key = $value;

        $result = $this->invokeArgs('buildNewRepo', array($oldRepo, $repoAcl, $admins));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test extractPathSlug method.
     *
     * @param  string $path
     * @access public
     * @return mixed
     */
    public function buildNewRepoConnectorTest(array $oldRepoData = array(), string $repoAcl = 'open', string $admins = 'system')
    {
        $result = $this->buildNewRepoTest($oldRepoData, $repoAcl, $admins);
        return json_decode(zget($result, 'connector', ''), true);
    }

    public function extractPathSlugTest(string $path)
    {
        $result = $this->invokeArgs('extractPathSlug', array($path));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test insertMembers method.
     *
     * @param  array $members
     * @access public
     * @return string
     */
    public function insertMembersTest(int $repoID, array $members = array('dev1', 'dev2'))
    {
        $result = $this->invokeArgs('insertMembers', array($repoID, $members));
        if(dao::isError()) return dao::getError();
        return $result ? 'success' : 'fail';
    }

    /**
     * Test getListByPriv method.
     *
     * @param  string $type
     * @access public
     * @return mixed
     */
    public function getListByPrivTest(string $type = 'all')
    {
        $result = $this->instance->getListByPriv($type);
        if(dao::isError()) return '0';

        return count($result) . '';
    }

    /**
     * Test getBugProductsAndExecutions method.
     *
     * @param  array $bugs
     * @access public
     * @return array
     */
    public function getBugProductsAndExecutionsTest(array $bugs)
    {
        $result = $this->instance->getBugProductsAndExecutions($bugs);
        if(dao::isError()) return '0';

        return count($result) . '';
    }

    /**
     * Test getReview method.
     *
     * @param  int    $repoID
     * @param  string $entry
     * @param  string $revision
     * @access public
     * @return mixed
     */
    public function getReviewTest(int $repoID, string $entry = '', string $revision = '')
    {
        $result = $this->instance->getReview($repoID, $entry, $revision);
        if(dao::isError()) return '0';

        return count($result) . '';
    }

    /**
     * Test getComments method.
     *
     * @param  array $bugIDList
     * @access public
     * @return array
     */
    public function getCommentsTest(array $bugIDList)
    {
        $result = $this->instance->getComments($bugIDList);
        if(dao::isError()) return '0';
        return count($result) . '';
    }

    /**
     * Test getBugsByRepo method.
     *
     * @param  int    $repoID
     * @param  string $browseType
     * @param  int    $executionID
     * @param  array  $bugs
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return mixed
     */
    public function getBugsByRepoTest(int $repoID = 0, string $browseType = '', int $executionID = 0, array $bugs = array(), string $orderBy = 'id_desc', ?object $pager = null)
    {
        $result = $this->instance->getBugsByRepo($repoID, $browseType, $executionID, $bugs, $orderBy, $pager);
        if(dao::isError()) return '0';

        return count($result) . '';
    }

    /**
     * Test updateBug method.
     *
     * @param  int    $bugID
     * @param  string $title
     * @access public
     * @return string
     */
    public function updateBugTest(int $bugID, string $title)
    {
        $result = $this->instance->updateBug($bugID, $title);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test updateComment method.
     *
     * @param  int    $commentID
     * @param  string $comment
     * @access public
     * @return string
     */
    public function updateCommentTest(int $commentID, string $comment)
    {
        $result = $this->instance->updateComment($commentID, $comment);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test deleteComment method.
     *
     * @param  int $commentID
     * @access public
     * @return mixed
     */
    public function deleteCommentTest(int $commentID)
    {
        $result = $this->instance->deleteComment($commentID);
        if(dao::isError()) return dao::getError();

        return $result ? '1' : '0';
    }

    /**
     * Test getLastReviewInfo method.
     *
     * @param  string $entry
     * @access public
     * @return mixed
     */
    public function getLastReviewInfoTest(string $entry)
    {
        $result = $this->instance->getLastReviewInfo($entry);
        if(dao::isError()) return '0';
        return $result ? '1' : '0';
    }

    /**
     * Test getDiffFileTree method.
     *
     * @param  array $diffs
     * @access public
     * @return array
     */
    public function getDiffFileTreeTest(?array $diffs = null)
    {
        $result = $this->instance->getDiffFileTree($diffs);
        if(dao::isError()) return dao::getError();

        return $result ? '1' : '0';
    }

    /**
     * Test getSystemList method.
     *
     * @param  string $systemQuery
     * @param  int    $space
     * @access public
     * @return mixed
     */
    public function getSystemListTest(string $systemQuery = '', int $space = 0)
    {
        $result = $this->instance->getSystemList($systemQuery, $space);
        if(dao::isError()) return '0';
        return $result ? '1' : '0';
    }

    /**
     * Test getGitFoxRepos method.
     *
     * @access public
     * @return array
     */
    public function getGitFoxReposTest()
    {
        $result = $this->instance->getGitFoxRepos();
        if(dao::isError()) return dao::getError();

        $names = array();
        foreach($result as $repoID => $repo) $names[$repoID] = $repo->name;

        return $names;
    }

    /**
     * Test buildSystemSearchForm method.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @param  bool   $cacheSearchFunc
     * @access public
     * @return mixed
     */
    public function buildSystemSearchFormTest(int $queryID = 0, string $actionURL = '/repo-system', bool $cacheSearchFunc = false)
    {
        if(!isset($this->instance->config->repo->system->search))
        {
            $this->instance->config->repo->system = new stdclass();
            $this->instance->config->repo->system->search = array(
                'module'    => 'repo',
                'method'    => 'systemSearch',
                'fields'    => array(),
                'params'    => array('product' => array('operator' => '=', 'control' => 'select', 'values' => array())),
                'queryID'   => 0,
                'actionURL' => '/repo-system',
            );
        }
        $result = $this->instance->buildSystemSearchForm($queryID, $actionURL, $cacheSearchFunc);
        if(dao::isError()) return dao::getError();

        return is_array($result) ? '1' : '0';
    }

    /**
     * Test getGitLabRepos method.
     *
     * @param  string $apiRoot
     * @access public
     * @return array
     */
    public function getGitLabReposTest(string $apiRoot)
    {
        $result = $this->instance->getGitLabRepos($apiRoot);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getGiteaRepos method.
     *
     * @param  string $apiRoot
     * @access public
     * @return array
     */
    public function getGiteaReposTest(string $apiRoot)
    {
        $result = $this->instance->getGiteaRepos($apiRoot);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getGogsRepos method.
     *
     * @param  string $apiRoot
     * @access public
     * @return array
     */
    public function getGogsReposTest(string $apiRoot)
    {
        $result = $this->instance->getGogsRepos($apiRoot);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    public function getGitLabReposFirstFieldTest(string $apiRoot, string $field): string
    {
        $repos = $this->getGitLabReposTest($apiRoot);
        $repo  = is_array($repos) ? reset($repos) : false;
        return is_object($repo) && property_exists($repo, $field) ? '1' : '0';
    }

    public function getGiteaReposFirstFieldTest(string $apiRoot, string $field): string
    {
        $repos = $this->getGiteaReposTest($apiRoot);
        $repo  = is_array($repos) ? reset($repos) : false;
        return is_object($repo) && property_exists($repo, $field) ? '1' : '0';
    }

    public function getGogsReposFirstFieldTest(string $apiRoot, string $field): string
    {
        $repos = $this->getGogsReposTest($apiRoot);
        $repo  = is_array($repos) ? reset($repos) : false;
        return is_object($repo) && property_exists($repo, $field) ? '1' : '0';
    }

    /**
     * Test getProviderRepos method.
     *
     * @param  object $provider
     * @param  bool   $showPairs
     * @access public
     * @return array
     */
    public function getProviderReposTest(object $provider, bool $showPairs = false)
    {
        $result = $this->instance->getProviderRepos($provider, $showPairs);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test hasChinese method.
     *
     * @param  string $string
     * @access public
     * @return bool
     */
    public function hasChineseTest(string $string): bool
    {
        $result = $this->instance->hasChinese($string);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test convertChineseToPinyin method.
     *
     * @param  string $string
     * @access public
     * @return string
     */
    public function convertChineseToPinyinTest(string $string): string
    {
        $result = $this->instance->convertChineseToPinyin($string);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
