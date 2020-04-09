<?php
/**
 * The control file of compile of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
        $this->loadModel('ci')->setMenu();
    }

    /**
     * Browse jenkins build.
     *
     * @param  int    $jobID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($jobID = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->ci->job . $this->lang->colon . $this->lang->compile->browse;
        $this->view->position[] = html::a($this->createLink('job', 'browse'), $this->lang->ci->job);
        $this->view->position[] = $this->lang->compile->browse;

        $this->loadModel('job');
        if($jobID) $this->view->job = $this->job->getById($jobID);
        $this->view->jobID     = $jobID;
        $this->view->buildList = $this->compile->getList($jobID, $orderBy, $pager);
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
        $this->view->logs   = str_replace("\r\n","<br />", $build->logs);
        $this->view->build  = $build;

        $this->view->title = $this->lang->ci->job . $this->lang->colon . $this->lang->compile->logs;
        $this->view->position[] = html::a($this->createLink('job', 'browse'), $this->lang->ci->job);
        $this->view->position[] = html::a($this->createLink('compile', 'browse', "jobID=" . $build->job), $this->lang->compile->browse);
        $this->view->position[] = $this->lang->compile->logs;
        $this->display();
    }
}

