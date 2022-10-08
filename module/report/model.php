<?php
/**
 * The model file of report module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
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
     * Compute percent of every item.
     *
     * @param  array    $datas
     * @access public
     * @return array
     */
    public function computePercent($datas)
    {
        $sum = 0;
        foreach($datas as $data) $sum += $data->value;

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
     * Create json data of single charts
     * @param  array $sets
     * @param  array $dateList
     * @return string the json string
     */
    public function createSingleJSON($sets, $dateList)
    {
        $data = '[';
        $now  = date('Y-m-d');
        $preValue = 0;
        $setsDate = array_keys($sets);
        foreach($dateList as $i => $date)
        {
            $date  = date('Y-m-d', strtotime($date));
            if($date > $now) break;
            if(!isset($sets[$date]) and $sets)
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

            $data .= isset($sets[$date]) ? "{$sets[$date]->value}," : "{$preValue},";
        }
        $data = rtrim($data, ',');
        $data .= ']';
        return $data;
    }

    /**
     * Convert date format.
     *
     * @param  array  $dateList
     * @param  string $format
     * @access public
     * @return array
     */
    public function convertFormat($dateList, $format = 'Y-m-d')
    {
        foreach($dateList as $i => $date) $dateList[$i] = date($format, strtotime($date));
        return $dateList;
    }

    /**
     * Get executions.
     *
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return array
     */
    public function getExecutions($begin = 0, $end = 0)
    {
        $permission = common::hasPriv('report', 'showProject') or $this->app->user->admin;
        $tasks      = $this->dao->select('t1.*, t2.name as executionName, t3.name as projectName')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution = t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t1.project = t3.id')
            ->where('t1.status')->ne('cancel')
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF(!$permission)->andWhere('t2.id')->in($this->app->user->view->sprints)->fi()
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.parent')->lt(1)
            ->andWhere('t2.status')->eq('closed')
            ->beginIF($begin)->andWhere('t2.begin')->ge($begin)->fi()
            ->beginIF($end)->andWhere('t2.end')->le($end)->fi()
            ->orderBy('t2.end_desc')
            ->fetchAll();

        $executions = array();
        foreach($tasks as $task)
        {
            $executionID = $task->execution;
            if(!isset($executions[$executionID]))
            {
                $executions[$executionID] = new stdclass();
                $executions[$executionID]->estimate = 0;
                $executions[$executionID]->consumed = 0;
            }

            $executions[$executionID]->projectName = $task->projectName;
            $executions[$executionID]->name        = $task->executionName;
            $executions[$executionID]->estimate   += $task->estimate;
            $executions[$executionID]->consumed   += $task->consumed;
        }

        return $executions;
    }

    /**
     * Get products.
     *
     * @access public
     * @return array
     */
    public function getProducts($conditions, $storyType = 'story')
    {
        $permission = common::hasPriv('report', 'showProduct') or $this->app->user->admin;
        $products   = $this->dao->select('t1.id as id, t1.code, t1.name, t1.PO')->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROGRAM)->alias('t2')->on('t1.program = t2.id')
            ->where('t1.deleted')->eq(0)
            ->beginIF(strpos($conditions, 'closedProduct') === false)->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF(!$permission)->andWhere('t1.id')->in($this->app->user->view->products)->fi()
            ->orderBy('t2.order_asc, t1.line_desc, t1.order_asc')
            ->fetchAll('id');

        $plans = $this->dao->select('id, product, branch, parent, title, begin, end')->from(TABLE_PRODUCTPLAN)->where('deleted')->eq(0)->andWhere('product')->in(array_keys($products))
            ->beginIF(strpos($conditions, 'overduePlan') === false)->andWhere('end')->gt(date('Y-m-d'))->fi()
            ->orderBy('product,parent_desc,begin')
            ->fetchAll('id');
        foreach($plans as $plan)
        {
            if($plan->parent > 0)
            {
                $parentPlan = zget($plans, $plan->parent, null);
                if($parentPlan)
                {
                    $products[$plan->product]->plans[$parentPlan->id] = $parentPlan;
                    unset($plans[$parentPlan->id]);
                }
                $plan->title = '>>' . $plan->title;
            }
            $products[$plan->product]->plans[$plan->id] = $plan;
        }

        $planStories      = array();
        $unplannedStories = array();
        $stmt = $this->dao->select('id,plan,product,status')
            ->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere('parent')->ge(0)
            ->beginIF($storyType)->andWhere('type')->eq($storyType)->fi()
            ->query();
        while($story = $stmt->fetch())
        {
            if(empty($story->plan))
            {
                $unplannedStories[$story->id] = $story;
                continue;
            }

            $storyPlans   = array();
            $storyPlans[] = $story->plan;
            if(strpos($story->plan, ',') !== false) $storyPlans = explode(',', trim($story->plan, ','));
            foreach($storyPlans as $planID)
            {
                if(isset($plans[$planID]))
                {
                    $planStories[$story->id] = $story;
                    break;
                }
            }
        }

        foreach($planStories as $story)
        {
            $storyPlans = array();
            $storyPlans[] = $story->plan;
            if(strpos($story->plan, ',') !== false) $storyPlans = explode(',', trim($story->plan, ','));
            foreach($storyPlans as $planID)
            {
                if(!isset($plans[$planID])) continue;
                $plan = $plans[$planID];
                $products[$plan->product]->plans[$planID]->status[$story->status] = isset($products[$plan->product]->plans[$planID]->status[$story->status]) ? $products[$plan->product]->plans[$planID]->status[$story->status] + 1 : 1;
            }
        }

        foreach($unplannedStories as $story)
        {
            $product = $story->product;
            if(isset($products[$product]))
            {
                if(!isset($products[$product]->plans[0]))
                {
                    $products[$product]->plans[0] = new stdClass();
                    $products[$product]->plans[0]->title = $this->lang->report->unplanned;
                    $products[$product]->plans[0]->begin = '';
                    $products[$product]->plans[0]->end   = '';
                }
                $products[$product]->plans[0]->status[$story->status] = isset($products[$product]->plans[0]->status[$story->status]) ? $products[$product]->plans[0]->status[$story->status] + 1 : 1;
            }
        }

        unset($products['']);
        return $products;
    }

    /**
     * Get bugs
     *
     * @param  int    $begin
     * @param  int    $end
     * @param  int    $product
     * @param  int    $execution
     * @access public
     * @return array
     */
    public function getBugs($begin, $end, $product, $execution)
    {
        $end = date('Ymd', strtotime("$end +1 day"));
        $bugs = $this->dao->select('id, resolution, openedBy, status')->from(TABLE_BUG)
            ->where('deleted')->eq(0)
            ->andWhere('openedDate')->ge($begin)
            ->andWhere('openedDate')->le($end)
            ->beginIF($product)->andWhere('product')->eq($product)->fi()
            ->beginIF($execution)->andWhere('execution')->eq($execution)->fi()
            ->fetchAll();

        $bugCreate = array();
        foreach($bugs as $bug)
        {
            $bugCreate[$bug->openedBy][$bug->resolution] = empty($bugCreate[$bug->openedBy][$bug->resolution]) ? 1 : $bugCreate[$bug->openedBy][$bug->resolution] + 1;
            $bugCreate[$bug->openedBy]['all']            = empty($bugCreate[$bug->openedBy]['all']) ? 1 : $bugCreate[$bug->openedBy]['all'] + 1;
            if($bug->status == 'resolved' or $bug->status == 'closed')
            {
                $bugCreate[$bug->openedBy]['resolved'] = empty($bugCreate[$bug->openedBy]['resolved']) ? 1 : $bugCreate[$bug->openedBy]['resolved'] + 1;
            }
        }

        foreach($bugCreate as $account => $bug)
        {
            $validRate = 0;
            if(isset($bug['fixed']))     $validRate += $bug['fixed'];
            if(isset($bug['postponed'])) $validRate += $bug['postponed'];
            $bugCreate[$account]['validRate'] = (isset($bug['resolved']) and $bug['resolved']) ? ($validRate / $bug['resolved']) : "0";
        }
        uasort($bugCreate, 'sortSummary');
        return $bugCreate;
    }

    /**
     * Get workload.
     *
     * @param int    $dept
     * @param string $assign
     *
     * @access public
     * @return array
     */
    public function getWorkload($dept = 0, $assign = 'assign')
    {
        $deptUsers = array();
        if($dept) $deptUsers = $this->loadModel('dept')->getDeptUserPairs($dept);

        if($assign == 'noassign')
        {
            $members = $this->dao->select('t1.account,t2.name,t1.root,t3.id as project,t3.name as projectname')->from(TABLE_TEAM)->alias('t1')
                ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t2.id = t1.root')
                ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t3.id = t2.project')
                ->where('t2.status')->notin('cancel, closed, done, suspended')
                ->beginIF($dept)->andWhere('t1.account')->in(array_keys($deptUsers))->fi()
                ->andWhere('t1.type')->eq('execution')
                ->andWhere("t1.account NOT IN(SELECT `assignedTo` FROM " . TABLE_TASK . " WHERE `execution` = t1.`root` AND `status` NOT IN('cancel, closed, done, pause') AND assignedTo != '' GROUP BY assignedTo)")
                ->andWhere('t2.deleted')->eq('0')
                ->fetchGroup('account', 'name');

            $workload = array();
            if(!empty($members))
            {
                foreach($members as $member => $executions)
                {
                    $project = array();
                    if(!empty($executions))
                    {
                        foreach($executions as $name => $execution)
                        {
                            $project[$execution->projectname]['projectID'] = $execution->project;
                            $project[$execution->projectname]['execution'][$name]['executionID'] = $execution->root;
                            $project[$execution->projectname]['execution'][$name]['count']       = 0;
                            $project[$execution->projectname]['execution'][$name]['manhour']     = 0;

                            $workload[$member]['total']['count']   = 0;
                            $workload[$member]['total']['manhour'] = 0;
                        }
                    }
                    $workload[$member]['task']['project'] = $project;
                }
            }
            return $workload;
        }

        $stmt = $this->dao->select('t1.*, t2.name as executionName,t3.name as projectname')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution = t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t3.id = t2.project')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.status')->in('wait,pause,doing')
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.status')->in('wait,suspended,doing')
            ->andWhere('assignedTo')->ne('');

        $allTasks = $stmt->fetchAll('id');
        if(empty($allTasks)) return array();

        $tasks = array();
        if(empty($dept))
        {
            $tasks = $allTasks;
        }
        else
        {
            foreach($allTasks as $taskID => $task)
            {
                if(isset($deptUsers[$task->assignedTo])) $tasks[$taskID] = $task;
            }
        }

        /* Fix bug for children. */
        $parents       = array();
        $taskIdList    = array();
        $taskGroups    = array();
        foreach($tasks as $task)
        {
            if($task->parent > 0) $parents[$task->parent] = $task->parent;
            $taskGroups[$task->assignedTo][$task->id] = $task;
        }

        $stmt = $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->in(array_keys($allTasks))
            ->beginIF($dept)->andWhere('account')->in(array_keys($deptUsers))->fi()
            ->query();
        $multiTaskTeams = array();
        while($taskTeam = $stmt->fetch())
        {
            $account = $taskTeam->account;
            if(!isset($multiTaskTeams[$account][$taskTeam->task]))
            {
                $multiTaskTeams[$account][$taskTeam->task] = $taskTeam;
            }
            else
            {
                $multiTaskTeams[$account][$taskTeam->task]->estimate += $taskTeam->estimate;
                $multiTaskTeams[$account][$taskTeam->task]->consumed += $taskTeam->consumed;
                $multiTaskTeams[$account][$taskTeam->task]->left     += $taskTeam->left;
            }
        }
        foreach($multiTaskTeams as $assignedTo => $taskTeams)
        {
            foreach($taskTeams as $taskTeam)
            {
                $userTask = clone $allTasks[$taskTeam->task];
                $userTask->estimate = $taskTeam->estimate;
                $userTask->consumed = $taskTeam->consumed;
                $userTask->left     = $taskTeam->left;
                $taskGroups[$assignedTo][$taskTeam->task] = $userTask;
            }
        }

        $workload = array();
        foreach($taskGroups as $user => $userTasks)
        {
            if($user)
            {
                $project = array();
                foreach($userTasks as $task)
                {
                    if(isset($parents[$task->id])) continue;

                    $project[$task->projectname]['projectID'] = isset($project[$task->projectname]['projectID']) ? $project[$task->projectname]['projectID'] : $task->project;
                    $project[$task->projectname]['execution'][$task->executionName]['executionID'] = isset($project[$task->projectname]['execution'][$task->executionName]['executionID']) ? $project[$task->projectname]['execution'][$task->executionName]['executionID'] : $task->execution;
                    $project[$task->projectname]['execution'][$task->executionName]['count']       = isset($project[$task->projectname]['execution'][$task->executionName]['count'])       ? $project[$task->projectname]['execution'][$task->executionName]['count'] + 1 : 1;
                    $project[$task->projectname]['execution'][$task->executionName]['manhour']     = isset($project[$task->projectname]['execution'][$task->executionName]['manhour'])     ? $project[$task->projectname]['execution'][$task->executionName]['manhour'] + $task->left : $task->left;

                    $workload[$user]['total']['count']   = isset($workload[$user]['total']['count'])   ? $workload[$user]['total']['count']  + 1 : 1;
                    $workload[$user]['total']['manhour'] = isset($workload[$user]['total']['manhour']) ? $workload[$user]['total']['manhour'] + $task->left : $task->left;
                }

                if(empty($project)) continue;
                $workload[$user]['task']['project'] = $project;
            }
        }
        unset($workload['closed']);
        return $workload;
    }

    /**
     * Get bug assign.
     *
     * @access public
     * @return array
     */
    public function getBugAssign()
    {
        $bugs = $this->dao->select('t1.*, t2.name as productName')->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.status')->eq('active')
            ->andWhere('t2.deleted')->eq(0)
            ->fetchGroup('assignedTo');
        $assign = array();
        foreach($bugs as $user => $userBugs)
        {
            if($user)
            {
                foreach($userBugs as $bug)
                {
                    $assign[$user]['bug'][$bug->productName]['count']     = isset($assign[$user]['bug'][$bug->productName]['count']) ? $assign[$user]['bug'][$bug->productName]['count'] + 1 : 1;
                    $assign[$user]['bug'][$bug->productName]['productID'] = $bug->product;
                    $assign[$user]['total']['count']   = isset($assign[$user]['total']['count']) ? $assign[$user]['total']['count'] + 1 : 1;
                }
            }
        }
        unset($assign['closed']);
        return $assign;
    }

    /**
     * Get System URL.
     *
     * @access public
     * @return void
     */
    public function getSysURL()
    {
        if(isset($this->config->mail->domain)) return $this->config->mail->domain;

        /* Ger URL when run in shell. */
        if(PHP_SAPI == 'cli')
        {
            $url = parse_url(trim($this->server->argv[1]));
            $port = (empty($url['port']) or $url['port'] == 80) ? '' : $url['port'];
            $host = empty($port) ? $url['host'] : $url['host'] . ':' . $port;
            return $url['scheme'] . '://' . $host;
        }
        else
        {
            return common::getSysURL();
        }
    }

    /**
     * Get user bugs.
     *
     * @access public
     * @return void
     */
    public function getUserBugs()
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
     * Get user tasks.
     *
     * @access public
     * @return void
     */
    public function getUserTasks()
    {
        return $this->dao->select('t1.id, t1.name, t2.account as user, t1.deadline')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.assignedTo = t2.account')
            ->leftJoin(TABLE_EXECUTION)->alias('t3')->on('t1.execution = t3.id')
            ->where('t1.assignedTo')->ne('')
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.status')->in('wait,doing')
            ->andWhere('t3.status')->ne('suspended')
            ->andWhere('t1.deadline', true)->eq('0000-00-00')
            ->orWhere('t1.deadline')->lt(date(DT_DATE1, strtotime('+4 day')))
            ->markRight(1)
            ->fetchGroup('user');
    }

    /**
     * Get user todos.
     *
     * @access public
     * @return array
     */
    public function getUserTodos()
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
            if($todo->type == 'task') $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_TASK)->fetch('name');
            if($todo->type == 'bug')  $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_BUG)->fetch('title');
            $todos[$todo->user][] = $todo;
        }
        return $todos;
    }

    /**
     * Get user testTasks.
     *
     * @access public
     * @return array
     */
    public function getUserTestTasks()
    {
        return $this->dao->select('t1.*, t2.account as user')->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.owner = t2.account')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere("(t1.status='wait' OR t1.status='doing')")
            ->fetchGroup('user');
    }

    /**
     * Get user login count in this year.
     *
     * @param  array  $accounts
     * @param  int    $year
     * @access public
     * @return int
     */
    public function getUserYearLogins($accounts, $year)
    {
        return $this->dao->select('count(*) as count')->from(TABLE_ACTION)->where('actor')->in($accounts)->andWhere('LEFT(date, 4)')->eq($year)->andWhere('action')->eq('login')->fetch('count');
    }

    /**
     * Get user action count in this year.
     *
     * @param  array  $accounts
     * @param  int    $year
     * @access public
     * @return int
     */
    public function getUserYearActions($accounts, $year)
    {
        return $this->dao->select('count(*) as count')->from(TABLE_ACTION)
            ->where('LEFT(date, 4)')->eq($year)
            ->beginIF($accounts)->andWhere('actor')->in($accounts)->fi()
            ->fetch('count');
    }

    /**
     * Get user contributions in this year.
     *
     * @param  array  $accounts
     * @param  int    $year
     * @access public
     * @return array
     */
    public function getUserYearContributions($accounts, $year)
    {
        $stmt = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('LEFT(date, 4)')->eq($year)
            ->andWhere('objectType')->in(array_keys($this->config->report->annualData['contributions']))
            ->beginIF($accounts)->andWhere('actor')->in($accounts)->fi()
            ->orderBy('objectType,objectID,id')
            ->query();

        $filterActions = array();
        $objectIdList  = array();
        while($action = $stmt->fetch())
        {
            $objectType  = $action->objectType;
            $objectID    = $action->objectID;
            $lowerAction = strtolower($action->action);
            if(!isset($this->config->report->annualData['contributions'][$objectType][$lowerAction])) continue;

            $objectIdList[$objectType][$objectID] = $objectID;
            $filterActions[$objectType][$objectID][$action->id] = $action;
        }

        foreach($objectIdList as $objectType => $idList)
        {
            $deletedIdList = $this->dao->select('id')->from($this->config->objectTables[$objectType])->where('deleted')->eq(1)->andWhere('id')->in($idList)->fetchPairs('id', 'id');
            foreach($deletedIdList as $id) unset($filterActions[$objectType][$id]);
        }

        $actionGroups = array();
        foreach($filterActions as $objectType => $objectActions)
        {
            foreach($objectActions as $objectID => $actions)
            {
                foreach($actions as $action) $actionGroups[$objectType][$action->id] = $action;
            }
        }

        $contributions = array();
        foreach($actionGroups as $objectType => $actions)
        {
            foreach($actions as $action)
            {
                $lowerAction = strtolower($action->action);
                $actionName  = $this->config->report->annualData['contributions'][$objectType][$lowerAction];

                $type = ($actionName == 'svnCommit' or $actionName == 'gitCommit') ? 'repo' : $objectType;
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
     * Get user todo stat in this year.
     *
     * @param  array  $accounts
     * @param  int    $year
     * @access public
     * @return object
     */
    public function getUserYearTodos($accounts, $year)
    {
        return $this->dao->select("count(*) as count, sum(if((`status` != 'done'), 1, 0)) AS `undone`, sum(if((`status` = 'done'), 1, 0)) AS `done`")->from(TABLE_TODO)
            ->where('LEFT(date, 4)')->eq($year)
            ->andWhere('deleted')->eq('0')
            ->andWhere('vision')->eq($this->config->vision)
            ->beginIF($accounts)->andWhere('account')->in($accounts)->fi()
            ->fetch();
    }

    /**
     * Get user effort stat in this error.
     *
     * @param  array  $accounts
     * @param  int    $year
     * @access public
     * @return object
     */
    public function getUserYearEfforts($accounts, $year)
    {
        $effort = $this->dao->select('count(*) as count, sum(consumed) as consumed')->from(TABLE_EFFORT)
            ->where('LEFT(date, 4)')->eq($year)
            ->beginIF($accounts)->andWhere('account')->in($accounts)->fi()
            ->fetch();

        $effort->consumed = !empty($effort->consumed) ? round($effort->consumed, 2) : 0;
        return $effort;
    }

    /**
     * Get count of created story,plan and closed story by accounts every product in this year.
     *
     * @param  array  $accounts
     * @param  int    $year
     * @access public
     * @return array
     */
    public function getUserYearProducts($accounts, $year)
    {
        /* Get changed products in this year. */
        $products = $this->dao->select('id,name')->from(TABLE_PRODUCT)
            ->where('deleted')->eq(0)
            ->andWhere('LEFT(createdDate, 4)')->eq($year)
            ->beginIF($accounts)
            ->andWhere('createdBy', true)->in($accounts)
            ->orWhere('PO')->in($accounts)
            ->orWhere('QD')->in($accounts)
            ->orWhere('RD')->in($accounts)
            ->markRight(1)
            ->fi()
            ->fetchAll('id');

        /* Get created plans in this year. */
        $plans = $this->dao->select('t1.id,t1.product')->from(TABLE_PRODUCTPLAN)->alias('t1')
            ->leftJoin(TABLE_ACTION)->alias('t2')->on("t1.id=t2.objectID and t2.objectType='productplan'")
            ->where('LEFT(t2.date, 4)')->eq($year)
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF($accounts)
            ->andWhere('t2.actor')->in($accounts)
            ->fi()
            ->andWhere('t2.action')->eq('opened')
            ->fetchAll();

        $planProducts = array();
        $planGroups   = array();
        foreach($plans as $plan)
        {
            $planProducts[$plan->product] = $plan->product;
            $planGroups[$plan->product][$plan->id] = $plan->id;
        }

        $createStoryProducts = $this->dao->select('DISTINCT product')->from(TABLE_STORY)
            ->where('LEFT(openedDate, 4)')->eq($year)
            ->andWhere('deleted')->eq(0)
            ->beginIF($accounts)->andWhere('openedBy')->in($accounts)->fi()
            ->fetchPairs('product', 'product');
        $closeStoryProducts  = $this->dao->select('DISTINCT product')->from(TABLE_STORY)
            ->where('LEFT(closedDate, 4)')->eq($year)
            ->andWhere('deleted')->eq(0)
            ->beginIF($accounts)->andWhere('closedBy')->in($accounts)->fi()
            ->fetchPairs('product', 'product');
        if($createStoryProducts or $closeStoryProducts)
        {
            $products += $this->dao->select('id,name')->from(TABLE_PRODUCT)
                ->where('id')->in($createStoryProducts + $closeStoryProducts + $planProducts)
                ->andWhere('deleted')->eq(0)
                ->fetchAll('id');
        }

        $createdStoryStats = $this->dao->select("product,sum(if((type = 'requirement'), 1, 0)) as requirement, sum(if((type = 'story'), 1, 0)) as story")->from(TABLE_STORY)
            ->where('product')->in(array_keys($products))
            ->andWhere('deleted')->eq(0)
            ->andWhere('LEFT(openedDate, 4)')->eq($year)
            ->beginIF($accounts)->andWhere('openedBy')->in($accounts)->fi()
            ->groupBy('product')
            ->fetchAll('product');

        $closedStoryStats = $this->dao->select("product,sum(if((status = 'closed'), 1, 0)) as closed")->from(TABLE_STORY)
            ->where('product')->in(array_keys($products))
            ->andWhere('deleted')->eq(0)
            ->andWhere('LEFT(closedDate, 4)')->eq($year)
            ->beginIF($accounts)->andWhere('closedBy')->in($accounts)->fi()
            ->groupBy('product')
            ->fetchAll('product');

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
     * Get count of finished task, story and resolved bug by accounts every executions in this year.
     *
     * @param  array  $accounts
     * @param  int    $year
     * @access public
     * @return array
     */
    public function getUserYearExecutions($accounts, $year)
    {
        /* Get changed executions in this year. */
        $executions = $this->dao->select('id,name')->from(TABLE_EXECUTION)->where('deleted')->eq(0)
            ->andwhere('type')->eq('sprint')
            ->andWhere('LEFT(begin, 4)', true)->eq($year)
            ->orWhere('LEFT(end, 4)')->eq($year)
            ->markRight(1)
            ->beginIF($accounts)
            ->andWhere('openedBy', true)->in($accounts)
            ->orWhere('PO')->in($accounts)
            ->orWhere('PM')->in($accounts)
            ->orWhere('QD')->in($accounts)
            ->orWhere('RD')->in($accounts)
            ->markRight(1)
            ->fi()
            ->orderBy('`order` desc')
            ->fetchAll('id');

        $teamExecutions = $this->dao->select('*')->from(TABLE_TEAM)
            ->where('type')->eq('execution')
            ->beginIF($accounts)->andWhere('account')->in($accounts)->fi()
            ->andWhere('LEFT(`join`, 4)')->eq($year)
            ->fetchPairs('root', 'root');
        $taskExecutions = $this->dao->select('execution')->from(TABLE_TASK)
            ->where('LEFT(finishedDate, 4)')->eq($year)
            ->andWhere('deleted')->eq(0)
            ->beginIF($accounts)->andWhere('finishedBy')->in($accounts)->fi()
            ->fetchPairs('execution', 'execution');
        if($teamExecutions or $taskExecutions)
        {
            $executions += $this->dao->select('id,name')->from(TABLE_EXECUTION)
                ->where('id')->in($teamExecutions + $taskExecutions)
                ->andWhere('deleted')->eq(0)
                ->andWhere('type')->eq('sprint')
                ->orderBy('`order` desc')
                ->fetchAll('id');
        }

        /* Get count of finished task, story and resolved bug in this year. */
        $taskStats = $this->dao->select('execution, count(*) as finishedTask, sum(if((story != 0), 1, 0)) as finishedStory')->from(TABLE_TASK)
            ->where('execution')->in(array_keys($executions))
            ->andWhere('finishedBy')->ne('')
            ->andWhere('LEFT(finishedDate, 4)')->eq($year)
            ->andWhere('deleted')->eq(0)
            ->beginIF($accounts)->andWhere('finishedBy')->in($accounts)->fi()
            ->groupBy('execution')
            ->fetchAll('execution');
        $resolvedBugs = $this->dao->select('t2.execution, count(*) as count')->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_BUILD)->alias('t2')->on('t1.resolvedBuild=t2.id')
            ->where('t2.execution')->in(array_keys($executions))
            ->andWhere('t1.resolvedBy')->ne('')
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('LEFT(t1.resolvedDate, 4)')->eq($year)
            ->beginIF($accounts)->andWhere('t1.resolvedBy')->in($accounts)->fi()
            ->groupBy('t2.execution')
            ->fetchAll('execution');

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
     * Get status stat that is all time, include story, task and bug.
     *
     * @access public
     * @return array
     */
    public function getAllTimeStatusStat()
    {
        $statusStat = array();
        $statusStat['story'] = $this->dao->select('status, count(status) as count')->from(TABLE_STORY)->where('deleted')->eq(0)->andWhere('type')->eq('story')->groupBy('status')->fetchPairs('status', 'count');
        $statusStat['task']  = $this->dao->select('status, count(status) as count')->from(TABLE_TASK)->where('deleted')->eq(0)->groupBy('status')->fetchPairs('status', 'count');
        $statusStat['bug']   = $this->dao->select('status, count(status) as count')->from(TABLE_BUG)->where('deleted')->eq(0)->groupBy('status')->fetchPairs('status', 'count');

        return $statusStat;
    }

    /**
     * Get year object stat, include status and action stat
     *
     * @param  array  $accounts
     * @param  string $year
     * @param  string $objectType   story|task|bug
     * @access public
     * @return array
     */
    public function getYearObjectStat($accounts, $year, $objectType)
    {
        $table = '';
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
            ->andWhere('t1.action')->in(array_keys($this->config->report->annualData['month'][$objectType]))
            ->beginIF($accounts)->andWhere('t1.actor')->in($accounts)->fi()
            ->query();

        /* Build object action stat and get status group. */
        $statuses   = array();
        $actionStat = array();
        while($action = $stmt->fetch())
        {
            $statuses[$action->objectID] = $action->status;

            $lowerAction = strtolower($action->action);
            if(!isset($actionStat[$lowerAction]))
            {
                foreach($months as $month) $actionStat[$lowerAction][$month] = 0;
            }

            $month = substr($action->date, 0, 7);
            $actionStat[$lowerAction][$month] += 1;
        }

        /* Build status stat. */
        $statusStat = array();
        foreach($statuses as $storyID => $status)
        {
            if(!isset($statusStat[$status])) $statusStat[$status] = 0;
            $statusStat[$status] += 1;
        }

        return array('statusStat' => $statusStat, 'actionStat' => $actionStat);
    }

    /**
     * Get year case stat, include result and action stat.
     *
     * @param  array  $accounts
     * @param  string $year
     * @access public
     * @return array
     */
    public function getYearCaseStat($accounts, $year)
    {
        $months = $this->getYearMonths($year);
        $stmt   = $this->dao->select('t1.*')->from(TABLE_ACTION)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.objectID=t2.id')
            ->where('t1.objectType')->eq('case')
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.action')->eq('opened')
            ->andWhere('LEFT(t1.date, 4)')->eq($year)
            ->beginIF($accounts)->andWhere('t1.actor')->in($accounts)->fi()
            ->query();

        /* Build create case stat. */
        $resultStat = array();
        $actionStat = array();
        foreach($months as $month)
        {
            $actionStat['opened'][$month]    = 0;
            $actionStat['run'][$month]       = 0;
            $actionStat['createBug'][$month] = 0;
        }

        while($action = $stmt->fetch())
        {
            $month = substr($action->date, 0, 7);
            $actionStat['opened'][$month] += 1;
        }

        /* Build testcase result stat and run case stat. */
        $stmt = $this->dao->select('t1.*')->from(TABLE_TESTRESULT)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case=t2.id')
            ->where('LEFT(t1.date, 4)')->eq($year)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF($accounts)->andWhere('t1.lastRunner')->in($accounts)->fi()
            ->query();
        while($testResult = $stmt->fetch())
        {
            if(!isset($resultStat[$testResult->caseResult])) $resultStat[$testResult->caseResult] = 0;
            $resultStat[$testResult->caseResult] += 1;

            $month = substr($testResult->date, 0, 7);
            $actionStat['run'][$month] += 1;
        }

        /* Build testcase create bug stat. */
        $stmt = $this->dao->select('t1.*')->from(TABLE_ACTION)->alias('t1')
            ->leftJoin(TABLE_BUG)->alias('t2')->on('t1.objectID=t2.id')
            ->where('t1.objectType')->eq('bug')
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('LEFT(t1.date, 4)')->eq($year)
            ->andWhere('t1.action')->eq('opened')
            ->andWhere('t2.case')->ne('0')
            ->beginIF($accounts)->andWhere('t1.actor')->in($accounts)->fi()
            ->query();
        while($action = $stmt->fetch())
        {
            $month = substr($action->date, 0, 7);
            $actionStat['createBug'][$month] += 1;
        }

        return array('resultStat' => $resultStat, 'actionStat' => $actionStat);
    }

    /**
     * Get year months.
     *
     * @param  string $year
     * @access public
     * @return array
     */
    public function getYearMonths($year)
    {
        $months = array();
        for($i = 1; $i <= 12; $i ++) $months[] = $year . '-' . sprintf('%02d', $i);

        return $months;
    }

    /**
     * Get status overview.
     *
     * @param  string $objectType
     * @param  array  $statusStat
     * @access public
     * @return string
     */
    public function getStatusOverview($objectType, $statusStat)
    {
        $allCount    = 0;
        $undoneCount = 0;
        foreach($statusStat as $status => $count)
        {
            $allCount += $count;
            if($objectType == 'story' and $status != 'closed') $undoneCount += $count;
            if($objectType == 'task' and $status != 'done' and $status != 'closed' and $status != 'cancel') $undoneCount += $count;
            if($objectType == 'bug' and $status == 'active') $undoneCount += $count;
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
     * Get project status overview.
     *
     * @param  array  $accounts
     * @access public
     * @return array
     */
    public function getProjectStatusOverview($accounts = array())
    {
        $projectStatus = $this->dao->select('t1.id,t1.status')->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_TEAM)->alias('t2')->on("t1.id=t2.root")
            ->where('t1.type')->in($this->config->systemMode == 'classic' ? 'sprint,stage,kanban' : 'project')
            ->beginIF($this->config->systemMode == 'classic')->andWhere('t2.type')->eq('execution')->fi()
            ->beginIF($this->config->systemMode == 'new')->andWhere('t2.type')->eq('project')->fi()
            ->beginIF(!empty($accounts))->andWhere('t2.account')->in($accounts)->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->fetchPairs('id', 'status');

        $statusOverview = array();
        foreach($projectStatus as $projectID => $status)
        {
            if(!isset($statusOverview[$status])) $statusOverview[$status] = 0;
            $statusOverview[$status] ++;
        }

        return $statusOverview;
    }

    /**
     * Get output data for API.
     *
     * @param  array    $accounts
     * @param  string   $year
     * @access public
     * @return array
     */
    public function getOutput4API($accounts, $year)
    {
        $stmt = $this->dao->select('id,objectType,objectID,action,extra')->from(TABLE_ACTION)
            ->where('objectType')->in(array_keys($this->config->report->outputData))
            ->andWhere('LEFT(date, 4)')->eq($year)
            ->beginIF($accounts)->andWhere('actor')->in($accounts)->fi()
            ->query();

        $outputData   = array();
        $actionGroup  = array();
        $objectIdList = array();
        while($action = $stmt->fetch())
        {
            if($action->objectType == 'release' and $action->action == 'changestatus')
            {
                if($action->extra == 'terminate') $action->action = 'stoped';
                if($action->extra == 'normal')    $action->action = 'activated';
            }
            unset($action->extra);

            if(!isset($this->config->report->outputData[$action->objectType][$action->action])) continue;

            if(!isset($outputData[$action->objectType][$action->action])) $outputData[$action->objectType][$action->action] = 0;
            $objectIdList[$action->objectType][$action->objectID] = $action->objectID;
            $actionGroup[$action->objectType][$action->id] = $action;
        }

        foreach($actionGroup as $objectType => $actions)
        {
            $deletedIdList = $this->dao->select('id')->from($this->config->objectTables[$objectType])->where('deleted')->eq(1)->andWhere('id')->in($objectIdList[$objectType])->fetchPairs('id', 'id');

            foreach($actions as $action)
            {
                if(isset($deletedIdList[$action->objectID])) continue;
                $outputData[$action->objectType][$action->action] += 1;
            }
        }

        $stmt = $this->dao->select('t1.*')->from(TABLE_ACTION)->alias('t1')
            ->leftJoin(TABLE_BUG)->alias('t2')->on('t1.objectID=t2.id')
            ->where('t1.objectType')->eq('bug')
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('LEFT(t1.date, 4)')->eq($year)
            ->andWhere('t1.action')->eq('opened')
            ->andWhere('t2.case')->ne('0')
            ->beginIF($accounts)->andWhere('t1.actor')->in($accounts)->fi()
            ->query();
        while($action = $stmt->fetch())
        {
            if(!isset($outputData['case']['createBug'])) $outputData['case']['createBug'] = 0;
            $outputData['case']['createBug'] += 1;
        }

        $outputData['case']['run'] = $this->dao->select('count(*) as count')->from(TABLE_TESTRESULT)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case=t2.id')
            ->where('LEFT(t1.date, 4)')->eq($year)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF($accounts)->andWhere('t1.lastRunner')->in($accounts)->fi()
            ->fetch('count');

        $processedOutput = array();
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
     * Get project and execution name.
     *
     * @access public
     * @return array
     */
    public function getProjectExecutions()
    {
        $executions = $this->dao->select('t1.id, t1.name, t2.name as projectname, t1.status')
            ->from(TABLE_EXECUTION)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.type')->in('stage,sprint')
            ->fetchAll();

        $pairs = array();
        foreach($executions as $execution)
        {
            $pairs[$execution->id] = $this->config->systemMode == 'new' ? $execution->projectname . '/' .$execution->name : $execution->name;
        }

        return $pairs;
    }
}

/**
 * @param $pre
 * @param $next
 *
 * @return int
 */
function sortSummary($pre, $next)
{
    if($pre['validRate'] == $next['validRate']) return 0;
    return $pre['validRate'] > $next['validRate'] ? -1 : 1;
}
