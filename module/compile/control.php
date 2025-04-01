<?php
declare(strict_types=1);
/**
 * The control file of compile of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     compile
 * @version     $Id$
 * @link        https://www.zentao.net
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
    public function __construct(string $moduleName = '', string $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        if(!in_array($this->app->rawMethod, array('browse', 'logs'))) $this->loadModel('ci')->setMenu();
    }

    /**
     * Browse jenkins build.
     *
     * @param  int    $repoID
     * @param  int    $jobID
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse(int $repoID = 0, int $jobID = 0, string $browseType = '', int $param = 0, string $orderBy = 'createdDate_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->loadModel('ci');
        $this->loadModel('job');

        if($jobID)
        {
            $job    = $this->job->getByID($jobID);
            $repoID = $job->repo;

            $this->view->job = $job;
        }

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

        $this->compileZen->buildSearchForm($repoID, $jobID, (int)$param);
        $buildList = $this->compile->getList($repoID, $jobID, $browseType, (int)$param, $orderBy, $pager);

        foreach($buildList as $build) $build->triggerType = $this->job->getTriggerConfig($build);

        $this->view->title      = $this->lang->ci->job . $this->lang->hyphen . $this->lang->compile->browse;
        $this->view->repoID     = $repoID;
        $this->view->jobID      = $jobID;
        $this->view->buildList  = $buildList;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->browseType = $browseType;
        $this->view->param      = $param;
        $this->display();
    }

    /**
     * View jenkins build logs.
     *
     * @param  int    $buildID
     * @access public
     * @return void
     */
    public function logs(int $buildID)
    {
        $this->loadModel('ci');
        if($this->session->repoID)
        {
            $this->ci->setMenu();
            $this->view->repoID = $this->session->repoID;
        }

        $build = $this->compile->getByID($buildID);
        $job   = $this->loadModel('job')->getByID($build->job);

        if(!in_array($build->status, array('created', 'pending'))) $build->logs = $this->compile->getLogs($job, $build);
        $logs = $build->logs ? str_replace(array("\r\n", "\n"), "<br />", $build->logs) : '';

        $this->view->logs  = $logs;
        $this->view->build = $build;
        $this->view->job   = $job;
        $this->view->title = $this->lang->ci->job . $this->lang->hyphen . $this->lang->compile->logs;
        $this->display();
    }

    /**
     * Sync compiles.
     *
     * @param  int    $repoID
     * @param  int    $jobID
     * @access public
     * @return bool
     */
    public function ajaxSyncCompile(int $repoID = 0, int $jobID = 0)
    {
        $this->compile->syncCompile($repoID, $jobID);

        if(dao::isError())
        {
            echo json_encode(dao::getError());
            return true;
        }
        echo 'success';
    }
}

