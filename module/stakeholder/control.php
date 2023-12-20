<?php
declare(strict_types=1);
class stakeholder extends control
{
    /**
     * 干系人列表页面。
     * Stakeholder list.
     *
     * @param  int    $projectID
     * @param  string $browseType all|inside|outside|key
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse(int $projectID, string $browseType = 'all', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->loadModel('project')->setMenu($projectID);

        /* Get stake holders list. */
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);
        $stakeholders = $this->stakeholder->getStakeholders($projectID, $browseType, $orderBy, $pager);

        /* Save SQL to session for previous and next buttons on the stakeholder detail page. */
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'stakeholder');

        $this->view->title        = $this->lang->stakeholder->browse;
        $this->view->pager        = $pager;
        $this->view->recTotal     = $recTotal;
        $this->view->recPerPage   = $recPerPage;
        $this->view->pageID       = $pageID;
        $this->view->projectID    = $projectID;
        $this->view->orderBy      = $orderBy;
        $this->view->browseType   = $browseType;
        $this->view->stakeholders = $stakeholders;

        $this->display();
    }

    /**
     * 创建一个干系人。
     * Create a stakeholder.
     *
     * @param  int    objectID
     * @access public
     * @return void
     */
    public function create(int $objectID = 0)
    {
        if($_POST)
        {
            $stakeholderData = form::data()
                ->setDefault('objectID', $objectID)
                ->get();

            $stakeholderID = $this->stakeholder->create($stakeholderData);

            if(!$stakeholderID || dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $stakeholderID));

            $moduleName = $this->app->tab == 'program' ? 'program'              : $this->moduleName;
            $methodName = $this->app->tab == 'program' ? 'stakeholder'          : 'browse';
            $param      = $this->app->tab == 'program' ? "programID=$objectID" : "projectID=$objectID";
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink($moduleName, $methodName, $param)));
        }

        $members = array();
        if($this->app->tab == 'program')
        {
            common::setMenuVars('program', $objectID);
            $members = $this->loadModel('program')->getTeamMemberPairs($objectID);
        }
        else
        {
            $this->loadModel('project')->setMenu($objectID);
            $members = $this->loadModel('user')->getTeamMemberPairs($objectID, 'project');
        }

        $stakeholders = $this->loadModel('stakeholder')->getStakeHolderPairs($objectID);
        foreach($members as $account => $realname)
        {
            if(isset($stakeholders[$account])) unset($members[$account]);
        }

        $this->view->title      = $this->lang->stakeholder->create;
        $this->view->companys   = $this->loadModel('company')->getOutsideCompanies();
        $this->view->programID  = $this->app->tab == 'program' ? $objectID : 0;
        $this->view->projectID  = $this->app->tab == 'project' ? $objectID : 0;
        $this->view->members    = $members;
        $this->view->objectID   = $objectID;

        $this->display();
    }

    /**
     * 批量创建干系人页面。
     * Batch create stakeholders.
     *
     * @param  int    $projectID
     * @param  string $dept
     * @param  int    $parentID
     * @access public
     * @return void
     */
    public function batchCreate(int $projectID, string $dept = '', int $parentID = 0)
    {
        if($_POST)
        {
            $stakeholderList = $this->stakeholder->batchCreate($projectID, $_POST['accounts']);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $stakeholderList));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('stakeholder', 'browse', "projectID=$projectID")));
        }

        if($this->app->tab == 'program')
        {
            common::setMenuVars('program', $projectID);
        }
        else
        {
            $this->loadModel('project')->setMenu($projectID);
        }

        $this->loadModel('dept');
        $this->loadModel('execution');

        $this->view->title              = $this->lang->stakeholder->batchCreate;
        $this->view->project            = $this->loadModel('project')->getByID($this->session->project);
        $this->view->users              = $this->loadModel('user')->getPairs('all|nodeleted|noclosed');
        $this->view->deptUsers          = $dept === '' ? array() : $this->dept->getDeptUserPairs((int)$dept);
        $this->view->dept               = $dept;
        $this->view->projectID          = $projectID;
        $this->view->depts              = $this->dept->getOptionMenu();
        $this->view->stakeholders       = $this->stakeholder->getStakeholders($projectID, 'all', 'id_desc');
        $this->view->parentStakeholders = $this->loadModel('program')->getStakeholders($parentID, 't1.id_desc');

        $this->display();
    }

    /**
     * 编辑一个干系人。
     * Edit a stakeholder.
     *
     * @param  int    $stakeholderID
     * @access public
     * @return void
     */
    public function edit(int $stakeholderID = 0)
    {
        $stakeholder = $this->stakeholder->getByID($stakeholderID);
        $this->loadModel('project')->setMenu($stakeholder->objectID);

        if($_POST)
        {
            $postData = form::data()->get();
            $changes  = $this->stakeholder->edit($stakeholderID, $postData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID = $this->loadModel('action')->create('stakeholder', $stakeholderID, 'Edited');
            $this->action->logHistory($actionID, $changes);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('stakeholder', 'browse', "projectID={$stakeholder->objectID}")));
        }

        $users = array();
        if($stakeholder->from == 'team') $users = $this->loadModel('user')->getTeamMemberPairs($this->session->project, 'project');
        if($stakeholder->from == 'company')
        {
            $members = $this->loadModel('user')->getTeamMemberPairs($this->session->project, 'project');
            $users   = $this->user->getPairs('noclosed');
            $users   = array_diff($users, $members);
        }

        if($stakeholder->objectType == 'project') $this->view->projectID = $stakeholder->objectID;

        $this->view->title       = $this->lang->stakeholder->edit;
        $this->view->stakeholder = $stakeholder;
        $this->view->users       = $users;
        $this->view->companys    = $this->loadModel('company')->getOutsideCompanies();

        $this->display();
    }

    /**
     * Ajax：获取项目集或项目的团队成员。
     * Ajax: Get team members of the program or project.
     *
     * @param  int    $programID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function ajaxGetMembers(int $programID = 0, int $projectID = 0)
    {
        if($programID)
        {
            $members = $this->loadModel('program')->getTeamMemberPairs($programID);
        }
        else
        {
            $members = $this->loadModel('user')->getTeamMemberPairs($projectID, 'project');
        }

        $stakeholders = $this->loadModel('stakeholder')->getStakeHolderPairs($programID ? $programID : $projectID);
        foreach($members as $account => $realname)
        {
            if(isset($stakeholders[$account])) unset($members[$account]);
        }

        $items = array();
        foreach($members as $account => $realname) $items[] = array('value' => $account, 'text' => $realname, 'keys' => $realname);

        return print(json_encode($items));
    }

    /**
     * Ajax get company user.
     *
     * @param  string $user
     * @param  int    $programID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function ajaxGetCompanyUser($user = '', $programID = 0, $projectID = 0)
    {
        if($programID)
        {
            $members = $this->loadModel('program')->getTeamMemberPairs($programID);
        }
        else
        {
            $members = $this->loadModel('user')->getTeamMemberPairs($projectID, 'project');
        }

        $users        = $this->loadModel('user')->getPairs('noclosed');
        $companyUsers = array_diff($users, $members);
        $stakeholders = $this->loadModel('stakeholder')->getStakeHolderPairs($programID ? $programID : $projectID);
        foreach($companyUsers as $account => $realname)
        {
            if(isset($stakeholders[$account])) unset($companyUsers[$account]);
        }

        $userItems = array();
        foreach($companyUsers as $account => $realname) $userItems[] = array('text' => $realname, 'value' => $account);

        return print(json_encode($userItems));
    }

    /**
     * Ajax get outside user.
     *
     * @access public
     * @return void
     */
    public function ajaxGetOutsideUser($objectID = 0)
    {
        $users        = $this->loadModel('user')->getPairs('noclosed|outside|noletter');
        $stakeholders = $this->loadModel('stakeholder')->getStakeHolderPairs($objectID);
        foreach($users as $account => $realname)
        {
            if(isset($stakeholders[$account])) unset($users[$account]);
        }

        $items = array();
        foreach($users as $account => $realname) $items[] = array('text' => $realname, 'value' => $account);

        return print(json_encode($items));
    }

    /**
     * Ajax get control.
     *
     * @access public
     * @return void
     */
    public function ajaxGetControl($activityID = 0)
    {
        $plan      = $this->dao->select('*')->from(TABLE_INTERVENTION)->where('activity')->eq($activityID)->fetch();
        $begin     = html::input("begin[$activityID]", isset($plan->begin) ? $plan->begin : '', 'class="form-control form-date"');
        $realBegin = html::input("realBegin[$activityID]", isset($plan->realBegin) ? $plan->realBegin: '', 'class="form-control form-date"');
        $status    = html::select("status[$activityID]", $this->lang->stakeholder->planField->stautsList, isset($plan->status) ? $plan->status : '', 'class="form-control"');
        $situation = html::select("situation[$activityID]", $this->lang->stakeholder->situationList, isset($plan->situation) ? $plan->situation : '', 'class="form-control"');

        $stakeholders = $this->stakeholder->getListByType();
        $partakeList = isset($plan->partake) ? json_decode($plan->partake) : new stdclass();
        $insideList  = array("<td style='width: 100px;'></td>");
        $outsideList = array("<td style='width: 100px;'></td>");
        if(isset($stakeholders['inside']))
        {
            $insideList = array();
            foreach($stakeholders['inside'] as $user)
            {
                $partake = isset($partakeList->{$user->account}) ? $partakeList->{$user->account} : '';
                $insideList[] = "<td style='width: 100px;'>" . html::select("partake[$activityID][$user->account]", $this->lang->stakeholder->planField->partakeList, $partake, "class='form-control'") . '</td>';
            }
        }

        if(isset($stakeholders['outside']))
        {
            $outsideList = array();
            foreach($stakeholders['outside'] as $user)
            {
                $partake = isset($partakeList->{$user->account}) ? $partakeList->{$user->account} : '';
                $outsideList[] = "<td style='width: 100px;'>" . html::select("partake[$activityID][$user->account]", $this->lang->stakeholder->planField->partakeList, $partake, "class='form-control'") . '</td>';
            }
        }

        $partakeList = array_merge($insideList, $outsideList);

        echo json_encode(array('begin' => $begin, 'realBegin' => $realBegin, 'status' => $status, 'situation' => $situation, 'partakeList' => $partakeList));
    }

    /**
     * Deleted user.
     *
     * @access public
     * @param  int    $userID
     * @param  string $confirm  yes|no
     * @return void
    */
    public function delete($userID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            $confirmURL = inLink('delete', "userID=$userID&confirm=yes");
            return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.confirm('{$this->lang->stakeholder->confirmDelete}').then((res) => {if(res) $.ajaxSubmit({url: '$confirmURL'});});"));
        }
        else
        {
            $stakeholder = $this->stakeholder->getByID($userID);
            $this->stakeholder->delete(TABLE_STAKEHOLDER, $userID);
            $this->loadModel('user')->updateUserView($this->session->project, 'project');

            /* Update linked products view. */
            if($stakeholder->objectType == 'project' and $stakeholder->objectID)
            {
                $this->loadModel('project')->updateInvolvedUserView($stakeholder->objectID, array($stakeholder->user));
            }
            return $this->send(array('result' => 'success', 'load' => true));
        }
    }

    /**
     * User details.
     *
     * @access public
     * @param  int  $userID
     * @return void
    */
    public function view($userID = 0)
    {
        $user = $this->stakeholder->getByID($userID);

        $this->loadModel('project')->setMenu($user->objectID);
        $this->commonAction($userID, 'stakeholder');

        if($user->objectType == 'project') $this->view->projectID = $user->objectID;

        $this->view->title      = $this->lang->stakeholder->common . $this->lang->colon . $this->lang->stakeholder->view;
        $this->view->user       = $user;
        $this->view->users      = $this->loadModel('user')->getTeamMemberPairs($this->session->project, 'project', 'nodeleted');
        $this->view->expects    = $this->stakeholder->getExpectByUser($userID);
        $this->view->preAndNext = $this->loadModel('common')->getPreAndNextObject('stakeholder', $userID);

        $this->display();
    }

    /**
     * Add communication record.
     *
     * @access public
     * @param  int  $userID
     * @return void
    */
    public function communicate($userID)
    {
        $this->commonAction($userID, 'stakeholder');
        if(!empty($_POST))
        {
            $this->stakeholder->communicate($userID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'closeModal' => true));
        }

        $this->view->title      = $this->lang->stakeholder->common . $this->lang->colon . $this->lang->stakeholder->communicate;
        $this->view->user       = $this->stakeholder->getByID($userID);
        $this->view->users      = $this->loadModel('user')->getTeamMemberPairs($this->session->project, 'project', 'nodeleted');
        $this->display();
    }

    /**
     * Add expect record.
     *
     * @access public
     * @param  int  $expectID
     * @return void
    */
    public function expect($expectID)
    {
        $user = $this->stakeholder->getByID($expectID);

        if(!empty($_POST))
        {
            $this->stakeholder->expect($user->id);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'closeModal' => true));
        }

        $this->view->title      = $this->lang->stakeholder->common . $this->lang->colon . $this->lang->stakeholder->communicate;
        $this->view->user       = $user;
        $this->display();
    }

    /**
     * Common actions of stakeholder module.
     *
     * @param  int    $stakeholderID
     * @access public
     * @return void
     */
    public function commonAction($stakeholderID, $object)
    {
        $this->view->actions = $this->loadModel('action')->getList($object, $stakeholderID);
    }

    /**
     * Issue details.
     *
     * @access public
     * @param  int  $userID
     * @return void
    */
    public function userIssue($userID)
    {
        $this->app->loadLang('issue');
        $stakeholder = $this->stakeholder->getByID($userID);

        $this->view->title      = $this->lang->stakeholder->common . $this->lang->colon . $this->lang->stakeholder->communicate;

        $this->view->stakeholder = $stakeholder;
        $this->view->projectID   = $stakeholder->objectID;
        $this->view->issueList   = $this->stakeholder->getStakeholderIssue($stakeholder->user);

        $this->display();
    }
}
