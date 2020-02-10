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
class ci extends control
{
    /**
     * ci constructor.
     * @param string $moduleName
     * @param string $methodName
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);

        $repoID = $this->session->repoID;
        foreach($this->lang->repo->menu as $key => $menu)
        {
            common::setMenuVars($this->lang->ci->menu, $key, $repoID);
        }

        if(common::hasPriv('ci', 'createJob') and strpos(',browsejob,', $this->methodName) > -1) {
            $this->lang->modulePageActions = html::a(helper::createLink('ci', 'createJob'), "<i class='icon icon-plus text-muted'></i> " . $this->lang->ci->create, '', "class='btn'");
        }
    }

    /**
     * CI index page.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $this->view->position[] = $this->lang->ci->common;

        $this->display();
    }

    /**
     * Browse ci job.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browseJob($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->jobList    = $this->ci->listJob($orderBy, $pager);

        $this->view->title      = $this->lang->ci->job . $this->lang->colon . $this->lang->ci->browse;
        $this->view->position[] = $this->lang->ci->job;
        $this->view->position[] = $this->lang->ci->browse;

        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;

        $this->display();
    }

    /**
     * Create a ci job.
     *
     * @access public
     * @return void
     */
    public function createJob()
    {
        if($_POST)
        {
            $this->ci->createJob();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browseJob')));
        }

        $this->app->loadLang('action');

        $this->view->title      = $this->lang->ci->job . $this->lang->colon . $this->lang->ci->create;
        $this->view->position[] = html::a(inlink('browseJob'), $this->lang->ci->job);
        $this->view->position[] = $this->lang->ci->create;

        $this->view->repoList      = $this->loadModel('repo')->listForSelectionWithType("true");
        $this->view->jenkinsList   = $this->loadModel('jenkins')->listForSelection("true");

        $this->display();
    }

    /**
     * Edit a ci job.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function editJob($id)
    {
        $job = $this->ci->getJobByID($id);
        if($_POST)
        {
            $this->ci->updateJob($id);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browseJob')));
        }

        $this->app->loadLang('action');

        $repo        = $this->loadModel('repo')->getRepoByID($job->repo);
        $job->repoType           = $repo->id . '-' . $repo->SCM;
        $this->view->job         = $job;

        $this->view->repoList    = $this->loadModel('repo')->listForSelectionWithType("true");
        $this->view->jenkinsList = $this->loadModel('jenkins')->listForSelection("true");

        $this->view->title       = $this->lang->ci->job . $this->lang->colon . $this->lang->ci->edit;
        $this->view->position[]  = html::a(inlink('browseJob'), $this->lang->ci->job);
        $this->view->position[]  = $this->lang->ci->edit;

        $this->display();
    }

    /**
     * Delete a ci job.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function deleteJob($id)
    {
        $this->ci->delete(TABLE_CI_JOB, $id);

        $command = 'moduleName=ci&methodName=exe&parm=' . $id;
        $this->dao->delete()->from(TABLE_CRON)->where('command')->eq($command)->exec();

        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->send(array('result' => 'success'));
    }

    /**
     * Exec a ci job.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function exeJob($id)
    {
        $result = $this->ci->exeJob($id);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        if(!$result) $this->send(array('result' => 'fail', 'message' => 'not found'));

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
    public function browseBuild($jobID = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->buildList  = $this->ci->listBuild($jobID, $orderBy, $pager);
        $this->view->job        = $this->ci->getJobByID($jobID);

        $this->view->title      = $this->lang->ci->job . $this->lang->colon . $this->lang->job->browseBuild;
        $this->view->position[] = html::a(inlink('browseJob'), $this->lang->ci->job);
        $this->view->position[]    = $this->lang->job->browseBuild;

        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->module     = 'ci';
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
        $build = $this->ci->getBuildByID($buildID);
        $this->view->logs   = str_replace("\r\n","<br />", $build->logs);
        $this->view->build  = $build;

        $this->view->title = $this->lang->ci->job . $this->lang->colon . $this->lang->job->viewLogs;
        $this->view->position[] = html::a(inlink('browseJob'), $this->lang->ci->job);
        $this->view->position[] = html::a(inlink('browseBuild', "jobID=" . $build->cijob), $this->lang->job->browseBuild);
        $this->view->position[] = $this->lang->job->viewLogs;

        $this->view->module     = 'ci';
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
        $this->ci->checkBuildStatus();
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->send(array('result' => 'success'));
    }

}