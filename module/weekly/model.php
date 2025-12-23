<?php
/**
 * The model file of weekly module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     weekly
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class weeklyModel extends model
{
    /**
     * GetPageNav
     *
     * @param  int    $project
     * @param  int    $date
     * @access public
     * @return string
     */
    public function getPageNav($project, $date)
    {
        $date       = date('Ymd', strtotime($this->getThisMonday($date)));
        $today      = helper::today();
        $thisSunday = date('Ymd', strtotime($this->getThisSunday($today)));
        switch($project->status)
        {
        case 'wait':
            $begin = helper::now();
            $end   = $begin;
            break;
        case 'doing':
            $begin = !helper::isZeroDate($project->realBegan) ? $project->realBegan : $date;
            $end   = $thisSunday;
            break;
        case 'suspended':
            $begin = !helper::isZeroDate($project->realBegan) ? $project->realBegan : $project->suspendedDate;
            $end   = $project->suspendedDate;
            break;
        case 'closed':
            $begin = !helper::isZeroDate($project->realBegan) ? $project->realBegan : $project->realEnd;
            $end   = $project->realEnd;
            break;
        }

        $weeks = $this->getWeekPairs($begin, $end);
        $current = zget($weeks, $date, current($weeks));

        $selectHtml  = "<div class='btn-group angle-btn'>";
        $selectHtml .= html::a('###', $this->lang->weekly->common . $this->lang->hyphen . $project->name, '', "class='btn'");
        $selectHtml .= '</div>';

        $selectHtml .= "<div class='btn-group angle-btn'>";
        $selectHtml .= "<div class='btn-group'>";
        $selectHtml .= "<a data-toggle='dropdown' class='btn' title=$current>" . $current . " <span class='caret'></span></a>";
        $selectHtml .= "<ul class='dropdown-menu'>";
        foreach($weeks as $day => $title)
        {
            $selectHtml .= '<li>' . html::a(helper::createLink('weekly', 'index', "project={$project->id}&date=$day"), $title) . '</li>';
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
    public function getWeekPairs($begin, $end = '')
    {
        $sn = $end != '' ? $this->getWeekSN($begin, $end) : $this->getWeekSN($begin, date('Y-m-d'));
        $weeks = array();
        for($i = 0; $i < $sn; $i++)
        {
            $monday = $this->getThisMonday($begin);
            $sunday = $this->getThisSunday($begin);
            $begin  = date('Y-m-d', strtotime("$begin +7 days"));
            $key    = date('Ymd', strtotime($monday));
            $weeks[$key] = sprintf($this->lang->weekly->weekDesc, $i + 1, $monday, $sunday);
        }
        krsort($weeks);
        return $weeks;
    }

    /**
     * GetFromDB
     *
     * @param  int    $project
     * @param  int    $date
     * @access public
     * @return object
     */
    public function getFromDB($project, $date)
    {
        $monday = $this->getThisMonday($date);
        return $this->dao->select('*')
            ->from(TABLE_WEEKLYREPORT)
            ->where('weekStart')->eq($monday)
            ->andWhere('project')->eq($project)
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
        $weekStart = $this->getThisMonday($date);
        $report    = new stdclass();
        $PVEV      = $this->getPVEV($project, $date, $mode = 'new');

        $report->pv        = $PVEV['PV'];
        $report->ev        = $PVEV['EV'];
        $report->ac        = $this->getAC($project, $date, $mode = 'new');
        $report->sv        = $this->getSV($report->ev, $report->pv);
        $report->cv        = $this->getCV($report->ev, $report->ac);
        $report->project   = $project;
        $report->weekStart = $weekStart;
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
        $timestamp = strtotime($date);

        $day = date('w', $timestamp);
        if($day == 0) $day = 7;

        return date('Y-m-d', $timestamp - (($day - 1) * 24 * 3600));
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
        return date('Y-m-d', strtotime($monday) + (6 * 24 * 3600));
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
     * @param  int    $project
     * @param  string $date
     * @access public
     * @return array
     */
    public function getStaff($project, $date = '')
    {
        if(!$date) $date = date('Y-m-d');
        $monday = $this->getThisMonday($date);
        $sunday = $this->getThisSunday($date);
        $executions = $this->loadModel('execution')->getList($project, 'all', 'all', 0, 0, 0);
        $executionIdList = array_keys($executions);
        return $this->dao->select('count(distinct account) as count')
            ->from(TABLE_EFFORT)
            ->where('objectType')->eq('task')
            ->andWhere('execution')->in($executionIdList)
            ->andWhere('date')->ge($monday)
            ->andWhere('date')->le($sunday)
            ->andWhere('deleted')->eq(0)
            ->fetch('count');
    }

    /**
     * GetFinished
     *
     * @param  int    $project
     * @param  string $date
     * @param  int    $pager
     * @access public
     * @return void
     */
    public function getFinished($project, $date = '', $pager = null)
    {
        if(!$date) $date = date('Y-m-d');
        $monday = $this->getThisMonday($date);
        $sunday = $this->getThisSunday($date);

        $executions = $this->loadModel('execution')->getList($project, 'all', $status = 'all', $limit = 0, $productID = 0, $branch = 0);
        $executionIdList = array_keys($executions);

        $tasks = $this->dao->select('*')
            ->from(TABLE_TASK)
            ->where('execution')->in($executionIdList)
            ->andWhere("(status = 'done' or closedReason = 'done')")
            ->andWhere('finishedDate')->ge($monday)
            ->andWhere('finishedDate')->le($sunday)
            ->andWhere('deleted')->eq(0)
            ->fetchAll();
        return $this->loadModel('task')->processTasks($tasks);
    }

    /**
     * GetPostponed
     *
     * @param  int    $project
     * @param  string $date
     * @access public
     * @return void
     */
    public function getPostponed($project, $date = '')
    {
        if(!$date) $date = date('Y-m-d');
        $monday = $this->getThisMonday($date);
        $sunday = $this->getThisSunday($date);
        $nextMonday = date('Y-m-d', strtotime($sunday) + 24 * 3600);

        $executions = $this->loadModel('execution')->getList($project, 'all', $status = 'all', $limit = 0, $productID = 0, $branch = 0);
        $executionIdList = array_keys($executions);
        $unFinished = $this->dao->select('*')
            ->from(TABLE_TASK)
            ->where('execution')->in($executionIdList)
            ->andWhere('status')->in('wait,doing,pause')
            ->andWhere('deadline')->ge($monday)
            ->andWhere('deadline')->le($sunday)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');

        $postponed = $this->dao->select('*')
            ->from(TABLE_TASK)
            ->where('execution')->in($executionIdList)
            ->andWhere('finishedDate')->gt($nextMonday)
            ->andWhere('deadline')->ge($monday)
            ->andWhere('deadline')->lt($nextMonday)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');

        $tasks = array_merge($unFinished, $postponed);
        return $this->loadModel('task')->processTasks($tasks);
    }

    /**
     * GetTasksOfNextWeek
     *
     * @param  int    $project
     * @param  string $date
     * @access public
     * @return void
     */
    public function getTasksOfNextWeek($project, $date = '')
    {
        if(!$date) $date = date('Y-m-d');
        $sunday       = $this->getThisSunday($date);
        $timestamp    = strtotime($sunday);
        $nextMonday   = date('Y-m-d', $timestamp + 24 * 3600);
        $sencondMondy = date('Y-m-d', $timestamp + (8 * 24 * 3600));

        $executions      = $this->loadModel('execution')->getList($project, 'all', $status = 'all', $limit = 0, $productID = 0, $branch = 0);
        $executionIdList = array_keys($executions);

        $tasks = $this->dao->select('*')
            ->from(TABLE_TASK)
            ->where('execution')->in($executionIdList)
            ->andWhere("((deadline >= '$nextMonday' and deadline < '$sencondMondy') or (estStarted >= '$nextMonday' and  estStarted < '$sencondMondy') or (estStarted < '$nextMonday' and deadline > '$sencondMondy'))")
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');

        return $this->loadModel('task')->processTasks($tasks);
    }

    /**
     * GetWorkloadByType
     *
     * @param  int    $project
     * @param  string $date
     * @access public
     * @return object
     */
    public function getWorkloadByType($project, $date = '')
    {
        if(!$date) $date = date('Y-m-d');

        $sunday       = $this->getThisSunday($date);
        $timestamp    = strtotime($sunday);
        $nextMonday   = date('Y-m-d', $timestamp + 24 * 3600);
        $sencondMondy = date('Y-m-d', $timestamp + (8 * 24 * 3600));

        $executions      = $this->loadModel('execution')->getList($project, 'all', $status = 'all', $limit = 0, $productID = 0, $branch = 0);
        $executionIdList = array_keys($executions);

        return $this->dao->select('type, sum(cast(estimate as decimal(10,2))) as workload')
            ->from(TABLE_TASK)
            ->where('execution')->in($executionIdList)
            ->andWhere('deleted')->eq(0)
            ->andWhere('isParent')->eq('0')
            ->groupBy('type')
            ->fetchPairs();
    }

    /**
     * GetPlanedTaskByWeek
     *
     * @param  int    $project
     * @param  string $date
     * @access public
     * @return array
     */
    public function getPlanedTaskByWeek($project, $date = '')
    {
        if(!$date) $date = date('Y-m-d');
        $monday     = $this->getThisMonday($date);
        $nextMonday = date('Y-m-d', strtotime($monday) + (7 * 24 * 3600));

        $executions      = $this->loadModel('execution')->getList($project);
        $executionIdList = array_keys($executions);

        return $this->dao->select('*')
            ->from(TABLE_TASK)
            ->where('execution')->in($executionIdList)
            ->andWhere('deadline')->ge($monday)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');
    }

    /**
     * Get PV and EV
     *
     * @param  int    $project
     * @param  string $date
     * @param  string $mode
     * @access public
     * @return array
     */
    public function getPVEV($projectID, $date = '', $mode = 'old')
    {
        $report = $this->getFromDB($projectID, $date);
        if(!empty($report) && $mode == 'old') return array('PV' => $report->pv, 'EV' => $report->ev);

        if(!$date) $date = date('Y-m-d');
        $lastDay = $this->getLastDay($date);
        $monday  = $this->getThisMonday($date);
        if(empty($lastDay)) $lastDay = $monday;

        $executions = $this->dao->select('id,begin,end,realBegan,realEnd,status')->from(TABLE_EXECUTION)->where('deleted')->eq(0)->andWhere('vision')->eq($this->config->vision)->andWhere('project')->eq($projectID)->fetchAll('id');
        $stmt       = $this->dao->select('*')->from(TABLE_TASK)
            ->where('execution')->in(array_keys($executions))
            ->andWhere("isParent")->eq(0)
            ->andWhere("deleted")->eq(0)
            ->andWhere("status")->ne('cancel')
            ->query();

        $PV = 0;
        $EV = 0;
        $this->loadModel('holiday');
        while($task = $stmt->fetch())
        {
            if(empty($task->execution)) continue;
            $execution = $executions[$task->execution];
            if(helper::isZeroDate($task->estStarted)) $task->estStarted = $execution->begin;
            if(helper::isZeroDate($task->deadline))   $task->deadline   = $execution->end;

            if($task->deadline <= $lastDay)
            {
                $PV += $task->estimate;
            }
            elseif($task->estStarted <= $lastDay)
            {
                $fullDays       = $this->holiday->getActualWorkingDays($task->estStarted, $task->deadline);
                $weekActualDays = $this->holiday->getActualWorkingDays($task->estStarted, $lastDay);
                if(!empty($fullDays) and !empty($weekActualDays)) $PV += round(count($weekActualDays) / count($fullDays) * $task->estimate, 2);
            }

            if($task->status == 'done' or $task->closedReason == 'done')
            {
                $EV += $task->estimate;
            }
            else
            {
                $task->progress = 0;
                if(($task->consumed + $task->left) > 0) $task->progress = round($task->consumed / ($task->consumed + $task->left) * 100, 2);
                $EV += round($task->estimate * $task->progress / 100, 2);
            }
        }

        return array('PV' => sprintf("%.2f", $PV), 'EV' => sprintf("%.2f", $EV));
    }

    /**
     * Get AC data.
     *
     * @param  int    $project
     * @param  string $date
     * @param  string $mode
     * @access public
     * @return int
     */
    public function getAC($project, $date = '', $mode = 'old')
    {
        $report = $this->getFromDB($project, $date);
        if(!empty($report) && $mode == 'old') return $report->ac;

        if(!$date) $date = date('Y-m-d');
        $lastDay = $this->getLastDay($date);
        if(empty($lastDay)) $lastDay = $this->getThisMonday($date);

        $AC = $this->dao->select('sum(consumed) as consumed')
            ->from(TABLE_EFFORT)
            ->where('project')->eq($project)
            ->andWhere('date')->le($lastDay)
            ->andWhere('deleted')->eq(0)
            ->fetch('consumed');

        if(is_null($AC)) $AC = 0;

        return sprintf("%.2f", $AC);
    }

    /**
     * Get left.
     *
     * @param  int    $projectID
     * @param  string $date
     * @access public
     * @return float
     */
    public function getLeft($projectID, $date = '')
    {
        if(!$date) $date = date('Y-m-d');
        $lastDay = $this->getLastDay($date);
        $monday  = $this->getThisMonday($date);
        if(empty($lastDay)) $lastDay = $monday;

        $executions = $this->dao->select('id,begin,end,realBegan,realEnd,status')->from(TABLE_EXECUTION)->where('deleted')->eq(0)->andWhere('vision')->eq($this->config->vision)->andWhere('project')->eq($projectID)->fetchAll('id');
        $stmt       = $this->dao->select('*')->from(TABLE_TASK)
            ->where('execution')->in(array_keys($executions))
            ->andWhere('isParent')->eq('0')
            ->andWhere("deleted")->eq(0)
            ->andWhere("status")->ne('cancel')
            ->query();

        $left = 0;
        while($task = $stmt->fetch())
        {
            $execution = $executions[$task->execution];
            if(helper::isZeroDate($task->estStarted)) $task->estStarted = $execution->begin;
            if(helper::isZeroDate($task->deadline))   $task->deadline   = $execution->end;

            if($task->deadline <= $lastDay or ($task->estStarted <= $lastDay and $task->deadline > $lastDay)) $left += $task->left;
        }

        return sprintf("%.2f", $left);
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
        return sprintf("%.2f", $sv * 100);
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
        return sprintf("%.2f", $cv * 100);
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
        $data       = (float)$data;
        foreach($tipsConfig as $tipConfig)
        {
            if((float)$tipConfig->min <= $data and (float)$tipConfig->max >= $data) return $tipConfig->tip;
        }

        return '';
    }

    /**
     * Get report data.
     *
     * @param  int     $projectID
     * @param  string  $date
     * @param  bool    $loadMaster
     * @access public
     * @return stdclass
     */
    public function getReportData($projectID = 0, $date = '', $loadMaster = false)
    {
        $data = new stdclass();

        $PVEV     = $this->getPVEV($projectID, $date);
        $data->pv = (float)$PVEV['PV'];
        $data->ev = (float)$PVEV['EV'];
        $data->ac = (float)$this->getAC($projectID, $date);
        $data->sv = $this->getSV($data->ev, $data->pv);
        $data->cv = $this->getCV($data->ev, $data->ac);

        $data->project   = $this->loadModel('project')->getByID($projectID);
        $data->weekSN    = $this->getWeekSN($data->project->begin, $date);
        $data->monday    = $this->getThisMonday($date);
        $data->lastDay   = $this->getThisSunday($date);
        $data->staff     = $this->getStaff($projectID, $date);
        $data->finished  = $this->getFinished($projectID, $date);
        $data->postponed = $this->getPostponed($projectID, $date);
        $data->nextWeek  = $this->getTasksOfNextWeek($projectID, $date);
        $data->workload  = $this->getWorkloadByType($projectID, $date);
        $data->progress  = $this->getTips('progress', $data->sv) . '<br/>' . $this->getTips('cost', $data->cv);

        if($loadMaster)
        {
            $data->users = $this->loadModel('user')->getPairs('noletter');
            $data->master = zget($data->users, $data->project->PM, '');
        }

        return $data;
    }

    /**
     * 添加内置项目周报模板。
     * Add builtin project weekly report template.
     *
     * @access public
     * @return bool
     */
    public function addBuiltinWeeklyTemplate(): bool
    {
        /* Set scope data. */
        $scopeID = $this->addBuiltinScope();
        if(!$scopeID) return false;

        /* Set category data. */
        $categoryID = $this->addBuiltinCategory($scopeID);

        /* Set docblock data. */
        $blockIdList = array();
        $docBlock    = new stdclass();
        foreach($this->config->weekly->charts as $chartKey => $chartContent)
        {
            $docBlock->type    = $chartKey;
            $docBlock->content = json_encode($chartContent);
            $this->dao->insert(TABLE_DOCBLOCK)->data($docBlock)->exec();
            $blockIdList[$chartKey] = $this->dao->lastInsertId();
        }

        /* Set template data. */
        $this->addBuiltinTemplate($scopeID, $categoryID, $blockIdList);

        return !dao::isError();
    }

    /**
     * 添加内置报告模板范围。
     * Add builtin report template scope.
     *
     * @access public
     * @return int|bool
     */
    public function addBuiltinScope(): int|bool
    {
        $scope = $this->dao->select('id')->from(TABLE_DOCLIB)->where('type')->eq('reportTemplate')->andWhere('main')->eq(1)->andWhere('vision')->eq($this->config->vision)->fetch();
        if($scope) return $scope->id;

        $this->loadModel('setting');

        /* Set scope data. */
        $scope = new stdClass();
        $scope->type      = 'reportTemplate';
        $scope->main      = '1';
        $scope->vision    = $this->config->vision;
        $scope->addedBy   = 'system';
        $scope->addedDate = helper::now();
        foreach($this->lang->weekly->builtInScopes as $vision => $scopeList)
        {
            $scopeMaps = array();
            $scope->vision = $vision;
            foreach($scopeList as $scopeKey => $scopeName)
            {
                if(empty($scopeName)) continue;

                $scope->name = $scopeName;
                $this->dao->insert(TABLE_DOCLIB)->data($scope)->exec();
                $scopeMaps[$scopeKey] = $this->dao->lastInsertID();
            }
            if(!empty($scopeMaps)) $this->setting->setItem("system.reporttemplate.builtInScopeMaps@{$vision}", json_encode($scopeMaps));
        }

        return array_pop($scopeMaps);
    }

    /**
     * 添加内置报告模板分类。
     * Add builtin report template category.
     *
     * @param  int $scopeID
     * @access public
     * @return int
     */
    public function addBuiltinCategory(int $scopeID): int
    {
        /* Set category data. */
        $category = new stdClass();
        $category->root  = $scopeID;
        $category->name  = $this->lang->projectCommon;
        $category->grade = 1;
        $category->type  = 'reportTemplate';
        $this->dao->insert(TABLE_MODULE)->data($category)->exec();

        $categoryID = $this->dao->lastInsertID();
        $this->dao->update(TABLE_MODULE)->set('`path`')->eq(",{$categoryID},")->where('id')->eq($categoryID)->exec();

        return $categoryID;
    }

    /**
     * 添加内置报告模板。
     * Add builtin report template.
     *
     * @param  int   $libID
     * @param  int   $moduleID
     * @param  array $blockIdList
     * @access public
     * @return bool
     */
    public function addBuiltinTemplate(int $libID, int $moduleID, array $blockIdList): bool
    {
        $now         = helper::now();
        $cycleConfig = array('turnon' => 'on', 'frequency' => 'week', 'acl' => 'open', 'readGroups' => '', 'readUsers' => '', 'groups' => '', 'users' => '');

        $objects = $this->dao->select('id')->from(TABLE_WORKFLOWGROUP)->where('type')->eq('project')->andWhere('projectModel')->in('waterfall,waterfallplus,ipd')->andWhere('status')->eq('normal')->andWhere('vision')->eq($this->config->vision)->andWhere('objectID')->eq(0)->andWhere('deleted')->eq(0)->fetchPairs('id');

        $template = new stdclass();
        $template->lib          = $libID;
        $template->module       = $moduleID;
        $template->title        = $this->lang->weekly->projectTemplate;
        $template->type         = 'text';
        $template->status       = 'normal';
        $template->acl          = 'open';
        $template->builtIn      = 1;
        $template->templateType = 'reportTemplate';
        $template->templateDesc = $this->lang->weekly->builtinDesc;
        $template->cycle        = 'week';
        $template->cycleConfig  = json_encode($cycleConfig);
        $template->objects      = ',' . implode(',', $objects) . ',';
        $template->addedBy      = 'system';
        $template->addedDate    = $now;
        $this->dao->insert(TABLE_DOC)->data($template)->exec();

        $templateID = $this->dao->lastInsertID();
        $this->dao->update(TABLE_DOC)->set('`path`')->eq(",{$templateID},")->set('`order`')->eq($templateID)->where('id')->eq($templateID)->exec();
        $this->dao->update(TABLE_DOCBLOCK)->set('doc')->eq($templateID)->where('id')->in(array_values($blockIdList))->exec();

        $templateContent = new stdclass();
        $templateContent->doc        = $templateID;
        $templateContent->title      = $template->title;
        $templateContent->type       = 'doc';
        $templateContent->version    = 1;
        $templateContent->rawContent = $this->getBuildinRawContent($blockIdList);
        $templateContent->addedBy    = 'system';
        $templateContent->addedDate  = $now;
        $this->dao->insert(TABLE_DOCCONTENT)->data($templateContent)->exec();

        $this->loadModel('action')->create('reportTemplate', $templateID, 'Created', '', '', 'system');

        return !dao::isError();
    }

    /**
     * 获取内置项目周报模板内容。
     * Get builtin project weekly report template content.
     *
     * @param  array $blockIdList
     * @access public
     * @return string
     */
    public function getBuildinRawContent(array $blockIdList): string
    {
        global $oldRequestType;
        if(!empty($oldRequestType) && $oldRequestType == 'PATH_INFO') $this->config->requestType = 'PATH_INFO';

        $blockCodes = array();
        foreach($blockIdList as $blockKey => $blockID) $blockCodes[] = '{' . $blockKey . '}';

        $rawContent = json_decode($this->lang->weekly->builtinRawContent);
        foreach($rawContent as $content)
        {
            if(isset($content->id)) $content->id = uniqid();
            if(isset($content->createDate)) $content->createDate = time();
            if(empty($content->children)) continue;

            foreach($content->children as $childList)
            {
                if(isset($childList->id)) $childList->id = uniqid();
                if(empty($childList->children)) continue;
                foreach($childList->children as $child)
                {
                    if(isset($child->id)) $child->id = uniqid();
                    if(!empty($child->props->text->delta))
                    {
                        foreach($child->props->text->delta as $delta)
                        {
                            if(isset($delta->attributes->holder->id)) $delta->attributes->holder->id = uniqid();
                            if(isset($delta->attributes->holder->name) && isset($blockIdList[$delta->attributes->holder->name])) $delta->attributes->holder->name = "{$delta->attributes->holder->name}_{$blockIdList[$delta->attributes->holder->name]}";
                            if(isset($delta->attributes->holder->data->blockID) && isset($blockIdList[$delta->attributes->holder->data->type])) $delta->attributes->holder->data->blockID = $blockIdList[$delta->attributes->holder->data->type];
                        }
                    }
                    if(!empty($child->props->content))
                    {
                        if(!empty($child->props->content->fetcher[0]))
                        {
                            $fetcher = $child->props->content->fetcher[0];
                            $fetcher->params = str_replace($blockCodes, array_values($blockIdList), $fetcher->params);

                            $fetcherLink = helper::createLink($fetcher->module, $fetcher->method, $fetcher->params);
                            $fetcherLink = str_replace(array('install.php', 'upgrade.php'), 'index.php', $fetcherLink);
                            $child->props->content->fetcher = "{$fetcherLink}";
                        }
                        if(isset($child->props->content->exportUrl)) $child->props->content->exportUrl = str_replace($blockCodes, array_values($blockIdList), $child->props->content->exportUrl);
                    }
                }
            }
        }

        if(!empty($oldRequestType) && $oldRequestType == 'PATH_INFO') $this->config->requestType = 'GET';
        return json_encode($rawContent);
    }
}
