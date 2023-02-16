<?php
/**
 * The control file of gitea module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
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

        /* This is essential when changing tab(menu) from gitea to repo. */
        /* Optional: common::setMenuVars('devops', $this->session->repoID); */
        $this->loadModel('ci')->setMenu();
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
            $this->checkToken();
            $giteaID = $this->gitea->create();

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $actionID = $this->loadModel('action')->create('gitea', $giteaID, 'created');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
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
            $this->checkToken();
            $this->gitea->update($giteaID);
            $gitea = $this->gitea->getByID($giteaID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action');
            $actionID = $this->action->create('gitea', $giteaID, 'edited');
            $changes  = common::createChanges($oldGitea, $gitea);
            $this->action->logHistory($actionID, $changes);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title = $this->lang->gitea->common . $this->lang->colon . $this->lang->gitea->edit;
        $this->view->gitea = $oldGitea;

        $this->display();
    }

    /**
     * Delete a gitea.
     *
     * @param  int    $giteaID
     * @access public
     * @return void
     */
    public function delete($giteaID, $confirm = 'no')
    {
        if($confirm != 'yes') return print(js::confirm($this->lang->gitea->confirmDelete, inlink('delete', "id=$giteaID&confirm=yes")));

        $oldGitea = $this->loadModel('pipeline')->getByID($giteaID);
        $actionID = $this->pipeline->delete($giteaID, 'gitea');
        if(!$actionID) return print(js::error($this->lang->pipeline->delError));

        $gitea   = $this->pipeline->getByID($giteaID);
        $changes = common::createChanges($oldGitea, $gitea);
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
        $gitea = fixer::input('post')->trim('url,token')->get();
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
     * @param  int     $giteaID
     * @access public
     * @return void
     */
    public function bindUser($giteaID)
    {
        $zentaoUsers = $this->dao->select('account,email,realname')->from(TABLE_USER)->fetchAll('account');
        $userPairs   = $this->loadModel('user')->getPairs('noclosed|noletter');

        if($_POST)
        {
            $this->gitea->bindUser($giteaID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->server->http_referer));
        }

        $this->view->title         = $this->lang->gitea->bindUser;
        $this->view->userPairs     = $userPairs;
        $this->view->giteaUsers    = $this->gitea->apiGetUsers($giteaID);
        $this->view->bindedUsers   = $this->gitea->getUserAccountIdPairs($giteaID);
        $this->view->matchedResult = $this->gitea->getMatchedUsers($giteaID, $this->view->giteaUsers, $zentaoUsers);
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
        $options  = "<option value=''></option>";
        foreach($branches as $branch)
        {
            $options .= "<option value='{$branch->name}'>{$branch->name}</option>";
        }
        $this->send($options);
    }
}
