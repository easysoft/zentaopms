<?php
declare(strict_types=1);
/**
 * The control file of gogs module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Liyuchun <liyuchun@easycorp.ltd>
 * @package     gogs
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
     * Gogs服务器列表。
     * Browse gogs list.
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
        $gogsList = $this->loadModel('pipeline')->getList('gogs', $orderBy, $pager);
        $myGogses = $this->gogs->getGogsListByAccount();
        foreach($gogsList as $gogs)
        {
            $gogs->isBindUser = true;
            if(!$this->app->user->admin && !isset($myGogses[$gogs->id])) $gogs->isBindUser = false;
        }

        $this->view->title    = $this->lang->gogs->common . $this->lang->colon . $this->lang->gogs->browse;
        $this->view->gogsList = $gogsList;
        $this->view->orderBy  = $orderBy;
        $this->view->pager    = $pager;

        $this->display();
    }

    /**
     * 添加gogs服务器。
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
            $priv = $this->checkToken($gogs);
            if(is_array($priv)) return $this->send($priv);

            $gogsID = $this->loadModel('pipeline')->create($gogs);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('gogs', $gogsID, 'created');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('space', 'browse')));
        }

        $this->view->title = $this->lang->gogs->common . $this->lang->colon . $this->lang->gogs->lblCreate;
        $this->display();
    }

    /**
     * 查看gogs服务器。
     * View a gogs.
     * @param  int    $gogsID
     * @access public
     * @return void
     */
    public function view(int $gogsID)
    {
        $gogs = $this->gogs->fetchByID($gogsID);

        $this->view->title      = $this->lang->gogs->common . $this->lang->colon . $this->lang->gogs->view;
        $this->view->gogs       = $gogs;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed');
        $this->view->actions    = $this->loadModel('action')->getList('gogs', $gogsID);
        $this->view->preAndNext = $this->loadModel('common')->getPreAndNextObject('pipeline', $gogsID);
        $this->display();
    }

    /**
     * 编辑gogs服务器。
     * Edit a gogs.
     *
     * @param  int    $gogsID
     * @access public
     * @return void
     */
    public function edit(int $gogsID)
    {
        $oldGogs = $this->gogs->fetchByID($gogsID);

        if($_POST)
        {
            $gogs = form::data($this->config->gogs->form->edit)->get();
            $this->checkToken($gogs);
            $this->loadModel('pipeline')->update($gogsID, $gogs);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $gogs     = $this->gogs->fetchByID($gogsID);
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
    public function delete(int $gogsID)
    {
        $oldGogs  = $this->loadModel('pipeline')->fetchByID($gogsID);
        $actionID = $this->pipeline->deleteByObject($gogsID, 'gogs');
        if(!$actionID)
        {
            $response['result']   = 'fail';
            $response['callback'] = sprintf('zui.Modal.alert("%s");', $this->lang->pipeline->delError);

            return $this->send($response);
        }

        $gogs    = $this->pipeline->fetchByID($gogsID);
        $changes = common::createChanges($oldGogs, $gogs);
        $this->loadModel('action')->logHistory($actionID, $changes);

        $response['load']   = $this->createLink('space', 'browse');
        $response['result'] = 'success';
        return $this->send($response);
    }

    /**
     * 绑定gogs用户到禅道用户。
     * Bind gogs user to zentao users.
     *
     * @param  int    $gogsID
     * @param  string $type
     * @access public
     * @return void
     */
    public function bindUser(int $gogsID, string $type = 'all')
    {
        if($_POST)
        {
            $this->gogs->bindUser($gogsID, (array)$this->post->zentaoUsers, (array)$this->post->gogsUserNames);
            if(dao::isError()) return $this->sendError(dao::getError());
            return $this->sendSuccess(array('message' => $this->lang->saveSuccess, 'load' => helper::createLink('space', 'browse')));
        }

        $userList      = array();
        $gogsUsers     = $this->gogs->apiGetUsers($gogsID);
        $bindedUsers   = $this->loadModel('pipeline')->getUserBindedPairs($gogsID, 'gogs');
        $matchedResult = $this->gogsZen->getMatchedUsers($gogsID, $gogsUsers);
        $zentaoUsers   = $this->loadModel('user')->getRealNameAndEmails(helper::arrayColumn($matchedResult, 'zentaoAccount'));

        foreach($gogsUsers as $gogsUser)
        {
            $user = new stdclass();
            $user->email          = '';
            $user->status         = 'notBind';
            $user->gogsID         = $gogsUser->id;
            $user->gogsEmail      = $gogsUser->email;
            $user->gogsAccount    = $gogsUser->account;
            $user->gogsUser       = $gogsUser->realname . '@' . $gogsUser->account;
            $user->gogsUserAvatar = $gogsUser->avatar;

            $user->zentaoUsers = isset($matchedResult[$gogsUser->id]) ? $matchedResult[$gogsUser->id]->zentaoAccount : '';
            if($user->zentaoUsers)
            {
                if(isset($zentaoUsers[$user->zentaoUsers])) $user->email = $zentaoUsers[$user->zentaoUsers]->email;

                if(isset($bindedUsers[$user->zentaoUsers]) && $bindedUsers[$user->zentaoUsers] == $gogsUser->id) $user->status = 'binded';
            }

            if($type != 'all' && $user->status != $type) continue;
            $userList[] = $user;
        }

        $this->view->title       = $this->lang->gogs->bindUser;
        $this->view->type        = $type;
        $this->view->gogsID      = $gogsID;
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
     * @param  int    $gogsID
     * @param  string $project
     * @access public
     * @return void
     */
    public function ajaxGetProjectBranches(int $gogsID, string $project)
    {
        $options = array(array('text' => '', 'value' => ''));
        if(!$gogsID || !$project) return print(json_encode($options));

        $project  = urldecode(base64_decode($project));
        $branches = $this->gogs->apiGetBranches($gogsID, $project);

        foreach($branches as $branch)
        {
            $options[] = array('text' => $branch->name, 'value' => $branch->name);
        }
        return print(json_encode($options));
    }
}
