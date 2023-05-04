<?php
declare(strict_types=1);

/**
 * The model file of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Lanzongjun <lanzongjun@easycorp.ltd>
 * @package     todo
 * @link        https://www.zentao.net
 */
class todoModel extends model
{
    /**
     * 创建待办
     * Create todo data.
     *
     * @param  object $todo
     * @param  object $formData
     * @access public
     * @return int|false
     */
    public function create(object $todo, object $formData): int|false
    {
        $processedTodo = $this->todoTao->processCreateData($todo, $formData);
        if(!$processedTodo) return false;

        $todoID = $this->todoTao->insert($processedTodo);
        if(dao::isError()) return false;

        return $todoID;
    }

    /**
     * Create batch todo
     *
     * @access public
     * @return array
     */
    public function batchCreate()
    {
        $todos = fixer::input('post')->get();

        $validTodos = array();
        $now        = helper::now();
        $assignedTo = $this->app->user->account;
        for($i = 0; $i < $this->config->todo->batchCreate; $i++)
        {
            $isExist    = false;
            $assignedTo = $todos->assignedTos[$i] == 'ditto' ? $assignedTo : $todos->assignedTos[$i];
            foreach($this->config->todo->objectList as $objects)
            {
                if(isset($todos->{$objects}[$i + 1]))
                {
                    $isExist = true;
                    break;
                }
            }

            if($todos->names[$i] != '' || $isExist)
            {
                $todo          = new stdclass();
                $todo->account = $this->app->user->account;
                if($this->post->switchDate == 'on' or $this->post->date == false)
                {
                    $todo->date = '2030-01-01';
                }
                else
                {
                    $todo->date = $this->post->date;
                }

                $todo->type         = $todos->types[$i];
                $todo->pri          = $todos->pris[$i];
                $todo->name         = isset($todos->names[$i]) ? $todos->names[$i] : '';
                $todo->desc         = $todos->descs[$i];
                $todo->begin        = isset($todos->begins[$i]) ? $todos->begins[$i] : 2400;
                $todo->end          = isset($todos->ends[$i]) ? $todos->ends[$i] : 2400;
                $todo->status       = "wait";
                $todo->private      = 0;
                $todo->objectID     = 0;
                $todo->assignedTo   = $assignedTo;
                $todo->assignedBy   = $this->app->user->account;
                $todo->assignedDate = $now;
                $todo->vision       = $this->config->vision;

                if(in_array($todo->type, $this->config->todo->moduleList)) $todo->objectID = isset($todos->{$this->config->todo->objectList[$todo->type]}[$i + 1]) ? $todos->{$this->config->todo->objectList[$todo->type]}[$i + 1] : 0;

                if($todo->type != 'custom' and $todo->objectID)
                {
                    $type   = $todo->type;
                    $object = $this->loadModel($type)->getByID($todo->objectID);
                    if(isset($object->name))  $todo->name = $object->name;
                    if(isset($object->title)) $todo->name = $object->title;
                }

                if($todo->end < $todo->begin) return print(js::alert(sprintf($this->lang->error->gt, $this->lang->todo->end, $this->lang->todo->begin)));

                $validTodos[] = $todo;
            }
            else
            {
                unset($todos->types[$i]);
                unset($todos->pris[$i]);
                unset($todos->names[$i]);
                unset($todos->descs[$i]);
                unset($todos->begins[$i]);
                unset($todos->ends[$i]);
            }
        }

        $todoIDList = array();
        foreach($validTodos as $todo)
        {
            $this->dao->insert(TABLE_TODO)->data($todo)->autoCheck()->exec();
            if(dao::isError())
            {
                echo js::error(dao::getError());
                return print(js::reload('parent'));
            }
            $todoID       = $this->dao->lastInsertID();
            $todoIDList[] = $todoID;
            $this->loadModel('score')->create('todo', 'create', $todoID);
            $this->loadModel('action')->create('todo', $todoID, 'opened');
        }

        return $todoIDList;
    }

    /**
     * 更新待办数据。
     * update a todo.
     *
     * @param  int    $todoID
     * @param  object $todo
     * @access public
     * @return array|false
     */
    public function update(int $todoID, object $todo): array|false
    {
        $oldTodo = $this->dao->findByID($todoID)->from(TABLE_TODO)->fetch();

        if(!$this->todoTao->updateRow($todoID, $todo)) return false;

        $this->loadModel('file')->updateObjectID($todo->uid, $todoID, 'todo');
        if(!empty($oldTodo->cycle)) $this->createByCycle(array($todoID => $todo));
        if(($this->config->edition == 'biz' || $this->config->edition == 'max') && $todo->type == 'feedback' && $todo->objectID) $this->loadModel('feedback')->updateStatus('todo', $todo->objectID, $todo->status);
        return common::createChanges($oldTodo, (array)$todo);
    }

    /**
     * 更新批量编辑待办数据。
     * Update batch edit todo data.
     *
     * @param array $todos
     * @param array $todoIdList
     * @access protected
     * @return array|int
     */
    public function batchUpdate(array $todos, array $todoIdList): array|int
    {
        if(empty($todos)) return $todos;

        $allChanges = array();

        /* Initialize todos from the post data. */
        $oldTodos = $this->getTodosByIdList($todoIdList);
        foreach($todos as $todoID => $todo)
        {
            $oldTodo = $oldTodos[$todoID];
            if(in_array($todo->type, $this->config->todo->moduleList)) $oldTodo->name = '';
            $this->updateTodoDataByID($todoID, $todo);

            if($oldTodo->status != 'done' and $todo->status == 'done') $this->loadModel('action')->create('todo', $todoID, 'finished', '', 'done');

            if(!dao::isError())
            {
                if(($this->config->edition == 'biz' || $this->config->edition == 'max') && $todo->type == 'feedback' && $todo->objectID && !isset($feedbacks[$todo->objectID]))
                {
                    $feedbacks[$todo->objectID] = $todo->objectID;
                    $this->loadModel('feedback')->updateStatus('todo', $todo->objectID, $todo->status);
                }

                $allChanges[$todoID] = common::createChanges($oldTodo, $todo);
            }
            else
            {
                return print(js::error('todo#' . $todoID . dao::getError(true)));
            }
        }

        return $allChanges;
    }

    /**
     * 开启待办事项
     * Start a todo.
     *
     * @param  int   $todoID
     * @access public
     * @return bool
     */
    public function start(int $todoID): bool
    {
        $this->dao->update(TABLE_TODO)->set('status')->eq('doing')->where('id')->eq($todoID)->exec();
        $this->loadModel('action')->create('todo', $todoID, 'started');

        return !dao::isError();
    }


    /**
     * 完成待办
     * Finish todo.
     *
     * @param  int     $todoID
     * @access public
     * @return bool
     */
    public function finish(int $todoID): bool
    {
        return $this->dealFinishData($todoID);
    }

    /**
     * 批量完成待办.
     * Batch finish todo.
     *
     * @param  int[]   $todoIDList
     * @access public
     * @return bool
     */
    public function batchFinish(array $todoIDList): bool
    {
        foreach($todoIDList as $todoID)
        {
            $finishResult = $this->dealFinishData($todoID);
            if(!$finishResult) return $finishResult;
        }
        return true;
    }

    /**
     * 获取待办事项详情数据
     * Get info of a todo.
     *
     * @param  int    $todoID
     * @param  bool   $setImgSize true|false
     * @access public
     * @return object|false
     */
    public function getByID(int $todoID, $setImgSize = false): object|false
    {
        $todo = $this->dao->findByID($todoID)->from(TABLE_TODO)->fetch();
        if(!$todo) return false;

        $todo = $this->loadModel('file')->replaceImgURL((object)$todo, 'desc');
        if($setImgSize) $todo->desc = $this->file->setImgSize($todo->desc);
        if($todo->type == 'story')    $todo->name = $this->dao->findByID($todo->objectID)->from(TABLE_STORY)->fetch('title');
        if($todo->type == 'task')     $todo->name = $this->dao->findByID($todo->objectID)->from(TABLE_TASK)->fetch('name');
        if($todo->type == 'bug')      $todo->name = $this->dao->findByID($todo->objectID)->from(TABLE_BUG)->fetch('title');
        if($todo->type == 'issue'  and $this->config->edition == 'max') $todo->name = $this->dao->findByID($todo->objectID)->from(TABLE_ISSUE)->fetch('title');
        if($todo->type == 'risk'   and $this->config->edition == 'max') $todo->name = $this->dao->findByID($todo->objectID)->from(TABLE_RISK)->fetch('name');
        if($todo->type == 'opportunity' and $this->config->edition == 'max') $todo->name = $this->dao->findByID($todo->objectID)->from(TABLE_OPPORTUNITY)->fetch('name');
        if($todo->type == 'review' and $this->config->edition == 'max') $todo->name = $this->dao->findByID($todo->objectID)->from(TABLE_REVIEW)->fetch('title');
        if($todo->type == 'testtask') $todo->name = $this->dao->findByID($todo->objectID)->from(TABLE_TESTTASK)->fetch('name');
        if($todo->type == 'feedback') $todo->name = $this->dao->findByID($todo->objectID)->from(TABLE_FEEDBACK)->fetch('title');
        $todo->date = str_replace('-', '', $todo->date);

        return $todo;
    }

    /**
     * Get todo list of a user.
     *
     * @param  string $type
     * @param  string $account
     * @param  string $status   all|today|thisweek|lastweek|before, or a date.
     * @param  int    $limit
     * @param  object $pager
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function getList($type = 'today', $account = '', $status = 'all', $limit = 0, $pager = null, $orderBy="date, status, begin")
    {
        $this->app->loadClass('date');
        $todos = array();
        $type = strtolower($type);

        if($type == 'all' or $type == 'assignedtoother')
        {
            $begin = '1970-01-01';
            $end   = '2109-01-01';
        }
        elseif($type == 'today')
        {
            $begin = date::today();
            $end   = $begin;
        }
        elseif($type == 'yesterday')
        {
            $begin = date::yesterday();
            $end   = $begin;
        }
        elseif($type == 'thisweek')
        {
            extract(date::getThisWeek());
        }
        elseif($type == 'lastweek')
        {
            extract(date::getLastWeek());
        }
        elseif($type == 'thismonth')
        {
            extract(date::getThisMonth());
        }
        elseif($type == 'lastmonth')
        {
            extract(date::getLastMonth());
        }
        elseif($type == 'thisseason')
        {
            extract(date::getThisSeason());
        }
        elseif($type == 'thisyear')
        {
            extract(date::getThisYear());
        }
        elseif($type == 'future')
        {
            $begin = '2030-01-01';
            $end   = $begin;
        }
        elseif($type == 'before')
        {
            $begin = '1970-01-01';
            $end   = date::today();
        }
        elseif($type == 'cycle')
        {
            $begin = $end = '';
        }
        else
        {
            $begin = $end = $type;
        }

        if(empty($account)) $account = $this->app->user->account;

        $stmt = $this->dao->select('*')->from(TABLE_TODO)
            ->where('deleted')->eq('0')
            ->andWhere('vision')->eq($this->config->vision)
            ->beginIF($type == 'assignedtoother')->andWhere('account', true)->eq($account)->fi()
            ->beginIF($type != 'assignedtoother')->andWhere('assignedTo', true)->eq($account)->fi()
            ->orWhere('finishedBy')->eq($account)
            ->orWhere('closedBy')->eq($account)
            ->markRight(1)
            ->beginIF($begin)->andWhere('date')->ge($begin)->fi()
            ->beginIF($end)->andWhere('date')->le($end)->fi()
            ->beginIF($status != 'all' and $status != 'undone')->andWhere('status')->in($status)->fi()
            ->beginIF($status == 'undone')->andWhere('status')->notin('done,closed')->fi()
            ->beginIF($type == 'cycle')->andWhere('cycle')->eq('1')->fi()
            ->beginIF($type != 'cycle')->andWhere('cycle')->eq('0')->fi()
            ->beginIF($type == 'assignedtoother')->andWhere('assignedTo')->notin(array($account, ''))->fi()
            ->orderBy($orderBy)
            ->beginIF($limit > 0)->limit($limit)->fi()
            ->page($pager)
            ->query();

        /* Set session. */
        $sql = explode('WHERE', $this->dao->get());
        $sql = explode('ORDER', $sql[1]);
        $this->session->set('todoReportCondition', $sql[0]);

        while($todo = $stmt->fetch())
        {
            if($todo->type == 'story')    $todo->name = $this->dao->findByID($todo->objectID)->from(TABLE_STORY)->fetch('title');
            if($todo->type == 'task')     $todo->name = $this->dao->findByID($todo->objectID)->from(TABLE_TASK)->fetch('name');
            if($todo->type == 'bug')      $todo->name = $this->dao->findByID($todo->objectID)->from(TABLE_BUG)->fetch('title');
            if($todo->type == 'testtask') $todo->name = $this->dao->findByID($todo->objectID)->from(TABLE_TESTTASK)->fetch('name');
            if($todo->type == 'issue'  && $this->config->edition == 'max') $todo->name = $this->dao->findByID($todo->objectID)->from(TABLE_ISSUE)->fetch('title');
            if($todo->type == 'risk'   && $this->config->edition == 'max') $todo->name = $this->dao->findByID($todo->objectID)->from(TABLE_RISK)->fetch('name');
            if($todo->type == 'opportunity' && $this->config->edition == 'max') $todo->name = $this->dao->findByID($todo->objectID)->from(TABLE_OPPORTUNITY)->fetch('name');
            if($todo->type == 'review' && $this->config->edition == 'max') $todo->name = $this->dao->findByID($todo->objectID)->from(TABLE_REVIEW)->fetch('title');
            if($todo->type == 'feedback' and $this->config->edition != 'open') $todo->name = $this->dao->findByID($todo->objectID)->from(TABLE_FEEDBACK)->fetch('title');
            $todo->begin = date::formatTime($todo->begin);
            $todo->end   = date::formatTime($todo->end);

            /* If is private, change the title to private. */
            if($todo->private and $this->app->user->account != $todo->account) $todo->name = $this->lang->todo->thisIsPrivate;

            $todos[] = $todo;
        }

        return $todos;
    }

    /**
     * Get by id list.
     *
     * @param  array $todoIDList
     * @access public
     * @return object
     */
    public function getByList($todoIDList = 0)
    {
        return $this->dao->select('*')->from(TABLE_TODO)
            ->beginIF($todoIDList)->where('id')->in($todoIDList)->fi()
            ->fetchAll('id');
    }

    /**
     * Judge an action is clickable or not.
     *
     * @param  object    $todo
     * @param  string    $action
     * @access public
     * @return bool
     */
    public static function isClickable($todo, $action)
    {
        $action = strtolower($action);

        if($action == 'finish')
        {
            if(!empty($todo->cycle)) return false;
            return $todo->status != 'done';
        }

        if($action == 'start')
        {
            if(!empty($todo->cycle)) return false;
            return $todo->status == 'wait';
        }

        return true;
    }

    /**
     * Create todo by cycle.
     *
     * @param  array   $todoList
     * @access public
     * @return void
     */
    public function createByCycle(array $todoList): void
    {
        $this->loadModel('action');
        $today      = helper::today();
        $now        = helper::now();
        $cycleList  = $this->todoTao->getCycleList($todoList);
        $validUsers = $this->dao->select('account')->from(TABLE_USER)->where('deleted')->eq(0)->fetchPairs('account', 'account');

        foreach($todoList as $todoID => $todo)
        {
            if(!isset($validUsers[$todo->account])) continue;

            $todo->config = json_decode($todo->config);
            $begin      = $todo->config->begin;
            $end        = $todo->config->end;
            $beforeDays = (int)$todo->config->beforeDays;
            if(!empty($beforeDays) && $beforeDays > 0) $begin = date('Y-m-d', strtotime("$begin -{$beforeDays} days"));
            if($today < $begin or (!empty($end) && $today > $end)) continue;

            $newTodo = $this->todoTao->buildCycleTodo($todo);
            if(isset($todo->assignedTo) and $todo->assignedTo) $newTodo->assignedDate = $now;

            $start  = strtotime($begin);
            $finish = strtotime("$today +{$beforeDays} days");
            foreach(range($start, $finish, 86400) as $today)
            {
                $today     = date('Y-m-d', $today);
                $lastCycle = zget($cycleList, $todoID, '');

                $date = $this->todoTao->getCycleTodoDate($todo, $lastCycle, $today);
                if($date === false) continue;

                if(!$date)                                     continue;
                if($date < $todo->config->begin)               continue;
                if($date < date('Y-m-d'))                      continue;
                if($date > date('Y-m-d', $finish))             continue;
                if(!empty($end) && $date > $end)               continue;
                if($lastCycle and ($date == $lastCycle->date)) continue;

                $newTodo->date = $date;
                $this->todoTao->insert($newTodo);
                $this->action->create('todo', $this->dao->lastInsertID(), 'opened', '', '', $newTodo->account);
                $cycleList[$todoID] = $newTodo;
            }
        }
    }

    /**
     * 激活待办事项
     * Activated a todo.
     *
     * @param  int $todoID
     * @access public
     * @return bool
     */
    public function activate(int $todoID): bool
    {
        $this->dao->update(TABLE_TODO)->set('status')->eq('wait')->where('id')->eq($todoID)->exec();
        $this->loadModel('action')->create('todo', $todoID, 'activated', '', 'wait');

        return !dao::isError();
    }

    /**
     * 关闭待办。如果是企业版或旗舰版则更新关联的反馈。
     * Close todo. Update related feedback if edition is biz or max.
     *
     * @param  int    $todoID
     * @access public
     * @return bool
     */
    public function close(int $todoID): bool
    {
        $isClosed = $this->todoTao->closeTodo($todoID);

        if(!$isClosed) return false;

        $this->loadModel('action')->create('todo', $todoID, 'closed', '', 'closed');

        if($this->config->edition == 'biz' || $this->config->edition == 'max')
        {
            $feedbackID = $this->dao->select('objectID')->from(TABLE_TODO)->where('id')->eq($todoID)->andWhere('type')->eq('feedback')->fetch('objectID');
            if($feedbackID) $this->loadModel('feedback')->updateStatus('todo', $feedbackID, 'closed');
        }
        return true;
    }

    /**
     * 指派待办.
     * Assign todo.
     *
     * @param  object  $todo
     * @access public
     * @return bool
     */
    public function assignTo(object $todo): bool
    {
        $todoID = (int)$todo->id;
        $result = $this->todoTao->updateRow($todoID, $todo);
        if(!$result) return false;

        $this->loadModel('action')->create('todo', $todoID, 'assigned', '', $todo->assignedTo);
        return !dao::isError();
    }

    /**
     * 获取待办事项的数量。
     * Get todo count.
     *
     * @param  string $account
     * @access public
     * @return int
     */
    public function getCount(string $account = ''): int
    {
        if(empty($account)) $account = $this->app->user->account;

        return $this->todoTao->getTodoCountByAccount($account);
    }

    /**
     * Gets the project ID of the to-do object.
     *
     * @param  array $todoList
     * @access public
     * @return array
     */
    public function getTodoProjects($todoList = array())
    {
        $projectIdList = array();
        foreach($todoList as $type => $todos)
        {
            $todoIdList = array_keys($todos);
            if(empty($todoIdList))
            {
                $projectIdList[$type] = array();
                continue;
            }

            $todoIdList = array_unique($todoIdList);
            if($type == 'task')     $projectIdList[$type] = $this->dao->select('id,project')->from(TABLE_TASK)->where('id')->in($todoIdList)->fetchPairs('id', 'project');
            if($type == 'bug')      $projectIdList[$type] = $this->dao->select('id,project')->from(TABLE_BUG)->where('id')->in($todoIdList)->fetchPairs('id', 'project');
            if($type == 'issue')    $projectIdList[$type] = $this->dao->select('id,project')->from(TABLE_ISSUE)->where('id')->in($todoIdList)->fetchPairs('id', 'project');
            if($type == 'risk')     $projectIdList[$type] = $this->dao->select('id,project')->from(TABLE_RISK)->where('id')->in($todoIdList)->fetchPairs('id', 'project');
            if($type == 'opportunity') $projectIdList[$type] = $this->dao->select('id,project')->from(TABLE_OPPORTUNITY)->where('id')->in($todoIdList)->fetchPairs('id', 'project');
            if($type == 'review')   $projectIdList[$type] = $this->dao->select('id,project')->from(TABLE_REVIEW)->where('id')->in($todoIdList)->fetchPairs('id', 'project');
            if($type == 'testtask') $projectIdList[$type] = $this->dao->select('id,project')->from(TABLE_TESTTASK)->where('id')->in($todoIdList)->fetchPairs('id', 'project');
        }

        return $projectIdList;
    }

    /**
     * 处理完成待办数据.
     * Deal finish todo data.
     *
     * @param  int     $todoID
     * @access public
     * @return bool
     */
    private function dealFinishData(int $todoID): bool
    {
        $todo = new stdClass();
        $todo->id         = $todoID;
        $todo->status     = 'done';
        $todo->finishedBy = $this->app->user->account;
        $this->todoTao->updateRow($todoID, $todo);

        if(dao::isError()) return false;

        $this->loadModel('action')->create('todo', $todoID, 'finished', '', 'done');

        if(($this->config->edition == 'biz' || $this->config->edition == 'max'))
        {
            $todo       = $this->todoTao->fetch($todoID);
            $feedbackID = $todo->idvalue ?? '' ;
            if($feedbackID) $this->loadModel('feedback')->updateStatus('todo', $feedbackID, 'done');
        }
        return true;
    }

    /**
     * 修改待办事项的时间。
     * Edit the date of todo.
     *
     * @param  array  $todoIDList
     * @param  string $date
     * @access public
     * @return bool
     */
    public function editDate(array $todoIDList, string $date): bool
    {
        return $this->todoTao->updateDate($todoIDList, $date);
    }

    /**
     * 获取导出的待办数据。
     * Get data for export todo.
     *
     * @param  string $orderBy
     * @param  object $formData
     * @access public
     * @return array
     */
    public function getByExportList(string $orderBy, object $formData): array
    {
        return $this->dao->select('*')->from(TABLE_TODO)
            ->where($this->session->todoReportCondition)
            ->beginIF($formData->rawdata->exportType == 'selected')->andWhere('id')->in($this->cookie->checkedItem)->fi()
            ->orderBy($orderBy)->fetchAll('id');
    }

    /**
     * 根据待办ID获取多条待办。
     * Get todo data by id list.
     *
     * @param  array $todoIdList
     * @access public
     * @return array
     */
    public function getTodosByIdList(array $todoIdList): array
    {
        return $this->todoTao->fetchRows($todoIdList);
    }

    /**
     * 根据待办ID更新待办数据。
     * Update todo data by id.
     *
     * @param  array $todoIdList
     * @access public
     * @return bool
     */
    public function updateTodoDataByID(int $todoID, object $todo): bool
    {
        return $this->todoTao->updateRow($todoID, $todo);
    }
}
