<?php
/**
 * The control file of gitea module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
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
            $this->loadModel('action');
            $actionID = $this->action->create('gitea', $giteaID, 'created');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title = $this->lang->gitea->common . $this->lang->colon . $this->lang->gitea->lblCreate;

        $this->display();
    }

    /**
     * View a gitea.
     * @param  int    $id
     * @access public
     * @return void
     */
    public function view($id)
    {
        $gitea = $this->gitea->getByID($id);

        $this->view->title      = $this->lang->gitea->common . $this->lang->colon . $this->lang->gitea->view;
        $this->view->gitea     = $gitea;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed');
        $this->view->actions    = $this->loadModel('action')->getList('gitea', $id);
        $this->view->preAndNext = $this->loadModel('common')->getPreAndNextObject('pipeline', $id);
        $this->display();
    }

    /**
     * Edit a gitea.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function edit($id)
    {
        $oldGitea = $this->gitea->getByID($id);

        if($_POST)
        {
            $this->checkToken();
            $this->gitea->update($id);
            $gitea = $this->gitea->getByID($id);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action');
            $actionID = $this->action->create('gitea', $id, 'edited');
            $changes  = common::createChanges($oldGitea, $gitea);
            $this->action->logHistory($actionID, $changes);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->view->title  = $this->lang->gitea->common . $this->lang->colon . $this->lang->gitea->edit;
        $this->view->gitea = $oldGitea;

        $this->display();
    }

    /**
     * Delete a gitea.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function delete($id, $confirm = 'no')
    {
        if($confirm != 'yes') return print(js::confirm($this->lang->gitea->confirmDelete, inlink('delete', "id=$id&confirm=yes")));

        $oldGitea = $this->gitea->getByID($id);
        $this->loadModel('action');
        $this->gitea->delete(TABLE_PIPELINE, $id);

        $actionID = $this->dao->lastInsertID();
        $gitea    = $this->gitea->getByID($id);
        $changes  = common::createChanges($oldGitea, $gitea);
        $this->action->logHistory($actionID, $changes);
        echo js::reload('parent');
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
}
