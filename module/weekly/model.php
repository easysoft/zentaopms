<?php
/**
 * The model file of weekly module of ChanzhiEPS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     weekly
 * @version     $Id$
 * @link        http://www.chanzhi.org
 */
class weeklyModel extends model
{
    /**
     * GetPageNav
     *
     * @param  int    $program
     * @param  int    $date
     * @access public
     * @return string
     */
    public function getPageNav($program, $date)
    {
        $date  = date('Ymd', strtotime($this->getThisMonday($date)));
        $begin = $program->begin;
        $weeks = $this->getWeekPairs($begin);
        $current = zget($weeks, $date, '');
        $selectHtml  = "<div class='btn-group angle-btn'>";
        $selectHtml .= html::a('###', $this->lang->weekly->common . $this->lang->colon . $program->name, '', "class='btn'");
        $selectHtml .= '</div>';

        $selectHtml .= "<div class='btn-group angle-btn'>";
        $selectHtml .= "<div class='btn-group'>";
        $selectHtml .= "<a data-toggle='dropdown' class='btn' title=$current>" . $current . " <span class='caret'></span></a>";
        $selectHtml .= "<ul class='dropdown-menu'>";
        foreach($weeks as $day => $title)
        {
            $selectHtml .= '<li>' . html::a(helper::createLink('weekly', 'index', "program={$program->id}&date=$day"), $title) . '</li>';
        }
        $selectHtml .='</ul></div></div>';
        return $selectHtml;

    }

    /**
     * GetWeekPairs
     *
     * @param  int    $begin
     * @access public
     * @return array
     */
    public function getWeekPairs($begin)
    {
        $sn = $this->getWeekSN($begin, date('Y-m-d'));
        $weeks = array();
        for($i = 0; $i <= $sn; $i++)
        {
            $monday = $this->getThisMonday($begin);
            $sunday = $this->getThisSunday($begin);
            $begin = date('Y-m-d', strtotime("$begin +7 days"));
            $key = date('Ymd', strtotime($monday));
            $weeks[$key] = sprintf($this->lang->weekly->weekDesc, $i + 1, $monday, $sunday);
        }
        krsort($weeks);
        return $weeks;
    }

    /**
     * GetFromDB
     *
     * @param  int    $program
     * @param  int    $date
     * @access public
     * @return object
     */
    public function getFromDB($program, $date)
    {
        $monday = $this->getThisMonday($date);
        return $this->dao->select('*')
            ->from(TABLE_WEEKLYREPORT)
            ->where('weekStart')->eq($monday)
            ->andWhere('PRJ')->eq($program)
            ->fetch();
    }

    /**
     * Save data.
     *
     * @param  int    $project
     * @param  int    $date
     * @access public
     * @return void
     */
    public function save($project, $date)
    {
        $report = new stdclass;
        $report->pv        = $this->getPV($project, $date);
        $report->ev        = $this->getEV($project, $date);
        $report->ac        = $this->getAC($project, $date);
        $report->sv        = $this->getSV($report->ev, $report->pv);
        $report->cv        = $this->getCV($report->ev, $report->ac);
        $report->PRJ       = $project;
        $report->weekStart = $this->getThisMonday($date);
        $report->staff     = $this->getStaff($project);
        $report->workload  = json_encode($this->getWorkloadByType($project, $date));
        $this->dao->replace(TABLE_WEEKLYREPORT)->data($report)->exec();
    }

    /**
     * GetWeekSN
     *
     * @param  int    $begin
     * @param  int    $date
     * @access public
     * @return int
     */
    public function getWeekSN($begin, $date)
    {
       return ceil((strtotime($date) - strtotime($begin)) / 7 / 86400);
    }

    /**
     * Get monday for a date.
     *
     * @param  int $date
     * @access public
     * @return date
     */
    public function getThisMonday($date)
    {
        $day = date('w', strtotime($date));
        if($day == 0) $day = 7;
        $days = $day - 1;
        return date('Y-m-d', strtotime("$date - $days days"));
    }

    /**
     * GetThisSunday
     *
     * @param  int    $date
     * @access public
     * @return date
     */
    public function getThisSunday($date)
    {
        $monday = $this->getThisMonday($date);
        return date('Y-m-d', strtotime("$monday +6 days"));
    }

    /**
     * GetLastDay
     *
     * @param  int    $date
     * @access public
     * @return string
     */
    public function getLastDay($date)
    {
        $this->loadModel('project');
        $weekend  = zget($this->config->project, 'weekend', 2);
        $monday   = $this->getThisMonday($date);
        $sunday   = $this->getThisSunday($date);
        $workdays = $this->loadModel('holiday')->getActualWorkingDays($monday, $sunday);
        return end($workdays);
    }

    /**
     * GetStaff
     *
     * @param  int    $program
     * @param  string $date
     * @access public
     * @return array
     */
    public function getStaff($program, $date = '')
    {
        if(!$date) $date = date('Y-m-d');
        $monday = $this->getThisMonday($date);
        $sunday = $this->getThisSunday($date);
        $projects = $this->loadModel('project')->getList($status = 'all', $limit = 0, $productID = 0, $branch = 0, $program);
        $projectIdList = array_keys($projects);

        return $this->dao->select('count(distinct t1.account) as count')
            ->from(TABLE_TASKESTIMATE)->alias('t1')
            ->leftJoin(TABLE_TASK)->alias('t2')->on('t1.task=t2.id')
            ->where('t2.project')->in($projectIdList)
            ->andWhere('t1.date')->ge($monday)
            ->andWhere('t1.date')->lt($sunday)
            ->fetch('count');
    }

    /**
     * GetFinished
     *
     * @param  int    $program
     * @param  string $date
     * @param  int    $pager
     * @access public
     * @return void
     */
    public function getFinished($program, $date = '', $pager = null)
    {
        if(!$date) $date = date('Y-m-d');
        $monday = $this->getThisMonday($date);
        $sunday = $this->getThisSunday($date);

        $projects = $this->loadModel('project')->getList($status = 'all', $limit = 0, $productID = 0, $branch = 0, $program);
        $projectIdList = array_keys($projects);

        $tasks = $this->dao->select('*')
            ->from(TABLE_TASK)
            ->where('project')->in($projectIdList)
            ->andWhere("(status='done' or closedReason= 'done')")
            ->andWhere('finishedDate')->ge($monday)
            ->andWhere('finishedDate')->le($sunday)
            ->fetchAll();
        return $this->loadModel('task')->processTasks($tasks);
    }

    /**
     * GetPostponed
     *
     * @param  int    $program
     * @param  string $date
     * @access public
     * @return void
     */
    public function getPostponed($program, $date = '')
    {
        if(!$date) $date = date('Y-m-d');
        $monday = $this->getThisMonday($date);
        $sunday = $this->getThisSunday($date);
        $nextMonday = date('Y-m-d', strtotime("$sunday +1 days"));

        $projects = $this->loadModel('project')->getList($status = 'all', $limit = 0, $productID = 0, $branch = 0, $program);
        $projectIdList = array_keys($projects);
        $unFinished = $this->dao->select('*')
            ->from(TABLE_TASK)
            ->where('project')->in($projectIdList)
            ->andWhere('status')->in('wait,doing,pause')
            ->andWhere('deadline')->ge($monday)
            ->andWhere('deadline')->le($sunday)
            ->fetchAll('id');

        $postponed = $this->dao->select('*')
            ->from(TABLE_TASK)
            ->where('project')->in($projectIdList)
            ->andWhere('finishedDate')->gt($nextMonday)
            ->andWhere('deadline')->ge($monday)
            ->andWhere('deadline')->lt($nextMonday)
            ->fetchAll('id');

        $tasks = array_merge($unFinished, $postponed);
        return $this->loadModel('task')->processTasks($tasks);
    }

    /**
     * GetTasksOfNextWeek
     *
     * @param  int    $program
     * @param  string $date
     * @access public
     * @return void
     */
    public function getTasksOfNextWeek($program, $date = '')
    {
        if(!$date) $date = date('Y-m-d');
        $sunday       = $this->getThisSunday($date);
        $nextMonday   = date('Y-m-d', strtotime("$sunday +1 days"));
        $sencondMondy = date('Y-m-d', strtotime("$sunday +8 days"));

        $projects      = $this->loadModel('project')->getList($status = 'all', $limit = 0, $productID = 0, $branch = 0, $program);
        $projectIdList = array_keys($projects);

        $tasks = $this->dao->select('*')
            ->from(TABLE_TASK)
            ->where('project')->in($projectIdList)
            ->andWhere("((deadline > '$nextMonday' and deadline < '$sencondMondy') or (estStarted > '$nextMonday' and  estStarted < '$sencondMondy'))")
            ->fetchAll('id');

        return $this->loadModel('task')->processTasks($tasks);
    }

    /**
     * GetWorkloadByType
     *
     * @param  int    $program
     * @param  string $date
     * @access public
     * @return object
     */
    public function getWorkloadByType($program, $date = '')
    {
        if(!$date) $date = date('Y-m-d');

        $sunday       = $this->getThisSunday($date);
        $nextMonday   = date('Y-m-d', strtotime("$sunday +1 days"));
        $sencondMondy = date('Y-m-d', strtotime("$sunday +8 days"));

        $projects      = $this->loadModel('project')->getList($status = 'all', $limit = 0, $productID = 0, $branch = 0, $program);
        $projectIdList = array_keys($projects);

        return $this->dao->select('type, sum(cast(estimate as decimal(10,2))) as workload')
            ->from(TABLE_TASK)
            ->where('project')->in($projectIdList)
            ->groupBy('type')
            ->fetchPairs();
    }

    /**
     * GetPlanedTaskByWeek
     *
     * @param  int    $program
     * @param  string $date
     * @access public
     * @return array
     */
    public function getPlanedTaskByWeek($program, $date = '')
    {
        if(!$date) $date = date('Y-m-d');
        $monday     = $this->getThisMonday($date);
        $nextMonday = date('Y-m-d', strtotime("$monday +7 days"));

        $projects      = $this->loadModel('project')->getList($status = 'all', $limit = 0, $productID = 0, $branch = 0, $program);
        $projectIdList = array_keys($projects);

        return $this->dao->select('*')
            ->from(TABLE_TASK)
            ->where('project')->in($projectIdList)
            ->andWhere('deadline')->ge($monday)
            ->fetchAll('id');
    }

    /**
     * GetPV
     *
     * @param  int    $program
     * @param  string $date
     * @access public
     * @return int
     */
    public function getPV($projectID, $date = '')
    {
        $report = $this->getFromDB($projectID, $date);
        if(!empty($report)) return $report->pv;

        if(!$date) $date = date('Y-m-d');
        $monday     = $this->getThisMonday($date);
        $sunday     = $this->getThisSunday($date);
        $lastDay    = $this->getLastDay($date);
        $nextMonday = date('Y-m-d', strtotime("$sunday +1 days"));
        $workdays   = $this->loadModel('holiday')->getActualWorkingDays($monday, $sunday);

        $executions = $this->loadModel('project')->getExecutionList($projectID);
        $executionIdList = array_keys($executions);

        $tasks = $this->dao->select('*')->from(TABLE_TASK)
            ->where('project')->in($executionIdList)
            ->andWhere("(estStarted < '$nextMonday' or estStarted='0000-00-00')")
            ->fetchAll('id');

        $PV = 0;
        foreach($tasks as $task)
        {
            if($task->estStarted == '0000-00-00') $task->estStarted = date('Y-m-d', strtotime($task->openedDate));
            if($task->deadline < $nextMonday)
            {
                $PV += $task->estimate;
                continue;
            }

            $fullDays   = $this->loadModel('holiday')->getActualWorkingDays($task->estStarted, $task->deadline);
            $passedDays = $this->loadModel('holiday')->getActualWorkingDays($task->estStarted, $sunday);

            $PV += count($passedDays) * $task->estimate / count($fullDays);
        }

        return round($PV, 2);
    }

    /**
     * Get EV data.
     *
     * @param  int    $projectID
     * @param  string $date
     * @access public
     * @return int
     */
    public function getEV($projectID, $date = '')
    {
        $report = $this->getFromDB($projectID, $date);
        if(!empty($report)) return $report->ev;

        $executions      = $this->loadModel('project')->getExecutionList($projectID);
        $executionIdList = array_keys($executions);

        if(!$date) $date = date('Y-m-d');
        $monday     = $this->getThisMonday($date);
        $sunday     = $this->getThisSunday($date);
        $lastDay    = $this->getLastDay($date);
        $nextMonday = date('Y-m-d', strtotime("$sunday +1 days"));

        $tasks = $this->dao->select('*')
            ->from(TABLE_TASK)
            ->where('project')->in($executionIdList)
            ->andWhere('consumed')->gt(0)
            ->andWhere('status')->ne('cancel')
            ->fetchAll('id');

        $EV = 0;
        foreach($tasks as $task)
        {
            if($task->status == 'done' or $task->closedReason == 'done')
            {
                $EV += $task->estimate;
            }
            else
            {
                $task->progress = round($task->consumed / ($task->consumed + $task->left), 2) * 100;
                $EV += $task->estimate * $task->progress / 100;
            }
        }
        return round($EV, 2);
    }

    /**
     * Get AC data.
     *
     * @param  int    $projectID
     * @param  string $date
     * @access public
     * @return int
     */
    public function getAC($projectID, $date = '')
    {
        $report = $this->getFromDB($projectID, $date);
        if(!empty($report)) return $report->ac;

        if(!$date) $date = date('Y-m-d');
        $monday          = $this->getThisMonday($date);
        $nextMonday      = date('Y-m-d', strtotime("$monday +7 days"));
        $executions      = $this->loadModel('project')->getExecutionList($projectID);
        $executionIdList = array_keys($executions);

        $AC = $this->dao->select('sum(t1.consumed) as consumed')
            ->from(TABLE_TASKESTIMATE)->alias('t1')
            ->leftJoin(TABLE_TASK)->alias('t2')->on('t1.task=t2.id')
            ->where('t2.project')->in($executionIdList)
            ->andWhere('t1.date')->ge($monday)
            ->andWhere('t1.date')->lt($nextMonday)
            ->fetch('consumed');

        return round($AC, 2);
    }

    /**
     * Get SV data.
     *
     * @param  int    $ev
     * @param  int    $pv
     * @access public
     * @return int
     */
    public function getSV($ev, $pv)
    {
        if($pv == 0) return 0;
        $sv = -1 * (1- ($ev / $pv));
        return number_format($sv * 100, 2);
    }

    /**
     * GetCV
     *
     * @param  int    $ev
     * @param  int    $ac
     * @access public
     * @return int
     */
    public function getCV($ev, $ac)
    {
        if($ac == 0) return 0;
        $cv = -1 * (1 - ($ev / $ac));
        return number_format($cv * 100, 2);
    }

    /**
     * GetTips
     *
     * @param  string $type
     * @param  int    $data
     * @access public
     * @return string
     */
    public function getTips($type = 'progress', $data = 0)
    {
        $this->app->loadConfig('custom');
        if($type == 'progress') $tipsConfig = isset($this->config->custom->SV->progressTip) ? $this->config->custom->SV->progressTip : '';
        if($type == 'cost')     $tipsConfig = isset($this->config->custom->CV->costTip) ? $this->config->custom->CV->costTip : '';

        if(empty($tipsConfig)) return '';

        $tipsConfig = json_decode($tipsConfig);
        foreach($tipsConfig as $tipConfig)
        {
            if($tipConfig->min <= $data and $tipConfig->max >= $data) return $tipConfig->tip;
        }

        return '';
    }
}
