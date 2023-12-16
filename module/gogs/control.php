<?php
/**
 * The control file of gogs module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Liyuchun <liyuchun@easycorp.ltd>
 * @package     product
 * @version     $Id: ${FILE_NAME} 5144 2022-08-01 liyuchun@easycorp.ltd $
 * @link        https://www.zentao.net
 */
class gogs extends control
{
    /**
     * The gogs constructor.
     * @param string $moduleName
     * @param string $methodName
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);

        if(!commonModel::hasPriv('instance', 'manage')) $this->loadModel('common')->deny('instance', 'manage', false);
        /* This is essential when changing tab(menu) from gogs to repo. */
        /* Optional: common::setMenuVars('devops', $this->session->repoID); */
        if($this->app->rawMethod != 'binduser') $this->loadModel('ci')->setMenu();
    }

    /**
     * Browse gogs.
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
        $gogsList = $this->gogs->getList($orderBy, $pager);
        $myGogses = $this->gogs->getGogsListByAccount();
        foreach($gogsList as $gogs)
        {
            $gogs->isBindUser = true;
            if(!$this->app->user->admin and !isset($myGogses[$gogs->id])) $gogs->isBindUser = false;
        }

        $this->view->title    = $this->lang->gogs->common . $this->lang->colon . $this->lang->gogs->browse;
        $this->view->gogsList = $gogsList;
        $this->view->orderBy  = $orderBy;
        $this->view->pager    = $pager;

        $this->display();
    }

    /**
     * Create a gogs.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $gogs = form::data($this->config->gogs->form->create)->get();
            $this->checkToken($gogs);
            $gogsID = $this->loadModel('pipeline')->create($gogs);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('gogs', $gogsID, 'created');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('space', 'browse')));
        }

        $this->view->title = $this->lang->gogs->common . $this->lang->colon . $this->lang->gogs->lblCreate;

        $this->display();
    }

    /**
     * View a gogs.
     * @param  int    $gogsID
     * @access public
     * @return void
     */
    public function view($gogsID)
    {
        $gogs = $this->gogs->getByID($gogsID);

        $this->view->title      = $this->lang->gogs->common . $this->lang->colon . $this->lang->gogs->view;
        $this->view->gogs       = $gogs;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed');
        $this->view->actions    = $this->loadModel('action')->getList('gogs', $gogsID);
        $this->view->preAndNext = $this->loadModel('common')->getPreAndNextObject('pipeline', $gogsID);
        $this->display();
    }

    /**
     * Edit a gogs.
     *
     * @param  int    $gogsID
     * @access public
     * @return void
     */
    public function edit($gogsID)
    {
        $oldGogs = $this->gogs->getByID($gogsID);

        if($_POST)
        {
            $gogs = form::data($this->config->gogs->form->edit)->get();
            $this->checkToken($gogs);
            $this->gogs->update($gogsID, $gogs);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $gogs     = $this->gogs->getByID($gogsID);
            $actionID = $this->loadModel('action')->create('gogs', $gogsID, 'edited');
            $changes  = common::createChanges($oldGogs, $gogs);
            $this->action->logHistory($actionID, $changes);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $this->view->title = $this->lang->gogs->common . $this->lang->colon . $this->lang->gogs->edit;
        $this->view->gogs  = $oldGogs;

        $this->display();
    }

    /**
     * 删除一条gogs数据。
     * Delete a gogs.
     *
     * @param  int    $gogsID
     * @access public
     * @return void
     */
    public function delete($gogsID)
    {
        $oldGogs  = $this->loadModel('pipeline')->getByID($gogsID);
        $actionID = $this->pipeline->deleteByObject($gogsID, 'gogs');
        if(!$actionID)
        {
            $response['result']   = 'fail';
            $response['callback'] = sprintf('zui.Modal.alert("%s");', $this->lang->pipeline->delError);

            return $this->send($response);
        }

        $gogs    = $this->pipeline->getByID($gogsID);
        $changes = common::createChanges($oldGogs, $gogs);
        $this->loadModel('action')->logHistory($actionID, $changes);

        $response['load']   = $this->createLink('space', 'browse');
        $response['result'] = 'success';
        return $this->send($response);
    }

    /**
     * Check post token has admin permissions.
     *
     * @access protected
     * @return void
     */
    protected function checkToken(object $gogs)
    {
        $this->dao->update('gogs')->data($gogs)->batchCheck($this->config->gogs->create->requiredFields, 'notempty');
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $result = $this->gogs->checkTokenAccess($gogs->url, $gogs->token);

        if($result === false) return $this->send(array('result' => 'fail', 'message' => array('url' => array($this->lang->gogs->hostError))));
        if(!$result) return $this->send(array('result' => 'fail', 'message' => array('token' => array($this->lang->gogs->tokenLimit))));

        return true;
    }

    /**
     * Bind gogs user to zentao users.
     *
     * @param  int    $gogsID
     * @param  string $type
     * @access public
     * @return void
     */
    public function bindUser($gogsID, $type = 'all')
    {
        $zentaoUsers = $this->dao->select('account,email,realname')->from(TABLE_USER)->fetchAll('account');
        $userPairs   = $this->loadModel('user')->getPairs('noclosed|noletter');

        if($_POST)
        {
            $this->gogs->bindUser($gogsID);
            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->sendSuccess(array('message' => $this->lang->saveSuccess, 'load' => helper::createLink('space', 'browse')));
        }

        $userList      = array();
        $gogsUsers     = $this->gogs->apiGetUsers($gogsID);
        $bindedUsers   = $this->loadModel('pipeline')->getUserBindedPairs($gogsID, 'gogs');
        $matchedResult = $this->gogs->getMatchedUsers($gogsID, $gogsUsers, $zentaoUsers);

        foreach($gogsUsers as $gogsUser)
        {
            $user = new stdclass();
            $user->email          = '';
            $user->status         = 'notBind';
            $user->gogsID         = $gogsUser->id;
            $user->gogsEmail      = $gogsUser->email;
            $user->gogsUser       = $gogsUser->realname . '@' . $gogsUser->account;
            $user->gogsUserAvatar = $gogsUser->avatar;

            $user->zentaoUsers = isset($matchedResult[$gogsUser->id]) ? $matchedResult[$gogsUser->id]->zentaoAccount : '';
            if($user->zentaoUsers)
            {
                if(isset($zentaoUsers[$user->zentaoUsers])) $user->email = $zentaoUsers[$user->zentaoUsers]->email;

                if(isset($bindedUsers[$user->zentaoUsers]) && $bindedUsers[$user->zentaoUsers] == $gogsUser->id)
                {
                    $user->status = 'binded';
                    if(!isset($bindedUsers[$user->zentaoUsers])) $user->status = 'bindedError';
                }
            }

            if($type != 'all' && $user->status != $type) continue;
            $userList[] = $user;
        }

        $this->view->title       = $this->lang->gogs->bindUser;
        $this->view->type        = $type;
        $this->view->gogsID      = $gogsID;
        $this->view->recTotal    = count($userList);
        $this->view->userList    = $userList;
        $this->view->userPairs   = $userPairs;
        $this->view->zentaoUsers = $zentaoUsers;
        $this->display();
    }

    /**
     * Ajax getProjectBranches
     *
     * @param  int    $gogsID
     * @param  string $project
     * @access public
     * @return void
     */
    public function ajaxGetProjectBranches($gogsID, $project)
    {
        if(!$gogsID or !$project) return $this->send(array('message' => array()));

        $project  = urldecode(base64_decode($project));
        $branches = $this->gogs->apiGetBranches($gogsID, $project);

        $options = array();
        $options[] = array('text' => '', 'value' => '');;
        foreach($branches as $branch)
        {
            $options[] = array('text' => $branch->name, 'value' => $branch->name);
        }
        return print(json_encode($options));
    }
}
