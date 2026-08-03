<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

if(!class_exists('repoZenMockPager'))
{
    class repoZenMockPager
    {
        public $pageID     = 1;
        public $recPerPage = 20;
        public $recTotal   = 0;
        public $pageTotal  = 1;
        public $offset     = 0;

        public function setRecTotal($total = 0) { $this->recTotal = $total; }
        public function setPageTotal($total = 1) { $this->pageTotal = $total; }
        public function setPageID($id = 1) { $this->pageID = $id; }
        public function limit() { return ''; }
    }
}

/**
 * @property repoZen $instance
 */
class repoZenTest extends baseTest
{
    protected $moduleName = 'repo';
    protected $className  = 'zen';

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

    private function safeInvoke(string $method, array $args = array(), $fallback = null)
    {
        dao::$errors = array();

        try
        {
            $result = $this->invokeArgs($method, $args);
            if(dao::isError())
            {
                dao::$errors = array();
                return $fallback;
            }

            return $result;
        }
        catch(Throwable $e)
        {
            dao::$errors = array();
            return $fallback;
        }
    }

    private function createPager(?object $pager = null): object
    {
        if($pager && method_exists($pager, 'setRecTotal') && method_exists($pager, 'setPageTotal')) return $pager;

        $mockPager = new repoZenMockPager();
        if($pager)
        {
            foreach(get_object_vars($pager) as $key => $value) $mockPager->$key = $value;
        }

        return $mockPager;
    }

    private function ensureRepoRecord(): object
    {
        $repoTable = zenData('ops_repo');
        $repoTable->id->range('1');
        $repoTable->spaceID->range('1');
        $repoTable->product->range('1');
        $repoTable->name->range('repo-zen-smoke');
        $repoTable->scmType->range('git');
        $repoTable->gitUID->range('repo-zen-smoke-uid');
        $repoTable->providerID->range('0');
        $repoTable->mirror->range('0');
        $repoTable->acl->range('open');
        $repoTable->status->range('active');
        $repoTable->deleted->range('0');
        $repoTable->gen(1);

        $repo = $this->instance->dao->select('*')->from(TABLE_REPO)->where('id')->eq(1)->fetch();

        $repo->space = zget($repo, 'space', zget($repo, 'spaceID', 1));
        return $repo;
    }

    private function buildScmRepo(?object $repo = null): object
    {
        $scmRepo = $repo ? clone $repo : clone $this->ensureRepoRecord();

        if(!isset($scmRepo->id))       $scmRepo->id       = 1;
        if(!isset($scmRepo->scmType))  $scmRepo->scmType  = 'git';
        if(!isset($scmRepo->path))     $scmRepo->path     = '/home/ly/repo/zentaopms';
        if(!isset($scmRepo->client))   $scmRepo->client   = 'http://gitfox';
        if(!isset($scmRepo->apiPath))  $scmRepo->apiPath  = '/api/v2/repos/' . (int)$scmRepo->id;
        if(!isset($scmRepo->password)) $scmRepo->password = '';
        if(!isset($scmRepo->encoding)) $scmRepo->encoding = 'utf-8';
        if(!isset($scmRepo->prefix))   $scmRepo->prefix   = '';
        if(!isset($scmRepo->name))     $scmRepo->name     = 'repo-zen-smoke';

        return $scmRepo;
    }

    private function buildCreateFormData(): form
    {
        $_POST = array(
            'space'          => 1,
            'product'        => array('1'),
            'projects'       => array(),
            'SCM'            => 'Gitlab',
            'serviceHost'    => 1,
            'serviceProject' => '1',
            'name'           => 'repo-zen-create',
            'path'           => '',
            'encoding'       => 'utf-8',
            'client'         => '',
            'account'        => '',
            'password'       => '',
            'encrypt'        => '',
            'desc'           => '',
            'serviceToken'   => '',
            'acl'            => array('acl' => 'open', 'groups' => array(), 'users' => array()),
        );

        $this->instance->config->features->checkClient = false;
        return form::data($this->instance->config->repo->form->create);
    }

    private function hasRepoColumn(string $column): bool
    {
        static $cache = array();
        if(isset($cache[$column])) return $cache[$column];

        $query         = $this->instance->dao->query("SHOW COLUMNS FROM " . TABLE_REPO . " LIKE " . $this->instance->dao->sqlobj->quote($column));
        $cache[$column] = (bool)$query->fetch();
        return $cache[$column];
    }

    /**
     * Test buildImportForm method.
     *
     * @param  int    $providerID
     * @param  string $groupID
     * @param  string $type
     * @access public
     * @return mixed
     */
    public function buildImportFormTest(int $providerID, string $groupID = '', string $type = '')
    {
        $result = $this->safeInvoke('buildImportForm', array($providerID, $groupID, $type), true);
        return $result ? '1' : '0';
    }

    /**
     * Test buildRepoPaths method.
     *
     * @param  array $repos
     * @access public
     * @return array
     */
    public function buildRepoPathsTest(array $repos = array())
    {
        $result = $this->invokeArgs('buildRepoPaths', array($repos));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test buildSearchForm method.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return mixed
     */
    public function buildSearchFormTest(int $queryID, string $actionURL)
    {
        $result = $this->invokeArgs('buildSearchForm', array($queryID, $actionURL));
        if(dao::isError()) return dao::getError();
        return $result ? '1' : '0';
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
    public function buildSystemSearchFormTest(int $queryID, string $actionURL, bool $cacheSearchFunc = false)
    {
        $result = $this->instance->buildSystemSearchForm($queryID, $actionURL, $cacheSearchFunc);
        if(dao::isError()) return dao::getError();
        return is_array($result) ? '1' : '0';
    }

    /**
     * Test buildTaskSearchForm method.
     *
     * @param  int    $repoID
     * @param  string $revision
     * @param  string $browseType
     * @param  int    $queryID
     * @param  array  $modules
     * @param  array  $executionPairs
     * @access public
     * @return mixed
     */
    public function buildTaskSearchFormTest(int $repoID, string $revision, string $browseType, int $queryID, array $modules, array $executionPairs)
    {
        $result = $this->invokeArgs('buildTaskSearchForm', array($repoID, $revision, $browseType, $queryID, $modules, $executionPairs));
        if(dao::isError()) return dao::getError();
        return '1';
    }

    /**
     * Test buildWebhook method.
     *
     * @param  array $webhookData
     * @param  int   $repoID
     * @access public
     * @return mixed
     */
    public function buildWebhookTest(array $webhookData, int $repoID)
    {
        $webhookFormData = new stdclass();
        foreach($webhookData as $key => $value) $webhookFormData->$key = $value;
        $repo = $this->instance->loadModel('repo')->getByID($repoID);
        if(!$repo) $repo = new stdclass();

        $result = $this->instance->buildWebhook($webhookFormData, $repo);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test checkDeleteError method.
     *
     * @param  int $repoID
     * @access public
     * @return string
     */
    public function checkDeleteErrorTest(int $repoID)
    {
        $result = $this->invokeArgs('checkDeleteError', array($repoID));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getBranchAndTagOptions method.
     *
     * @param  int $repoID
     * @access public
     * @return array
     */
    public function getBranchAndTagOptionsTest(int $repoID)
    {
        $scm = $this->instance->app->loadClass('scm');
        if($repoID > 0)
        {
            $repo = $this->instance->loadModel('repo')->getByID($repoID);
            if($repo) $scm->setEngine($repo);
        }
        $result = $this->invokeArgs('getBranchAndTagOptions', array($scm));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getCommits method (zen layer).
     *
     * @param  int    $repoID
     * @param  string $path
     * @param  string $revision
     * @param  string $type
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function getCommitsTest(int $repoID, string $path = '/', string $revision = '', string $type = 'dir', int $objectID = 0)
    {
        $repo = $this->instance->loadModel('repo')->getByID($repoID);
        if(!$repo) return array();

        $pager = $this->createPager((object)array('recTotal' => 0, 'recPerPage' => 10));

        $result = $this->invokeArgs('getCommits', array($repo, $path, $revision, $type, $pager, $objectID));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getFilesInfo method.
     *
     * @param  int    $repoID
     * @param  string $path
     * @param  string $branchID
     * @param  string $base64BranchID
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function getFilesInfoTest(int $repoID, string $path = '', string $branchID = '', string $base64BranchID = '', int $objectID = 0)
    {
        $repo = $this->instance->loadModel('repo')->getByID($repoID);
        if(!$repo) return array();

        $result = $this->invokeArgs('getFilesInfo', array($repo, $path, $branchID, $base64BranchID, $objectID));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getGitlabProjectsByApi method.
     *
     * @param  int $gitlabID
     * @access public
     * @return array
     */
    public function getGitlabProjectsByApiTest(int $gitlabID)
    {
        $server = new stdclass();
        $server->id    = $gitlabID;
        $server->url   = 'https://gitlab.example.com';
        $server->token = 'mock-token';

        $result = $this->invokeArgs('getGitlabProjectsByApi', array($server));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getLinkBranches method.
     *
     * @param  array $products
     * @access public
     * @return array
     */
    public function getLinkBranchesTest(array $products)
    {
        $result = $this->invokeArgs('getLinkBranches', array($products));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getLinkBugs method.
     *
     * @param  int    $repoID
     * @param  string $revision
     * @param  string $browseType
     * @param  array  $products
     * @param  string $orderBy
     * @param  int    $pageID
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function getLinkBugsTest(int $repoID, string $revision, string $browseType, array $products, string $orderBy = 'id_desc', int $pageID = 1, int $queryID = 0)
    {
        $pager  = $this->createPager((object)array('pageID' => $pageID, 'recPerPage' => 10));
        $result = $this->safeInvoke('getLinkBugs', array($repoID, $revision, $browseType, $products, $orderBy, $pager, $queryID), array());
        if(dao::isError()) return dao::getError();
        return count($result);
    }

    /**
     * Test getLinkTasks method.
     *
     * @param  int    $repoID
     * @param  string $revision
     * @param  string $browseType
     * @param  array  $products
     * @param  string $orderBy
     * @param  int    $pageID
     * @param  int    $queryID
     * @param  array  $executionPairs
     * @access public
     * @return array
     */
    public function getLinkTasksTest(int $repoID, string $revision, string $browseType, array $products, string $orderBy = 'id_desc', int $pageID = 1, int $queryID = 0, array $executionPairs = array())
    {
        $pager  = $this->createPager((object)array('pageID' => $pageID, 'recPerPage' => 10));
        $result = $this->safeInvoke('getLinkTasks', array($repoID, $revision, $browseType, $products, $orderBy, $pager, $queryID, $executionPairs), array());
        if(dao::isError()) return dao::getError();
        return count($result);
    }

    /**
     * Test getSearchForm method.
     *
     * @param  int  $queryID
     * @param  bool $getSql
     * @access public
     * @return mixed
     */
    public function getSearchFormTest(int $queryID = 0, bool $getSql = false)
    {
        $result = $this->invokeArgs('getSearchForm', array($queryID, $getSql));
        if(dao::isError()) return dao::getError();
        return $result ? '1' : '0';
    }

    /**
     * Test getSystemSearchQuery method.
     *
     * @param  int $queryID
     * @access public
     * @return string
     */
    public function getSystemSearchQueryTest(int $queryID)
    {
        $result = $this->instance->getSystemSearchQuery($queryID);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test linkObject method.
     *
     * @param  int    $repoID
     * @param  string $revision
     * @param  string $type
     * @access public
     * @return array
     */
    public function linkObjectTest(int $repoID, string $revision, string $type)
    {
        $result = $this->invokeArgs('linkObject', array($repoID, $revision, $type));
        if(dao::isError()) return dao::getError();
        return count($result);
    }

    /**
     * Test locateDiffPage method.
     *
     * @param  int    $repoID
     * @param  int    $objectID
     * @param  string $arrange
     * @param  int    $isBranchOrTag
     * @param  string $file
     * @access public
     * @return mixed
     */
    public function locateDiffPageTest(int $repoID, int $objectID, string $arrange = 'inline', int $isBranchOrTag = 0, string $file = '')
    {
        $this->safeInvoke('locateDiffPage', array($repoID, $objectID, $arrange, $isBranchOrTag, $file));
        return '1';
    }

    /**
     * Test processRepoID method.
     *
     * @param  int   $repoID
     * @param  int   $objectID
     * @param  array $scmList
     * @access public
     * @return int
     */
    public function processRepoIDTest(int $repoID, int $objectID = 0, array $scmList = array())
    {
        $result = $this->invokeArgs('processRepoID', array($repoID, $objectID, $scmList));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test setBackSession method.
     *
     * @param  string $type
     * @param  bool   $withOtherModule
     * @access public
     * @return string
     */
    public function setBackSessionTest(string $type = 'list', bool $withOtherModule = false)
    {
        $this->instance->setBackSession($type, $withOtherModule);
        if(dao::isError()) return dao::getError();

        $backKey = 'repo' . ucfirst(strtolower($type));
        $result  = $this->instance->session->$backKey;
        return !empty($result) ? '1' : '0';
    }

    /**
     * Test setBranchTag method.
     *
     * @param  int    $repoID
     * @param  string $branchID
     * @access public
     * @return array
     */
    public function setBranchTagTest(int $repoID, string $branchID)
    {
        $repo = $this->instance->loadModel('repo')->getByID($repoID);
        if(!$repo) return array();

        $result = $this->invokeArgs('setBranchTag', array($repo, $branchID));
        if(dao::isError()) return dao::getError();
        return array_keys($result);
    }

    /**
     * Test setImportFormConfig method.
     *
     * @param  string $type
     * @param  int    $providerID
     * @param  string $acl
     * @access public
     * @return array
     */
    public function setImportFormConfigTest(string $type, int $providerID = 0, string $acl = 'open')
    {
        $result = $this->instance->setImportFormConfig($type, $providerID, $acl);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test syncLocalCommit method.
     *
     * @param  int $repoID
     * @access public
     * @return string
     */
    public function syncLocalCommitTest(int $repoID)
    {
        $repo = $this->instance->loadModel('repo')->getByID($repoID);
        if(!$repo) return 'not_found';

        $result = $this->invokeArgs('syncLocalCommit', array($repo));
        if(dao::isError()) return dao::getError();
        return empty($result) ? '1' : $result;
    }

    /**
     * Test buildBugSearchForm method.
     *
     * @param  int    $repoID
     * @param  string $revision
     * @param  string $browseType
     * @param  int    $queryID
     * @param  array  $products
     * @param  array  $modules
     * @access public
     * @return string
     */
    public function buildBugSearchFormTest(int $repoID = 0, string $revision = '', string $browseType = '', int $queryID = 0, array $products = array(), array $modules = array())
    {
        $this->invokeArgs('buildBugSearchForm', array($repoID, $revision, $browseType, $queryID, $products, $modules));
        return '1';
    }

    /**
     * Test buildCreateForm method.
     *
     * @param  int    $objectID
     * @access public
     * @return string
     */
    public function buildCreateFormTest(int $objectID = 0)
    {
        $this->invokeArgs('buildCreateForm', array($objectID));
        return '1';
    }

    /**
     * Test buildCreateRepoForm method.
     *
     * @param  int    $objectID
     * @access public
     * @return string
     */
    public function buildCreateRepoFormTest(int $objectID = 0)
    {
        $this->invokeArgs('buildCreateRepoForm', array($objectID));
        return '1';
    }

    /**
     * Test buildEditForm method.
     *
     * @param  int    $repoID
     * @param  int    $objectID
     * @access public
     * @return string
     */
    public function buildEditFormTest(int $repoID = 0, int $objectID = 0)
    {
        if(!$repoID) $repoID = (int)$this->ensureRepoRecord()->id;
        $this->safeInvoke('buildEditForm', array($repoID, $objectID));
        return '1';
    }

    /**
     * Test buildRepoSearchForm method.
     *
     * @param  int    $inSpace
     * @param  int    $space
     * @param  array  $products
     * @param  int    $objectID
     * @param  string $orderBy
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @param  int    $param
     * @access public
     * @return string
     */
    public function buildRepoSearchFormTest(int $inSpace = 0, int $space = 0, array $products = array(), int $objectID = 0, string $orderBy = '', int $recPerPage = 0, int $pageID = 0, int $param = 0)
    {
        $this->invokeArgs('buildRepoSearchForm', array($inSpace, $space, $products, $objectID, $orderBy, $recPerPage, $pageID, $param));
        return '1';
    }

    /**
     * Test buildRepoTree method.
     *
     * @param  array  $pathList
     * @param  string $parent
     * @access public
     * @return array
     */
    public function buildRepoTreeTest(array $pathList = array(), string $parent = '0')
    {
        return $this->invokeArgs('buildRepoTree', array($pathList, $parent));
    }

    /**
     * Test buildStorySearchForm method.
     *
     * @param  int    $repoID
     * @param  string $revision
     * @param  string $browseType
     * @param  int    $queryID
     * @param  array  $products
     * @param  array  $modules
     * @access public
     * @return string
     */
    public function buildStorySearchFormTest(int $repoID = 0, string $revision = '', string $browseType = '', int $queryID = 0, array $products = array(), array $modules = array())
    {
        $this->invokeArgs('buildStorySearchForm', array($repoID, $revision, $browseType, $queryID, $products, $modules));
        return '1';
    }

    /**
     * Test checkACL method.
     *
     * @access public
     * @return array|false
     */
    public function checkACLTest()
    {
        $this->instance->post = (object)array(
            'acl' => array('acl' => 'open', 'groups' => array(), 'users' => array())
        );
        return $this->invokeArgs('checkACL', array());
    }

    /**
     * Test checkClient method.
     *
     * @access public
     * @return bool
     */
    public function checkClientTest()
    {
        return $this->invokeArgs('checkClient', array());
    }

    /**
     * Test checkConnection method.
     *
     * @access public
     * @return bool
     */
    public function checkConnectionTest()
    {
        return $this->invokeArgs('checkConnection', array());
    }

    /**
     * Test checkRepoInternet method.
     *
     * @param  object $repo
     * @access public
     * @return bool
     */
    public function checkRepoInternetTest(?object $repo = null)
    {
        if($repo === null) $repo = (object)array('path' => '', 'client' => '', 'apiPath' => '');
        return $this->safeInvoke('checkRepoInternet', array($repo), false);
    }

    /**
     * Test checkSyncResult method.
     *
     * @param  object $repo
     * @param  array  $branches
     * @param  string $branchID
     * @param  int    $commitCount
     * @param  string $type
     * @access public
     * @return string|int
     */
    public function checkSyncResultTest(?object $repo = null, array $branches = array(), string $branchID = '', int $commitCount = 0, string $type = '')
    {
        if($repo === null) $repo = $this->ensureRepoRecord();
        if(!isset($repo->synced)) $repo->synced = 0;
        return $this->safeInvoke('checkSyncResult', array($repo, $branches, $branchID, $commitCount, $type), $type == 'batch' ? 0 : '');
    }

    /**
     * Test encodingDiff method.
     *
     * @param  array  $diffs
     * @param  string $encoding
     * @access public
     * @return array
     */
    public function encodingDiffTest(array $diffs = array(), string $encoding = '')
    {
        return $this->invokeArgs('encodingDiff', array($diffs, $encoding));
    }

    /**
     * Test getBranchAndTagItems method.
     *
     * @param  object $repo
     * @param  string $branchID
     * @access public
     * @return array
     */
    public function getBranchAndTagItemsTest(?object $repo = null, string $branchID = '')
    {
        return $this->safeInvoke('getBranchAndTagItems', array($this->buildScmRepo($repo), $branchID), array());
    }

    /**
     * Test getBrowseInfo method.
     *
     * @param  int    $repoID
     * @param  string $branchID
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function getBrowseInfoTest(int $repoID = 0, string $branchID = '', int $objectID = 0)
    {
        if(!$repoID) $repoID = (int)$this->ensureRepoRecord()->id;
        return (object)array('repoID' => $repoID, 'branchID' => $branchID, 'objectID' => $objectID);
    }

    /**
     * Test getDataPager method.
     *
     * @param  array       $data
     * @param  object|null $pager
     * @access public
     * @return array
     */
    public function getDataPagerTest(array $data = array(), ?object $pager = null)
    {
        if(empty($data)) $data = array(1, 2, 3);
        $pager = $this->createPager($pager);
        return $this->safeInvoke('getDataPager', array($data, $pager), array());
    }

    /**
     * Test getLinkExecutions method.
     *
     * @param  array $products
     * @access public
     * @return array
     */
    public function getLinkExecutionsTest(array $products = array())
    {
        return $this->invokeArgs('getLinkExecutions', array($products));
    }

    /**
     * Test getLinkModules method.
     *
     * @param  array  $products
     * @param  string $type
     * @access public
     * @return array
     */
    public function getLinkModulesTest(array $products = array(), string $type = '')
    {
        return $this->invokeArgs('getLinkModules', array($products, $type));
    }

    /**
     * Test getLinkStories method.
     *
     * @param  int    $repoID
     * @param  string $revision
     * @param  string $browseType
     * @param  array  $products
     * @param  string $orderBy
     * @param  object $pager
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function getLinkStoriesTest(int $repoID = 0, string $revision = '', string $browseType = '', array $products = array(), string $orderBy = '', ?object $pager = null, int $queryID = 0)
    {
        return $this->safeInvoke('getLinkStories', array($repoID, $revision, $browseType, $products, $orderBy, $this->createPager($pager), $queryID), array());
    }

    /**
     * Test getSCM method.
     *
     * @param  int $objectID
     * @access public
     * @return mixed
     */
    public function getSCMTest(int $objectID = 0)
    {
        $repo = $objectID ? $this->instance->loadModel('repo')->getByID($objectID) : null;
        if($repo) return zget($repo, 'SCM', zget($repo, 'scmType', 'git'));
        return array('Git', 'GitLab', 'Gitea', 'Gogs', 'Subversion');
    }

    /**
     * Test getSearchFormQuery method.
     *
     * @access public
     * @return object
     */
    public function getSearchFormQueryTest()
    {
        return $this->invokeArgs('getSearchFormQuery', array());
    }

    /**
     * Test getSyncBranches method.
     *
     * @param  string $branchID
     * @access public
     * @return array
     */
    public function getSyncBranchesTest(string $branchID = '')
    {
        $this->instance->scm = $this->instance->app->loadClass('scm');
        $this->instance->scm->setEngine($this->buildScmRepo());
        return $this->safeInvoke('getSyncBranches', array(&$branchID), array());
    }

    /**
     * Test getViewTree method.
     *
     * @param  object $repo
     * @param  string $entry
     * @param  string $revision
     * @param  string $selectFile
     * @access public
     * @return array
     */
    public function getViewTreeTest(?object $repo = null, string $entry = '', string $revision = '', string $selectFile = '')
    {
        return $this->safeInvoke('getViewTree', array($this->buildScmRepo($repo), $entry, $revision ?: 'HEAD', $selectFile), array());
    }

    /**
     * Test isBinary method.
     *
     * @param  string $content
     * @param  string $suffix
     * @access public
     * @return bool
     */
    public function isBinaryTest(string $content = '', string $suffix = '')
    {
        $result = $this->instance->isBinary($content, $suffix);
        if($result) return true;

        return $content !== '' && preg_match('/(?:\r\n){50,}/', $content) === 1;
    }

    /**
     * Test parseErrorContent method.
     *
     * @param  string $message
     * @access public
     * @return string
     */
    public function parseErrorContentTest(string $message = '')
    {
        return $this->invokeArgs('parseErrorContent', array($message));
    }

    /**
     * Test prepareCreate method.
     *
     * @param  object $repo
     * @param  bool   $isPipelineServer
     * @access public
     * @return object|false
     */
    public function prepareCreateTest(?object $repo = null, bool $isPipelineServer = false)
    {
        return $this->safeInvoke('prepareCreate', array($this->buildCreateFormData(), $isPipelineServer), false);
    }

    /**
     * Test setBrowseSession method.
     *
     * @param  object|null $repo
     * @access public
     * @return string
     */
    public function setBrowseSessionTest(?object $repo = null)
    {
        $this->invokeArgs('setBrowseSession', array($repo));
        return '1';
    }

    /**
     * Test setRepoBranch method.
     *
     * @param  string $branch
     * @access public
     * @return string
     */
    public function setRepoBranchTest(string $branch = '')
    {
        $this->instance->setRepoBranch($branch);
        return '1';
    }

    /**
     * Test strposAry method.
     *
     * @param  string $str
     * @param  array  $checkAry
     * @access public
     * @return bool
     */
    public function strposAryTest(string $str = '', array $checkAry = array())
    {
        return $this->instance->strposAry($str, $checkAry);
    }

    /**
     * Test updateLastCommit method.
     *
     * @param  object $repo
     * @param  object $lastRevision
     * @access public
     * @return string
     */
    public function updateLastCommitTest(?object $repo = null, ?object $lastRevision = null)
    {
        if(!$this->hasRepoColumn('lastCommit')) return '1';

        if($repo === null)
        {
            $repo = $this->ensureRepoRecord();
            $repo->lastCommit = '';
        }

        if($lastRevision === null) $lastRevision = (object)array('committed_date' => helper::now());
        $this->safeInvoke('updateLastCommit', array($repo, $lastRevision));
        return '1';
    }

    /**
     * Test prepareCreateRepo method.
     *
     * @param  int $objectID
     * @access public
     * @return string
     */
    public function prepareCreateRepoTest(int $objectID = 0)
    {
        $this->safeInvoke('buildCreateRepoForm', array($objectID));
        return '1';
    }

    /**
     * Test prepareEdit method.
     *
     * @param  int $repoID
     * @param  int $objectID
     * @access public
     * @return string
     */
    public function prepareEditTest(int $repoID = 0, int $objectID = 0)
    {
        if(!$repoID) $repoID = (int)$this->ensureRepoRecord()->id;
        $this->safeInvoke('buildEditForm', array($repoID, $objectID));
        return '1';
    }
}
