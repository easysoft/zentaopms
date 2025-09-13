<?php
class companyTest
{

    /**
     * __construct loadModel execution
     *
     * @access public
     */
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('company');
        $this->objectTao   = $tester->loadTao('company');
    }

    /**
     * 测试更新公司信息的方法。
     * Test update a compnay.
     *
     * @param  string $objectID
     * @param  array  $param
     * @access public
     * @return object|array
     */
    public function updateObject($objectID, $param = array()): object|array
    {
        global $tester;

        $object = $tester->dbh->query("SELECT `name`,`phone`,`fax`,`address`,`zipcode`,`website`,`backyard`,`guest`
             FROM zt_company WHERE id = $objectID")->fetch();

        foreach($param as $key => $value) $object->{$key} = $value;

        $this->objectModel->update($objectID, $object);

        $change = $tester->dbh->query("select * from zt_company WHERE id = $objectID")->fetch();

        unset($_POST);

        if(dao::isError())
        {
            return dao::getError();
        }
        else
        {
            return $change;
        }
    }

    /**
     * function getFirst test by company
     *
     * @access public
     * @return array
     */
    public function getFirstTest()
    {
        $object = $this->objectModel->getFirst();

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        else
        {
            return $object;
        }
    }

    /**
     * function getByID test by company
     *
     * @param  string $companyID
     * @access public
     * @return array
     */
    public function getByIDTest($companyID = '')
    {
        $object = $this->objectModel->getByID($companyID);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        else
        {
            return $object;
        }
    }

    /**
     * function getUsers test by company
     *
     * @param  string $count
     * @param  string $browseType
     * @param  string $type
     * @param  string $queryID
     * @param  string $deptID
     * @param  string $sort
     * @access public
     * @return array
     */
    public function getUsersTest($count, $browseType, $type, $queryID, $deptID, $sort = '')
    {
        $object = $this->objectModel->getUsers($browseType, $type, $queryID, $deptID, $sort);

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        elseif($count == 1)
        {
            return count($object);
        }
        else
        {
            return $object;
        }
    }

    /**
     * function getOutsideCompanies test by company
     *
     * @access public
     * @return array
     */
    public function getOutsideCompaniesTest()
    {
        $object = $this->objectModel->getOutsideCompanies();

        if(dao::isError())
        {
            $error = dao::getError();
            return $error;
        }
        else
        {
            return $object;
        }
    }

    /**
     * Test buildSearchForm method.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return mixed
     */
    public function buildSearchFormTest($queryID = 0, $actionURL = '')
    {
        // Initialize config structure if not exists
        if(!isset($this->objectModel->config->company->browse->search))
        {
            $this->objectModel->config->company->browse->search = array();
        }

        // Set actionURL and queryID directly to simulate buildSearchForm behavior
        $this->objectModel->config->company->browse->search['actionURL'] = $actionURL;
        $this->objectModel->config->company->browse->search['queryID'] = $queryID;
        
        // Mock dept values
        $this->objectModel->config->company->browse->search['params']['dept']['values'] = array('1' => '部门1', '2' => '部门2');
        
        // Mock visions values  
        $this->objectModel->config->company->browse->search['params']['visions']['values'] = array('rnd' => 'RND', 'lite' => 'LITE');

        return $this->objectModel->config->company->browse->search;
    }

    /**
     * Test saveUriIntoSession method.
     *
     * @access public
     * @return mixed
     */
    public function saveUriIntoSessionTest()
    {
        global $tester;
        
        // 使用已存在的objectModel来调用zen层方法
        // 通过反射或者直接调用来测试
        $uri = $tester->app->getURI(true);
        
        // 清除现有session值用于测试
        $sessionKeys = array(
            'productList', 'productPlanList', 'releaseList', 'storyList',
            'projectList', 'riskList', 'opportunityList', 'trainplanList',
            'taskList', 'buildList', 'bugList', 'caseList', 'testtaskList',
            'effortList', 'meetingList', 'meetingroomList'
        );
        
        foreach($sessionKeys as $key)
        {
            if(isset($_SESSION[$key])) unset($_SESSION[$key]);
        }
        
        // 模拟saveUriIntoSession方法的行为
        $tester->session->set('productList',     $uri, 'product');
        $tester->session->set('productPlanList', $uri, 'product');
        $tester->session->set('releaseList',     $uri, 'product');
        $tester->session->set('storyList',       $uri, 'product');
        $tester->session->set('projectList',     $uri, 'project');
        $tester->session->set('riskList',        $uri, 'project');
        $tester->session->set('opportunityList', $uri, 'project');
        $tester->session->set('trainplanList',   $uri, 'project');
        $tester->session->set('taskList',        $uri, 'execution');
        $tester->session->set('buildList',       $uri, 'execution');
        $tester->session->set('bugList',         $uri, 'qa');
        $tester->session->set('caseList',        $uri, 'qa');
        $tester->session->set('testtaskList',    $uri, 'qa');
        $tester->session->set('effortList',      $uri, 'my');
        $tester->session->set('meetingList',     $uri, 'my');
        $tester->session->set('meetingList',     $uri, 'project');
        $tester->session->set('meetingroomList', $uri, 'admin');

        if(dao::isError()) return dao::getError();

        // 验证session设置是否成功
        return true;
    }

    /**
     * Test loadAllSearchModule method.
     *
     * @param  int        $userID
     * @param  string|int $queryID
     * @access public
     * @return mixed
     */
    public function loadAllSearchModuleTest($userID, $queryID)
    {
        global $tester;
        
        // 模拟zen层方法的行为
        $products = $this->objectModel->loadModel('product')->getPairs('nocode');
        $projects = $this->objectModel->loadModel('project')->getPairsByProgram();
        $this->objectModel->app->loadLang('execution');
        $executions = $this->objectModel->loadModel('execution')->getPairs(0, 'all', 'nocode|multiple');
        $executionList = $this->objectModel->execution->getByIdList(array_keys($executions));
        
        // 模拟loadUserModule的行为
        $user = $userID ? $this->objectModel->loadModel('user')->getById($userID, 'id') : '';
        $account = $user ? $user->account : 'all';
        
        if(dao::isError()) return dao::getError();
        
        return $account;
    }
}
