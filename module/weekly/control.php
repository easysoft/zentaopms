<?php
/**
 * The control file of weekly of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
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
        if(!common::hasPriv('weekly', 'view')) return $this->locate($this->createLink('user', 'deny', 'module=weekly&method=view'));

        $this->commonAction($projectID);
        if(!$date) $date = helper::today();
        $date = date('Y-m-d', strtotime($date));

        $this->weekly->save($projectID, $date);

        /* Get report data from module and assign data to view object. */
        $data = $this->weekly->getReportData($projectID, $date);
        foreach($data as $key => $val) $this->view->$key = $val;

        $this->view->title = $this->lang->weekly->common;
        $this->view->date  = $date;
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

    /**
     *  定时创建报告。
     *  Generate weekly report.
     *
     * @access public
     * @return void
     */
    public function createCycleReport()
    {
        if(in_array($this->config->edition, array('open', 'biz'))) return print('Support min edition is max');

        $errors = $this->loadModel('reporttemplate')->createCycleReport();
        if($errors) return print(json_encode($errors));
        return print('success');
    }
}
