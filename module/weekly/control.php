<?php
/**
 * The control file of weekly of ChanzhiEPS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     weekly
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class weekly extends control
{
    /**
     * The construct function, load users.
     *
     * @access public
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
        $this->view->users = $this->loadModel('user')->getPairs('noletter');
    }

    /**
     * Common action.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function commonAction($projectID = 0)
    {
        $this->loadModel('project')->setMenu($projectID);
    }

    /**
     * Index
     *
     * @param  int    $projectID
     * @param  string $date
     * @access public
     * @return void
     */
    public function index($projectID = 0, $date = '')
    {
        $this->commonAction($projectID);
        if(!$date) $date = helper::today();
        $date = date('Y-m-d', strtotime($date));

        $this->weekly->save($projectID, $date);

        $this->view->title = $this->lang->weekly->common;

        $PVEV = $this->weekly->getPVEV($projectID, $date);
        $this->view->pv = $PVEV['PV'];
        $this->view->ev = $PVEV['EV'];
        $this->view->ac = $this->weekly->getAC($projectID, $date);
        $this->view->sv = $this->weekly->getSV($this->view->ev, $this->view->pv);
        $this->view->cv = $this->weekly->getCV($this->view->ev, $this->view->ac);

        $this->view->project   = $this->loadModel('project')->getByID($projectID);
        $this->view->weekSN    = $this->weekly->getWeekSN($this->view->project->begin, $date);
        $this->view->monday    = $this->weekly->getThisMonday($date);
        $this->view->lastDay   = $this->weekly->getLastDay($date);
        $this->view->staff     = $this->weekly->getStaff($projectID, $date);
        $this->view->finished  = $this->weekly->getFinished($projectID, $date);
        $this->view->postponed = $this->weekly->getPostponed($projectID, $date);
        $this->view->nextWeek  = $this->weekly->getTasksOfNextWeek($projectID, $date);
        $this->view->workload  = $this->weekly->getWorkloadByType($projectID, $date);

        $this->lang->modulePageNav = $this->weekly->getPageNav($this->view->project, $date);
        $this->display();
    }

    /**
     * ComputeWeekly
     *
     * @access public
     * @return void
     */
    public function computeWeekly()
    {
        $projects = $this->dao->select('id, name')->from(TABLE_PROJECT)
           ->where('deleted')->eq(0)
           ->andWhere('type')->eq('project')
           ->fetchPairs();
        $date = helper::today();

        foreach($projects as $projectID => $project) $this->weekly->save($projectID, $date);
    }
}
