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

/**
 * Print cell data.
 *
 * @param object $col
 * @param object $task
 * @param array  $users
 * @param string $browseType
 * @param array  $branchGroups
 * @param array  $modulePairs
 * @param string $mode
 * @param bool   $child
 * @param bool   $showBranch
 *
 * @access public
 * @return void
 */
public function printCell($col, $task, $users, $browseType, $branchGroups, $modulePairs = array(), $mode = 'datatable', $child = false, $showBranch = false)
{
    $canBatchEdit         = common::hasPriv('task', 'batchEdit', !empty($task) ? $task : null);
    $canBatchClose        = (common::hasPriv('task', 'batchClose', !empty($task) ? $task : null) and strtolower($browseType) != 'closed');
    $canBatchCancel       = common::hasPriv('task', 'batchCancel', !empty($task) ? $task : null);
    $canBatchChangeModule = common::hasPriv('task', 'batchChangeModule', !empty($task) ? $task : null);
    $canBatchAssignTo     = common::hasPriv('task', 'batchAssignTo', !empty($task) ? $task : null);

    $canBatchAction = ($canBatchEdit or $canBatchClose or $canBatchCancel or $canBatchChangeModule or $canBatchAssignTo);
    $storyChanged   = (!empty($task->storyStatus) and $task->storyStatus == 'active' and $task->latestStoryVersion > $task->storyVersion and !in_array($task->status, array('cancel', 'closed')));

    $canView  = common::hasPriv('task', 'view');
    $taskLink = helper::createLink('task', 'view', "taskID=$task->id");
    $account  = $this->app->user->account;
    $id       = $col->id;
    if($col->show)
    {
        $class = "c-{$id}";
        if($id == 'status') $class .= ' task-' . $task->status;
        if($id == 'id')     $class .= ' cell-id';
        if($id == 'name')   $class .= ' text-left';
        if($id == 'deadline') $class .= ' text-center';
        if($id == 'deadline' and isset($task->delay)) $class .= ' delayed';
        if($id == 'assignedTo') $class .= ' has-btn text-left';
        if($id == 'lane') $class .= ' text-left';
        if(strpos('progress', $id) !== false) $class .= ' text-right';

        $title = '';
        if($id == 'name')
        {
            $title = " title='{$task->name}'";
            if(!empty($task->children)) $class .= ' has-child';
        }
        if($id == 'story') $title = " title='{$task->storyTitle}'";
        if($id == 'estimate' || $id == 'consumed' || $id == 'left')
        {
            $value = round($task->$id, 1);
            $title = " title='{$value} {$this->lang->execution->workHour}'";
        }

        echo "<td class='" . $class . "'" . $title . ">";
        if(isset($this->config->bizVersion)) $this->loadModel('flow')->printFlowCell('task', $task, $id);
        switch($id)
        {
        case 'id':
            if($canBatchAction)
            {
                echo html::checkbox('taskIDList', array($task->id => '')) . html::a(helper::createLink('task', 'view', "taskID=$task->id"), sprintf('%03d', $task->id));
            }
            else
            {
                printf('%03d', $task->id);
            }
            break;
        case 'pri':
            echo "<span class='label-pri label-pri-" . $task->pri . "' title='" . zget($this->lang->task->priList, $task->pri, $task->pri) . "'>";
            echo zget($this->lang->task->priList, $task->pri, $task->pri);
            echo "</span>";
            break;
        case 'name':
            if($showBranch) $showBranch = isset($this->config->execution->task->showBranch) ? $this->config->execution->task->showBranch : 1;
            if($task->parent > 0 and isset($task->parentName)) $task->name = "{$task->parentName} / {$task->name}";
            if(!empty($task->product) and isset($branchGroups[$task->product][$task->branch]) and $showBranch) echo "<span class='label label-badge label-outline'>" . $branchGroups[$task->product][$task->branch] . '</span> ';
            if($task->module and isset($modulePairs[$task->module])) echo "<span class='label label-gray label-badge'>" . $modulePairs[$task->module] . '</span> ';
            if($task->parent > 0) echo '<span class="label label-badge label-light" title="' . $this->lang->task->children . '">' . $this->lang->task->childrenAB . '</span> ';
            if(!empty($task->team)) echo '<span class="label label-badge label-light" title="' . $this->lang->task->multiple . '">' . $this->lang->task->multipleAB . '</span> ';
            echo $canView ? html::a($taskLink, $task->name, null, "style='color: $task->color' title='$task->name'") : "<span style='color: $task->color'>$task->name</span>";
            if(!empty($task->children)) echo '<a class="task-toggle" data-id="' . $task->id . '"><i class="icon icon-angle-double-right"></i></a>';
            if($task->fromBug) echo html::a(helper::createLink('bug', 'view', "id=$task->fromBug"), "[BUG#$task->fromBug]", '', "class='bug'");
            break;
        case 'type':
            echo $this->lang->task->typeList[$task->type];
            break;
        case 'status':
            $storyChanged ? print("<span class='status-story status-changed' title='{$this->lang->story->changed}'>{$this->lang->story->changed}</span>") : print("<span class='status-task status-{$task->status}' title='{$this->processStatus('task', $task)}'> " . $this->processStatus('task', $task) . "</span>");
            break;
        case 'estimate':
            echo round($task->estimate, 1) . $this->lang->execution->workHourUnit;
            break;
        case 'consumed':
            echo round($task->consumed, 1) . $this->lang->execution->workHourUnit;
            break;
        case 'left':
            echo round($task->left, 1)     . $this->lang->execution->workHourUnit;
            break;
        case 'progress':
            echo round($task->progress, 2) . '%';
            break;
        case 'deadline':
            if(substr($task->deadline, 0, 4) > 0) echo substr($task->deadline, 5, 6);
            break;
        case 'openedBy':
            echo zget($users, $task->openedBy);
            break;
        case 'openedDate':
            echo substr($task->openedDate, 5, 11);
            break;
        case 'estStarted':
            echo $task->estStarted;
            break;
        case 'realStarted':
            echo substr($task->realStarted, 5, 11);
            break;
        case 'assignedTo':
            $this->printAssignedHtml($task, $users);
            break;
        case 'lane':
            echo trim($task->lane);
            break;
        case 'assignedDate':
            echo substr($task->assignedDate, 5, 11);
            break;
        case 'finishedBy':
            echo zget($users, $task->finishedBy);
            break;
        case 'finishedDate':
            echo substr($task->finishedDate, 5, 11);
            break;
        case 'canceledBy':
            echo zget($users, $task->canceledBy);
            break;
        case 'canceledDate':
            echo substr($task->canceledDate, 5, 11);
            break;
        case 'closedBy':
            echo zget($users, $task->closedBy);
            break;
        case 'closedDate':
            echo substr($task->closedDate, 5, 11);
            break;
        case 'closedReason':
            echo $this->lang->task->reasonList[$task->closedReason];
            break;
        case 'story':
            if(!empty($task->storyID))
            {
                if(common::hasPriv('story', 'view'))
                {
                    echo html::a(helper::createLink('story', 'view', "storyid=$task->storyID", 'html', true), "<i class='icon icon-{$this->lang->icons['story']}'></i>", '', "class='iframe' data-width='1050' title='{$task->storyTitle}'");
                }
                else
                {
                    echo "<i class='icon icon-{$this->lang->icons['story']}' title='{$task->storyTitle}'></i>";
                }
            }
            break;
        case 'mailto':
            $mailto = explode(',', $task->mailto);
            foreach($mailto as $account)
            {
                $account = trim($account);
                if(empty($account)) continue;
                echo zget($users, $account) . ' &nbsp;';
            }
            break;
        case 'lastEditedBy':
            echo zget($users, $task->lastEditedBy);
            break;
        case 'lastEditedDate':
            echo substr($task->lastEditedDate, 5, 11);
            break;
        case 'actions':
            if($storyChanged)
            {
                common::printIcon('task', 'confirmStoryChange', "taskid=$task->id", $task, 'list', '', 'hiddenwin');
                break;
            }

            if($task->status != 'pause') common::printIcon('task', 'start', "taskID=$task->id", $task, 'list', '', '', 'iframe', true);
            if($task->status == 'pause') common::printIcon('task', 'restart', "taskID=$task->id", $task, 'list', '', '', 'iframe', true);
            common::printIcon('task', 'close',  "taskID=$task->id", $task, 'list', '', '', 'iframe', true);
            common::printIcon('task', 'finish', "taskID=$task->id", $task, 'list', '', '', 'iframe', true);

            common::printIcon('task', 'recordEstimate', "taskID=$task->id", $task, 'list', 'time', '', 'iframe', true);
            common::printIcon('task', 'edit',   "taskID=$task->id", $task, 'list');
            common::printIcon('task', 'batchCreate', "execution=$task->execution&storyID=$task->story&moduleID=$task->module&taskID=$task->id&ifame=0", $task, 'list', 'split', '', '', '', '', $this->lang->task->children);
            break;
        }
        echo '</td>';
    }
}