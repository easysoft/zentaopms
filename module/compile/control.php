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
        if($methodName != 'browse') $this->loadModel('ci')->setMenu();
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
    public function browse($repoID = 0, $jobID = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        if($jobID)
        {
            $job    = $this->loadModel('job')->getById($jobID);
            $repoID = $job->repo;

            $this->view->job = $job;
        }

        $this->compile->syncCompile($repoID, $jobID);

        $this->app->loadLang('job');
        $this->loadModel('ci')->setMenu($repoID);

        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->ci->job . $this->lang->colon . $this->lang->compile->browse;
        $this->view->position[] = html::a($this->createLink('job', 'browse'), $this->lang->ci->job);
        $this->view->position[] = $this->lang->compile->browse;

        $this->view->repoID    = $repoID;
        $this->view->jobID     = $jobID;
        $this->view->buildList = $this->compile->getList($repoID, $jobID, $orderBy, $pager);
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
        $build = $this->compile->getByID($buildID);
        $job   = $this->loadModel('job')->getByID($build->job);

        if(empty($build->logs) and !in_array($build->status, array('created', 'pending'))) $build->logs = $this->compile->getLogs($job, $build);
        $logs = str_replace("\r\n", "<br />", $build->logs);
        $logs = str_replace("\n", "<br />", $logs);

        $this->view->logs  = $logs;
        $this->view->build = $build;
        $this->view->job   = $job;

        $this->view->title = $this->lang->ci->job . $this->lang->colon . $this->lang->compile->logs;
        $this->view->position[] = html::a($this->createLink('job', 'browse'), $this->lang->ci->job);
        $this->view->position[] = html::a($this->createLink('compile', 'browse', "jobID=" . $build->job), $this->lang->compile->browse);
        $this->view->position[] = $this->lang->compile->logs;
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

