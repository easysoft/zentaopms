<?php
/**
 * The control file of gitea module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: ${FILE_NAME} 5144 2020/1/8 8:10 下午 chenqi@cnezsoft.com $
 * @link        http://www.zentao.net
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
     * Browse gitea.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {

        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Admin user don't need bind. */
        $giteaList = $this->gitea->getList($orderBy, $pager);
        $myGiteas  = $this->gitea->getGiteaListByAccount();
        foreach($giteaList as $gitea)
        {
            $gitea->isBindUser = true;
            if(!$this->app->user->admin and !isset($myGiteas[$gitea->id])) $gitea->isBindUser = false;
        }

        $this->view->title     = $this->lang->gitea->common . $this->lang->colon . $this->lang->gitea->browse;
        $this->view->giteaList = $giteaList;
        $this->view->orderBy   = $orderBy;
        $this->view->pager     = $pager;

        $this->display();
    }

    /**
     * Create a gitea.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $gitea = form::data($this->config->gitea->form->create)
                ->add('type', 'gitea')
                ->add('private',md5(rand(10,113450)))
                ->add('createdBy', $this->app->user->account)
                ->add('createdDate', helper::now())
                ->trim('url,token')
                ->skipSpecial('url,token')
                ->remove('account,password,appType')
                ->get();
            $this->checkToken($gitea);
            $giteaID = $this->loadModel('pipeline')->create($gitea);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $actionID = $this->loadModel('action')->create('gitea', $giteaID, 'created');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('space', 'browse')));
        }

        $this->view->title = $this->lang->gitea->common . $this->lang->colon . $this->lang->gitea->lblCreate;

        $this->display();
    }

    /**
     * View a gitea.
     * @param  int    $giteaID
     * @access public
     * @return void
     */
    public function view($giteaID)
    {
        $gitea = $this->gitea->getByID($giteaID);

        $this->view->title      = $this->lang->gitea->common . $this->lang->colon . $this->lang->gitea->view;
        $this->view->gitea      = $gitea;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed');
        $this->view->actions    = $this->loadModel('action')->getList('gitea', $giteaID);
        $this->view->preAndNext = $this->loadModel('common')->getPreAndNextObject('pipeline', $giteaID);
        $this->display();
    }

    /**
     * Edit a gitea.
     *
     * @param  int    $giteaID
     * @access public
     * @return void
     */
    public function edit($giteaID)
    {
        $oldGitea = $this->gitea->getByID($giteaID);

        if($_POST)
        {
            $gitea = fixer::input('post')->trim('url,token')->get();
            $this->checkToken($gitea);
            $this->gitea->update($giteaID);
            $gitea = $this->gitea->getByID($giteaID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action');
            $actionID = $this->action->create('gitea', $giteaID, 'edited');
            $changes  = common::createChanges($oldGitea, $gitea);
            $this->action->logHistory($actionID, $changes);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
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
    public function delete($giteaID)
    {
        $oldGitea = $this->loadModel('pipeline')->getByID($giteaID);
        $actionID = $this->pipeline->deleteByObject($giteaID, 'gitea');
        if(!$actionID)
        {
            $response['result']   = 'fail';
            $response['callback'] = sprintf('zui.Modal.alert("%s");', $this->lang->pipeline->delError);
            return $this->send($response);
        }

        $gitea   = $this->pipeline->getByID($giteaID);
        $changes = common::createChanges($oldGitea, $gitea);
        $this->loadModel('action')->logHistory($actionID, $changes);

        $response['load']    = $this->createLink('space', 'browse');
        $response['message'] = zget($this->lang->instance->notices, 'uninstallSuccess');
        $response['result']  = 'success';

        return $this->send($response);
    }

    /**
     * Check post token has admin permissions.
     *
     * @access protected
     * @return void
     */
    protected function checkToken(object $gitea)
    {
        $this->dao->update('gitea')->data($gitea)->batchCheck($this->config->gitea->create->requiredFields, 'notempty');
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $result = $this->gitea->checkTokenAccess($gitea->url, $gitea->token);

        if($result === false) return $this->send(array('result' => 'fail', 'message' => array('url' => array($this->lang->gitea->hostError))));
        if(!$result) return $this->send(array('result' => 'fail', 'message' => array('token' => array($this->lang->gitea->tokenLimit))));

        return true;
    }

    /**
     * Bind gitea user to zentao users.
     *
     * @param  int    $giteaID
     * @param  string $type
     * @access public
     * @return void
     */
    public function bindUser($giteaID, $type = 'all')
    {
        $zentaoUsers = $this->dao->select('account,email,realname')->from(TABLE_USER)->fetchAll('account');
        $userPairs   = $this->loadModel('user')->getPairs('noclosed|noletter');

        if($_POST)
        {
            $this->gitea->bindUser($giteaID);
            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->sendSuccess(array('message' => $this->lang->saveSuccess, 'load' => helper::createLink('space', 'browse')));
        }

        $userList      = array();
        $giteaUsers    = $this->gitea->apiGetUsers($giteaID);
        $bindedUsers   = $this->gitea->getUserAccountIdPairs($giteaID);
        $matchedResult = $this->gitea->getMatchedUsers($giteaID, $giteaUsers, $zentaoUsers);

        foreach($giteaUsers as $giteaUserID => $giteaUser)
        {
            $user = new stdclass();
            $user->email           = '';
            $user->status          = 'notBind';
            $user->giteaID         = $giteaUser->id;
            $user->giteaEmail      = $giteaUser->email;
            $user->giteaUser       = $giteaUser->realname . '@' . $giteaUser->account;
            $user->giteaUserAvatar = $giteaUser->avatar;

            $user->zentaoUsers = isset($matchedResult[$giteaUser->id]) ? $matchedResult[$giteaUser->id]->zentaoAccount : '';
            if($user->zentaoUsers)
            {
                if(isset($zentaoUsers[$user->zentaoUsers])) $user->email = $zentaoUsers[$user->zentaoUsers]->email;

                if(isset($bindedUsers[$user->zentaoUsers]) && $bindedUsers[$user->zentaoUsers] == $giteaUser->id)
                {
                    $user->status = 'binded';
                    if(!isset($bindedUsers[$user->zentaoUsers])) $user->status = 'bindedError';
                }
            }

            if($type != 'all' && $user->status != $type) continue;
            $userList[] = $user;
        }

        $this->view->title       = $this->lang->gitea->bindUser;
        $this->view->type        = $type;
        $this->view->giteaID     = $giteaID;
        $this->view->recTotal    = count($userList);
        $this->view->userList    = $userList;
        $this->view->userPairs   = $userPairs;

        $this->view->zentaoUsers = $zentaoUsers;
        $this->display();
    }

    /**
     * Ajax getProjectBranches
     *
     * @param  int    $giteaID
     * @param  string $project
     * @access public
     * @return void
     */
    public function ajaxGetProjectBranches($giteaID, $project)
    {
        if(!$giteaID or !$project) return $this->send(array('message' => array()));

        $project  = urldecode(base64_decode($project));
        $branches = $this->gitea->apiGetBranches($giteaID, $project);

        $options = array();
        $options[] = array('text' => '', 'value' => '');;
        foreach($branches as $branch)
        {
            $options[] = array('text' => $branch->name, 'value' => $branch->name);
        }
        return print(json_encode($options));
    }
}
