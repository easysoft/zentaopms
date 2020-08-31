<?php
/**
 * The control file of issue module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     issue
 * @version     $Id: control.php 5145 2013-07-15 06:47:26Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class issue extends control
{
    /**
     * Get issue list data.
     *
     * @param  string $browseType
     * @param  int param
     * @param  string orderBy
     * @param  int recTotal
     * @param  int recPerPage
     * @param  int pageID
     * @access public
     * @return void
     */
    public function browse($browseType = 'all', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $browseType = strtolower($browseType);

        /* Load pager */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $queryID   = ($browseType == 'bysearch') ? (int)$param : 0;
        $actionURL = $this->createLink('issue', 'browse', "browseType=bysearch&queryID=myQueryID");
        $this->issue->buildSearchForm($actionURL, $queryID);

        $this->view->title      = $this->lang->issue->common . $this->lang->colon . $this->lang->issue->browse;
        $this->view->position[] = $this->lang->issue->browse;

        $this->view->pager      = $pager;
        $this->view->param      = $param;
        $this->view->orderBy    = $orderBy;
        $this->view->browseType = $browseType;
        $this->view->issueList  = $this->issue->getIssueList($browseType, $queryID, $orderBy, $pager);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|pofirst|nodeleted');

        $this->display();
    }

    /**
     * Create a issue.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $issueID = $this->issue->create();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('issue', $issueID, 'Opened');
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inLink('browse', 'browseType=all')));
        }

        $this->view->title      = $this->lang->issue->common . $this->lang->colon . $this->lang->issue->create;
        $this->view->position[] = $this->lang->issue->common;
        $this->view->position[] = $this->lang->issue->create;

        $this->view->users = $this->loadModel('user')->getPairs('noclosed|nodeleted');

        $this->display();
    }

   /**
    * Batch create issues.
    *
    * @access public
    * @return void
    */
    public function batchCreate()
    {
        if($_POST)
        {
            $results = $this->issue->batchCreate();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inLink('browse', 'browseType=all')));
        }

        $this->view->title      = $this->lang->issue->common . $this->lang->colon . $this->lang->issue->batchCreate;
        $this->view->position[] = $this->lang->issue->common;
        $this->view->position[] = $this->lang->issue->batchCreate;

        $this->view->users = $this->loadModel('user')->getPairs('noclosed|nodeleted');

        $this->display();
    }

    /**
     * Delete an issue.
     *
     * @param  int    $issueID
     * @param  string $confirm yes|no
     * @access public
     * @return void
     */
    public function delete($issueID = 0, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->issue->confirmDelete, inLink('delete', "issueID=$issueID&confirm=yes")));
        }
        else
        {
            $this->issue->delete(TABLE_ISSUE, $issueID);
            die(js::locate(inLink('browse'), 'parent'));
        }
    }

    /**
     * Edit an issue.
     *
     * @param  int    $issueID
     * @access public
     * @return void
     */
    public function edit($issueID)
    {
        if($_POST)
        {
            $changes = $this->issue->update($issueID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID = $this->loadModel('action')->create('issue', $issueID, 'Edited');
            $this->action->logHistory($actionID, $changes);
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inLink('browse', 'browseType=all')));
        }

        $this->view->title      = $this->lang->issue->common . $this->lang->colon . $this->lang->issue->edit;
        $this->view->position[] = $this->lang->issue->common;
        $this->view->position[] = $this->lang->issue->edit;

        $this->view->users = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $this->view->issue = $this->issue->getByID($issueID);

        $this->display();
    }

    /**
     * Assign an issue.
     *
     * @param  int    $issueID
     * @access public
     * @return void
     */
    public function assignTo($issueID)
    {
        if($_POST)
        {
            $changes = $this->issue->assignTo($issueID);

            if(dao::isError()) die(js::error(dao::getError()));
            $actionID = $this->loadModel('action')->create('issue', $issueID, 'Assigned', '', $this->post->assignedTo);

            $this->action->logHistory($actionID, $changes);
            die(js::closeModal('parent.parent', 'this'));
        }

        $this->view->issue = $this->issue->getByID($issueID);
        $this->view->users = $this->loadModel('user')->getPairs('noclosed|nodeleted');

        $this->display();
    }

    /**
     * Close an issue.
     *
     * @param  int    $issueID
     * @access public
     * @return void
     */
    public function close($issueID)
    {
        if($_POST)
        {
            $changes = $this->issue->close($issueID);

            if(dao::isError()) die(js::error(dao::getError()));
            $actionID = $this->loadModel('action')->create('issue', $issueID, 'Closed');

            $this->action->logHistory($actionID, $changes);
            die(js::closeModal('parent.parent', 'this'));
        }

        $this->view->issue = $this->issue->getByID($issueID);

        $this->display();
    }

    /**
     * Cancel an issue.
     *
     * @param  int    $issueID
     * @access public
     * @return void
     */
    public function cancel($issueID)
    {
        if($_POST)
        {
            $changes = $this->issue->cancel($issueID);

            if(dao::isError()) die(js::error(dao::getError()));
            $actionID = $this->loadModel('action')->create('issue', $issueID, 'Canceled');

            $this->action->logHistory($actionID, $changes);
            die(js::closeModal('parent.parent', 'this'));
        }

        $this->view->issue = $this->issue->getByID($issueID);

        $this->display();
    }

    /**
     * Activate an issue.
     *
     * @param  int    $issueID
     * @access public
     * @return void
     */
    public function activate($issueID)
    {
        if($_POST)
        {
            $changes = $this->issue->activate($issueID);

            if(dao::isError()) die(js::error(dao::getError()));
            $actionID = $this->loadModel('action')->create('issue', $issueID, 'Activated');

            $this->action->logHistory($actionID, $changes);
            die(js::closeModal('parent.parent', 'this'));
        }

        $this->view->issue = $this->issue->getByID($issueID);
        $this->view->users = $this->loadModel('user')->getPairs('noclosed|nodeleted');

        $this->display();
    }

    /**
     * Resolve a issue.
     *
     * @param  int    $issue
     * @access public
     * @return void
     */
    public function resolve($issue)
    {
        if($_POST)
        {
            $this->issue->resolve($issue);
            $resolution = $this->post->issue['resolution'];

            $objectID = '';
            if($resolution == 'totask')
            {
                $objectID   = $this->issue->createTask($issue);
                $objectLink = html::a($this->createLink('task', 'view', "id=$objectID"), $this->post->name, "data-toggle='modal'");
                $comment    = sprintf($this->lang->issue->logComments[$resolution], $objectLink);
                $this->loadModel('action')->create('task', $objectID, 'Opened', '');
                $this->loadModel('action')->create('issue', $issue, 'Resolved', $comment);
            }

            if($resolution == 'tostory')
            {
                $objectID   = $this->issue->createStory($issue);
                $objectLink = html::a($this->createLink('story', 'view', "id=$objectID"), $this->post->title, "data-toggle='modal'");
                $comment    = sprintf($this->lang->issue->logComments[$resolution], $objectLink);
                $this->loadModel('action')->create('story', $objectID, 'Opened', '');
                $this->loadModel('action')->create('issue', $issue, 'Resolved', $comment);
            }
            if($resolution == 'tobug')
            {
                $objectID   = $this->issue->createBug($issue);
                $objectLink = html::a($this->createLink('bug', 'view', "id=$objectID"), $this->post->title, "data-toggle='modal'");
                $comment    = sprintf($this->lang->issue->logComments[$resolution], $objectLink);
                $this->loadModel('action')->create('bug', $objectID, 'Opened', '');
                $this->loadModel('action')->create('issue', $issue, 'Resolved', $comment);
            }

            if($resolution == 'torisk')
            {
                $objectID   = $this->issue->createRisk($issue);
                $objectLink = html::a($this->createLink('risk', 'view', "id=$objectID"), $this->post->title, "data-toggle='modal'");
                $comment    = sprintf($this->lang->issue->logComments[$resolution], $objectLink);
                $this->loadModel('action')->create('risk', $objectID, 'Opened', '');
                $this->loadModel('action')->create('issue', $issue, 'Resolved', $comment);
            }

            $this->dao->update(TABLE_ISSUE)->set('objectID')->eq($objectID)->where('id')->eq($issue)->exec();
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title = $this->lang->issue->resolve;
        $this->view->issue = $this->issue->getByID($issue);
        $this->view->users = $this->loadModel('user')->getPairs('noletter');

        $this->prepairParams($this->view->issue);
        $this->display();
    }

    /**
     * prepairParams
     *
     * @param  int    $issue
     * @access public
     * @return void
     */
    public function prepairParams($issue)
    {
        $this->loadModel('task');
        $this->loadModel('risk');
        $this->loadModel('bug');
        $this->loadModel('tree');
        $this->loadModel('story');
        $this->loadModel('build');
        $this->loadModel('release');
        $this->loadModel('project')->getLimitedProject();
        $limitedProjects = !empty($_SESSION['limitedProjects']) ? $_SESSION['limitedProjects'] : '';

        $task = new stdClass();
        $task->module     = 0;
        $task->assignedTo = '';
        $task->name       = $issue->title;
        $task->story      = 0;
        $task->type       = '';
        $task->pri        = '3';
        $task->estimate   = '';
        $task->desc       = $issue->desc;
        $task->estStarted = '';
        $task->deadline   = '';
        $task->mailto     = '';
        $task->color      = '';

        $projectID        = $this->session->project;
        $project          = $this->project->getById($projectID);
        $users            = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $members          = $this->project->getTeamMemberPairs($projectID, 'nodeleted');
        $showAllModule    = isset($this->config->project->task->allModule) ? $this->config->project->task->allModule : '';
        $moduleOptionMenu = $this->tree->getTaskOptionMenu($projectID, 0, 0, $showAllModule ? 'allModule' : '');

        $stories = $this->story->getProjectStoryPairs($projectID, 0, 0);

        /* Set Custom*/
        foreach(explode(',', $this->config->task->customCreateFields) as $field) $customFields[$field] = $this->lang->task->$field;

        $this->view->customFields  = $customFields;
        $this->view->showFields    = $this->config->task->custom->createFields;
        $this->view->showAllModule = $showAllModule;

        $this->view->project          = $project;
        $this->view->task             = $task;
        $this->view->users            = $users;
        $this->view->stories          = $stories;
        $this->view->members          = $members;
        $this->view->moduleOptionMenu = $moduleOptionMenu;

        $this->view->projects = $this->loadModel('project')->getPairs();
        $this->view->program  = $this->loadModel('project')->getById($this->session->program);
        $this->view->products = $this->loadModel('product')->getPairs();
        $this->view->productID = $this->session->product;
        $this->view->moduleID  = 0;
        $this->view->branch    = 0;
        $this->view->modules   = $this->tree->getOptionMenu($this->view->productID, 'story');

        $this->view->builds  = $this->loadModel('build')->getProductBuildPairs($this->view->productID, '', 'noempty,noterminate,nodone');
        $this->view->buildID = 0;
    }

    /**
     *  View an issue.
     *
     * @param  int    $issueID
     * @access public
     * @return void
     */
    public function view($issueID)
    {
        $this->commonAction($issueID, 'issue');
        $issue = $this->issue->getByID($issueID);
        $this->view->title      = $this->lang->issue->common . $this->lang->colon . $issue->title;
        $this->view->position[] = $this->lang->issue->common;
        $this->view->position[] = $this->lang->issue->basicInfo;

        $this->view->users = $this->loadModel('user')->getPairs('noletter|pofirst|nodeleted');
        $this->view->issue = $issue;
        $this->display();
    }

    /**
     * commonAction
     *
     * @param  int    $issueID
     * @param  int    $object
     * @access public
     * @return void
     */
    public function commonAction($issueID, $object)
    {
        $this->view->actions = $this->loadModel('action')->getList($object, $issueID);
    }
}
