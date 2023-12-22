<?php
declare(strict_types=1);
/**
 * The control file of gitea module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     gitea
 * @link        https://www.zentao.net
 */
class gitea extends control
{
    /**
     * The gitea constructor.
     * @param string $moduleName
     * @param string $methodName
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);

        if(!commonModel::hasPriv('instance', 'manage')) $this->loadModel('common')->deny('instance', 'manage', false);

        /* This is essential when changing tab(menu) from gitea to repo. */
        /* Optional: common::setMenuVars('devops', $this->session->repoID); */
        if($this->app->rawMethod != 'binduser') $this->loadModel('ci')->setMenu();
    }

    /**
     * Gitea服务器列表。
     * Browse gitea.
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
        $giteaList = $this->loadModel('pipeline')->getList('gitea', $orderBy, $pager);
        $myGiteas  = $this->gitea->getGiteaListByAccount();
        foreach($giteaList as $gitea)
        {
            $gitea->isBindUser = true;
            if(!$this->app->user->admin && !isset($myGiteas[$gitea->id])) $gitea->isBindUser = false;
        }

        $this->view->title     = $this->lang->gitea->common . $this->lang->colon . $this->lang->gitea->browse;
        $this->view->giteaList = $giteaList;
        $this->view->orderBy   = $orderBy;
        $this->view->pager     = $pager;

        $this->display();
    }

    /**
     * 增加一个gitea服务器。
     * Create a gitea.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $gitea  = form::data($this->config->gitea->form->create)->get();
            $result = $this->checkToken($gitea);
            if(is_array($result)) return $this->send($result);

            $giteaID = $this->loadModel('pipeline')->create($gitea);
            if(dao::isError()) return $this->sendError(dao::getError());

            $this->loadModel('action')->create('gitea', $giteaID, 'created');
            return $this->sendSuccess(array('locate' => $this->createLink('space', 'browse')));
        }

        $this->view->title = $this->lang->gitea->common . $this->lang->colon . $this->lang->gitea->lblCreate;
        $this->display();
    }

    /**
     * 查看一个gitea服务器。
     * View a gitea.
     * @param  int    $giteaID
     * @access public
     * @return void
     */
    public function view(int $giteaID)
    {
        $gitea = $this->gitea->fetchByID($giteaID);

        $this->view->title   = $this->lang->gitea->common . $this->lang->colon . $this->lang->gitea->view;
        $this->view->gitea   = $gitea;
        $this->view->users   = $this->loadModel('user')->getPairs('noclosed');
        $this->view->actions = $this->loadModel('action')->getList('gitea', $giteaID);
        $this->display();
    }

    /**
     * 编辑一个gitea服务器。
     * Edit a gitea.
     *
     * @param  int    $giteaID
     * @access public
     * @return void
     */
    public function edit(int $giteaID)
    {
        $oldGitea = $this->gitea->fetchByID($giteaID);

        if($_POST)
        {
            $gitea  = form::data($this->config->gitea->form->edit)->get();
            $result = $this->checkToken($gitea);
            if(is_array($result)) return $this->send($result);

            $this->loadModel('pipeline')->update($giteaID, $gitea);
            if(dao::isError()) return $this->sendError(dao::getError());

            $gitea    = $this->gitea->fetchByID($giteaID);
            $actionID = $this->loadModel('action')->create('gitea', $giteaID, 'edited');
            $changes  = common::createChanges($oldGitea, $gitea);
            $this->action->logHistory($actionID, $changes);
            return $this->sendSuccess(array('load' => true, 'closeModal' => true));
        }

        $this->view->title = $this->lang->gitea->common . $this->lang->colon . $this->lang->gitea->edit;
        $this->view->gitea = $oldGitea;
        $this->display();
    }

    /**
     * 删除一条gitea记录
     * Delete a gitea.
     *
     * @param  int    $giteaID
     * @access public
     * @return void
     */
    public function delete(int $giteaID)
    {
        $oldGitea = $this->loadModel('pipeline')->fetchByID($giteaID);
        $actionID = $this->pipeline->deleteByObject($giteaID, 'gitea');
        if(!$actionID)
        {
            $response['result']   = 'fail';
            $response['callback'] = sprintf('zui.Modal.alert("%s");', $this->lang->pipeline->delError);
            return $this->send($response);
        }

        $gitea   = $this->pipeline->fetchByID($giteaID);
        $changes = common::createChanges($oldGitea, $gitea);
        $this->loadModel('action')->logHistory($actionID, $changes);

        $response['load']    = $this->createLink('space', 'browse');
        $response['message'] = zget($this->lang->instance->notices, 'uninstallSuccess');
        $response['result']  = 'success';

        return $this->send($response);
    }

    /**
     * 绑定gitea用户到禅道用户。
     * Bind gitea user to zentao users.
     *
     * @param  int    $giteaID
     * @param  string $type
     * @access public
     * @return void
     */
    public function bindUser(int $giteaID, string $type = 'all')
    {
        if($_POST)
        {
            $this->gitea->bindUser($giteaID, (array)$this->post->zentaoUsers, (array)$this->post->giteaUserNames);
            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->sendSuccess(array('load' => helper::createLink('space', 'browse')));
        }

        $userList      = array();
        $giteaUsers    = $this->gitea->apiGetUsers($giteaID);
        $matchedResult = $this->giteaZen->getMatchedUsers($giteaID, $giteaUsers);
        $bindedUsers   = $this->loadModel('pipeline')->getUserBindedPairs($giteaID, 'gitea');
        $zentaoUsers   = $this->loadModel('user')->getRealNameAndEmails(helper::arrayColumn($matchedResult, 'zentaoAccount'));

        foreach($giteaUsers as $giteaUser)
        {
            $user = new stdclass();
            $user->email           = '';
            $user->status          = 'notBind';
            $user->giteaID         = $giteaUser->id;
            $user->giteaEmail      = $giteaUser->email;
            $user->giteaAccount    = $giteaUser->account;
            $user->giteaUser       = $giteaUser->realname . '@' . $giteaUser->account;
            $user->giteaUserAvatar = $giteaUser->avatar;

            $user->zentaoUsers = isset($matchedResult[$giteaUser->id]) ? $matchedResult[$giteaUser->id]->zentaoAccount : '';
            if($user->zentaoUsers)
            {
                if(isset($zentaoUsers[$user->zentaoUsers])) $user->email = $zentaoUsers[$user->zentaoUsers]->email;

                if(isset($bindedUsers[$user->zentaoUsers]) && $bindedUsers[$user->zentaoUsers] == $giteaUser->id) $user->status = 'binded';
            }

            if($type != 'all' && $user->status != $type) continue;
            $userList[] = $user;
        }

        $this->view->title       = $this->lang->gitea->bindUser;
        $this->view->type        = $type;
        $this->view->giteaID     = $giteaID;
        $this->view->recTotal    = count($userList);
        $this->view->userList    = $userList;
        $this->view->userPairs   = $this->user->getPairs('noclosed|noletter');
        $this->view->zentaoUsers = $zentaoUsers;
        $this->display();
    }

    /**
     * 获取分支列表。
     * Ajax get branches.
     *
     * @param  int    $giteaID
     * @param  string $project
     * @access public
     * @return void
     */
    public function ajaxGetProjectBranches(int $giteaID, string $project)
    {
        $options = array(array('text' => '', 'value' => ''));
        if(!$giteaID || !$project) return print(json_encode($options));

        $project  = urldecode(base64_decode($project));
        $branches = $this->gitea->apiGetBranches($giteaID, $project);

        foreach($branches as $branch)
        {
            $options[] = array('text' => $branch->name, 'value' => $branch->name);
        }
        return print(json_encode($options));
    }
}
