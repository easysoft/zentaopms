<?php
/**
 * The control file of gogs module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Liyuchun <liyuchun@easycorp.ltd>
 * @package     product
 * @version     $Id: ${FILE_NAME} 5144 2022-08-01 liyuchun@easycorp.ltd $
 * @link        http://www.zentao.net
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

        /* This is essential when changing tab(menu) from gogs to repo. */
        /* Optional: common::setMenuVars('devops', $this->session->repoID); */
        $this->loadModel('ci')->setMenu();
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
            $this->checkToken();
            $gogsID = $this->gogs->create();

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $actionID = $this->loadModel('action')->create('gogs', $gogsID, 'created');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
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
            $this->checkToken();
            $this->gogs->update($gogsID);
            $gogs = $this->gogs->getByID($gogsID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action');
            $actionID = $this->action->create('gogs', $gogsID, 'edited');
            $changes  = common::createChanges($oldGogs, $gogs);
            $this->action->logHistory($actionID, $changes);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title = $this->lang->gogs->common . $this->lang->colon . $this->lang->gogs->edit;
        $this->view->gogs  = $oldGogs;

        $this->display();
    }

    /**
     * Delete a gogs.
     *
     * @param  int    $gogsID
     * @access public
     * @return void
     */
    public function delete($gogsID, $confirm = 'no')
    {
        if($confirm != 'yes') return print(js::confirm($this->lang->gogs->confirmDelete, inlink('delete', "id=$gogsID&confirm=yes")));

        $oldGogs  = $this->loadModel('pipeline')->getByID($gogsID);
        $actionID = $this->pipeline->delete($gogsID, 'gogs');
        if(!$actionID) return print(js::error($this->lang->pipeline->delError));

        $gogs    = $this->pipeline->getByID($gogsID);
        $changes = common::createChanges($oldGogs, $gogs);
        $this->loadModel('action')->logHistory($actionID, $changes);
        return print(js::reload('parent'));
    }

    /**
     * Check post token has admin permissions.
     *
     * @access protected
     * @return void
     */
    protected function checkToken()
    {
        $gogs = fixer::input('post')->trim('url,token')->get();
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
     * @param  int     $gogsID
     * @access public
     * @return void
     */
    public function bindUser($gogsID)
    {
        $zentaoUsers = $this->dao->select('account,email,realname')->from(TABLE_USER)->fetchAll('account');
        $userPairs   = $this->loadModel('user')->getPairs('noclosed|noletter');

        if($_POST)
        {
            $this->gogs->bindUser($gogsID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->server->http_referer));
        }

        $this->view->title         = $this->lang->gogs->bindUser;
        $this->view->userPairs     = $userPairs;
        $this->view->gogsUsers     = $this->gogs->apiGetUsers($gogsID);
        $this->view->bindedUsers   = $this->gogs->getUserAccountIdPairs($gogsID);
        $this->view->matchedResult = $this->gogs->getMatchedUsers($gogsID, $this->view->gogsUsers, $zentaoUsers);
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
        $options  = "<option value=''></option>";
        foreach($branches as $branch)
        {
            $options .= "<option value='{$branch->name}'>{$branch->name}</option>";
        }
        $this->send($options);
    }
}
