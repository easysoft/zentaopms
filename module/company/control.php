<?php
/**
 * The control file of company module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
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
        $this->app->loadLang('user');
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
    public function browse($deptID = 0)
    {
        $this->lang->set('menugroup.company', 'company');
        $childDeptIds = $this->dept->getAllChildID($deptID);

        $this->company->setMenu($deptID);

        $header['title'] = $this->lang->company->index . $this->lang->colon . $this->lang->dept->common;
        $position[]      = $this->lang->dept->common;

        $this->view->header      = $header;
        $this->view->position    = $position;
        $this->view->users       = $this->dept->getUsers($childDeptIds);
        $this->view->deptTree    = $this->dept->getTreeMenu($rooteDeptID = 0, array('deptModel', 'createMemberLink'));
        $this->view->parentDepts = $this->dept->getParents($deptID);
        $this->view->deptID      = $deptID;

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
        $this->lang->company->menu = $this->lang->admin->menu;

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
     * @param  string $type 
     * @param  string $orderBy 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function dynamic($type = 'today', $param = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Save session. */
        $uri   = $this->app->getURI(true);
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
        $account = $type == 'account' ? $param : 'all';
        $period  = $type == 'account' ? 'all'  : $type;

        /* The header and position. */
        $this->view->header->title = $this->lang->company->common . $this->lang->colon . $this->lang->company->dynamic;
        $this->view->position[]    = $this->lang->company->dynamic;

        /* Assign. */
        $this->view->type    = $type;
        $this->view->users   = $this->loadModel('user')->getPairs('nodeleted|noletter');
        $this->view->account = $account;
        $this->view->actions = $this->loadModel('action')->getDynamic($account, $period, $orderBy, $pager);
        $this->display();
    }

}
