<?php
/**
 * The control file of weekly of ChanzhiEPS.
 *
 * @copyright   Copyright 2009-2023 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     weekly
 * @version     $Id$
 * @link        https://www.zentao.net
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
        if(common::hasPriv('weekly', 'exportweeklyreport'))
        {
            $this->lang->TRActions = "<div class='btn-toolbar pull-right'>" . html::a($this->createLink('weekly', 'exportweeklyreport', 'module=' . $this->app->getModuleName() . '&projectID=' . $projectID), $this->lang->export, '', "class='btn btn-primary' data-width='30%' id='exportreport' data-group='project'") . '</div';
        }

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

        /* Get report data from module and assign data to view object. */
        $data = $this->weekly->getReportData($projectID, $date);
        foreach($data as $key => $val) $this->view->$key = $val;

        $this->view->title = $this->lang->weekly->common;
        $this->view->date  = $date;

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
           ->andWhere('status')->ne('closed')
           ->fetchPairs();

        $date      = helper::today();
        $weekStart = $this->weekly->getThisMonday($date);

        foreach($projects as $projectID => $project)
        {
            $this->dao->delete()->from(TABLE_WEEKLYREPORT)->where('project')->eq($projectID)->andWhere('weekStart')->eq($weekStart)->exec();
            $this->weekly->save($projectID, $date);
        }
    }
}
