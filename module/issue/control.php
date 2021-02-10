<?php
/**
 * The control file of issue module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yong Lei <leiyong@easycorp.ltd>
 * @package     issue
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class issue extends control
{
    /**
     * Get issue list data.
     *
     * @param  string $browseType bySearch|open|assignTo|closed|suspended|canceled
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($browseType = 'all', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $uri = $this->app->getURI(true);
        $this->session->set('issueList',  $uri);

        /* Load pager */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $browseType = strtolower($browseType);
        $queryID    = ($browseType == 'bysearch') ? (int)$param : 0;
        $actionURL  = $this->createLink('issue', 'browse', "browseType=bysearch&queryID=myQueryID");
        $this->issue->buildSearchForm($actionURL, $queryID);

        $this->view->title      = $this->lang->issue->common . $this->lang->colon . $this->lang->issue->browse;
        $this->view->position[] = $this->lang->issue->browse;

        $this->view->pager      = $pager;
        $this->view->param      = $param;
        $this->view->orderBy    = $orderBy;
        $this->view->browseType = $browseType;
        $this->view->issueList  = $this->issue->getList($this->session->PRJ, $browseType, $queryID, $orderBy, $pager);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter|pofirst|nodeleted');

        $this->display();
    }

    /**
     * Create an issue.
     *
     * @param  string $from  issue|stakeholder
     * @param  string $owner
     * @access public
     * @return void
     */
    public function create($from = 'issue', $owner = '')
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

        $this->view->users  = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $this->view->owners = $this->loadModel('stakeholder')->getStakeholders4Issue();
        $this->view->from   = $from;
        $this->view->owner  = $owner;

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
            $issues = $this->issue->batchCreate();
            foreach($issues as $issue) $this->loadModel('action')->create('issue', $issue, 'Opened');

            die(js::locate($this->inLink('browse'), 'parent'));
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
     * Confirm the issue.
     *
     * @param  int    $issueID
     * @access public
     * @return void
     */
    public function confirm($issueID)
    {
        if($_POST)
        {
            $changes = $this->issue->confirm($issueID);

            if(dao::isError()) die(js::error(dao::getError()));
            $actionID = $this->loadModel('action')->create('issue', $issueID, 'Confirmed');

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
     * Resolve an issue.
     *
     * @param  int    $issueID
     * @access public
     * @return void
     */
    public function resolve($issueID)
    {
        if($_POST)
        {
            $data = fixer::input('post')->stripTags('steps', $this->config->allowedTags)->get();
            $resolution = $data->resolution;
            unset($_POST['resolution'], $_POST['resolvedBy'], $_POST['resolvedDate']);

            $objectID = '';
            if($resolution == 'totask')
            {
                $objectID = $this->issue->createTask($issueID);
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

                $objectLink = html::a($this->createLink('task', 'view', "id=$objectID"), $this->post->name);
                $comment    = sprintf($this->lang->issue->logComments[$resolution], $objectLink, "data-toggle='modal'");

                $this->loadModel('action')->create('task', $objectID, 'Opened', '');
                $this->loadModel('action')->create('issue', $issueID, 'Resolved', $comment);
            }

            if($resolution == 'tostory')
            {
                $objectID   = $this->issue->createStory($issueID);
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
                $objectLink = html::a($this->createLink('story', 'view', "id=$objectID"), $this->post->title, "data-toggle='modal'");
                $comment    = sprintf($this->lang->issue->logComments[$resolution], $objectLink);

                $this->loadModel('action')->create('story', $objectID, 'Opened', '');
                $this->loadModel('action')->create('issue', $issueID, 'Resolved', $comment);
            }

            if($resolution == 'tobug')
            {
                $objectID   = $this->issue->createBug($issueID);
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
                $objectLink = html::a($this->createLink('bug', 'view', "id=$objectID"), $this->post->title, "data-toggle='modal'");
                $comment    = sprintf($this->lang->issue->logComments[$resolution], $objectLink);

                $this->loadModel('action')->create('bug', $objectID, 'Opened', '');
                $this->loadModel('action')->create('issue', $issueID, 'Resolved', $comment);
            }

            if($resolution == 'torisk')
            {
                $objectID   = $this->issue->createRisk($issueID);
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
                $objectLink = html::a($this->createLink('risk', 'view', "id=$objectID"), $this->post->name, "data-toggle='modal'");
                $comment    = sprintf($this->lang->issue->logComments[$resolution], $objectLink);

                $this->loadModel('action')->create('risk', $objectID, 'Opened', '');
                $this->loadModel('action')->create('issue', $issueID, 'Resolved', $comment);
            }

            $this->issue->resolve($issueID, $data);
            if($resolution == 'resolved') $this->loadModel('action')->create('issue', $issueID, 'Resolved');
            $this->dao->update(TABLE_ISSUE)->set('objectID')->eq($objectID)->where('id')->eq($issueID)->exec();

            if(isonlybody()) $this->send(array('locate' => 'parent', 'message' => $this->lang->saveSuccess, 'result' => 'success'));
            die(js::locate(inLink('browse'), 'parent'));
        }

        $this->view->title = $this->lang->issue->resolve;
        $this->view->issue = $this->issue->getByID($issueID);
        $this->view->users = $this->loadModel('user')->getPairs('noclosed|nodeleted');

        $this->display();
    }

    /**
     * Get different types of resolution forms.
     *
     * @access public
     * @return void
     */
    public function ajaxGetResolveForm()
    {
        $data  = fixer::input('post')->get();
        $issue = $this->issue->getByID($data->issueID);
        $users = $this->loadModel('user')->getPairs('noclosed|nodeleted');

        $task = new stdClass();
        $task->module     = 0;
        $task->assignedTo = '';
        $task->name       = $issue->title;
        $task->type       = '';
        $task->estimate   = '';
        $task->desc       = $issue->desc;
        $task->estStarted = '';
        $task->deadline   = '';

        $this->view->resolution = $data->mode;
        $this->view->issue      = $issue;
        $this->view->users      = $users;
        $this->view->task       = $task;

        if(in_array($data->mode, array('tostory', 'tobug', 'totask')))
        {
            $this->loadModel('task');
            $this->loadModel('tree');
            $this->loadModel('project');
            $projects = $this->project->getExecutionsByProject($this->session->PRJ, 'all', 0, true);

            $projectID = $this->session->project;
            $projectID = isset($projects[$projectID]) ? $projectID : key($projects);

            $moduleOptionMenu = array('' => '/');
            if($data->mode == 'totask') $moduleOptionMenu = $this->tree->getOptionMenu($projectID, 'task');

            $this->view->moduleOptionMenu = $moduleOptionMenu;
            $this->view->showAllModule    = 'allModule';;
            $this->view->projects         = $projects;
            $this->view->projectID        = $projectID;
            $this->view->moduleID         = 0;
            $this->view->branch           = 0;
        }

        if(in_array($data->mode, array('tostory', 'tobug')))
        {
            $products  = $this->loadModel('product')->getProductPairsByProject($this->session->PRJ);
            $productID = $this->session->product;
            $productID = isset($products[$productID]) ? $productID : key($products);
            $branches  = $this->loadModel('branch')->getPairs($productID, 'noempty');

            $module = $data->mode == 'tostory' ? 'story' : 'bug';
            $moduleOptionMenu = $this->tree->getOptionMenu($productID, $module);

            $this->view->moduleOptionMenu = $moduleOptionMenu;
            $this->view->branches         = $branches;
            $this->view->products         = $products;
            $this->view->productID        = $productID;
        }

        switch($data->mode)
        {
            case 'totask':
                $this->loadModel('story');

                $this->view->project    = $this->project->getById($projectID);
                $this->view->members    = $this->project->getTeamMemberPairs($projectID, 'nodeleted');
                $this->view->stories    = $this->story->getProjectStoryPairs($projectID, 0, 0);
                $this->view->showFields = $this->config->task->custom->createFields;

                $this->display('issue', 'taskform');
                break;
            case 'tobug':
                $this->loadModel('bug');
                $this->view->builds     = $this->loadModel('build')->getProductBuildPairs($productID, '', 'noempty,noterminate,nodone');
                $this->view->buildID    = 0;
                $this->view->showFields = $this->config->bug->custom->createFields;

                $this->display('issue', 'bugform');
                break;
            case 'tostory':
                $this->loadModel('story');
                $this->view->plans      = $this->loadModel('productplan')->getPairsForStory($productID, key($branches), true);
                $this->view->showFields = $this->config->story->custom->createFields;
                $this->display('issue', 'storyform');
                break;
            case 'torisk':
                $this->app->loadLang('risk');
                $this->display('issue', 'riskform');
                break;
            case 'resolved':
                $this->display('issue', 'resolveform');
                break;
        }
    }

    /**
     * AJAX: return issues of a user in html select.
     *
     * @param  int    $userID
     * @param  string $id
     * @param  string $status
     * @access public
     * @return void
     */
    public function ajaxGetUserIssues($userID = '', $id = '', $status = 'all')
    {
        if($userID == '') $userID = $this->app->user->id;
        $user    = $this->loadModel('user')->getById($userID, 'id');
        $account = $user->account;

        $issues = $this->issue->getUserIssuePairs($account, 0, $status);

        if($id) die(html::select("issues[$id]", $issues, '', 'class="form-control"'));
        die(html::select('issue', $issues, '', 'class=form-control'));
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
        /* Set actions and get issue by id. */
        $issue = $this->issue->getByID($issueID);
        if(!$issue) die(js::error($this->lang->notFound) . js::locate('back'));

        $this->session->PRJ = $issue->PRJ;
        $this->commonAction($issueID, 'issue');

        $this->view->title      = $this->lang->issue->common . $this->lang->colon . $issue->title;
        $this->view->position[] = $this->lang->issue->common;
        $this->view->position[] = $this->lang->issue->basicInfo;

        $this->view->users = $this->loadModel('user')->getPairs('noletter|pofirst|nodeleted');
        $this->view->issue = $issue;
        $this->display();
    }

    /**
     * Common actions of issue module.
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
