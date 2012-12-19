<?php
/**
 * The control file of company module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     company
 * @version     $Id$
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
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('dept');
        $this->company->setMenu();
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
     * @param  int    $deptID 
     * @access public
     * @return void
     */
    public function browse($param = 0, $type = 'bydept', $orderBy = 'id', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('search');
        $this->lang->set('menugroup.company', 'company');

        $deptID = $type == 'bydept' ? (int)$param : 0;
        $this->company->setMenu($deptID);

        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $queryID = $type == 'bydept' ? 0 : (int)$param;
        $this->config->company->browse->search['actionURL'] = $this->createLink('company', 'browse', "param=myQueryID&type=bysearch");
        $this->config->company->browse->search['queryID']   = $queryID;
        $this->config->company->browse->search['params']['dept']['values'] = array('' => '') + $this->dept->getOptionMenu();

        $header['title'] = $this->lang->company->index . $this->lang->colon . $this->lang->dept->common;
        $position[]      = $this->lang->dept->common;

        if($type == 'bydept')
        {
            $childDeptIds = $this->dept->getAllChildID($deptID);
            $users        = $this->dept->getUsers($childDeptIds, $pager, $orderBy);
        }
        else
        {
            if($queryID)
            {
                $query = $this->search->getQuery($queryID);
                if($query)
                {
                    $this->session->set('userQuery', $query->sql);
                    $this->session->set('userForm', $query->form);
                }
                else
                {
                    $this->session->set('userQuery', ' 1 = 1');
                }
            }
            $users = $this->loadModel('user')->getByQuery($this->session->userQuery, $pager, $orderBy);
        }

        $this->view->header      = $header;
        $this->view->position    = $position;
        $this->view->users       = $users;
        $this->view->searchForm  = $this->fetch('search', 'buildForm', $this->config->company->browse->search);
        $this->view->deptTree    = $this->dept->getTreeMenu($rooteDeptID = 0, array('deptModel', 'createMemberLink'));
        $this->view->parentDepts = $this->dept->getParents($deptID);
        $this->view->orderBy     = $orderBy;
        $this->view->deptID      = $deptID;
        $this->view->pager       = $pager;
        $this->view->param       = $param;
        $this->view->type        = $type;

        $this->display();
    }

    /**
     * Create a company.
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        if(!empty($_POST))
        {
            $this->company->create();
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate($this->createLink('admin', 'browsecompany'), 'parent'));
        }

        $this->lang->set('menugroup.company', 'admin');
        $this->lang->company->menu      = $this->lang->admin->menu;
        $this->lang->company->menuOrder = $this->lang->admin->menuOrder;

        $header['title'] = $this->lang->admin->common . $this->lang->colon . $this->lang->company->create;
        $position[]      = html::a($this->createLink('admin', 'browsecompany'), $this->lang->admin->company);
        $position[]      = $this->lang->company->create;
        $this->view->header   = $header;
        $this->view->position = $position;

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
            die(js::alert($this->lang->company->successSaved));
        }

        $header['title'] = $this->lang->company->common . $this->lang->colon . $this->lang->company->edit;
        $position[]      = $this->lang->company->edit;
        $this->view->header    = $header;
        $this->view->position  = $position;
        $this->view->company   = $this->company->getById($this->app->company->id);

        $this->display();
    }

    /**
     * Delete a company.
     * 
     * @param  int    $companyID 
     * @param  string $confirm      yes|no
     * @access public
     * @return void
     */
    public function delete($companyID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            echo js::confirm($this->lang->company->confirmDelete, $this->createLink('company', 'delete', "companyID=$companyID&confirm=yes"));
            exit;
        }
        else
        {
            $this->company->delete($companyID);
            echo js::locate($this->createLink('admin', 'browseCompany'), 'parent');
            exit;
        }
    }

    /**
     * Company dynamic.
     * 
     * @param  string $browseType 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function dynamic($browseType = 'today', $param = '', $orderBy = 'date_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadLang('user');
        $this->app->loadLang('project');
        $this->loadModel('action');

        /* Save session. */
        $uri = $this->app->getURI(true);
        $this->session->set('productList',     $uri);
        $this->session->set('productPlanList', $uri);
        $this->session->set('releaseList',     $uri);
        $this->session->set('storyList',       $uri);
        $this->session->set('projectList',     $uri);
        $this->session->set('taskList',        $uri);
        $this->session->set('buildList',       $uri);
        $this->session->set('bugList',         $uri);
        $this->session->set('caseList',        $uri);
        $this->session->set('testtaskList',    $uri);

        /* Set the pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);
        $this->view->orderBy = $orderBy;
        $this->view->pager   = $pager;

        /* Set the user and type. */
        $account = $browseType == 'account' ? $param : 'all';
        $product = $browseType == 'product' ? $param : 'all';
        $project = $browseType == 'project' ? $param : 'all';
        $period  = ($browseType == 'account' or $browseType == 'product' or $browseType == 'project') ? 'all'  : $browseType;
        $queryID = ($browseType == 'bysearch') ? (int)$param : 0;

        /* Get products' list.*/
        $products = $this->loadModel('product')->getPairs();
        $products = array($this->lang->product->select) + $products;
        $this->view->products = $products;

        /* Get projects' list.*/
        $projects = $this->loadModel('project')->getPairs();
        $projects = array($this->lang->project->select) + $projects;
        $this->view->projects = $projects; 

        /* Get users.*/
        $users = $this->loadModel('user')->getPairs('nodeleted|noclosed');
        $users[''] = $this->lang->user->select;
        $this->view->users    = $users; 

        /* The header and position. */
        $this->view->header->title = $this->lang->company->common . $this->lang->colon . $this->lang->company->dynamic;
        $this->view->position[]    = $this->lang->company->dynamic;

        /* Get actions. */
        if($browseType != 'bysearch') 
        {
            $actions = $this->action->getDynamic($account, $period, $orderBy, $pager, $product, $project);
        }
        else
        {
            $actions = $this->action->getDynamicBySearch($products, $projects, $queryID, $orderBy, $pager); 
        }

        /* Build search form. */
        $projects[0] = '';
        $products[0] = '';
        $users['']   = '';
        ksort($projects);
        ksort($products);
        $projects['all'] = $this->lang->project->allProject;
        $products['all'] = $this->lang->product->allProduct;
        $this->config->company->dynamic->search['actionURL'] = $this->createLink('company', 'dynamic', "browseType=bysearch&param=myQueryID");
        $this->config->company->dynamic->search['queryID']   = $queryID;
        $this->config->company->dynamic->search['params']['project']['values'] = $projects;
        $this->config->company->dynamic->search['params']['product']['values'] = $products; 
        $this->config->company->dynamic->search['params']['actor']['values']   = $users; 
        $this->loadModel('search')->setSearchParams($this->config->company->dynamic->search);

        /* Assign. */
        $this->view->browseType = $browseType;
        $this->view->account    = $account;
        $this->view->product    = $product;
        $this->view->project    = $project;
        $this->view->queryID    = $queryID; 
        $this->view->actions    = $actions;
        $this->display();
    }
}
