<?php
/**
 * Get the project or execution in which the user participates..
 *
 * @param  string $account
 * @param  string $type project|execution
 * @param  string $status
 * @param  string $orderBy
 * @param  object $pager
 * @access public
 * @return array
 */
public function getObjects($account, $type = 'execution', $status = 'all', $orderBy = 'id_desc', $pager = null)
{
    $objectType    = $type == 'execution' ? 'sprint,stage,kanban' : $type;
    $myObjectsList = $this->dao->select('t1.*,t2.*')->from(TABLE_TEAM)->alias('t1')
        ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.root = t2.id')
        ->where('t1.type')->eq($type)
        ->andWhere('t2.type')->in($objectType)
        ->beginIF(strpos('doing|wait|suspended|closed', $status) !== false)->andWhere('status')->eq($status)->fi()
        ->beginIF($status == 'done')->andWhere('status')->in('done,closed')->fi()
        ->beginIF($status == 'undone')->andWhere('status')->notin('done,closed')->fi()
        ->beginIF($status == 'openedbyme')->andWhere('openedBy')->eq($account)->fi()
        ->beginIF($type == 'execution' and !$this->app->user->admin)->andWhere('t2.id')->in($this->app->user->view->sprints)->fi()
        ->beginIF($type == 'project' and !$this->app->user->admin)->andWhere('t2.id')->in($this->app->user->view->projects)->fi()
        ->andWhere('t1.account')->eq($account)
        ->andWhere('t2.deleted')->eq(0)
        ->andWhere('t2.vision')->eq($this->config->vision)
        ->orderBy("t2.$orderBy")
        ->page($pager)
        ->fetchAll('root');

    $objectIdList  = array();
    $projectIdList = array();
    foreach($myObjectsList as $object)
    {
        $objectIdList[]  = $object->id;
        $projectIdList[] = $object->project;
    }

    /* Get all tasks and compute totalConsumed, totalLeft, totalWait, progress according to them. */
    $hours       = array();
    $emptyHour   = array('totalConsumed' => 0, 'totalLeft' => 0, 'progress' => 0, 'waitTasks' => 0, 'assignedToMeTasks' => 0, 'doneTasks' => 0, 'taskTotal' => 0);
    $searchField = $type == 'project' ? 'project' : 'execution';
    $tasks       = $this->dao->select('id, project, execution, consumed, `left`, status, assignedTo,finishedBy')
        ->from(TABLE_TASK)
        ->where('parent')->lt(1)
        ->andWhere($searchField)->in($objectIdList)->fi()
        ->andWhere('deleted')->eq(0)
        ->fetchGroup($searchField, 'id');

    /* Compute totalEstimate, totalConsumed, totalLeft. */
    foreach($tasks as $objectID => $objectTasks)
    {
        $hour = (object)$emptyHour;
        $hour->taskTotal = count($objectTasks);
        foreach($objectTasks as $task)
        {
            if($task->status == 'wait') $hour->waitTasks += 1;
            if($task->finishedBy != '') $hour->doneTasks += 1;
            if($task->status != 'cancel') $hour->totalConsumed += $task->consumed;
            if($task->status != 'cancel' and $task->status != 'closed') $hour->totalLeft += $task->left;
            if($task->assignedTo == $account) $hour->assignedToMeTasks += 1;
        }
        $hours[$objectID] = $hour;
    }

    /* Compute totalReal and progress. */
    foreach($hours as $hour)
    {
        $hour->totalConsumed = round($hour->totalConsumed, 1);
        $hour->totalLeft     = round($hour->totalLeft, 1);
        $hour->totalReal     = $hour->totalConsumed + $hour->totalLeft;
        $hour->progress      = $hour->totalReal ? round($hour->totalConsumed / $hour->totalReal, 2) * 100 : 0;
    }

    $myObjects   = array();
    $projectList = $this->loadModel('project')->getByIdList($projectIdList);
    foreach($myObjectsList as $object)
    {
        /* Judge whether the project or execution is delayed. */
        if($object->status != 'done' and $object->status != 'closed' and $object->status != 'suspended')
        {
            $delay = helper::diffDate(helper::today(), $object->end);
            if($delay > 0) $object->delay = $delay;
        }

        /* Process the hours. */
        $object->progress          = isset($hours[$object->id]) ? $hours[$object->id]->progress : 0;
        $object->waitTasks         = isset($hours[$object->id]) ? $hours[$object->id]->waitTasks : 0;
        $object->doneTasks         = isset($hours[$object->id]) ? $hours[$object->id]->doneTasks : 0;
        $object->taskTotal         = isset($hours[$object->id]) ? $hours[$object->id]->taskTotal : 0;
        $object->totalConsumed     = isset($hours[$object->id]) ? $hours[$object->id]->totalConsumed : 0;
        $object->assignedToMeTasks = isset($hours[$object->id]) ? $hours[$object->id]->assignedToMeTasks : 0;

        if($object->project)
        {
            $parentProject = zget($projectList, $object->project, '');
            $object->projectName = $parentProject ? $parentProject->name : '';
        }
        $myObjects[$object->id] = $object;
    }

    return $myObjects;
}

