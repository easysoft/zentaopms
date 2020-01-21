<?php

/**
 * The control file of ci module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: ${FILE_NAME} 5144 2020/1/8 8:10 下午 chenqi@cnezsoft.com $
 * @link        http://www.zentao.net
 */
class citask extends control
{
    /**
     * ci constructor.
     * @param string $moduleName
     * @param string $methodName
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
    }

    /**
     * Browse ci task.
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

        $this->view->title      = $this->lang->citask->common . $this->lang->colon . $this->lang->citask->browse;
        $this->view->taskList   = $this->citask->listAll($orderBy, $pager);

        $this->view->position[] = $this->lang->ci->common;
        $this->view->position[] = html::a(inlink('browse'), $this->lang->citask->common);
        $this->view->position[] = $this->lang->citask->browse;

        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->module      = 'citask';
        $this->display();
    }

    /**
     * Create a ci task.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $this->citask->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->app->loadLang('action');
        $this->view->title         = $this->lang->citask->create . $this->lang->colon . $this->lang->citask->create;

        $this->view->position[] = $this->lang->ci->common;
        $this->view->position[] = html::a(inlink('browse'), $this->lang->citask->common);
        $this->view->position[] = $this->lang->citask->create;

        $this->view->repoList      = $this->loadModel('cirepo')->listForSelection("true");
        $this->view->jenkinsList   = $this->loadModel('cijenkins')->listForSelection("true");
        $this->view->module        = 'citask';

        $this->display();
    }

    /**
     * Edit a ci task.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function edit($id)
    {
        $citask = $this->citask->getByID($id);
        if($_POST)
        {
            $this->citask->update($id);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->app->loadLang('action');
        $this->view->title         = $this->lang->citask->edit . $this->lang->colon . $citask->name;

        $this->view->position[] = $this->lang->ci->common;
        $this->view->position[] = html::a(inlink('browse'), $this->lang->citask->common);
        $this->view->position[]    = $this->lang->citask->edit;

        $this->view->citask        = $citask;

        $this->view->repoList      = $this->loadModel('cirepo')->listForSelection("true");
        $this->view->jenkinsList   = $this->loadModel('cijenkins')->listForSelection("true");
        $this->view->module        = 'citask';
        $this->display();
    }

    /**
     * Delete a ci task.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function delete($id)
    {
        $this->citask->delete(TABLE_CI_TASK, $id);

        $command = 'moduleName=citask&methodName=exe&parm=' . $id;
        $this->dao->delete()->from(TABLE_CRON)->where('command')->eq($command)->exec();

        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->send(array('result' => 'success'));
    }

    /**
     * Exec a ci task.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function exe($id)
    {
        error_log("===exeCitask " . $id);

        $this->citask->exe($id);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->send(array('result' => 'success'));
    }

    /**
     * Browse jenkins build.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browseBuild($taskID = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->citask->common . $this->lang->colon . $this->lang->citask->browseBuild;
        $this->view->buildList  = $this->citask->listBuild($taskID, $orderBy, $pager);

        $this->view->position[] = $this->lang->ci->common;
        $this->view->position[] = html::a(inlink('browse'), $this->lang->citask->common);
        $this->view->position[]    = $this->lang->citask->browseBuild;

        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->module      = 'citask';
        $this->display();
    }

    /**
     * View jenkins build logs.
     *
     * @param  int    $buildID
     * @access public
     * @return void
     */
    public function viewBuildLogs($buildID)
    {
        $this->view->title = $this->lang->citask->common . $this->lang->colon . $this->lang->citask->viewLogs;

        $build = $this->citask->getBuild($buildID);
        $this->view->logs  = str_replace("\r\n","<br />", $build->logs);

        $this->view->position[] = $this->lang->ci->common;
        $this->view->position[] = html::a(inlink('browse'), $this->lang->citask->common);
        $this->view->position[] = html::a(inlink('browseBuild', "taskID=" . $build->citask), $this->lang->citask->browseBuild);
        $this->view->position[] = $this->lang->citask->viewLogs;

        $this->view->module      = 'citask';
        $this->display();
    }

    /**
     * Send a request to jenkins to check build status.
     *
     * @access public
     * @return void
     */
    public function checkBuildStatus()
    {
        error_log("===checkBuildStatus");

        $this->citask->checkBuildStatus();
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->send(array('result' => 'success'));
    }

}