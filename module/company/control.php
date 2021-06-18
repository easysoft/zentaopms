<?php
/**
 * The control file of company module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     company
 * @version     $Id: control.php 5100 2013-07-12 00:25:23Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
class company extends control
{
    /**
     * Construct function, load dept and user models auto.
     *
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->loadModel('dept');
    }

    /**
     * Index page, header to browse.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $this->locate($this->createLink('company', 'browse'));
    }

    /**
     * Browse departments and users of a company.
     *
     * @param  int    $param
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($browseType = 'inside', $param = 0, $type = 'bydept', $orderBy = 'id', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->lang->navGroup->company = 'admin';

        $this->loadModel('search');

        $deptID = $type == 'bydept' ? (int)$param : 0;
        $this->company->setMenu($deptID);

        /* Save session. */
        $this->session->set('userList', $this->app->getURI(true), 'admin');

        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort = $this->loadModel('common')->appendOrder($orderBy);

        /* Build the search form. */
        $queryID   = $type == 'bydept' ? 0 : (int)$param;
        $actionURL = $this->createLink('company', 'browse', "browseType=all&param=myQueryID&type=bysearch");
        $this->company->buildSearchForm($queryID, $actionURL);

        /* Get users. */
        $users = $this->company->getUsers($browseType, $type, $queryID, $deptID, $sort, $pager);

        /* Remove passwd. */
        foreach($users as $user) unset($user->password);

        /* Assign. */
        $this->view->title       = $this->lang->company->index . $this->lang->colon . $this->lang->dept->common;
        $this->view->position[]  = $this->lang->dept->common;
        $this->view->users       = $users;
        $this->view->searchForm  = $this->fetch('search', 'buildForm', $this->config->company->browse->search);
        $this->view->deptTree    = $this->dept->getTreeMenu($rooteDeptID = 0, array('deptModel', 'createMemberLink'));
        $this->view->parentDepts = $this->dept->getParents($deptID);
        $this->view->dept        = $this->dept->getById($deptID);
        $this->view->orderBy     = $orderBy;
        $this->view->deptID      = $deptID;
        $this->view->pager       = $pager;
        $this->view->param       = $param;
        $this->view->type        = $type;
        $this->view->browseType  = $browseType;
        $this->view->companies   = $this->company->getOutsideCompanies();

        $this->display();
    }

    public function create()
    {
        if(!empty($_POST))
        {
            $this->company->create();
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::reload('parent.parent'));
        }

        $this->view->title     = $this->lang->company->common . $this->lang->colon . $this->lang->company->create;
        $this->view->position  = $this->lang->company->create;

        $this->display();
    }

    /**
     * Edit a company.
     *
     * @access public
     * @return void
     */
    public function edit()
    {
        if(!empty($_POST))
        {
            $this->company->update();
            if(dao::isError()) die(js::error(dao::getError()));

            /* reset company in session. */
            $company = $this->loadModel('company')->getFirst();
            $this->session->set('company', $company);

            die(js::reload('parent.parent'));
        }

        $this->company->setMenu();
        $title      = $this->lang->company->common . $this->lang->colon . $this->lang->company->edit;
        $position[] = $this->lang->company->edit;
        $this->view->title     = $title;
        $this->view->position  = $position;
        $this->view->company   = $this->company->getById($this->app->company->id);

        $this->display();
    }

    /**
     * View a company.
     *
     * @access public
     * @return void
     */
    public function view()
    {
        $this->company->setMenu();
        $this->view->title      = $this->lang->company->common . $this->lang->colon . $this->lang->company->view;
        $this->view->position[] = $this->lang->company->view;
        $this->view->company    = $this->company->getById($this->app->company->id);
        $this->display();
    }

    /**
     * Company dynamic.
     *
     * @param  string $browseType
     * @param  string $param
     * @param  int    $recTotal
     * @param  string $date
     * @param  string $direction    next|pre
     * @access public
     * @return void
     */
    public function dynamic($browseType = 'today', $param = '', $recTotal = 0, $date = '', $direction = 'next')
    {
        $this->company->setMenu();
        $this->app->loadLang('user');
        $this->app->loadLang('execution');
        $this->loadModel('action');

        /* Save session. */
        $uri = $this->app->getURI(true);
        $this->session->set('productList',     $uri, 'product');
        $this->session->set('productPlanList', $uri, 'product');
        $this->session->set('releaseList',     $uri, 'product');
        $this->session->set('storyList',       $uri, 'product');
        $this->session->set('projectList',     $uri, 'project');
        $this->session->set('riskList',        $uri, 'project');
        $this->session->set('opportunityList', $uri, 'project');
        $this->session->set('trainplanList',   $uri, 'project');
        $this->session->set('executionList',   $uri, 'execution');
        $this->session->set('taskList',        $uri, 'execution');
        $this->session->set('buildList',       $uri, 'execution');
        $this->session->set('bugList',         $uri, 'qa');
        $this->session->set('caseList',        $uri, 'qa');
        $this->session->set('testtaskList',    $uri, 'qa');
        $this->session->set('effortList',      $uri, 'my');
        $this->session->set('meetingList',     $uri, 'my');
        $this->session->set('meetingList',     $uri, 'project');
        $this->session->set('meetingroomList', $uri, 'admin');

        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage = 50, $pageID = 1);

        /* Append id for secend sort. */
        $orderBy = $direction == 'next' ? 'date_desc' : 'date_asc';
        $sort    = $this->loadModel('common')->appendOrder($orderBy);

        /* Set the user and type. */
        $account = 'all';
        $user    = '';
        if($browseType == 'account')
        {
            $user = $this->loadModel('user')->getById((int)$param, 'id');
            if($user) $account = $user->account;
        }
        $product   = $browseType  == 'product'   ? $param : 'all';
        $project   = $browseType  == 'project'   ? $param : 'all';
        $execution = $browseType  == 'execution' ? $param : 'all';
        $period    = in_array($browseType, array('account', 'product', 'project', 'execution')) ? 'all' : $browseType;
        $queryID   = ($browseType == 'bysearch') ? (int)$param : 0;
        $date      = empty($date) ? '' : date('Y-m-d', $date);

        /* Get products' list.*/
        $products = $this->loadModel('product')->getPairs('nocode');
        $products = array($this->lang->company->product) + $products;
        $this->view->products = $products;

        /* Get projects' list.*/
        $projects = $this->loadModel('project')->getPairsByProgram(0);
        $projects = array($this->lang->company->project) + $projects;
        $this->view->projects = $projects;

        /* Get executions' list.*/
        $executions = $this->loadModel('execution')->getPairs(0, 'all', 'nocode');
        $executions = array($this->lang->company->execution) + $executions;
        $this->view->executions = $executions;

        /* Get users.*/
        $userIdPairs = $this->loadModel('user')->getPairs('noclosed|nodeleted|noletter|useid');
        $userIdPairs[''] = $this->lang->company->user;
        $this->view->userIdPairs = $userIdPairs;

        $accountPairs = $this->user->getPairs('noclosed|nodeleted|noletter');
        $accountPairs[''] = '';

        /* The header and position. */
        $this->view->title      = $this->lang->company->common . $this->lang->colon . $this->lang->company->dynamic;
        $this->view->position[] = $this->lang->company->dynamic;

        /* Get actions. */
        if($browseType != 'bysearch')
        {
            $actions = $this->action->getDynamic($account, $period, $sort, $pager, $product, $project, $execution, $date, $direction);
        }
        else
        {
            $actions = $this->action->getDynamicBySearch($products, $projects, $executions, $queryID, $sort, $pager, $date, $direction);
        }

        /* Build search form. */
        $executions[0] = '';
        $products[0]   = '';
        ksort($executions);
        ksort($products);
        $executions['all'] = $this->lang->execution->allExecutions;
        $products['all']   = $this->lang->product->allProduct;

        foreach($this->lang->action->search->label as $action => $name)
        {
            if($action) $this->lang->action->search->label[$action] .= " [ $action ]";
        }

        $this->config->company->dynamic->search['actionURL'] = $this->createLink('company', 'dynamic', "browseType=bysearch&param=myQueryID");
        $this->config->company->dynamic->search['queryID'] = $queryID;
        $this->config->company->dynamic->search['params']['action']['values']    = $this->lang->action->search->label;
        $this->config->company->dynamic->search['params']['product']['values']   = $products;
        $this->config->company->dynamic->search['params']['project']['values']   = $projects;
        $this->config->company->dynamic->search['params']['execution']['values'] = $executions;
        $this->config->company->dynamic->search['params']['actor']['values']     = $accountPairs;
        $this->loadModel('search')->setSearchParams($this->config->company->dynamic->search);

        /* Assign. */
        $this->view->browseType   = $browseType;
        $this->view->account      = $account;
        $this->view->accountPairs = $accountPairs;
        $this->view->product      = $product;
        $this->view->project      = $project;
        $this->view->execution    = $execution;
        $this->view->queryID      = $queryID;
        $this->view->orderBy      = $orderBy;
        $this->view->pager        = $pager;
        $this->view->user         = $user;
        $this->view->param        = $param;
        $this->view->dateGroups   = $this->action->buildDateGroup($actions, $direction, $browseType);
        $this->view->direction    = $direction;
        $this->display();
    }

    /**
     * Ajax get outside company.
     *
     * @access public
     * @return void
     */
    public function ajaxGetOutsideCompany()
    {
        $companies = $this->company->getOutsideCompanies();
        die(html::select('company', $companies, '', "class='form-control chosen'"));
    }
}
