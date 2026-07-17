<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

/**
 * @property repoZen $instance
 */
class repoZenTest extends baseTest
{
    protected $moduleName = 'repo';
    protected $className  = 'zen';

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
        $result = $this->invokeArgs('buildImportForm', array($providerID, $groupID, $type));
        if(dao::isError()) return dao::getError();
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
    public function buildSystemSearchFormTest(int $queryID, string $actionURL, bool $cacheSearchFunc = true)
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

        $pager = new stdclass();
        $pager->recTotal = 0;
        $pager->recPerPage = 10;

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
        $pager = new stdclass();
        $pager->recTotal = 0;
        $pager->recPerPage = 10;

        $result = $this->invokeArgs('getLinkBugs', array($repoID, $revision, $browseType, $products, $orderBy, $pager, $queryID));
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
        $pager = new stdclass();
        $pager->recTotal = 0;
        $pager->recPerPage = 10;

        $result = $this->invokeArgs('getLinkTasks', array($repoID, $revision, $browseType, $products, $orderBy, $pager, $queryID, $executionPairs));
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
        $result = $this->invokeArgs('locateDiffPage', array($repoID, $objectID, $arrange, $isBranchOrTag, $file));
        if(dao::isError()) return dao::getError();
        return $result ? '1' : '0';
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
}
