<?php
declare(strict_types=1);
/**
 * The control file of gitlab module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: ${FILE_NAME} 5144 2020/1/8 8:10 下午 chenqi@cnezsoft.com $
 * @link        https://www.zentao.net
 */
class gitlab extends control
{
    /**
     * The gitlab constructor.
     * @param string $moduleName
     * @param string $methodName
     */
    public function __construct(string $moduleName = '', string $methodName = '')
    {
        parent::__construct($moduleName, $methodName);

        if(stripos($this->methodName, 'ajax') === false)
        {
            if(!commonModel::hasPriv('space', 'browse')) $this->loadModel('common')->deny('space', 'browse', false);

            if(!in_array(strtolower(strtolower($this->methodName)), array('browseproject', 'browsegroup', 'browseuser', 'browsebranch', 'browsetag')))
            {
                if(!commonModel::hasPriv('instance', 'manage')) $this->loadModel('common')->deny('instance', 'manage', false);
            }
        }
        /* This is essential when changing tab(menu) from gitlab to repo. */
        /* Optional: common::setMenuVars('devops', $this->session->repoID); */
        $this->loadModel('ci')->setMenu();
    }

    /**
     * Gitlab列表。
     * Browse gitlab.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse(string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Admin user don't need bind. */
        $gitlabList = $this->gitlab->getList($orderBy, $pager);

        $this->view->title      = $this->lang->gitlab->common . $this->lang->colon . $this->lang->gitlab->browse;
        $this->view->gitlabList = $gitlabList;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;

        $this->display();
    }

    /**
     * 创建一个gitlab。
     * Create a gitlab.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $gitlab = form::data($this->config->gitlab->form->create)
                ->add('type', 'gitlab')
                ->add('private',md5((string)rand(10,113450)))
                ->add('createdBy', $this->app->user->account)
                ->add('createdDate', helper::now())
                ->trim('url,token')
                ->skipSpecial('url,token,account,password')
                ->remove('account,password,appType')
                ->get();
            $this->checkToken($gitlab);
            $gitlabID = $this->loadModel('pipeline')->create($gitlab);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action');
            $actionID = $this->action->create('gitlab', $gitlabID, 'created');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('space', 'browse')));
        }

        $this->view->title = $this->lang->gitlab->common . $this->lang->colon . $this->lang->gitlab->lblCreate;

        $this->display();
    }

    /**
     * Gitlab详情页。
     * View a gitlab.
     * @param  int    $gitlabID
     * @access public
     * @return void
     */
    public function view(int $gitlabID)
    {
        $gitlab = $this->gitlab->getByID($gitlabID);

        $this->view->title      = $this->lang->gitlab->common . $this->lang->colon . $this->lang->gitlab->view;
        $this->view->gitlab     = $gitlab;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed');
        $this->view->actions    = $this->loadModel('action')->getList('gitlab', $gitlabID);
        $this->view->preAndNext = $this->loadModel('common')->getPreAndNextObject('pipeline', $gitlabID);
        $this->display();
    }

    /**
     * 编辑gitlab。
     * Edit a gitlab.
     *
     * @param  int    $gitlabID
     * @access public
     * @return void
     */
    public function edit(int $gitlabID)
    {
        $oldGitLab = $this->gitlab->getByID($gitlabID);

        if($_POST)
        {
            $gitlab = form::data($this->config->gitlab->form->edit)->trim('url,token')->get();
            $this->checkToken($gitlab, $gitlabID);
            $this->gitlab->update($gitlabID);
            $gitLab = $this->gitlab->getByID($gitlabID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action');
            $actionID = $this->action->create('gitlab', $gitlabID, 'edited');
            $changes  = common::createChanges($oldGitLab, $gitLab);
            $this->action->logHistory($actionID, $changes);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $this->view->title  = $this->lang->gitlab->common . $this->lang->colon . $this->lang->gitlab->edit;
        $this->view->gitlab = $oldGitLab;

        $this->display();
    }

    /**
     * Bind gitlab user to zentao users.
     *
     * @param  int     $gitlabID
     * @param  string  $type
     * @access public
     * @return void
     */
    public function bindUser(int $gitlabID, string $type = 'all')
    {
        $userPairs = $this->loadModel('user')->getPairs('noclosed|noletter');

        $gitlab = $this->gitlab->getByID($gitlabID);
        $user   = $this->gitlab->apiGetCurrentUser($gitlab->url, $gitlab->token);
        if(!isset($user->is_admin) or !$user->is_admin) return $this->send(array('result' => 'fail', 'message' => $this->lang->gitlab->tokenLimit, 'locate' => $this->createLink('gitlab', 'edit', array('gitlabID' => $gitlabID))));

        $zentaoUsers = $this->dao->select('account,email,realname')->from(TABLE_USER)->where('deleted')->eq('0')->fetchAll('account');

        if($_POST)
        {
            $users       = $this->post->zentaoUsers;
            $gitlabNames = $this->post->gitlabUserNames;

            $result = $this->gitlabZen->checkUserRepeat($users);
            if($result['result'] != 'success') return $this->send($result);

            $this->gitlabZen->bindUsers($gitlabID, $users, $gitlabNames, $zentaoUsers);

            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->sendSuccess(array('message' => $this->lang->saveSuccess, 'load' => helper::createLink('space', 'browse')));
        }

        $userList      = array();
        $gitlabUsers   = $this->gitlab->apiGetUsers($gitlabID);
        $bindedUsers   = $this->gitlab->getUserAccountIdPairs($gitlabID);
        $matchedResult = $this->gitlab->getMatchedUsers($gitlabID, $gitlabUsers, $zentaoUsers);

        foreach($gitlabUsers as $gitlabUserID => $gitlabUser)
        {
            $user = new stdclass();
            $user->email            = '';
            $user->status           = 'notBind';
            $user->gitlabID         = $gitlabUser->id;
            $user->gitlabEmail      = $gitlabUser->email;
            $user->gitlabUser       = $gitlabUser->realname . '@' . $gitlabUser->account;
            $user->gitlabUserAvatar = $gitlabUser->avatar;

            $user->zentaoUsers = isset($matchedResult[$gitlabUser->id]) ? $matchedResult[$gitlabUser->id]->zentaoAccount : '';
            if($user->zentaoUsers)
            {
                if(isset($zentaoUsers[$user->zentaoUsers])) $user->email = $zentaoUsers[$user->zentaoUsers]->email;

                if(isset($bindedUsers[$user->zentaoUsers]) && $bindedUsers[$user->zentaoUsers] == $gitlabUser->id)
                {
                    $user->status = 'binded';
                    if(!isset($bindedUsers[$user->zentaoUsers])) $user->status = 'bindedError';
                }
            }

            if($type != 'all' && $user->status != $type) continue;
            $userList[] = $user;
        }

        $this->view->title       = $this->lang->gitlab->bindUser;
        $this->view->type        = $type;
        $this->view->gitlabID    = $gitlabID;
        $this->view->recTotal    = count($userList);
        $this->view->userList    = $userList;
        $this->view->userPairs   = $userPairs;
        $this->view->zentaoUsers = $zentaoUsers;
        $this->display();
    }

    /**
     * 删除一条gitlab记录。
     * Delete a gitlab.
     *
     * @param  int    $gitlabID
     * @access public
     * @return void
     */
    public function delete(int $gitlabID)
    {
        $oldGitLab = $this->loadModel('pipeline')->getByID($gitlabID);
        $actionID  = $this->pipeline->deleteByObject($gitlabID, 'gitlab');
        if(!$actionID)
        {
            $response['result']   = 'fail';
            $response['callback'] = sprintf('zui.Modal.alert("%s");', $this->lang->pipeline->delError);

            return $this->send($response);
        }

        $gitLab   = $this->gitlab->getByID($gitlabID);
        $changes  = common::createChanges($oldGitLab, $gitLab);
        $this->loadModel('action')->logHistory($actionID, $changes);

        $response['load']   = $this->createLink('space', 'browse');
        $response['result'] = 'success';
        return $this->send($response);
    }

    /**
     * 检查post的token是否有管理员权限。
     * Check post token has admin permissions.
     *
     * @param  object    $gitlab
     * @param  int       $gitlabID
     * @access protected
     * @return void
     */
    protected function checkToken(object $gitlab, int $gitlabID = 0)
    {
        $this->dao->update('gitlab')->data($gitlab)->batchCheck($gitlabID ? $this->config->gitlab->edit->requiredFields : $this->config->gitlab->create->requiredFields, 'notempty');
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        if(strpos($gitlab->url, 'http') !== 0) return $this->send(array('result' => 'fail', 'message' => array('url' => array(sprintf($this->lang->gitlab->hostError, $this->config->gitlab->minCompatibleVersion)))));
        if(!$gitlab->token) return $this->send(array('result' => 'fail', 'message' => array('token' => array($this->lang->gitlab->tokenError))));

        $user = $this->gitlab->checkTokenAccess($gitlab->url, $gitlab->token);

        if(is_bool($user)) return $this->send(array('result' => 'fail', 'message' => array('url' => array(sprintf($this->lang->gitlab->hostError, $this->config->gitlab->minCompatibleVersion)))));
        if(!isset($user->id)) return $this->send(array('result' => 'fail', 'message' => array('token' => array($this->lang->gitlab->tokenError))));

        /* Verify version compatibility. */
        $result = $this->gitlab->getVersion($gitlab->url, $gitlab->token);
        if(empty($result) or !isset($result->version) or (version_compare($result->version, $this->config->gitlab->minCompatibleVersion, '<'))) return $this->send(array('result' => 'fail', 'message' => array('url' => array($this->lang->gitlab->notCompatible))));
    }

    /**
     * Webhook api.
     *
     * @access public
     * @return void
     */
    public function webhook()
    {
        $this->gitlab->webhookCheckToken();
        $product = $this->get->product;
        $gitlab  = $this->get->gitlab;
        $project = $this->get->project;

        $input       = file_get_contents('php://input');
        $requestBody = json_decode($input);
        $result      = $this->gitlab->webhookParseBody($requestBody, $gitlab);

        $this->gitlabZen->recordWebhookLogs($input, $result);

        if($result->action == 'updateissue' and isset($result->changes->assignees)) $this->gitlab->webhookAssignIssue($result);

        //if($result->action = 'reopenissue') $this->gitlab->webhookIssueReopen($gitlab, $result);

        if($result->action == 'closeissue') $this->gitlab->webhookCloseIssue($result);

        if($result->action == 'updateissue') $this->gitlab->webhookSyncIssue($gitlab, $result);

        $this->view->result = 'success';
        $this->view->status = 'ok';
        $this->view->data   = $result->object;
        $this->display();
    }

    /**
     * Gitlab群组列表。
     * Browse gitlab group.
     *
     * @param  int     $gitlabID
     * @param  string  $orderBy
     * @access public
     * @return void
     */
    public function browseGroup(int $gitlabID, string $orderBy = 'name_asc')
    {
        if(!$this->app->user->admin)
        {
            $openID = $this->gitlab->getUserIDByZentaoAccount($gitlabID, $this->app->user->account);
            if(!$openID) return print(js::alert($this->lang->gitlab->mustBindUser) . js::locate($this->createLink('gitlab', 'browse')));
        }

        $fixOrderBy  = str_replace('fullName', 'name', $orderBy);
        $keyword     = fixer::input('post')->setDefault('keyword', '')->get('keyword');
        $groups      = $this->gitlab->apiGetGroups($gitlabID, $fixOrderBy, '', $keyword);
        $adminGroups = $this->gitlab->apiGetGroups($gitlabID, $fixOrderBy, 'owner', $keyword);

        $adminGroupIDList = array();
        foreach($adminGroups as $group) $adminGroupIDList[] = $group->id;

        $this->view->title            = $this->lang->gitlab->common . $this->lang->colon . $this->lang->gitlab->browseGroup;
        $this->view->gitlabID         = $gitlabID;
        $this->view->gitlab           = $this->gitlab->getByID($gitlabID);
        $this->view->gitlabGroupList  = $groups;
        $this->view->adminGroupIDList = $adminGroupIDList;
        $this->view->orderBy          = $orderBy;
        $this->view->keyword          = $keyword;
        $this->display();
    }

    /**
     * 创建一个gitlab群组。
     * Creat a gitlab group.
     *
     * @param  int     $gitlabID
     * @access public
     * @return void
     */
    public function createGroup(int $gitlabID)
    {
        if(!$this->app->user->admin)
        {
            $openID = $this->gitlab->getUserIDByZentaoAccount($gitlabID, $this->app->user->account);
            if(!$openID) return print(js::alert($this->lang->gitlab->mustBindUser) . js::locate($this->createLink('gitlab', 'browse')));
        }

        if($_POST)
        {
            $this->gitlab->createGroup($gitlabID);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browseGroup', "gitlabID=$gitlabID")));
        }

        $gitlab = $this->gitlab->getByID($gitlabID);

        $this->view->title    = $this->lang->gitlab->common . $this->lang->colon . $this->lang->gitlab->group->create;
        $this->view->gitlab   = $gitlab;
        $this->view->gitlabID = $gitlabID;
        $this->display();
    }

    /**
     * 编辑一个gitlab群组。
     * Edit a gitlab group.
     *
     * @param  int     $gitlabID
     * @param  int     $userID
     * @access public
     * @return void
     */
    public function editGroup(int $gitlabID, int $groupID)
    {
        if(!$this->app->user->admin)
        {
            $openID = $this->gitlab->getUserIDByZentaoAccount($gitlabID, $this->app->user->account);
            if(!$openID) return print(js::alert($this->lang->gitlab->mustBindUser) . js::locate($this->createLink('space', 'browse')));

            $members = $this->gitlab->apiGetGroupMembers($gitlabID, $groupID, $openID);
            if(empty($members) or $members[0]->access_level < $this->config->gitlab->accessLevel['owner']) return print(js::alert($this->lang->gitlab->noAccess) . js::locate($this->createLink('space', 'browse')));
        }

        if($_POST)
        {
            $this->gitlab->editGroup($gitlabID);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browseGroup', "gitlabID=$gitlabID")));
        }

        $gitlab = $this->gitlab->getByID($gitlabID);
        $group  = $this->gitlab->apiGetSingleGroup($gitlabID, $groupID);

        $this->view->title    = $this->lang->gitlab->common . $this->lang->colon . $this->lang->gitlab->group->edit;
        $this->view->gitlab   = $gitlab;
        $this->view->group    = $group;
        $this->view->gitlabID = $gitlabID;
        $this->display();
    }

    /**
     * 删除一个gitlab群组。
     * Delete a gitlab group.
     *
     * @param  int    $gitlabID
     * @param  int    $groupID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function deleteGroup(int $gitlabID, int $groupID, string $confirm = 'no')
    {
        if($confirm != 'yes') return print(js::confirm($this->lang->gitlab->group->confirmDelete , inlink('deleteGroup', "gitlabID=$gitlabID&groupID=$groupID&confirm=yes")));

        $group    = $this->gitlab->apiGetSingleGroup($gitlabID, $groupID);
        $response = $this->gitlab->apiDeleteGroup($gitlabID, $groupID);

        /* If the status code beginning with 20 is returned or empty is returned, it is successful. */
        if(!$response or substr($response->message, 0, 2) == '20')
        {
            $this->loadModel('action')->create('gitlabgroup', $groupID, 'deleted', '', $group->name);
            return print(js::reload('parent'));
        }

        $errorKey = array_search($response->message, $this->lang->gitlab->apiError);
        $result   = $errorKey === false ? $response->message : zget($this->lang->gitlab->errorLang, $errorKey);
        return print(js::alert($result));
    }

    /**
     * 管理gitlba群组成员。
     * Manage a gitlab group members.
     *
     * @param  int    $gitlabID
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function manageGroupMembers(int $gitlabID, int $groupID)
    {
        if($_POST)
        {
            $data = fixer::input('post')->get();

            $ids = array_filter($data->id);
            if(count($ids) != count(array_unique($ids))) return $this->send(array('result' => 'fail', 'message' => $this->lang->gitlab->group->repeatError));
            $newMembers = array();
            foreach($ids as $key => $id)
            {
                /* Check whether each member has selected accesslevel. */
                if(empty($data->access_level[$key])) return $this->send(array('result' => 'fail', 'message' => $this->lang->gitlab->group->memberAccessLevel . $this->lang->gitlab->group->emptyError ));

                $newMembers[$id] = (object)array('access_level'=>$data->access_level[$key], 'expires_at'=>$data->expires_at[$key]);
            }

            $currentMembers = $this->gitlab->apiGetGroupMembers($gitlabID, $groupID);

            list($addedMembers, $deletedMembers, $updatedMembers) = $this->gitlabZen->getGroupMemberData($currentMembers, $newMembers);

            foreach($addedMembers as $addedMember)
            {
                $this->gitlab->apiCreateGroupMember($gitlabID, $groupID, $addedMember);
            }

            foreach($updatedMembers as $updatedMember)
            {
                $this->gitlab->apiUpdateGroupMember($gitlabID, $groupID, $updatedMember);
            }

            foreach($deletedMembers as $deletedMemberID)
            {
                $this->gitlab->apiDeleteGroupMember($gitlabID, $groupID, $deletedMemberID);
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browseGroup', "gitlabID=$gitlabID")));
        }

        /* Get gitlab users data. */
        $gitlabUserList = $this->gitlab->apiGetUsers($gitlabID, true);
        $gitlabUsers    = array(''=>'');
        foreach($gitlabUserList as $gitlabUser)
        {
            $gitlabUsers[$gitlabUser->id] = $gitlabUser->realname;
        }

        $accessLevels   = $this->lang->gitlab->accessLevels;
        $currentMembers = $this->gitlab->apiGetGroupMembers($gitlabID, $groupID);

        $this->view->title          = $this->lang->gitlab->common . $this->lang->colon . $this->lang->gitlab->group->manageMembers;
        $this->view->currentMembers = $currentMembers;
        $this->view->gitlabUsers    = $gitlabUsers;
        $this->view->gitlabID       = $gitlabID;
        $this->display();
    }

    /**
     * Gitlab用户列表。
     * Browse gitlab user.
     *
     * @param  int     $gitlabID
     * @param  string  $orderBy
     * @access public
     * @return void
     */
    public function browseUser(int $gitlabID, string $orderBy = 'id_desc')
    {
        $isAdmin = true;
        if(!$this->app->user->admin)
        {
            $userID = $this->gitlab->getUserIDByZentaoAccount($gitlabID, $this->app->user->account);
            $user   = $this->gitlab->apiGetSingleUser($gitlabID, $userID);
            if(!$user->is_admin) $isAdmin = false;
        }

        $keyword = fixer::input('post')->setDefault('keyword', '')->get('keyword');
        $users   = $this->gitlab->apiGetUsers($gitlabID, false, $orderBy);
        if($keyword)
        {
            foreach($users as $key => $user)
            {
                if(strpos($user->realname, $keyword) === false) unset($users[$key]);
            }
        }

        $this->view->title          = $this->lang->gitlab->common . $this->lang->colon . $this->lang->gitlab->browseUser;
        $this->view->gitlabID       = $gitlabID;
        $this->view->gitlabUserList = $users;
        $this->view->orderBy        = $orderBy;
        $this->view->isAdmin        = $isAdmin;
        $this->view->keyword        = $keyword;
        $this->display();
    }

    /**
     * 创建一个gitlab用户。
     * Creat a gitlab user.
     *
     * @param  int    $gitlabID
     * @access public
     * @return void
     */
    public function createUser(int $gitlabID)
    {
        if($_POST)
        {
            $gitlabUser = form::data($this->config->gitlab->form->user->create)->get();
            if(!empty($_FILES['avatar'])) $gitlabUser->avatar = curl_file_create($_FILES['avatar']['tmp_name'], $_FILES['avatar']['type'], $_FILES['avatar']['name']);

            $this->gitlab->createUser($gitlabID, $gitlabUser);

            if(dao::isError())
            {
                $message = dao::getError();
                foreach($message as &$msg) if(is_string($msg)) $msg = zget($this->lang->gitlab->errorResonse, $msg, $msg);
                return $this->send(array('result' => 'fail', 'message' => $message));
            }
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browseUser', "gitlabID=$gitlabID")));
        }

        $users       = $this->loadModel('user')->getList();
        $bindedUsers = $this->gitlab->getUserAccountIdPairs($gitlabID);
        $userPairs   = array();
        $userInfos   = array();
        foreach($users as $key => $user)
        {
            if(!isset($bindedUsers[$user->account]))
            {
                $userPairs[$user->account] = $user->realname;
                $userInfos[$user->account] = $user;
            }
        }

        $this->view->title     = $this->lang->gitlab->common . $this->lang->colon . $this->lang->gitlab->user->create;
        $this->view->userPairs = $userPairs;
        $this->view->users     = $userInfos;
        $this->view->gitlabID  = $gitlabID;
        $this->display();
    }

    /**
     * 编辑一个gitlab用户。
     * Edit a gitlab user.
     *
     * @param  int    $gitlabID
     * @param  int    $userID
     * @access public
     * @return void
     */
    public function editUser(int $gitlabID, int $userID)
    {
        if($_POST)
        {
            $gitlabUser = form::data($this->config->gitlab->form->user->edit)
                ->removeIF(!$this->post->password, 'password,password_repeat')
                ->get();
            if(!empty($_FILES['avatar']['name'])) $gitlabUser->avatar = curl_file_create($_FILES['avatar']['tmp_name'], $_FILES['avatar']['type'], $_FILES['avatar']['name']);

            $this->gitlab->editUser($gitlabID, $gitlabUser);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'reload' => inlink('browseUser', "gitlabID=$gitlabID")));
        }

        $gitlabUser        = $this->gitlab->apiGetSingleUser($gitlabID, $userID);
        $zentaoBindAccount = $this->dao->select('account')->from(TABLE_OAUTH)->where('providerType')->eq('gitlab')->andWhere('providerID')->eq($gitlabID)->andWhere('openID')->eq($gitlabUser->id)->fetch('account');

        $users       = $this->loadModel('user')->getList();
        $bindedUsers = $this->gitlab->getUserAccountIdPairs($gitlabID);
        $userPairs   = array();
        foreach($users as $user)
        {
            if(!isset($bindedUsers[$user->account]) or $user->account == $zentaoBindAccount) $userPairs[$user->account] = $user->realname;
        }

        $this->view->title             = $this->lang->gitlab->common . $this->lang->colon . $this->lang->gitlab->user->edit;
        $this->view->user              = $gitlabUser;
        $this->view->userPairs         = $userPairs;
        $this->view->zentaoBindAccount = $zentaoBindAccount;
        $this->view->gitlabID          = $gitlabID;
        $this->display();
    }

    /**
     * 删除一个gitlab用户。
     * Delete a gitlab user.
     *
     * @param  int    $gitlabID
     * @param  int    $userID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function deleteUser(int $gitlabID, int $userID, string $confirm = 'no')
    {
        if($confirm != 'yes') return print(js::confirm($this->lang->gitlab->user->confirmDelete , inlink('deleteUser', "gitlabID=$gitlabID&userID=$userID&confirm=yes")));

        $user    = $this->gitlab->apiGetSingleUser($gitlabID, $userID);
        $reponse = $this->gitlab->apiDeleteUser($gitlabID, $userID);

        /* If the status code beginning with 20 is returned or empty is returned, it is successful. */
        if(!$reponse or substr($reponse->message, 0, 2) == '20')
        {
            $this->loadModel('action')->create('gitlabuser', $userID, 'deleted', '', $user->name);

            /* Delete user bind. */
            $this->dao->delete()->from(TABLE_OAUTH)->where('providerType')->eq('gitlab')->andWhere('providerID')->eq($gitlabID)->andWhere('openID')->eq($userID)->exec();

            return print(js::reload('parent'));
        }

        echo js::alert($reponse->message);
    }

    /**
     * gitlab项目列表页。
     * Browse gitlab project.
     *
     * @param  int    $gitlabID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browseProject(int $gitlabID, string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 15, int $pageID = 1)
    {
        $openID = 0;
        if(!$this->app->user->admin)
        {
            $openID = $this->gitlab->getUserIDByZentaoAccount($gitlabID, $this->app->user->account);
            if(!$openID) return print(js::alert($this->lang->gitlab->mustBindUser) . js::locate($this->createLink('space', 'browse')));
        }

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $keyword = fixer::input('post')->setDefault('keyword', '')->get('keyword'); // Fix bug#16741.

        $result = $this->gitlab->apiGetProjectsPager($gitlabID, $keyword, $orderBy, $pager);

        /* Get group id list by gitlab user that the user is maintainer. */
        $groupIDList = array(0 => 0);
        $groups      = $this->gitlab->apiGetGroups($gitlabID, 'name_asc', 'maintainer');
        foreach($groups as $group) $groupIDList[] = $group->id;

        foreach($result['projects'] as $key => $project)
        {
            $project->adminer = (bool)$this->app->user->admin;
            if(!$project->adminer and isset($project->owner) and $project->owner->id == $openID) $project->adminer = true;

            $project->isMaintainer = $this->gitlab->checkUserAccess($gitlabID, $project->id, $project, $groupIDList, 'maintainer');
            $project->isDeveloper  = $this->gitlab->checkUserAccess($gitlabID, $project->id, $project, $groupIDList, 'developer');
        }

        $gitlab    = $this->gitlab->getByID($gitlabID);
        $repos     = $this->loadModel('repo')->getRepoListByClient($gitlabID);
        $repoPairs = array();
        foreach($repos as $repo) $repoPairs[$repo->serviceProject] = $repo->id;

        $this->view->gitlab            = $gitlab;
        $this->view->keyword           = urldecode(urldecode($keyword));
        $this->view->pager             = $result['pager'];
        $this->view->title             = $this->lang->gitlab->common . $this->lang->colon . $this->lang->gitlab->browseProject;
        $this->view->gitlabID          = $gitlabID;
        $this->view->gitlabProjectList = $result['projects'];
        $this->view->orderBy           = $orderBy;
        $this->view->repoPairs         = $repoPairs;
        $this->display();
    }

    /**
     * 创建一个gitlab项目。
     * Creat a gitlab project.
     *
     * @param  int     $gitlabID
     * @access public
     * @return void
     */
    public function createProject(int $gitlabID)
    {
        if($_POST)
        {
            $this->gitlab->createProject($gitlabID);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browseProject', "gitlabID=$gitlabID")));
        }

        $gitlab = $this->gitlab->getByID($gitlabID);
        $user   = $this->gitlab->apiGetCurrentUser($gitlab->url, $gitlab->token);

        /* Get namespaces data */
        $namespacesList = $this->gitlab->apiGetNamespaces($gitlabID);
        $namespaces = array();
        foreach($namespacesList as $namespace)
        {
            if($namespace->kind == 'user' and $namespace->path == $user->username)
            {
                $namespaces[$namespace->id] = $namespace->path;
                $this->view->defaultSpace = $namespace->id;
            }
            if($namespace->kind == 'group') $namespaces[$namespace->id] = $namespace->path;
        }

        $this->view->title      = $this->lang->gitlab->common . $this->lang->colon . $this->lang->gitlab->project->create;
        $this->view->gitlab     = $gitlab;
        $this->view->user       = $user;
        $this->view->namespaces = $namespaces;
        $this->view->gitlabID   = $gitlabID;
        $this->display();
    }

    /**
     * 编辑一个gitlab项目。
     * Edit a gitlab project.
     *
     * @param  int     $gitlabID
     * @param  int     $projectID
     * @access public
     * @return void
     */
    public function editProject(int $gitlabID, int $projectID)
    {
        if($_POST)
        {
            $this->gitlab->editProject($gitlabID);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browseProject', "gitlabID=$gitlabID")));
        }

        $project = $this->gitlab->apiGetSingleProject($gitlabID, $projectID);

        $this->view->title    = $this->lang->gitlab->common . $this->lang->colon . $this->lang->gitlab->project->edit;
        $this->view->project  = $project;
        $this->view->gitlabID = $gitlabID;
        $this->display();
    }

    /**
     * 删除一个gitlab项目。
     * Delete a gitlab project.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function deleteProject(int $gitlabID, int $projectID, string $confirm = 'no')
    {
        if($confirm != 'yes') return print(js::confirm($this->lang->gitlab->project->confirmDelete , inlink('deleteProject', "gitlabID=$gitlabID&projectID=$projectID&confirm=yes")));

        $project = $this->gitlab->apiGetSingleProject($gitlabID, $projectID);
        $reponse = $this->gitlab->apiDeleteProject($gitlabID, $projectID);

        /* If the status code beginning with 20 is returned or empty is returned, it is successful. */
        if(!$reponse or substr($reponse->message, 0, 2) == '20')
        {
            $this->loadModel('action')->create('gitlabproject', $projectID, 'deleted', '', $project->name);
            return print(js::reload('parent'));
        }

        echo js::alert($reponse->message);
    }

    /**
     * Gitlab分支列表。
     * Browse gitlab branch.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browseBranch(int $gitlabID, int $projectID, string $orderBy = 'name_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->session->set('gitlabBranchList', $this->app->getURI(true));

        $branchList = array();
        $result = $this->gitlab->apiGetBranches($gitlabID, $projectID);
        foreach($result as $gitlabBranch)
        {
            $branch = new stdClass();
            $branch->name              = $gitlabBranch->name;
            $branch->lastCommitter     = $gitlabBranch->commit->committer_name;
            $branch->lastCommittedDate = date('Y-m-d H:i:s', strtotime($gitlabBranch->commit->committed_date));

            $branchList[] = $branch;
        }

        /* Data sort. */
        list($order, $sort) = explode('_', $orderBy);
        $orderList = array();
        foreach($branchList as $branch)
        {
            $orderList[] = $branch->$order;
        }
        array_multisort($orderList, $sort == 'desc' ? SORT_DESC : SORT_ASC, $branchList);

        /* Pager. */
        $this->app->loadClass('pager', true);
        $recTotal   = count($branchList);
        $pager      = new pager($recTotal, $recPerPage, $pageID);
        $branchList = array_chunk($branchList, $pager->recPerPage);

        $this->view->gitlab            = $this->gitlab->getByID($gitlabID);
        $this->view->pager             = $pager;
        $this->view->title             = $this->lang->gitlab->common . $this->lang->colon . $this->lang->gitlab->browseBranch;
        $this->view->gitlabID          = $gitlabID;
        $this->view->projectID         = $projectID;
        $this->view->gitlabBranchList  = empty($branchList) ? $branchList: $branchList[$pageID - 1];
        $this->view->orderBy           = $orderBy;
        $this->display();
    }

    /**
     * 创建一个gitlab分支。
     * Creat a gitlab branch.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function createBranch(int $gitlabID, int $projectID)
    {
        if($_POST)
        {
            $this->gitlab->createBranch($gitlabID, $projectID);
            if(dao::isError()) return $this->sendError(dao::getError());

            $locate = $this->session->gitlabBranchList ? $this->session->gitlabBranchList : inlink('browseBranch', "gitlibID=$gitlabID&projectID=$projectID");
            return $this->sendSuccess(array('message' => $this->lang->gitlab->createSuccess, 'load' => $locate));
        }

        /* Get branches by api. */
        $branches = $this->gitlab->apiGetBranches($gitlabID, $projectID);
        if(!is_array($branches)) $branches= array();

        $branchPairs = array();
        foreach($branches as $branch) $branchPairs[$branch->name] = $branch->name;

        $this->view->title       = $this->lang->gitlab->common . $this->lang->colon . $this->lang->gitlab->createBranch;
        $this->view->gitlabID    = $gitlabID;
        $this->view->projectID   = $projectID;
        $this->view->branchPairs = $branchPairs;
        $this->display();
    }

    /**
     * Gitlab tag列表。
     * Browse gitlab tag.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browseTag(int $gitlabID, int $projectID, string $orderBy = 'updated_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $project = $this->gitlab->apiGetSingleProject($gitlabID, $projectID);

        if(!$this->app->user->admin)
        {
            $openID = $this->gitlab->getUserIDByZentaoAccount($gitlabID, $this->app->user->account);
            if(!$openID) return print(js::alert($this->lang->gitlab->mustBindUser) . js::locate($this->createLink('gitlab', 'browse')));
        }

        $this->session->set('gitlabTagList', $this->app->getURI(true));
        $keyword = fixer::input('post')->setDefault('keyword', '')->get('keyword');

        /* Pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $tagList = array();
        $result  = $this->gitlab->apiGetTags($gitlabID, $projectID, $orderBy, $keyword, $pager);
        foreach($result as $gitlabTag)
        {
            $tag = new stdClass();
            $tag->name          = $gitlabTag->name;
            $tag->lastCommitter = $gitlabTag->commit->committer_name;
            $tag->updated       = date('Y-m-d H:i:s', strtotime($gitlabTag->commit->committed_date));
            $tag->protected     = $gitlabTag->protected;
            $tag->gitlabID      = $gitlabID;
            $tag->projectID     = $projectID;
            $tag->tagName       = helper::safe64Encode(urlencode($tag->name));

            $tagList[] = $tag;
        }

        $this->view->gitlab        = $this->gitlab->getByID($gitlabID);
        $this->view->pager         = $pager;
        $this->view->title         = $this->lang->gitlab->common . $this->lang->colon . $this->lang->gitlab->browseTag;
        $this->view->gitlabID      = $gitlabID;
        $this->view->projectID     = $projectID;
        $this->view->keyword       = $keyword;
        $this->view->project       = $project;
        $this->view->gitlabTagList = $tagList;
        $this->view->orderBy       = $orderBy;
        $this->display();
    }

    /**
     * 导入gitlab issue 到禅道。
     * Import gitlab issue to zentaopms.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function importIssue(int $gitlabID, int $projectID)
    {
        $gitlab = $this->gitlab->getByID($gitlabID);
        if($gitlab) $user = $this->gitlab->apiGetCurrentUser($gitlab->url, $gitlab->token);
        if(empty($user->is_admin)) return print(js::alert($this->lang->gitlab->tokenLimit) . js::locate($this->createLink('gitlab', 'edit', array('gitlabID' => $gitlabID))));

        if($_POST)
        {
            $executionList  = $this->post->executionList;
            $objectTypeList = $this->post->objectTypeList;
            $productList    = $this->post->productList;

            $failedIssues = array();
            foreach($executionList as $issueID => $executionID)
            {
                if(empty($executionID) and $productList[$issueID]) return $this->send(array('result' => 'fail', 'message' => $this->lang->gitlab->importIssueError));
            }

            foreach($executionList as $issueID => $executionID)
            {
                if(empty($executionID)) continue;

                $objectType = $objectTypeList[$issueID];

                $issue             = $this->gitlab->apiGetSingleIssue($gitlabID, $projectID, $issueID);
                $issue->objectType = $objectType;
                $issue->objectID   = 0; // Meet the required parameters for issueToZentaoObject.
                if(isset($issue->assignee)) $issue->assignee_id = $issue->assignee->id;
                $issue->updated_by_id = $issue->author->id; // Here can be replaced by current zentao user.

                $object            = $this->gitlab->issueToZentaoObject($issue, $gitlabID);
                $object->product   = $productList[$issueID];
                $object->execution = $executionID;
                $clonedObject      = clone $object;

                if($objectType == 'task')  $objectID = $this->loadModel('task')->createTaskFromGitlabIssue($clonedObject, $executionID);
                if($objectType == 'bug')   $objectID = $this->loadModel('bug')->createBugFromGitlabIssue($clonedObject, $executionID);
                if($objectType == 'story') $objectID = $this->loadModel('story')->createStoryFromGitlabIssue($clonedObject, $executionID);

                if($objectID)
                {
                    $this->loadModel('action')->create($objectType, $objectID, 'ImportFromGitlab', '', $issueID);

                    $object->id = $objectID;
                    $this->gitlab->saveImportedIssue($gitlabID, $projectID, $objectType, $objectID, $issue, $object);
                }
                else
                {
                    $failedIssues[] = $issue->iid;
                }
            }

            if($failedIssues) return $this->send(array('result' => 'success', 'message' => $this->lang->gitlab->importIssueWarn));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'reload' => true));
        }

        $savedIssueIDList = $this->dao->select('BID as issueID')->from(TABLE_RELATION)
            ->where('relation')->eq('gitlab')
            ->andWhere('BType')->eq('issue')
            ->andWhere('BVersion')->eq($projectID)
            ->andWhere('extra')->eq($gitlabID)
            ->fetchAll('issueID');

        /* 'not[iids]' option in gitlab API has a issue when iids is too long. */
        $gitlabIssues = $this->gitlab->apiGetIssues($gitlabID, $projectID, '&state=opened');
        if(!is_array($gitlabIssues)) $gitlabIssues = array();
        foreach($gitlabIssues as $index => $issue)
        {
            foreach($savedIssueIDList as $savedIssueID)
            {
                if($issue->iid == $savedIssueID->issueID)
                {
                    unset($gitlabIssues[$index]);
                    break;
                }
            }
        }

        $products = $this->loadModel("product")->getPairs();

        $this->view->title           = $this->lang->gitlab->common . $this->lang->colon . $this->lang->gitlab->importIssue;
        $this->view->importable      = empty($gitlabIssues) ? false : true;
        $this->view->products        = $products;
        $this->view->gitlabID        = $gitlabID;
        $this->view->gitlabProjectID = $projectID;
        $this->view->objectTypes     = $this->config->gitlab->objectTypes;
        $this->view->gitlabIssues    = array_values($gitlabIssues);

        $this->display();
    }

    /**
     * 根据版本库创建webhook。
     * Create Webhook by repoID.
     *
     * @param  int    $repoID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function createWebhook(int $repoID, string $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->gitlab->confirmAddWebhook, $this->createLink('gitlab', 'createWebhook', "repoID=$repoID&confirm=yes")));
        }
        else
        {
            $repo = $this->loadModel('repo')->getByID($repoID);
            $res  = $this->gitlab->addPushWebhook($repo);

            if($res or is_array($res))
            {
                return print(js::alert($this->lang->gitlab->addWebhookSuccess));
            }
            else
            {
                return print(js::error($this->lang->gitlab->failCreateWebhook));
            }
        }
    }

    /**
     * 管理gitlab项目成员。
     * Manage a gitlab project members.
     *
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function manageProjectMembers(int $repoID)
    {
        if($_POST)
        {
            $data = fixer::input('post')->get();

            $accounts = array_filter($data->account);
            if(count($accounts) != count(array_unique($accounts))) return $this->send(array('result' => 'fail', 'message' => $this->lang->gitlab->group->repeatError));

            $repo        = $this->loadModel('repo')->getByID($repoID);
            $users       = $this->loadModel('user')->getPairs('noletter|noempty|nodeleted');
            $bindedUsers = $this->gitlab->getUserAccountIdPairs($repo->gitService);

            if(empty($repo->acl))
            {
                $repo->acl = new stdClass();
                $repo->acl->users = array();
            }

            $newGitlabMembers = array();
            foreach($accounts as $key => $account)
            {
                /* If the user has set permissions, check whether they are bound. */
                if(!empty($data->access_level[$key]))
                {
                    if(!isset($bindedUsers[$account])) return $this->send(array('result' => 'fail', 'message' => $users[$account] . ' ' . $this->lang->gitlab->project->notbindedError));
                    $newGitlabMembers[$bindedUsers[$account]] = (object) array('access_level' => $data->access_level[$key], 'expires_at' => $data->expires_at[$key]);
                }
            }

            $gitlabCurrentMembers = $this->gitlab->apiGetProjectMembers($repo->gitService, $repo->project);

            list($addedMembers, $deletedMembers, $updatedMembers) = $this->gitlabZen->getProjectMemberData($gitlabCurrentMembers, $newGitlabMembers, $bindedUsers, $accounts, !empty($repo->acl->users) ? $repo->acl->users : array());

            foreach($addedMembers as $addedMember)
            {
                $this->gitlab->apiCreateProjectMember($repo->gitService, $repo->project, $addedMember);
            }

            foreach($updatedMembers as $updatedMember)
            {
                $this->gitlab->apiUpdateProjectMember($repo->gitService, $repo->project, $updatedMember);
            }

            foreach($deletedMembers as $deletedMemberID)
            {
                $this->gitlab->apiDeleteProjectMember($repo->gitService, $repo->project, (int)$deletedMemberID);
            }

            $repo->acl->users = array_values($accounts);
            $this->dao->update(TABLE_REPO)->data(array('acl' => json_encode($repo->acl)))->where('id')->eq($repoID)->exec();
            return $this->sendSuccess(array('load' => helper::createLink('gitlab', 'browseProject', "gitlabID={$repo->gitService}")));
        }

        $repo           = $this->loadModel('repo')->getByID($repoID);
        $users          = $this->loadModel('user')->getPairs('noletter|noempty|nodeleted|noclosed');
        $projectMembers = $this->gitlab->apiGetProjectMembers($repo->gitService, $repo->project);
        if(!is_array($projectMembers)) $projectMembers = array();

        /* Get users accesslevel. */
        $userAccessData = array();
        $bindedUsers    = $this->gitlab->getUserIdAccountPairs($repo->gitService);

        foreach($projectMembers as $projectMember)
        {
            if(isset($projectMember->id) and isset($bindedUsers[$projectMember->id]))
            {
                $account                  = $bindedUsers[$projectMember->id];
                $projectMember->account   = $account;
                $userAccessData[$account] = $projectMember;
            }
        }

        $this->view->title          = $this->lang->gitlab->common . $this->lang->colon . $this->lang->gitlab->manageProjectMembers;
        $this->view->userAccessData = $userAccessData;
        $this->view->users          = $users;
        $this->view->repo           = $repo;
        $this->display();
    }

    /**
     * Ajax方式获取执行根据产品id。
     * AJAX: Get executions by productID.
     *
     * @param  int    $productID
     * @access public
     * @return string
     */
    public function ajaxGetExecutionsByProduct(int $productID)
    {
        if(!$productID) return $this->send(array('message' => array()));

        $executions = $this->loadModel('product')->getExecutionPairsByProduct($productID);

        $options = array();
        $options[] = array('text' => '', 'value' => '');;
        foreach($executions as $index => $execution)
        {
            $options[] = array('text' => $execution, 'value' => $index);
        }
        return print(json_encode($options));
    }

    /**
     * Ajax方式获取项目分支。
     * AJAX: Get project branches.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function ajaxGetProjectBranches(int $gitlabID, int $projectID)
    {
        if(!$gitlabID or !$projectID) return $this->send(array('message' => array()));

        $branches = $this->gitlab->apiGetBranches($gitlabID, $projectID);

        $options = array();
        $options[] = array('text' => '', 'value' => '');;
        foreach($branches as $branch)
        {
            $options[] = array('text' => $branch->name, 'value' => $branch->name);
        }
        return print(json_encode($options));
    }

    /**
     * Ajax方式获取合并请求用户键值对。
     * AJAX: Get MR user pairs to select assignee_ids and reviewer_ids.
     * Attention: The user must be a member of the GitLab project.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function ajaxGetMRUserPairs(int $gitlabID, int $projectID)
    {
        if(!$gitlabID) return $this->send(array('message' => array()));

        $bindedUsers     = $this->gitlab->getUserIdRealnamePairs($gitlabID);
        $rawProjectUsers = $this->gitlab->apiGetProjectUsers($gitlabID, $projectID);
        $users           = array();
        foreach($rawProjectUsers as $rawProjectUser)
        {
            if(!empty($bindedUsers[$rawProjectUser->id])) $users[$rawProjectUser->id] = $bindedUsers[$rawProjectUser->id];
        }

        $options  = "<option value=''></option>";
        foreach($users as $index => $user)
        {
            $options .= "<option value='{$index}'>{$user}</option>";
        }
        $this->send($options);
    }

    /**
     * 创建一个gitlab标签。
     * Create a gitlab tag.
     *
     * @param  int $gitlabID
     * @param  int $projectID
     * @access public
     * @return void
     */
    public function createTag(int $gitlabID, int $projectID)
    {
        if($_POST)
        {
            $this->gitlab->createTag($gitlabID, $projectID);

            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->sendSuccess(array('load' => inlink('browseTag', "gitlabID=$gitlabID&projectID=$projectID")));
        }

        $gitlabBranches = $this->gitlab->apiGetBranches($gitlabID, $projectID);
        $branches       = array();
        foreach($gitlabBranches as $branch)
        {
            $branches[$branch->name] = $branch->name;
        }

        $this->view->title     = $this->lang->gitlab->common . $this->lang->colon . $this->lang->gitlab->createTag;
        $this->view->gitlabID  = $gitlabID;
        $this->view->projectID = $projectID;
        $this->view->branches  = $branches;
        $this->display();
    }

    /**
     * 删除一个gitlab标签。
     * Delete a gitlab tag.
     *
     * @param  int    $gitlabID
     * @param  int    $projectID
     * @param  string $tagName
     * @access public
     * @return void
     */
    public function deleteTag(int $gitlabID, int $projectID, string $tagName = '')
    {
        /* Fix error when request type is PATH_INFO and the tag name contains '-'.*/
        $tagName = urldecode(helper::safe64Decode($tagName));
        $reponse = $this->gitlab->apiDeleteTag($gitlabID, $projectID, $tagName);

        /* If the status code beginning with 20 is returned or empty is returned, it is successful. */
        if(!$reponse or substr($reponse->message, 0, 2) == '20')
        {
            $this->loadModel('action')->create('gitlabtag', $projectID, 'deleted', '', $projectID);
            return $this->sendSuccess(array('load' => true));
        }

        return $this->sendError($reponse->message);
    }

    /**
     * 管理gitlab分支保护策略。
     * Manage a gitlab branch protected.
     *
     * @param  int    $repoID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function manageBranchPriv(int $gitlabID, int $projectID)
    {
        if(!$this->app->user->admin)
        {
            $openID = $this->gitlab->getUserIDByZentaoAccount($gitlabID, $this->app->user->account);
            if(!$openID) return print(js::alert($this->lang->gitlab->mustBindUser) . js::locate($this->createLink('gitlab', 'browse')));

            $project = $this->gitlab->apiGetSingleProject($gitlabID, $projectID);
            if(!$this->gitlab->checkUserAccess($gitlabID, $projectID, $project)) return print(js::alert($this->lang->gitlab->noAccess) . js::locate($this->createLink('gitlab', 'browse')));
        }

        $hasAccessBranches = $this->gitlab->apiGetBranchPrivs($gitlabID, $projectID, '', 'name_asc');
        foreach($hasAccessBranches as $branch)
        {
            $branch->pushAccess  = (string)$this->gitlab->checkAccessLevel($branch->push_access_levels);
            $branch->mergeAccess = (string)$this->gitlab->checkAccessLevel($branch->merge_access_levels);
        }

        if(!empty($_POST))
        {
            $result = $this->gitlab->manageBranchPrivs($gitlabID, $projectID, $hasAccessBranches);
            if(!empty($result)) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->gitlab->svaeFailed, implode(', ', $result))));

            return $this->send(array('message' => $this->lang->saveSuccess, 'result' => 'success', 'locate' => inlink('browseProject', "gitlabID=$gitlabID")));
        }
        $allBranches = $this->gitlab->apiGetBranches($gitlabID, $projectID);
        $branchPairs = array();
        foreach($allBranches as $branch) $branchPairs[$branch->name] = $branch->name;

        $this->view->title             = $this->lang->gitlab->common . $this->lang->colon . $this->lang->gitlab->browseBranchPriv;
        $this->view->gitlabID          = $gitlabID;
        $this->view->projectID         = $projectID;
        $this->view->branchPairs       = $branchPairs;
        $this->view->hasAccessBranches = $hasAccessBranches;
        $this->display();
    }

    /**
     * 管理gitlab 标签保护策略。
     * Manage a gitlab tag protected.
     *
     * @param  int    $repoID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function manageTagPriv(int $gitlabID, int $projectID)
    {
        if(!$this->app->user->admin)
        {
            $openID = $this->gitlab->getUserIDByZentaoAccount($gitlabID, $this->app->user->account);
            if(!$openID) return print(js::alert($this->lang->gitlab->mustBindUser) . js::locate($this->createLink('gitlab', 'browse')));

            $project = $this->gitlab->apiGetSingleProject($gitlabID, $projectID);
            if(!$this->gitlab->checkUserAccess($gitlabID, $projectID, $project)) return print(js::alert($this->lang->gitlab->noAccess) . js::locate($this->createLink('gitlab', 'browse')));
        }

        $hasAccessTags = $this->gitlab->apiGetTagPrivs($gitlabID, $projectID, '', 'name_asc');
        foreach($hasAccessTags as $tag) $tag->createAccess = (string)$this->gitlab->checkAccessLevel($tag->create_access_levels);

        if(!empty($_POST))
        {
            $result = $this->gitlab->manageTagPrivs($gitlabID, $projectID, $hasAccessTags);
            if(!empty($result)) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->gitlab->svaeFailed, implode(', ', $result))));

            return $this->send(array('message' => $this->lang->saveSuccess, 'result' => 'success', 'locate' => inlink('browseProject', "gitlabID=$gitlabID")));
        }
        $allTags  = $this->gitlab->apiGetTags($gitlabID, $projectID);
        $tagPairs = array();
        foreach($allTags as $tag) $tagPairs[$tag->name] = $tag->name;

        $this->view->title         = $this->lang->gitlab->common . $this->lang->colon . $this->lang->gitlab->browseTagPriv;
        $this->view->gitlabID      = $gitlabID;
        $this->view->projectID     = $projectID;
        $this->view->hasAccessTags = $hasAccessTags;
        $this->view->tagPairs      = $tagPairs;
        $this->display();
    }
}
