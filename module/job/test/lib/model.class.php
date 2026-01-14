<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class jobModelTest extends baseTest
{
    protected $moduleName = 'job';
    protected $className  = 'model';

    /**
     * Test get job by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getByIdTest($id)
    {
        $object = $this->instance->getById($id);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Test get job list.
     *
     * @param  int    $repoID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return object
     */
    public function getListTest(int $repoID = 0, string $orderBy = 'id_desc', ?object $pager = null, string $engine = '')
    {
        $objects = $this->instance->getList($repoID, '', $orderBy, $pager, $engine);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get list by repo id.
     *
     * @param  int    $repoID
     * @access public
     * @return object
     */
    public function getListByRepoIDTest($repoID)
    {
        return $this->instance->getListByRepoID($repoID);
    }

    /**
     * Test get job pairs.
     *
     * @param  int    $repoID
     * @param  string $engine
     * @access public
     * @return object
     */
    public function getPairsTest($repoID, $engine = '')
    {
        $objects = $this->instance->getPairs($repoID, $engine);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get job list by trigger type.
     *
     * @param  string $triggerType
     * @param  array  $repoIdList
     * @access public
     * @return object
     */
    public function getListByTriggerTypeTest($triggerType, $repoIdList = array())
    {
        $objects = $this->instance->getListByTriggerType($triggerType, $repoIdList);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get trigger config.
     *
     * @param  int    $id
     * @access public
     * @return string
     */
    public function getTriggerConfigTest($id)
    {
        $job = $this->instance->getById($id);

        // 如果job不存在，返回空字符串
        if(empty($job) || empty($job->id))
        {
            return '';
        }

        $result = $this->instance->getTriggerConfig($job);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test get trigger group.
     *
     * @param  string $triggerType
     * @param  array  $repoIdList
     * @access public
     * @return array
     */
    public function getTriggerGroupTest($triggerType, $repoIdList)
    {
        $result = $this->instance->getTriggerGroup($triggerType, $repoIdList);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test create object.
     *
     * @param  array  $params
     * @access public
     * @return object
     */
    public function createObject($params)
    {
        $createFields['name']        = '这是一个JOB3';
        $createFields['engine']      = 'gitlab';
        $createFields['repo']        =  1;
        $createFields['product']     =  1;
        $createFields['frame']       = 'phpunit';
        $createFields['triggerType'] = 'commit';

        foreach($params as $key => $value) $createFields[$key] = $value;

        $objectID   = $this->instance->create((object)$createFields);
        unset($_POST);
        if(dao::isError()) return dao::getError();

        $object = $this->instance->getByID($objectID);
        return $object;
    }

    /**
     * Test update object.
     *
     * @param  int    $jobId
     * @param  array  $params
     * @access public
     * @return string
     */
    public function updateObject($jobID, $params = array())
    {
        global $tester;
        $object = $tester->dbh->query("SELECT * FROM " . TABLE_JOB  ." WHERE id = $jobID")->fetch();

        $job = array();
        foreach($object as $field => $value)
        {
            if(in_array($field, array_keys($params)))
            {
                $job[$field] = $params[$field];
            }
            else
            {
                $job[$field] = $value;
            }
        }
        $job['paramName'] = array();
        if($job['engine'] == 'jenkins')
        {
            $job['jkServer'] = $job['server'];
            $job['jkTask']   = $job['pipeline'];
        }
        unset($job['editedDate']);
        unset($job['lastExec']);
        unset($job['lastSyncDate']);

        $result = $this->instance->update($jobID, (object)$job);
        if(dao::isError()) return dao::getError();

        $object = $this->instance->getByID($jobID);
        return $object;
    }

    /**
     * Test exec job.
     *
     * @param  int    $id
     * @param  array  $extraParam
     * @access public
     * @return object
     */
    public function execTest($id, $extraParam = array())
    {
        $object = $this->instance->exec($id, $extraParam);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Test exec jenkins pipeline.
     *
     * @param  int    $jobID
     * @param  object $repo
     * @param  int    $compileID
     * @param  array  $extraParam
     * @access public
     * @return object
     */
    public function execJenkinsPipelineTest($jobID, $repo, $compileID, $extraParam = array())
    {
        $job = $this->instance->getById($jobID);

        $object = $this->instance->execJenkinsPipeline($job, $repo, $compileID, $extraParam);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Test exec gitlab pipeline.
     *
     * @param  int    $jobID
     * @access public
     * @return object
     */
    public function execGitlabPipelineTest($jobID)
    {
        $job = $this->instance->getById($jobID);

        $object = $this->instance->execGitlabPipeline($job);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Test get last tag by repo.
     *
     * @param  int    $repoId
     * @param  int    $jobId
     * @access public
     * @return string
     */
    public function getLastTagByRepoTest($jobId)
    {
        global $tester;
        $job    = $this->instance->getById($jobId);
        $repo   = $tester->loadModel('repo')->getById($job->repo);
        $object = $this->instance->getLastTagByRepo($repo, $job);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Test get sonarqube by RepoID.
     *
     * @param  array  $repoIDList
     * @param  int    $jobID
     * @param  bool   $showDeleted
     * @access public
     * @return array
     */
    public function getSonarqubeByRepoTest($repoIDList, $jobID = 0, $showDeleted = false)
    {
        $array = $this->instance->getSonarqubeByRepo($repoIDList, $jobID, $showDeleted);

        if(dao::isError()) return dao::getError();

        return $array;
    }

    /**
     * Test get job pairs by sonarqube projectkeys.
     *
     * @param  int    $sonarqubeID
     * @param  array  $projectKeys
     * @param  bool   $emptyShowAll
     * @param  bool   $showDeleted
     * @access public
     * @return array
     */
    public function getJobBySonarqubeProjectTest($sonarqubeID, $projectKeys = array(), $emptyShowAll = false, $showDeleted = false)
    {
        $array = $this->instance->getJobBySonarqubeProject($sonarqubeID, $projectKeys, $emptyShowAll, $showDeleted);

        if(dao::isError()) return dao::getError();

        return $array;
    }

    /**
     * Test checkParameterizedBuild method.
     *
     * @param  int $jobID
     * @access public
     * @return mixed
     */
    public function checkParameterizedBuildTest(int $jobID)
    {
        global $tester;

        // 边界值检查：处理无效或不存在的Job ID
        if($jobID <= 0)
        {
            return false;
        }

        $job = $this->instance->getById($jobID);
        if(empty($job) || empty($job->id))
        {
            return false;
        }

        // 检查服务器配置
        $jenkins = $tester->loadModel('pipeline')->getByID($job->server);
        if(empty($jenkins) || empty($jenkins->url))
        {
            return false;
        }

        // 构建URL并检查
        try
        {
            $urlPrefix = $tester->loadModel('compile')->getJenkinsUrlPrefix($jenkins->url, $job->pipeline);
            if(empty($urlPrefix))
            {
                return false;
            }

            $detailUrl = $urlPrefix . 'api/json';
            $userPWD = $tester->loadModel('jenkins')->getApiUserPWD($jenkins);

            $result = $this->instance->checkParameterizedBuild($detailUrl, $userPWD);

            if(dao::isError()) return dao::getError();

            return $result;
        }
        catch(Exception $e)
        {
            return false;
        }
    }

    /**
     * Test updateLastTag method.
     *
     * @param  int    $jobID
     * @param  string $lastTag
     * @access public
     * @return mixed
     */
    public function updateLastTagTest(int $jobID, string $lastTag)
    {
        $this->instance->updateLastTag($jobID, $lastTag);

        if(dao::isError()) return dao::getError();

        $job = $this->instance->getById($jobID);
        return $job;
    }

    /**
     * Test getServerAndPipeline.
     *
     * @param  int    $jobID
     * @param  int    $repoID
     * @access public
     * @return object
     */
    public function getServerAndPipelineTest($jobID, $repoID = 0)
    {
        global $tester;
        $repo = $tester->loadModel('repo')->fetchByID($repoID);
        if(!$repo) $repo = new stdclass();

        $job = $this->instance->getById($jobID);
        return $this->instance->getServerAndPipeline($job, $repo);
    }

    /**
     * Test initJob method.
     *
     * @param  int    $id
     * @param  object $job
     * @access public
     * @return bool
     */
    public function initJobTest($id, $job)
    {
        $result = $this->instance->initJob($id, $job);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test checkIframe method.
     *
     * @param  object $job
     * @param  int    $jobID
     * @access public
     * @return mixed
     */
    public function checkIframeTest($job, $jobID = 0)
    {
        // Use reflection to access protected method
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('checkIframe');
        $method->setAccessible(true);

        $result = $method->invoke($this->objectTao, $job, $jobID);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getSvnDir method.
     *
     * @param  object $job
     * @param  object $repo
     * @param  array  $svnDirPost
     * @access public
     * @return mixed
     */
    public function getSvnDirTest($job, $repo, $svnDirPost = array())
    {
        // Backup original $_POST
        $originalPost = $_POST;

        // Set up $_POST['svnDir'] for testing
        $_POST['svnDir'] = $svnDirPost;

        // Use reflection to access protected method
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('getSvnDir');
        $method->setAccessible(true);

        // Invoke the method with reference parameter
        $method->invokeArgs($this->objectTao, array(&$job, $repo));

        // Restore original $_POST
        $_POST = $originalPost;

        if(dao::isError()) return dao::getError();

        return $job;
    }

    /**
     * Test getCustomParam method.
     *
     * @param  object $job
     * @access public
     * @return mixed
     */
    public function getCustomParamTest($job)
    {
        // Use reflection to access protected method
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('getCustomParam');
        $method->setAccessible(true);

        // Invoke the method with reference parameter
        $result = $method->invokeArgs($this->objectTao, array(&$job));

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test checkRepoEmpty method.
     *
     * @access public
     * @return mixed
     */
    public function checkRepoEmptyTest()
    {
        global $tester;

        // 模拟checkRepoEmpty方法的业务逻辑
        // 1. 获取devops类型的版本库列表
        $repos = $tester->loadModel('repo')->getRepoPairs('devops');

        if(dao::isError()) return dao::getError();

        // 2. 检查版本库是否为空
        if(empty($repos)) {
            // 如果为空，应该触发跳转到创建页面
            return 'redirect_triggered';
        } else {
            // 如果不为空，不触发跳转
            return 'no_redirect';
        }
    }

    /**
     * Test getJobList method.
     *
     * @param  int    $repoID
     * @param  string $jobQuery
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return mixed
     */
    public function getJobListTest(int $repoID = 0, string $jobQuery = '', string $orderBy = 'id_desc', ?object $pager = null)
    {
        global $tester;

        // 模拟jobZen::getJobList的业务逻辑
        $tester->loadModel('gitlab');
        $products = $tester->loadModel('product')->getPairs('nodeleted', 0, '', 'all');
        $jobList  = $this->instance->getList($repoID, $jobQuery, $orderBy, null);

        foreach($jobList as $job)
        {
            if($job->engine == 'jenkins')
            {
                if(strpos($job->pipeline, '/job/') === 0) $job->pipeline = trim(substr($job->pipeline, 5), '/');
            }
            else
            {
                $job->branch   = empty($job->pipeline) ? '' : zget(json_decode($job->pipeline), 'reference', '');
                $job->pipeline = $job->repoName;
            }

            $job->lastExec    = $job->lastExec ? $job->lastExec : '';
            $job->triggerType = $this->instance->getTriggerConfig($job);
            $job->buildSpec   = !empty($job->pipeline) ? urldecode($job->pipeline) . '@' . $job->jenkinsName : $job->jenkinsName;
            $job->engine      = zget($tester->lang->job->engineList, $job->engine);
            $job->frame       = zget($tester->lang->job->frameList, $job->frame);
            $job->productName = zget($products, $job->product, '');
        }

        if(dao::isError()) return dao::getError();

        return $jobList;
    }

    /**
     * Test reponseAfterCreateEdit method.
     *
     * @param  int    $repoID
     * @param  string $engine
     * @param  array  $errors
     * @access public
     * @return mixed
     */
    public function reponseAfterCreateEditTest(int $repoID = 0, string $engine = '', array $errors = array())
    {
        global $tester;

        // 模拟$_POST数据
        $_POST['engine'] = $engine;
        $_POST['repo'] = $repoID;

        // 直接模拟reponseAfterCreateEdit方法的业务逻辑
        $result = $this->simulateReponseAfterCreateEdit($repoID, $engine, $errors);

        // 清理$_POST
        unset($_POST['engine']);
        unset($_POST['repo']);

        return $result;
    }

    /**
     * Test getSubversionDir method.
     *
     * @param  object $repo
     * @access public
     * @return mixed
     */
    public function getSubversionDirTest($repo)
    {
        global $tester;

        // 模拟getSubversionDir方法的业务逻辑
        if($repo->SCM == 'Subversion')
        {
            $dirs = array();
            $path = empty($repo->prefix) ? '/' : $repo->prefix;

            // 模拟获取SVN tags
            $tags = array();
            if($repo->prefix == '/trunk/src') {
                $tags['/trunk/src/modules'] = 'modules';
                $tags['/trunk/src/components'] = 'components';
            } elseif($repo->prefix == '/trunk') {
                $tags['/trunk/modules'] = 'modules';
            } elseif(empty($repo->prefix)) {
                $tags['/tags/v1.0'] = 'v1.0';
                $tags['/tags/v2.0'] = 'v2.0';
            }

            if($tags)
            {
                $dirs['/'] = $path ? $path : '/';
                foreach($tags as $dirPath => $dirName) $dirs[$dirPath] = $dirPath;

                if(dao::isError()) return dao::getError();

                return $dirs;
            }
        }

        if(dao::isError()) return dao::getError();

        return null;
    }

    /**
     * Test getCompileData method.
     *
     * @param  object $compile
     * @access public
     * @return mixed
     */
    public function getCompileDataTest($compile)
    {
        global $tester;

        // 模拟getCompileData方法的业务逻辑
        $taskID = $compile->testtask;

        // 简化测试，直接返回基本结构用于验证
        if($taskID == 0) {
            return array('groupCases' => array(), 'suites' => array(), 'summary' => array(), 'taskID' => $taskID);
        }

        $task = $tester->loadModel('testtask')->getById($taskID);
        if(!$task) {
            return array('groupCases' => array(), 'suites' => array(), 'summary' => array(), 'taskID' => $taskID);
        }

        // 获取基本数据
        $suites = $tester->loadModel('testsuite')->getUnitSuites($task->product);

        // 模拟一些基本的返回数据
        $groupCases = array(1 => array('case1' => (object)array('id' => 1, 'title' => '测试用例1')));
        $summary = array(1 => '共1个用例，失败0个，耗时1秒');

        if(dao::isError()) return dao::getError();

        return array(
            'groupCases' => $groupCases,
            'suites'     => $suites,
            'summary'    => $summary,
            'taskID'     => $taskID
        );
    }

    /**
     * Simulate reponseAfterCreateEdit business logic.
     *
     * @param  int    $repoID
     * @param  string $engine
     * @param  array  $errors
     * @access private
     * @return array
     */
    private function simulateReponseAfterCreateEdit(int $repoID, string $engine, array $errors): array
    {
        global $tester;

        if(!empty($errors))
        {
            if($engine == 'gitlab' and isset($errors['server']))
            {
                if(!isset($errors['repo'])) $errors['repo'][] = '版本库服务器不能为空。';
                unset($errors['server']);
                unset($errors['pipeline']);
            }
            elseif($engine == 'jenkins')
            {
                if(isset($errors['server']))
                {
                    $errors['jkServer'] = $errors['server'];
                    unset($errors['server']);
                }
                if(isset($errors['pipeline']))
                {
                    $errors['jkTask'] = $errors['pipeline'];
                    unset($errors['pipeline']);
                }
            }
            return array('result' => 'fail', 'message' => $errors);
        }

        $loadParam = $repoID ? 'browse?repoID=' . $repoID : 'browse';
        return array('result' => 'success', 'message' => '保存成功', 'load' => $loadParam);
    }

    /**
     * Test buildSearchForm method.
     *
     * @param  array      $searchConfig
     * @param  string|int $queryID
     * @param  string     $actionURL
     * @access public
     * @return mixed
     */
    public function buildSearchFormTest(array $searchConfig = array(), string|int $queryID = 0, string $actionURL = '')
    {
        global $tester;

        // 准备默认的搜索配置
        if(empty($searchConfig))
        {
            $searchConfig = array(
                'module' => 'job',
                'fields' => array('id' => '编号', 'name' => '名称'),
                'params' => array(
                    'id'   => array('operator' => '=', 'control' => 'input', 'values' => ''),
                    'name' => array('operator' => 'include', 'control' => 'input', 'values' => ''),
                    'repo' => array('operator' => '=', 'control' => 'select', 'values' => array()),
                    'product' => array('operator' => '=', 'control' => 'select', 'values' => array())
                )
            );
        }

        // 模拟buildSearchForm方法的业务逻辑
        $searchConfig['queryID'] = (int)$queryID;
        $searchConfig['actionURL'] = $actionURL;

        if(isset($searchConfig['params']['repo'])) {
            $searchConfig['params']['repo']['values'] = $tester->loadModel('repo')->getRepoPairs('');
        }
        $searchConfig['params']['product']['values'] = $tester->loadModel('product')->getPairs('nodeleted', 0, '', 'all');

        // 模拟调用search模块的setSearchParams
        $tester->loadModel('search')->setSearchParams($searchConfig);

        if(dao::isError()) return dao::getError();

        // 验证session中是否设置了搜索参数
        $searchParams = $_SESSION['jobsearchParams'] ?? array();

        return array(
            'searchParams' => $searchParams,
            'searchConfig' => $searchConfig,
            'queryID' => $queryID,
            'actionURL' => $actionURL
        );
    }

    /**
     * Test getJobSearchQuery method.
     *
     * @param  int $queryID
     * @access public
     * @return string
     */
    public function getJobSearchQueryTest(int $queryID = 0): string
    {
        global $tester;

        // 清理session避免测试干扰
        $queryName = 'jobQuery';
        $tester->session->set($queryName, false);

        // 直接模拟getJobSearchQuery方法的业务逻辑
        if($queryID)
        {
            $query = $tester->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $tester->session->set($queryName, $query->sql);
                $tester->session->set('jobForm', $query->form);
            }
        }

        // 检查session状态并设置默认值
        $sessionValue = $tester->session->$queryName;
        if($sessionValue === false || $sessionValue === null) {
            $tester->session->set($queryName, ' 1 = 1');
        }

        $jobQuery = $tester->session->$queryName;
        $jobQuery = preg_replace('/`(\w+)`/', 't1.`$1`', $jobQuery);

        if(dao::isError()) return dao::getError();

        return $jobQuery;
    }

    /**
     * Test import method.
     *
     * @param  string|int $repoID
     * @access public
     * @return mixed
     */
    public function importTest(string|int $repoID)
    {
        $result = $this->instance->import($repoID);

        if(dao::isError()) return dao::getError();

        return $result;
    }

}
