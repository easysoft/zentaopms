<?php
/**
 * The model file of report module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
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
        foreach($datas as $data) $data->percent = round($data->value / $sum, 2);
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
        foreach($dateList as $i => $date)
        {
            $date  = date('Y-m-d', strtotime($date));
            $data .= isset($sets[$date]) ? "{$sets[$date]->value}," : "'',";
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
     * Get projects. 
     * 
     * @access public
     * @return void
     */
    public function getProjects()
    {
        $projects = array();

        $tasks = $this->dao->select('t1.*')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')
            ->on('t1.project = t2.id')
            ->where('t1.status')->ne('cancel')
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->fetchAll();
        foreach($tasks as $task)
        {
            if(!isset($projects[$task->project])) $projects[$task->project] = new stdclass();

            $projects[$task->project]->estimate = isset($projects[$task->project]->estimate) ? $projects[$task->project]->estimate + $task->estimate : $task->estimate;
            $projects[$task->project]->consumed = isset($projects[$task->project]->consumed) ? $projects[$task->project]->consumed + $task->consumed : $task->consumed;
            $projects[$task->project]->tasks    = isset($projects[$task->project]->tasks)    ? $projects[$task->project]->tasks + 1 : 1;
            if($task->type == 'devel') $projects[$task->project]->devConsumed  = isset($projects[$task->project]->devConsumed) ? $projects[$task->project]->devConsumed + $task->consumed : $task->consumed;
            if($task->type == 'test')  $projects[$task->project]->testConsumed = isset($projects[$task->project]->testConsumed) ? $projects[$task->project]->testConsumed + $task->consumed : $task->consumed;
        }

        $bugs = $this->dao->select('t1.project')
            ->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')
            ->on('t1.project = t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->fetchAll();
        foreach($bugs as $bug)
        {
            if($bug->project)
            {
                if(!isset($projects[$bug->project]))$projects[$bug->project] = new stdclass();
                $projects[$bug->project]->bugs = isset($projects[$bug->project]->bugs) ? $projects[$bug->project]->bugs + 1 : 1;
            }
        }

        $stories = $this->dao->select('t1.project')
            ->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')
            ->on('t1.project = t2.id')
            ->leftJoin(TABLE_STORY)->alias('t3')
            ->on('t1.story = t3.id')
            ->where('t2.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0)
            ->fetchAll();
        foreach($stories as $story)
        {
            if(!isset($projects[$story->project])) $projects[$story->project] = new stdclass();

            $projects[$story->project]->stories = isset($projects[$story->project]->stories) ? $projects[$story->project]->stories + 1 : 1;
        }

        $projectList = $this->dao->select('id, name, status')->from(TABLE_PROJECT)->fetchAll();
        $projectPairs = array();
        foreach($projectList as $project)
        {
            $projectPairs[$project->id] = $project->name;
            if($project->status != 'done') unset($projects[$project->id]);
        }
        foreach($projects as $id => $project)
        {
            if(!isset($projectPairs[$id]))
            { 
                unset($projects[$id]);
                continue;
            }
            if(!isset($project->stories)) $projects[$id]->stories = 0;
            if(!isset($project->bugs)) $projects[$id]->bugs = 0;
            if(!isset($project->devConsumed)) $projects[$id]->devConsumed = 0;
            if(!isset($project->testConsumed)) $projects[$id]->testConsumed = 0;
            if(!isset($project->consumed)) $projects[$id]->consumed = 0;
            if(!isset($project->estimate)) $projects[$id]->estimate = 0;
            $projects[$id]->name = $projectPairs[$id];
        }
        return $projects;
    }

    /**
     * Get products. 
     * 
     * @access public
     * @return array 
     */
    public function getProducts()
    {
        $products = $this->dao->select('id, code, name, PO')->from(TABLE_PRODUCT)->where('deleted')->eq(0)->fetchAll('id');
        $plans    = $this->dao->select('*')->from(TABLE_PRODUCTPLAN)->where('deleted')->eq(0)->andWhere('product')->in(array_keys($products))->fetchAll('id');
        if(!$plans) return array();
        foreach($plans as $plan) $products[$plan->product]->plans[$plan->id] = $plan;

        $planStories = $this->dao->select('plan, id, status')->from(TABLE_STORY)->where('deleted')->eq(0)->andWhere('plan')->in(array_keys($plans))->fetchGroup('plan', 'id');
        foreach($planStories as $planID => $stories)
        {
            foreach($stories as $story)
            {
                $plan = $plans[$story->plan];
                $products[$plan->product]->plans[$story->plan]->status[$story->status] = isset($products[$plan->product]->plans[$story->plan]->status[$story->status]) ? $products[$plan->product]->plans[$story->plan]->status[$story->status] + 1 : 1;
            }
        }
        $unplannedStories = $this->dao->select('product, id, status')->from(TABLE_STORY)->where('deleted')->eq(0)->andWhere('plan')->eq(0)->andWhere('product')->in(array_keys($products))->fetchGroup('product', 'id');
        foreach($unplannedStories as $product => $stories)
        {
            $products[$product]->plans[0] = new stdClass();
            $products[$product]->plans[0]->title = $this->lang->report->unplanned;
            $products[$product]->plans[0]->begin = '';
            $products[$product]->plans[0]->end   = '';
            foreach($stories as $story) 
            {
                $products[$product]->plans[0]->status[$story->status] = isset($products[$product]->plans[0]->status[$story->status]) ? $products[$product]->plans[0]->status[$story->status] + 1 : 1;
            }
        }
        return $products;
    }

    /**
     * Get bugs 
     * 
     * @param  int    $begin 
     * @param  int    $end 
     * @access public
     * @return array
     */
    public function getBugs($begin, $end)
    {
        $end = date('Ymd', strtotime("$end +1 day"));
        $bugs = $this->dao->select('id, resolution, openedBy, status')->from(TABLE_BUG)
            ->where('deleted')->eq(0)
            ->andWhere('openedDate')->ge($begin)
            ->andWhere('openedDate')->le($end)
            ->fetchAll();

        $bugSummary = array();
        foreach($bugs as $bug)
        {
            $bugSummary[$bug->openedBy][$bug->resolution] = empty($bugSummary[$bug->openedBy][$bug->resolution]) ? 1 : $bugSummary[$bug->openedBy][$bug->resolution] + 1;
            $bugSummary[$bug->openedBy]['all']            = empty($bugSummary[$bug->openedBy]['all']) ? 1 : $bugSummary[$bug->openedBy]['all'] + 1;
            if($bug->status == 'resolved' or $bug->status == 'closed')
            {
                $bugSummary[$bug->openedBy]['resolved'] = empty($bugSummary[$bug->openedBy]['resolved']) ? 1 : $bugSummary[$bug->openedBy]['resolved'] + 1;
            }
        }

        foreach($bugSummary as $account => $bug)
        {
            $validRate = 0;
            if(isset($bug['fixed']))     $validRate += $bug['fixed'];
            if(isset($bug['postponed'])) $validRate += $bug['postponed'];
            $bugSummary[$account]['validRate'] = (isset($bug['resolved']) and $bug['resolved']) ? ($validRate / $bug['resolved']) : "0";
        }
        uasort($bugSummary, 'sortSummary');
        return $bugSummary; 
    }

    /**
     * Get workload. 
     * 
     * @access public
     * @return array
     */
    public function getWorkload()
    {
        $tasks = $this->dao->select('t1.*, t2.name as projectName')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')
            ->on('t1.project = t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.status')->notin('cancel, closed, done')
            ->andWhere('t2.deleted')->eq(0)
            ->fetchGroup('assignedTo');
        $workload = array();
        foreach($tasks as $user => $userTasks)
        {
            if($user)
            {
                foreach($userTasks as $task)
                {
                    $workload[$user]['task'][$task->projectName]['count']     = isset($workload[$user]['task'][$task->projectName]['count']) ? $workload[$user]['task'][$task->projectName]['count'] + 1 : 1;
                    $workload[$user]['task'][$task->projectName]['manhour']   = isset($workload[$user]['task'][$task->projectName]['manhour']) ? $workload[$user]['task'][$task->projectName]['manhour'] + $task->left : $task->left;
                    $workload[$user]['task'][$task->projectName]['projectID'] = $task->project;
                    $workload[$user]['total']['count']   = isset($workload[$user]['total']['count']) ? $workload[$user]['total']['count'] + 1 : 1;
                    $workload[$user]['total']['manhour'] = isset($workload[$user]['total']['manhour']) ? $workload[$user]['total']['manhour'] + $task->left : $task->left;
                }
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
        $bugs = $this->dao->select('t1.*, t2.name as productName')
            ->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')
            ->on('t1.product = t2.id')
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
        $bugs = $this->dao->select('t1.id, t1.title, t2.account as user')
            ->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')
            ->on('t1.assignedTo = t2.account')
            ->where('t1.assignedTo')->ne('')
            ->andWhere('t1.assignedTo')->ne('closed')
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->fetchGroup('user');
        return $bugs;
    }

    /**
     * Get user tasks.
     * 
     * @access public
     * @return void
     */
    public function getUserTasks()
    {
        $tasks = $this->dao->select('t1.id, t1.name, t2.account as user')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')
            ->on('t1.assignedTo = t2.account')
            ->where('t1.assignedTo')->ne('')
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.status')->in('wait, doing')
            ->fetchGroup('user');

        return $tasks;
    }

    /**
     * Get user todos.
     * 
     * @access public
     * @return void
     */
    public function getUserTodos()
    {
        $stmt = $this->dao->select('t1.*, t2.account as user')
            ->from(TABLE_TODO)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')
            ->on('t1.account = t2.account')
            ->where('t1.status')->eq('wait')
            ->orWhere('t1.status')->eq('doing')
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
}

function sortSummary($pre, $next)
{
    if($pre['validRate'] == $next['validRate']) return 0;
    return $pre['validRate'] > $next['validRate'] ? -1 : 1;
}
