<?php
/**
 * The control file of budget currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     budget
 * @version     $Id: control.php 5107 2013-07-12 01:46:12Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class budget extends control
{
    /**
     * __construct
     *
     * @param  string $module
     * @param  string $method
     * @access public
     * @return void
     */
    public function __construct($module = '', $method = '')
    {
        parent::__construct($module, $method);
        $this->view->program = $this->loadModel('project')->getByID($this->session->PRJ);
    }

    /**
     * The budget browse page.
     *
     * @param  varchar $orderBy
     * @param  int     $recTotal
     * @param  int     $recPerPage
     * @param  int     $pageID
     * @access public
     * @return void
     */
    public function browse($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->budget->common . $this->lang->budget->list;
        $this->view->position[] = $this->lang->budget->common . $this->lang->budget->list;
        $this->view->budgets    = $this->budget->getList($this->session->PRJ, $orderBy, $pager);
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->modules    = $this->loadModel('tree')->getOptionMenu(0, $viewType = 'subject', $startModuleID = 0);
        $this->view->stages     = $this->loadModel('programplan')->getPlanPairsForBudget($this->session->PRJ);
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->display();
    }

    /**
     * The budget summary page.
     *
     * @access public
     * @return void
     */
    public function summary()
    {
        $getSubjectStructure = $this->budget->getSubjectStructure();
        $isChildren          = false;
        if(is_array($getSubjectStructure))
        {
            foreach($getSubjectStructure as $subjects)
            {
                if(isset($subjects['hasChild']))
                {
                    $isChildren = true;
                    break;
                }
            }
        }

        $this->view->title            = $this->lang->budget->common . $this->lang->budget->summary;
        $this->view->position[]       = $this->lang->budget->common . $this->lang->budget->summary;
        $this->view->subjectStructure = $getSubjectStructure;
        $this->view->isChildren       = $isChildren;
        $this->view->subjects         = $this->budget->getSubjects($this->session->PRJ);
        $this->view->stages           = $this->budget->getStages($this->session->PRJ);
        $this->view->stagePairs       = $this->loadModel('programplan')->getPlanPairsForBudget($this->session->PRJ);
        $this->view->summary          = $this->budget->getSummary($this->session->PRJ);
        $this->view->modules          = $this->loadModel('tree')->getOptionMenu(0, $viewType = 'subject', $startModuleID = 0);
        $this->display();
    }

    /**
     * Create a budget.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $budgetID = $this->budget->create();

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            $this->loadModel('action')->create('budget', $budgetID, 'created');

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = inlink('browse');
            $this->send($response);
        }

        $this->view->title      = $this->lang->budget->create . $this->lang->budget->common;
        $this->view->position[] = $this->lang->budget->create . $this->lang->budget->common;
        $this->view->subjects   = array(0 => '') + $this->budget->getSubjectOption();
        $this->view->stages     = $this->loadModel('programplan')->getPlanPairsForBudget($this->session->PRJ);
        $this->display();
    }

    /**
     * Edit a budget.
     *
     * @access public
     * @return void
     */
    public function edit($budgetID)
    {
        if($_POST)
        {
            $changes = $this->budget->update($budgetID);

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('budget', $budgetID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = inlink('browse');
            $this->send($response);
        }

        $this->view->title      = $this->lang->budget->edit . $this->lang->budget->common;
        $this->view->position[] = $this->lang->budget->edit . $this->lang->budget->common;
        $this->view->subjects   = array('' => '') + $this->budget->getSubjectOption();
        $this->view->stages     = $this->loadModel('programplan')->getPlanPairsForBudget($this->session->PRJ);
        $this->view->budget     = $this->budget->getByID($budgetID);
        $this->display();
    }

    /**
     * View a budget.
     *
     * @param  int  $budgetID
     * @access public
     * @return void
     */
    public function view($budgetID)
    {
        $this->view->stages     = $this->budget->getStages($this->session->PRJ);
        $this->view->subjects   = $this->budget->getSubjectOption();
        $this->view->stages     = $this->loadModel('programplan')->getPlanPairsForBudget($this->session->PRJ);
        $this->view->budget     = $this->budget->getByID($budgetID);
        $this->view->actions    = $this->loadModel('action')->getList('budget', $budgetID);
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->display();
    }

    /**
     * Delete a budget.
     *
     * @param  int     $budgetID
     * @param  varchar $confirm
     * @access public
     * @return void
     */
    public function delete($budgetID = 0, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            echo js::confirm($this->lang->budget->confirmDelete, $this->createLink('budget', 'delete', "id=$budgetID&confirm=yes"));
            exit;
        }
        else
        {
            $this->budget->delete(TABLE_BUDGET, $budgetID);
            die(js::locate(inlink('browse'), 'parent'));
        }

    }

    /**
     * Batch create budgets.
     *
     * @access public
     * @return void
     */
    public function batchCreate()
    {
        if($_POST)
        {
            $this->budget->batchCreate();

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = inlink('browse');
            $this->send($response);
        }

        $this->view->title      = $this->lang->budget->batchCreate . $this->lang->budget->common;
        $this->view->position[] = $this->lang->budget->batchCreate . $this->lang->budget->common;
        $this->view->subjects   = array('' => '') + $this->budget->getSubjectOption();
        $this->view->stages     = $this->loadModel('programplan')->getPlanPairsForBudget($this->session->PRJ);
        $this->display();
    }
}
