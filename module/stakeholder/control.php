<?php
class stakeholder extends control
{
    /**  
     * Stakeholder list.
     *
     * @param  string $browseType
     * @param  string orderBy
     * @param  int recTotal
     * @param  int recPerPage
     * @param  int pageID
     * @access public
     * @return void
     */
    public function browse($browseType = 'all', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $stakeholders = $this->stakeholder->getStakeholders($browseType, $orderBy, $pager);

        $this->view->title       = $this->lang->stakeholder->browse;
        $this->view->position[]  = $this->lang->stakeholder->browse;

        $this->view->pager        = $pager;
        $this->view->recTotal     = $recTotal;
        $this->view->recPerPage   = $recPerPage;
        $this->view->pageID       = $pageID;
        $this->view->orderBy      = $orderBy;
        $this->view->browseType   = $browseType;
        $this->view->stakeholders = $stakeholders;

        $this->display();
    }

    /**  
     * Create a stakeholder.
     *
     * @param  int programID
     * @access public
     * @return void
     */
    public function create($programID = 0)
    {
        if($_POST)
        {
            $stakeholderID = $this->stakeholder->create($programID);

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;

            if(!$stakeholderID or dao::isError())
            {    
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            $actionID = $this->loadModel('action')->create('stakeholder', $stakeholderID, 'added');

            $moduleName = $programID ? 'program'              : $this->moduleName;
            $methodName = $programID ? 'stakeholder'          : 'browse';
            $param      = $programID ? "programID=$programID" : '';
            $locate     = $this->createLink($moduleName, $methodName, $param);
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate));
        }

        if($programID)
        {
            $this->loadModel('program');
            $this->app->rawModule = 'program';
            $this->app->rawMethod = 'stakeholder';
            $this->lang->navGroup->program = 'program';
            $this->lang->program->switcherMenu = $this->program->getPGMSwitcher($programID, true);
            $this->program->setPGMViewMenu($programID);

            $this->view->members = $this->program->getPRJTeamMemberPairs($programID);
        }
        else
        {
            $this->view->members = $this->loadModel('project')->getTeamMemberPairs($this->session->PRJ);
        }

        $this->view->title      = $this->lang->stakeholder->create;
        $this->view->position[] = $this->lang->stakeholder->create;
        $this->view->companys   = $this->loadModel('company')->getOutsideCompanies();
        $this->view->programID  = $programID;

        $this->display();
    }

    /**  
     * Batch create stakeholders.
     *
     * @access public
     * @return void
     */
    public function batchCreate($dept = '', $parentID = 0) 
    {    
        if($_POST)
        {    
            $this->stakeholder->batchCreate();
            die(js::locate($this->createLink('stakeholder', 'browse'), 'parent'));
        }    

        $this->loadModel('user');
        $this->loadModel('dept');
        $deptUsers = $dept === '' ? array() : $this->dept->getDeptUserPairs($dept);

        $this->view->title      = $this->lang->stakeholder->batchCreate;
        $this->view->position[] = $this->lang->stakeholder->batchCreate;

        $this->view->project            = $this->loadModel('program')->getPRJByID($this->session->PRJ);
        $this->view->users              = $this->user->getPairs('all|nodeleted|noclosed');
        $this->view->deptUsers          = $deptUsers;
        $this->view->dept               = $dept;
        $this->view->projectID          = $this->session->PRJ;
        $this->view->depts              = array('' => '') + $this->dept->getOptionMenu();
        $this->view->stakeholders       = $this->stakeholder->getStakeholders('all', 'id_desc');
        $this->view->parentStakeholders = $this->program->getStakeholders($parentID, 't1.id_desc');

        $this->display();
    }

    /**  
     * Edit a stakeholder.
     *
     * @param  int $stakeholderID
     * @access public
     * @return void
     */
    public function edit($stakeholderID = 0)
    {
        $stakeholder = $this->stakeholder->getByID($stakeholderID);
        if($_POST)
        {
            $changes = $this->stakeholder->edit($stakeholderID);

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;

            if(dao::isError())
            {    
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                $this->send($response);
            }

            $actionID = $this->loadModel('action')->create('stakeholder', $stakeholderID, 'Edited');
            $this->action->logHistory($actionID, $changes);
            $response['locate'] = $this->createLink('stakeholder', 'browse', '');
            $this->send($response);
        }

        $users = array('' => '');
        if($stakeholder->type == 'team') $users = $this->loadModel('project')->getTeamMemberPairs($this->session->PRJ);
        elseif($stakeholder->type == 'company')
        {
            $members = $this->loadModel('project')->getTeamMemberPairs($this->session->PRJ);
            $users   = $this->loadModel('user')->getPairs('noclosed');
            $users   = array('' => '') + array_diff($users, $members);
        }

        $this->view->title       = $this->lang->stakeholder->edit;
        $this->view->position[]  = $this->lang->stakeholder->edit;

        $this->view->stakeholder = $stakeholder;
        $this->view->users       = $users;
        $this->view->companys    = $this->loadModel('company')->getOutsideCompanies();
        $this->display();
    }

    /**  
     * Ajax get members.
     *
     * @param  string $user
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function ajaxGetMembers($user = '', $programID = 0)
    {
        $members = $programID == 0 ? $this->loadModel('project')->getTeamMemberPairs($this->session->PRJ) : $this->loadModel('program')->getPRJTeamMemberPairs($programID);
        die(html::select('user', $members, $user, "class='form-control chosen'"));
    }

    /**  
     * Ajax get company user.
     *
     * @param  string $user
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function ajaxGetCompanyUser($user = '', $programID = 0)
    {
        $members = $programID == 0 ? $this->loadModel('project')->getTeamMemberPairs($this->session->PRJ) : $this->loadModel('program')->getPRJTeamMemberPairs($programID);
        $users   = $this->loadModel('user')->getPairs('noclosed');
        $companyUsers = array('' => '') + array_diff($users, $members);

        die(html::select('user', $companyUsers, $user, "class='form-control chosen'"));
    }

    /**  
     * Ajax get outside user.
     *
     * @access public
     * @return void
     */
    public function ajaxGetOutsideUser()
    {
        $users = $this->loadModel('user')->getPairs('noclosed|outside|noletter');

        die(html::select('user', $users, '', "class='form-control chosen' onchange=changeUser(this.value);"));
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

        die(json_encode(array('begin' => $begin, 'realBegin' => $realBegin, 'status' => $status, 'situation' => $situation, 'partakeList' => $partakeList)));
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
            die(js::confirm($this->lang->stakeholder->confirmDelete, inLink('delete', "userID=$userID&confirm=yes")));
        }
        else
        {
            $this->stakeholder->delete(TABLE_STAKEHOLDER, $userID);
            $this->loadModel('user')->updateUserView($this->session->PRJ, 'project');
            die(js::reload('parent'));
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
        $this->commonAction($userID, 'stakeholder');
        $this->view->title      = $this->lang->stakeholder->common . $this->lang->colon . $this->lang->stakeholder->view;
        $this->view->position[] = $this->lang->stakeholder->view;

        $this->view->user    = $this->stakeholder->getByID($userID);
        $this->view->users   = $this->loadModel('project')->getTeamMemberPairs($this->session->PRJ ,'nodeleted');
        $this->view->expects = $this->stakeholder->getExpectByUser($userID);

        $this->display();
    }

    /**
     * Stakeholder issues.
     *
     * @access public
     * @return void
    */
    public function issue()
    {
        $this->loadModel('issue');

        if(common::hasPriv('issue', 'create')) $this->lang->TRActions = html::a($this->createLink('issue', 'create', 'from=stakeholder'), "<i class='icon icon-sm icon-plus'></i> " . $this->lang->issue->create, '', "class='btn btn-primary'");

        $this->view->title      = $this->lang->stakeholder->common . $this->lang->colon . $this->lang->stakeholder->issue;
        $this->view->position[] = $this->lang->stakeholder->issue;

        $this->view->users    = $this->loadModel('company')->getCompanyUserPairs();
        $this->view->issues   = $this->stakeholder->getIssues();
        $this->display();
    }

    /**
     * View activity's issues.
     *
     * @access public
     * @return void
    */
    public function viewIssue($activityID = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', true);
        $pager   = pager::init($recTotal, $recPerPage, $pageID);

        $this->view->users    = $this->loadModel('user')->getPairs('all|noletter');
        $this->view->activity = $this->dao->findByID($activityID)->from(TABLE_ACTIVITY)->fetch();
        $this->view->issues   = $this->loadModel('issue')->getStakeholderIssue('', $activityID, $pager);
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
            $result = $this->stakeholder->communicate($userID);
            if(dao::isError()) die(js::error(dao::getError()));
            if(isonlybody()) die(js::closeModal('parent.parent', 'this'));
        }

        $this->view->title      = $this->lang->stakeholder->common . $this->lang->colon . $this->lang->stakeholder->communicate;
        $this->view->position[] = $this->lang->stakeholder->view;
        $this->view->user       = $this->stakeholder->getByID($userID);
        $this->view->users      = $this->loadModel('project')->getTeamMemberPairs($this->session->PRJ ,'nodeleted');
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
            $expectID = $this->stakeholder->expect($user->id);
            if(dao::isError()) die(js::error(dao::getError()));

            die(js::closeModal('parent.parent', 'this'));
        }

        $this->view->title      = $this->lang->stakeholder->common . $this->lang->colon . $this->lang->stakeholder->communicate;
        $this->view->position[] = $this->lang->stakeholder->view;
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
        $this->view->position[] = $this->lang->stakeholder->view;

        $this->view->stakeholder = $stakeholder;
        $this->view->issueList   = $this->stakeholder->getStakeholderIssue($stakeholder->user);

        $this->display();
    }
}
