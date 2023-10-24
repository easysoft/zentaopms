<?php
/**
 * The control file of compile of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     compile
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class compile extends control
{
    /**
     * Construct
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        if(!in_array($this->app->rawMethod, array('browse', 'logs'))) $this->loadModel('ci')->setMenu();
    }

    /**
     * Browse jenkins build.
     *
     * @param  int    $repoID
     * @param  int    $jobID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($repoID = 0, $jobID = 0, $orderBy = 'createdDate_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('ci');
        if($jobID)
        {
            $job    = $this->loadModel('job')->getById($jobID);
            $repoID = $job->repo;

            $this->view->job = $job;
        }

        if($repoID || $jobID) $this->compile->syncCompile($repoID, $jobID);

        $this->app->loadLang('job');
        if($repoID)
        {
            $this->ci->setMenu($repoID);
        }
        else
        {
            $this->session->set('repoID', 0);
        }

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $buildList = $this->compile->getList($repoID, $jobID, $orderBy, $pager);

        foreach($buildList as $build) $build->triggerType = $this->loadModel('job')->getTriggerConfig($build);

        $this->view->title     = $this->lang->ci->job . $this->lang->colon . $this->lang->compile->browse;
        $this->view->repoID    = $repoID;
        $this->view->jobID     = $jobID;
        $this->view->buildList = $buildList;
        $this->view->orderBy   = $orderBy;
        $this->view->pager     = $pager;
        $this->display();
    }

    /**
     * View jenkins build logs.
     *
     * @param  int    $buildID
     * @access public
     * @return void
     */
    public function logs($buildID)
    {
        if($this->session->repoID)
        {
            $this->loadModel('ci')->setMenu();
            $this->view->repoID = $this->session->repoID;
        }

        $build = $this->compile->getByID($buildID);
        $job   = $this->loadModel('job')->getByID($build->job);

        if(empty($build->logs) and !in_array($build->status, array('created', 'pending'))) $build->logs = $this->compile->getLogs($job, $build);
        $logs        = '';
        if($build->logs)
        {
            $logs = str_replace("\r\n", "<br />", $build->logs);
            $logs = str_replace("\n", "<br />", $logs);
        }

        $this->view->logs  = $logs;
        $this->view->build = $build;
        $this->view->job   = $job;

        $this->view->title = $this->lang->ci->job . $this->lang->colon . $this->lang->compile->logs;
        $this->display();
    }

    /**
     * Sync compiles.
     *
     * @access public
     * @return bool
     */
    public function syncCompile()
    {
        $this->compile->syncCompile();

        if(dao::isError())
        {
            echo json_encode(dao::getError());
            return true;
        }
        echo 'success';
    }
}

