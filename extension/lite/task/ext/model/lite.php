<?php
/**
 * Get tasks of a execution.
 *
 * @param int    $executionID
 * @param int    $productID
 * @param string $type
 * @param string $modules
 * @param string $orderBy
 * @param null   $pager
 *
 * @access public
 * @return array|void
 */
public function getExecutionTasks($executionID, $productID = 0, $type = 'all', $modules = 0, $orderBy = 'status_asc, id_desc', $pager = null)
{
    if(is_string($type)) $type = strtolower($type);
    $tasks = $this->dao->select('DISTINCT t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.product, t2.branch, t2.version AS latestStoryVersion, t2.status AS storyStatus, t3.realname AS assignedToRealName')
        ->from(TABLE_TASK)->alias('t1')
        ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
        ->leftJoin(TABLE_USER)->alias('t3')->on('t1.assignedTo = t3.account')
        ->leftJoin(TABLE_TEAM)->alias('t4')->on('t4.root = t1.id')
        ->leftJoin(TABLE_MODULE)->alias('t5')->on('t1.module = t5.id')
        ->where('t1.execution')->eq((int)$executionID)
        ->beginIF($type == 'myinvolved')
        ->andWhere("((t4.`account` = '{$this->app->user->account}' AND t4.`type` = 'task') OR t1.`assignedTo` = '{$this->app->user->account}' OR t1.`finishedby` = '{$this->app->user->account}')")
        ->fi()
        ->beginIF($productID)->andWhere("((t5.root=" . (int)$productID . " and t5.type='story') OR t2.product=" . (int)$productID . ")")->fi()
        ->beginIF($type == 'undone')->andWhere('t1.status')->notIN('done,closed')->fi()
        ->beginIF($type == 'needconfirm')->andWhere('t2.version > t1.storyVersion')->andWhere("t2.status = 'active'")->fi()
        ->beginIF($type == 'assignedtome')->andWhere('t1.assignedTo')->eq($this->app->user->account)->fi()
        ->beginIF($type == 'finishedbyme')
        ->andWhere('t1.finishedby', 1)->eq($this->app->user->account)
        ->orWhere('t1.finishedList')->like("%,{$this->app->user->account},%")
        ->markRight(1)
        ->fi()
        ->beginIF($type == 'delayed')->andWhere('t1.deadline')->gt('1970-1-1')->andWhere('t1.deadline')->lt(date(DT_DATE1))->andWhere('t1.status')->in('wait,doing')->fi()
        ->beginIF(is_array($type) or strpos(',all,undone,needconfirm,assignedtome,delayed,finishedbyme,myinvolved,', ",$type,") === false)->andWhere('t1.status')->in($type)->fi()
        ->beginIF($modules)->andWhere('t1.module')->in($modules)->fi()
        ->andWhere('t1.deleted')->eq(0)
        ->orderBy($orderBy)
        ->page($pager, 't1.id')
        ->fetchAll('id');

    $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'task', ($productID or in_array($type, array('myinvolved', 'needconfirm'))) ? false : true);

    if(empty($tasks)) return array();

    $taskList = array_keys($tasks);
    $taskTeam = $this->dao->select('*')->from(TABLE_TEAM)->where('root')->in($taskList)->andWhere('type')->eq('task')->fetchGroup('root');
    if(!empty($taskTeam))
    {
        foreach($taskTeam as $taskID => $team) $tasks[$taskID]->team = $team;
    }

    $parents = array();
    foreach($tasks as $task)
    {
        if($task->parent > 0) $parents[$task->parent] = $task->parent;
    }
    $parents = $this->dao->select('*')->from(TABLE_TASK)->where('id')->in($parents)->fetchAll('id');
    
    $lanes      = array();
    $cardsWhere = '';
    foreach($tasks as $task) 
    {
        if(empty($cardsWhere)) 
        {
            $cardsWhere = "cards like '%,{$task->id},%'";
        } 
        else
        {
            $cardsWhere .= " or cards like '%,{$task->id},%'";
        }
    }
    $lanes = $this->dao->select('t1.lane,t2.name,t1.cards')
        ->from(TABLE_KANBANCELL)->alias('t1')
        ->leftJoin(TABLE_KANBANLANE)->alias('t2')->on('t1.lane = t2.id')
        ->where('t1.kanban')->eq($executionID)
        ->andWhere("($cardsWhere)")
        ->fetchAll();

    foreach($tasks as $task)
    {
        if($task->parent > 0)
        {
            if(isset($tasks[$task->parent]))
            {
                $tasks[$task->parent]->children[$task->id] = $task;
                unset($tasks[$task->id]);
            }
            else
            {
                $parent = $parents[$task->parent];
                $task->parentName = $parent->name;
            }
        }

        $task->lane = '';
        if(!empty($lanes))
        {
            foreach($lanes as $lane) 
            {
                if(strpos($lane->cards, ",{$task->id},") !== false)  $task->lane = $lane->name;
            }
        }
    }

    return $this->processTasks($tasks);
}