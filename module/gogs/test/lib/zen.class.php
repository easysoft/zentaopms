<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class gogsZenTest extends baseTest
{
    protected $moduleName = 'gogs';
    protected $className  = 'zen';

    public $gogs = null;

    public function __construct($moduleName = '', $className = '')
    {
        parent::__construct($moduleName, $className);

        $this->gogs = $this->instance->loadModel('gogs');
    }

    /**
     * Test bindUser method.
     *
     * @param  array  $userList
     * @access public
     * @return array|string
     */
    public function bindUserTester(array $userList): array|string
    {
        $nameList = array();
        foreach($userList as $openID => $user) $nameList[$openID] = 'Gitea-' . $user;

        $result = $this->gogs->bindUser(1, $userList, $nameList);
        if(!$result) return dao::getError();

        return $this->gogs->dao->select('*')->from(TABLE_OAUTH)
            ->where('providerID')->eq(1)
            ->andWhere('providerType')->eq('gogs')
            ->fetchAll();
    }

    /**
     * Test apiGetMergeRequests method.
     *
     * @param  int    $gogsID
     * @param  string $project
     * @access public
     * @return mixed
     */
    public function apiGetMergeRequestsTest(int $gogsID = 0, string $project = ''): mixed
    {
        $result = $this->gogs->apiGetMergeRequests($gogsID, $project);
        if(dao::isError()) return dao::getError();

        return is_array($result) ? count($result) : 0;
    }

    /**
     * Test checkToken method.
     *
     * @param  object $gogs
     * @access public
     * @return mixed
     */
    public function checkTokenTest(object $gogs): mixed
    {
        $this->instance->gogs = $this->gogs;

        $method = $this->reflection->getMethod('checkToken');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance, $gogs);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getMatchedUsers method.
     *
     * @param  int   $gogsID
     * @param  array $gogsUsers
     * @access public
     * @return mixed
     */
    public function getMatchedUsersTest(int $gogsID, array $gogsUsers): mixed
    {
        $method = $this->reflection->getMethod('getMatchedUsers');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance, $gogsID, $gogsUsers);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test apiGetProjects method.
     *
     * @param  int $gogsID
     * @access public
     * @return mixed
     */
    public function apiGetProjectsTest(int $gogsID): mixed
    {
        $result = $this->gogs->apiGetProjects($gogsID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test apiGetBranches method.
     *
     * @param  int    $gogsID
     * @param  string $project
     * @access public
     * @return mixed
     */
    public function apiGetBranchesTest(int $gogsID, string $project): mixed
    {
        $result = $this->gogs->apiGetBranches($gogsID, $project);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test apiGetSingleProject method.
     *
     * @param  int    $gogsID
     * @param  string $projectID
     * @access public
     * @return mixed
     */
    public function apiGetSingleProjectTest(int $gogsID, string $projectID): mixed
    {
        $result = $this->gogs->apiGetSingleProject($gogsID, $projectID);
        if(dao::isError()) return dao::getError();

        if(is_null($result)) return 0;
        if(!is_object($result)) return $result;

        // 返回简单的验证结果
        return isset($result->name) ? 1 : 0;
    }

    /**
     * Test getApiRoot method.
     *
     * @param  int $gogsID
     * @access public
     * @return mixed
     */
    public function getApiRootTest(int $gogsID): mixed
    {
        $result = $this->gogs->getApiRoot($gogsID);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
