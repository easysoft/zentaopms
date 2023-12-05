<?php
/**
 * The model file of report module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     report
 * @version     $Id: model.php 4726 2013-05-03 05:51:27Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class reportModel extends model
{
    /**
     * 构造函数。
     * Construct.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadBIDAO();
    }

    /**
     * 计算每项数据的百分比。
     * Compute percent of every item.
     *
     * @param  array  $datas
     * @access public
     * @return array
     */
    public function computePercent(array $datas): array
    {
        /* Get data total. */
        $sum = 0;
        foreach($datas as $data) $sum += $data->value;

        /* Compute percent, and get total percent. */
        $totalPercent = 0;
        foreach($datas as $i => $data)
        {
            $data->percent = round($data->value / $sum, 4);
            $totalPercent += $data->percent;
        }
        if(isset($i)) $datas[$i]->percent = round(1 - $totalPercent + $datas[$i]->percent, 4);
        return $datas;
    }

    /**
     * 创建单个图表的 json 数据。
     * Create json data of single charts
     *
     * @param  array $sets
     * @param  array $dateList
     * @return array
     */
    public function createSingleJSON(array $sets, array $dateList): array
    {
        $preValue = 0;
        $data     = array();
        $now      = date('Y-m-d');
        $setsDate = array_keys($sets);
        foreach($dateList as $date)
        {
            $date = date('Y-m-d', strtotime($date));
            if($date > $now) break;

            if(!isset($sets[$date]) && $sets)
            {
                $tmpDate = $setsDate;
                $tmpDate[] = $date;
                sort($tmpDate);
                $tmpDateStr = ',' . join(',', $tmpDate);
                $preDate = rtrim(substr($tmpDateStr, 0, strpos($tmpDateStr, $date)), ',');
                $preDate = substr($preDate, strrpos($preDate, ',') + 1);

                if($preDate)
                {
                    $preValue = $sets[$preDate];
                    $preValue = $preValue->value;
                }
            }

            $data[] = isset($sets[$date]) ? $sets[$date]->value : $preValue;
        }

        return $data;
    }

    /**
     * 转换日期格式。
     * Convert date format.
     *
     * @param  array  $dateList
     * @param  string $format
     * @access public
     * @return array
     */
    public function convertFormat(array $dateList, string $format = 'Y-m-d'): array
    {
        foreach($dateList as $i => $date) $dateList[$i] = date($format, strtotime($date));
        return $dateList;
    }

    /**
     * 获取系统的 URL。
     * Get System URL.
     *
     * @access public
     * @return string
     */
    public function getSysURL(): string
    {
        if(isset($this->config->mail->domain)) return $this->config->mail->domain;

        /* Ger URL when run in shell. */
        if(PHP_SAPI == 'cli')
        {
            $url  = parse_url(trim($this->server->argv[1]));
            $port = empty($url['port']) || $url['port'] == 80 ? '' : $url['port'];
            $host = empty($port) ? $url['host'] : $url['host'] . ':' . $port;
            return $url['scheme'] . '://' . $host;
        }
        else
        {
            return common::getSysURL();
        }
    }

    /**
     * 获取用户的 bugs。
     * Get user bugs.
     *
     * @access public
     * @return array
     */
    public function getUserBugs(): array
    {
        return $this->dao->select('t1.id, t1.title, t2.account as user, t1.deadline')
            ->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')
            ->on('t1.assignedTo = t2.account')
            ->where('t1.assignedTo')->ne('')
            ->andWhere('t1.assignedTo')->ne('closed')
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.deadline', true)->eq('0000-00-00')
            ->orWhere('t1.deadline')->lt(date(DT_DATE1, strtotime('+4 day')))
            ->markRight(1)
            ->fetchGroup('user');
    }

    /**
     * 获取用户的任务。
     * Get user tasks.
     *
     * @access public
     * @return void
     */
    public function getUserTasks(): array
    {
        return $this->dao->select('t1.id, t1.name, t2.account as user, t1.deadline')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.assignedTo = t2.account')
            ->leftJoin(TABLE_EXECUTION)->alias('t3')->on('t1.execution = t3.id')
            ->leftJoin(TABLE_PROJECT)->alias('t4')->on('t1.project = t4.id')
            ->where('t1.assignedTo')->ne('')
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0)
            ->andWhere('t4.deleted')->eq(0)
            ->andWhere('t1.status')->in('wait,doing')
            ->andWhere('t3.status')->ne('suspended')
            ->andWhere('t1.deadline', true)->eq('0000-00-00')
            ->orWhere('t1.deadline')->lt(date(DT_DATE1, strtotime('+4 day')))
            ->markRight(1)
            ->fetchGroup('user');
    }

    /**
     * 获取用户的待办。
     * Get user todos.
     *
     * @access public
     * @return array
     */
    public function getUserTodos(): array
    {
        $stmt = $this->dao->select('t1.*, t2.account as user')
            ->from(TABLE_TODO)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')
            ->on('t1.account = t2.account')
            ->where('t1.cycle')->eq(0)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.status')->in('wait,doing')
            ->query();

        $todos = array();
        while($todo = $stmt->fetch())
        {
            if($todo->type == 'task') $todo->name = $this->dao->findById($todo->objectID)->from(TABLE_TASK)->fetch('name');
            if($todo->type == 'bug')  $todo->name = $this->dao->findById($todo->objectID)->from(TABLE_BUG)->fetch('title');
            $todos[$todo->user][] = $todo;
        }
        return $todos;
    }

    /**
     * 获取用户的测试单。
     * Get user testTasks.
     *
     * @access public
     * @return array
     */
    public function getUserTestTasks(): array
    {
        return $this->dao->select('t1.*, t2.account as user')->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.owner = t2.account')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere("(t1.status='wait' OR t1.status='doing')")
            ->fetchGroup('user');
    }

    /**
     * 获取用户今年的登录次数。
     * Get user login count in this year.
     *
     * @param  array  $accounts
     * @param  string $year
     * @access public
     * @return int
     */
    public function getUserYearLogins(array $accounts, string $year): int
    {
        return $this->dao->select('count(*) as count')->from(TABLE_ACTION)->where('actor')->in($accounts)->andWhere('LEFT(date, 4)')->eq($year)->andWhere('action')->eq('login')->fetch('count');
    }

    /**
     * 获取用户本年的操作数。
     * Get user action count in this year.
     *
     * @param  array  $accounts
     * @param  string $year
     * @access public
     * @return int
     */
    public function getUserYearActions(array $accounts, string $year): int
    {
        return $this->dao->select('count(*) as count')->from(TABLE_ACTION)
            ->where('LEFT(date, 4)')->eq($year)
            ->beginIF($accounts)->andWhere('actor')->in($accounts)->fi()
            ->fetch('count');
    }

    /**
     * 获取用户某年的动态数。
     * Get user contributions in this year.
     *
     * @param  array  $accounts
     * @param  string $year
     * @access public
     * @return array
     */
    public function getUserYearContributions(array $accounts, string $year): array
    {
        /* Get required actions for annual report. */
        $filterActions = array();
        $stmt          = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('LEFT(date, 4)')->eq($year)
            ->andWhere('objectType')->in(array_keys($this->config->report->annualData['contributions']))
            ->beginIF($accounts)->andWhere('actor')->in($accounts)->fi()
            ->orderBy('objectType,objectID,id')
            ->query();
        while($action = $stmt->fetch())
        {
            if(isset($this->config->report->annualData['contributions'][$action->objectType][strtolower($action->action)])) $filterActions[$action->objectType][$action->objectID][$action->id] = $action;
        }

        /* Only get undeleted actions. */
        $actionGroups = array();
        foreach($filterActions as $objectType => $objectActions)
        {
            $deletedIdList = $this->dao->select('id,id')->from($this->config->objectTables[$objectType])->where('deleted')->eq(1)->andWhere('id')->in(array_keys($objectActions))->fetchPairs();
            foreach($objectActions as $actions)
            {
                foreach($actions as $action)
                {
                    if(!isset($deletedIdList[$action->id])) $actionGroups[$objectType][$action->id] = $action;
                }
            }
        }

        /* Calculate the number of actions . */
        $contributions = array();
        foreach($actionGroups as $objectType => $actions)
        {
            foreach($actions as $action)
            {
                $actionName  = $this->config->report->annualData['contributions'][$objectType][strtolower($action->action)];
                $type        = $actionName == 'svnCommit' || $actionName == 'gitCommit' ? 'repo' : $objectType;
                if(!isset($contributions[$type][$actionName])) $contributions[$type][$actionName] = 0;
                $contributions[$type][$actionName] += 1;
            }
        }
        $contributions['case']['run'] = $this->dao->select('count(*) as count')->from(TABLE_TESTRESULT)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case=t2.id')
            ->where('LEFT(t1.date, 4)')->eq($year)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF($accounts)->andWhere('t1.lastRunner')->in($accounts)->fi()
            ->fetch('count');
        return $contributions;
    }

    /**
     * 获取用户某年的待办统计。
     * Get user todo stat in this year.
     *
     * @param  array  $accounts
     * @param  string $year
     * @access public
     * @return object
     */
    public function getUserYearTodos(array $accounts, string $year): object
    {
        return $this->dao->select("count(*) as count, sum(if((`status` != 'done'), 1, 0)) AS `undone`, sum(if((`status` = 'done'), 1, 0)) AS `done`")->from(TABLE_TODO)
            ->where('LEFT(date, 4)')->eq($year)
            ->andWhere('deleted')->eq('0')
            ->andWhere('vision')->eq($this->config->vision)
            ->beginIF($accounts)->andWhere('account')->in($accounts)->fi()
            ->fetch();
    }

    /**
     * 获取用户某年的工时统计。
     * Get user effort stat in this year.
     *
     * @param  array  $accounts
     * @param  string $year
     * @access public
     * @return object
     */
    public function getUserYearEfforts(array $accounts, string $year): object
    {
        $effort = $this->dao->select('count(*) as count, sum(consumed) as consumed')->from(TABLE_EFFORT)
            ->where('LEFT(date, 4)')->eq($year)
            ->andWhere('deleted')->eq(0)
            ->beginIF($accounts)->andWhere('account')->in($accounts)->fi()
            ->fetch();

        $effort->consumed = !empty($effort->consumed) ? round($effort->consumed, 2) : 0;
        return $effort;
    }

    /**
     * 获取用户某年的产品下创建的需求、计划，创建和关闭的需求数量统计。
     * Get count of created story,plan and closed story by accounts every product in this year.
     *
     * @param  array  $accounts
     * @param  string $year
     * @access public
     * @return array
     */
    public function getUserYearProducts(array $accounts, string $year): array
    {
        /* Get changed products in this year. */
        list($products, $planGroups, $createdStoryStats, $closedStoryStats) = $this->reportTao->getAnnualProductStat($accounts, $year);

        /* Merge created plan, created story and closed story in every product. */
        foreach($products as $productID => $product)
        {
            $product->plan        = 0;
            $product->requirement = 0;
            $product->story       = 0;
            $product->closed      = 0;

            $plans = zget($planGroups, $productID, array());
            if($plans) $product->plan = count($plans);

            $createdStoryStat = zget($createdStoryStats, $productID, '');
            if($createdStoryStat)
            {
                $product->requirement = $createdStoryStat->requirement;
                $product->story       = $createdStoryStat->story;
            }

            $closedStoryStat = zget($closedStoryStats, $productID, '');
            if($closedStoryStat) $product->closed = $closedStoryStat->closed;
        }

        return $products;
    }

    /**
     * 获取用户某年内每次执行的已完成任务、故事和已解决的bug。
     * Get count of finished task, story and resolved bug by accounts every executions in a year.
     *
     * @param  array  $accounts
     * @param  string $year
     * @access public
     * @return array
     */
    public function getUserYearExecutions(array $accounts, string $year): array
    {
        /* Get changed executions in this year. */
        list($executions, $taskStats, $resolvedBugs) = $this->reportTao->getAnnualExecutionStat($accounts, $year);

        foreach($executions as $executionID => $execution)
        {
            $execution->task  = 0;
            $execution->story = 0;
            $execution->bug   = 0;

            $taskStat = zget($taskStats, $executionID, '');
            if($taskStat)
            {
                $execution->task  = $taskStat->finishedTask;
                $execution->story = $taskStat->finishedStory;
            }

            $resolvedBug = zget($resolvedBugs, $executionID, '');
            if($resolvedBug) $execution->bug = $resolvedBug->count;
        }

        return $executions;
    }

    /**
     * 获取所有时间的状态，包括需求、任务和 bug。
     * Get status stat that is all time, include story, task and bug.
     *
     * @access public
     * @return array
     */
    public function getAllTimeStatusStat(): array
    {
        $statusStat = array();
        $statusStat['story'] = $this->dao->select('status, count(status) as count')->from(TABLE_STORY)->where('deleted')->eq(0)->andWhere('type')->eq('story')->groupBy('status')->fetchPairs('status', 'count');
        $statusStat['task']  = $this->dao->select('status, count(status) as count')->from(TABLE_TASK)->where('deleted')->eq(0)->groupBy('status')->fetchPairs('status', 'count');
        $statusStat['bug']   = $this->dao->select('status, count(status) as count')->from(TABLE_BUG)->where('deleted')->eq(0)->groupBy('status')->fetchPairs('status', 'count');
        return $statusStat;
    }

    /**
     * 获取年度需求、任务或者 bug 的状态统计。
     * Get year object stat, include status and action stat
     *
     * @param  array  $accounts
     * @param  string $year
     * @param  string $objectType story|task|bug
     * @access public
     * @return array
     */
    public function getYearObjectStat(array $accounts, string $year, string $objectType): array
    {
        if($objectType == 'story') $table = TABLE_STORY;
        if($objectType == 'task')  $table = TABLE_TASK;
        if($objectType == 'bug')   $table = TABLE_BUG;
        if(empty($table)) return array();

        $months = $this->getYearMonths($year);
        $stmt   = $this->dao->select('t1.*, t2.status')->from(TABLE_ACTION)->alias('t1')
            ->leftJoin($table)->alias('t2')->on('t1.objectID=t2.id')
            ->where('t1.objectType')->eq($objectType)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('LEFT(t1.date, 4)')->eq($year)
            ->andWhere('t1.action')->in($this->config->report->annualData['monthAction'][$objectType])
            ->beginIF($accounts)->andWhere('t1.actor')->in($accounts)->fi()
            ->query();

        /* Build object action stat and object status stat. */
        $actionStat = array();
        $statusStat = array();
        while($action = $stmt->fetch())
        {
            if(!isset($statusStat[$action->status])) $statusStat[$action->status] = 0;
            $statusStat[$action->status] ++;

            /* Story, bug can from feedback and ticket, task can from feedback, change this action down to opened. */
            $lowerAction = strtolower($action->action);
            if(in_array($lowerAction, array('fromfeedback', 'fromticket'))) $lowerAction = 'opened';
            if(!isset($actionStat[$lowerAction]))
            {
                foreach($months as $month) $actionStat[$lowerAction][$month] = 0;
            }

            $month = substr($action->date, 0, 7);
            $actionStat[$lowerAction][$month] += 1;
        }

        return array('statusStat' => $statusStat, 'actionStat' => $actionStat);
    }

    /**
     * 获取年度用例的结果状态和动态统计。
     * Get year case stat, include result and action stat.
     *
     * @param  array  $accounts
     * @param  string $year
     * @access public
     * @return array
     */
    public function getYearCaseStat(array $accounts, string $year): array
    {
        $actionStat = $resultStat = array();
        $months     = $this->getYearMonths($year);
        foreach($months as $month) $actionStat['opened'][$month] = $actionStat['run'][$month] = $actionStat['createBug'][$month] = 0;

        return $this->reportTao->buildAnnualCaseStat($accounts, $year, $actionStat, $resultStat);
    }

    /**
     * 获取年度月份。
     * Get year months.
     *
     * @param  string $year
     * @access public
     * @return array
     */
    public function getYearMonths(string $year): array
    {
        $months = array();
        for($i = 1; $i <= 12; $i ++) $months[] = $year . '-' . sprintf('%02d', $i);

        return $months;
    }

    /**
     * 获取状态总览。
     * Get status overview.
     *
     * @param  string $objectType
     * @param  array  $statusStat
     * @access public
     * @return string
     */
    public function getStatusOverview(string $objectType, array $statusStat): string
    {
        $allCount    = 0;
        $undoneCount = 0;
        foreach($statusStat as $status => $count)
        {
            $allCount += $count;
            if($objectType == 'story' && $status != 'closed') $undoneCount += $count;
            if($objectType == 'task' && $status != 'done' && $status != 'closed' && $status != 'cancel') $undoneCount += $count;
            if($objectType == 'bug' && $status == 'active') $undoneCount += $count;
        }

        $overview = '';
        if($objectType == 'story') $overview .= $this->lang->report->annualData->allStory;
        if($objectType == 'task')  $overview .= $this->lang->report->annualData->allTask;
        if($objectType == 'bug')   $overview .= $this->lang->report->annualData->allBug;
        $overview .= ' &nbsp; ' . $allCount;
        $overview .= '<br />';
        $overview .= $objectType == 'bug' ? $this->lang->report->annualData->unresolve : $this->lang->report->annualData->undone;
        $overview .= ' &nbsp; ' . $undoneCount;

        return $overview;
    }

    /**
     * 测试获取项目状态总览。
     * Get project status overview.
     *
     * @param  array  $accounts
     * @access public
     * @return array
     */
    public function getProjectStatusOverview(array $accounts = array()): array
    {
        $projectStatus = $this->dao->select('t1.id,t1.status')->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_TEAM)->alias('t2')->on("t1.id=t2.root")
            ->where('t1.type')->in('project')
            ->andWhere('t2.type')->eq('project')
            ->beginIF(!empty($accounts))->andWhere('t2.account')->in($accounts)->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->fetchPairs();

        $statusOverview = array();
        foreach($projectStatus as $status)
        {
            if(!isset($statusOverview[$status])) $statusOverview[$status] = 0;
            $statusOverview[$status] ++;
        }

        return $statusOverview;
    }

    /**
     * 为 API 获取输出的数据。
     * Get output data for API.
     *
     * @param  array    $accounts
     * @param  string   $year
     * @access public
     * @return array
     */
    public function getOutput4API(array $accounts, string $year): array
    {
        $processedOutput = array();
        $outputData      = $this->reportTao->getOutputData($accounts, $year);
        foreach($this->config->report->outputData as $objectType => $actions)
        {
            if(!isset($outputData[$objectType])) continue;

            $objectActions = $outputData[$objectType];
            $processedOutput[$objectType]['total'] = array_sum($objectActions);

            foreach($actions as $action => $langCode)
            {
                if(empty($objectActions[$action])) continue;

                $processedOutput[$objectType]['actions'][$langCode]['code']  = $langCode;
                $processedOutput[$objectType]['actions'][$langCode]['name']  = $this->lang->report->annualData->actionList[$langCode];
                $processedOutput[$objectType]['actions'][$langCode]['total'] = $objectActions[$action];
            }
        }

        return $processedOutput;
    }

    /**
     * 获取项目和执行名称。
     * Get project and execution name.
     *
     * @access public
     * @return array
     */
    public function getProjectExecutions(): array
    {
        $executions = $this->dao->select('t1.id, t1.name, t2.name as projectname, t1.status, t1.multiple')
            ->from(TABLE_EXECUTION)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.type')->in('stage,sprint')
            ->fetchAll();

        $pairs = array();
        foreach($executions as $execution)
        {
            if($execution->multiple)  $pairs[$execution->id] = $execution->projectname . '/' . $execution->name;
            if(!$execution->multiple) $pairs[$execution->id] = $execution->projectname;
        }

        return $pairs;
    }
}
