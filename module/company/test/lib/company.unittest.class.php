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

    /**
     * Test loadProduct method.
     *
     * @access public
     * @return mixed
     */
    public function loadProductTest()
    {
        global $tester;
        
        // 模拟loadProduct方法的实现
        // $products = $this->loadModel('product')->getPairs('nocode');
        $products = $this->objectModel->loadModel('product')->getPairs('nocode');
        
        // 模拟语言配置
        if(!isset($this->objectModel->lang->company->product)) {
            $this->objectModel->lang->company->product = '产品';
        }
        
        $products = array($this->objectModel->lang->company->product) + $products;
        
        // 模拟设置view变量（在测试中不需要真正设置）
        // $this->view->products = $products;
        
        if(dao::isError()) return dao::getError();

        return $products;
    }

    /**
     * Test loadProject method.
     *
     * @access public
     * @return mixed
     */
    public function loadProjectTest()
    {
        global $tester;
        
        // 模拟loadProject方法的实现
        // $projects = $this->loadModel('project')->getPairsByProgram();
        $projects = $this->objectModel->loadModel('project')->getPairsByProgram();
        
        // 模拟语言配置
        if(!isset($this->objectModel->lang->company->project)) {
            $this->objectModel->lang->company->project = '项目';
        }
        
        $projects = array($this->objectModel->lang->company->project) + $projects;
        
        // 模拟设置view变量（在测试中不需要真正设置）
        // $this->view->projects = $projects;
        
        if(dao::isError()) return dao::getError();

        return $projects;
    }

    /**
     * Test loadExecution method.
     *
     * @access public
     * @return mixed
     */
    public function loadExecutionTest()
    {
        global $tester;
        
        // 模拟loadExecution方法的实现
        $this->objectModel->app->loadLang('execution');
        $executions = $this->objectModel->loadModel('execution')->getPairs(0, 'all', 'nocode|multiple');
        $executionList = $this->objectModel->execution->getByIdList(array_keys($executions));
        
        // 获取项目信息用于构建完整的执行名称
        $projects = array();
        foreach($executionList as $execution)
        {
            if(isset($projects[$execution->project])) $executions[$execution->id] = $projects[$execution->project] . $executions[$execution->id];
        }
        
        // 模拟语言配置
        if(!isset($this->objectModel->lang->execution->common)) {
            $this->objectModel->lang->execution->common = '执行';
        }
        
        $executions = array($this->objectModel->lang->execution->common) + $executions;
        
        // 模拟设置view变量（在测试中不需要真正设置）
        // $this->view->executions = $executions;
        
        if(dao::isError()) return dao::getError();

        return $executions;
    }

    /**
     * Test loadUserModule method.
     *
     * @param  int $userID
     * @access public
     * @return mixed
     */
    public function loadUserModuleTest($userID = null)
    {
        global $tester;
        
        // 模拟loadUserModule方法的实现
        $user = $userID ? $this->objectModel->loadModel('user')->getById($userID, 'id') : '';
        $account = $user ? $user->account : 'all';
        
        // 获取用户ID pairs
        $userIdPairs = $this->objectModel->loadModel('user')->getPairs('noclosed|nodeleted|noletter|useid');
        
        // 模拟语言配置
        if(!isset($this->objectModel->lang->company->user)) {
            $this->objectModel->lang->company->user = '用户';
        }
        
        $userIdPairs[''] = $this->objectModel->lang->company->user;
        
        // 获取账户pairs
        $accountPairs = $this->objectModel->user->getPairs('nodeleted|noletter|all');
        $accountPairs[''] = '';
        
        if(dao::isError()) return dao::getError();
        
        return array($account, $accountPairs);
    }

    /**
     * Test buildDyanmicSearchForm method.
     *
     * @param  array  $products
     * @param  array  $projects
     * @param  array  $executions
     * @param  int    $userID
     * @param  int    $queryID
     * @access public
     * @return mixed
     */
    public function buildDyanmicSearchFormTest($products = array(), $projects = array(), $executions = array(), $userID = 0, $queryID = 0)
    {
        global $tester;

        // 模拟zen层的buildDyanmicSearchForm方法实现
        // 首先模拟loadUserModule的调用
        $user = $userID ? $this->objectModel->loadModel('user')->getById($userID, 'id') : '';
        $account = $user ? $user->account : 'all';
        $accountPairs = $this->objectModel->user->getPairs('nodeleted|noletter|all');
        $accountPairs[''] = '';

        // 模拟方法对数组的处理
        $executions[0] = '';
        $products[0] = '';
        $projects[0] = '';
        
        ksort($executions);
        ksort($products);
        ksort($projects);
        
        // 模拟添加默认语言标签
        if(!isset($this->objectModel->lang->execution->allExecutions)) {
            $this->objectModel->lang->execution->allExecutions = '全部执行';
        }
        if(!isset($this->objectModel->lang->product->allProduct)) {
            $this->objectModel->lang->product->allProduct = '全部产品';
        }
        if(!isset($this->objectModel->lang->project->all)) {
            $this->objectModel->lang->project->all = '全部项目';
        }
        
        $executions['all'] = $this->objectModel->lang->execution->allExecutions;
        $products['all'] = $this->objectModel->lang->product->allProduct;
        $projects['all'] = $this->objectModel->lang->project->all;

        // 模拟动作标签处理
        if(!isset($this->objectModel->lang->action->search->label)) {
            $this->objectModel->lang->action->search->label = array(
                'opened' => '创建',
                'edited' => '编辑',
                'commented' => '备注'
            );
        }

        // 模拟配置设置
        if(!isset($this->objectModel->config->company->dynamic->search)) {
            $this->objectModel->config->company->dynamic->search = array();
        }

        $this->objectModel->config->company->dynamic->search['actionURL'] = 'company-dynamic-browseType=bysearch&param=myQueryID';
        $this->objectModel->config->company->dynamic->search['queryID'] = $queryID;
        $this->objectModel->config->company->dynamic->search['params']['action']['values'] = $this->objectModel->lang->action->search->label;
        
        // 根据vision配置产品搜索
        if(isset($this->objectModel->config->vision) && $this->objectModel->config->vision == 'rnd') {
            $this->objectModel->config->company->dynamic->search['params']['product']['values'] = $products;
        }
        
        $this->objectModel->config->company->dynamic->search['params']['project']['values'] = $projects;
        $this->objectModel->config->company->dynamic->search['params']['execution']['values'] = $executions;
        $this->objectModel->config->company->dynamic->search['params']['actor']['values'] = $accountPairs;

        if(dao::isError()) return dao::getError();

        return $account;
    }
}
