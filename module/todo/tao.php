<?php
declare(strict_types=1);
class todoTao extends todoModel
{
    /**
     * 获取单条待办。
     * Get a todo.
     *
     * @param  int     $todoID
     * @param  object  $todo
     * @return object
     */
    protected function fetch(int $todoID): object
    {
        return $this->dao->select('*')->from(TABLE_TODO)->where('id')->eq($todoID)->fetch();
    }

    /**
     * 获取多条待办。
     * Get a todo.
     *
     * @param  array  $todoIdList
     * @return array
     */
    protected function fetchRows(array $todoIdList): array
    {
        return $this->dao->select('*')->from(TABLE_TODO)->where('id')->in(array_keys($todoIdList))->fetchAll('id');
    }

    /**
     * 获取待办数量。
     * Get todo count.
     *
     * @param  string    $account
     * @param  string    $vision
     * @access protected
     * @return int
     */
    protected function getCountByAccount(string $account, string $vision = 'rnd'): int
    {
        return $this->dao->select('count(*) as count')->from(TABLE_TODO)
            ->where('cycle')->eq('0')
            ->andWhere('deleted')->eq('0')
            ->andWhere('vision')->eq($vision)
            ->andWhere('account', true)->eq($account)
            ->orWhere('assignedTo')->eq($account)
            ->orWhere('finishedBy')->eq($account)
            ->markRight(1)
            ->fetch('count');
    }

    /**
     * 获取各模块列表。
     * Get project list.
     *
     * @param  string $table
     * @param  array $idList
     * @access protected
     * @return array
     */
    protected function getProjectList(string $table, array $idList): array
    {
        return $this->dao->select('id,project')->from($table)->where('id')->in($idList)->fetchPairs('id', 'project');
    }

    /**
     * 插入待办数据
     * Insert todo data.
     *
     * @param  object $todo
     * @access protected
     * @return int
     */
    protected function insert(object $todo): int
    {
        $this->dao->insert(TABLE_TODO)->data($todo)
            ->autoCheck()
            ->checkIF(!in_array($todo->type, $this->config->todo->moduleList), $this->config->todo->create->requiredFields, 'notempty')
            ->checkIF(in_array($todo->type, $this->config->todo->moduleList) && $todo->objectID == 0, 'objectID', 'notempty')
            ->exec();

        return (int)$this->dao->lastInsertID();
    }

    /**
     * 更新待办数据
     * Update todo data.
     *
     * @param  int    $todoID
     * @param  object $todo
     * @access protected
     * @return bool
     */
    protected function updateRow(int $todoID, object $todo): bool
    {
        $this->dao->update(TABLE_TODO)->data($todo)
            ->autoCheck()
            ->checkIF(isset($todo->type) && in_array($todo->type, array('custom', 'feedback')), $this->config->todo->edit->requiredFields, 'notempty')
            ->checkIF(isset($todo->type) && in_array($todo->type, $this->config->todo->moduleList) && $todo->objectID == 0, 'objectID', 'notempty')
            ->where('id')->eq($todoID)
            ->exec();

        return !dao::isError();
    }

    /**
     * Close one todo.
     *
     * @param int $todoID
     * @access protected
     * @return bool
     */
    protected function closeTodo(int $todoID): bool
    {
        $now = helper::now();
        $this->dao->update(TABLE_TODO)
            ->set('status')->eq('closed')
            ->set('closedBy')->eq($this->app->user->account)
            ->set('closedDate')->eq($now)
            ->set('assignedTo')->eq('closed')
            ->set('assignedDate')->eq($now)
            ->where('id')->eq($todoID)
            ->exec();
        return !dao::isError();
    }

    /**
     * 处理要创建的todo的数据。
     * Process the data for the todo to be created.
     *
     * @param  object $todo
     * @param  object $formData
     * @access protected
     * @return object|false
     */
    protected function processCreateData(object $todo, object $formData): object|false
    {
        if(!isset($todo->pri) and in_array($todo->type, $this->config->todo->moduleList) and !in_array($todo->type, array('review', 'feedback')))
        {
            $todo->pri = $this->dao->select('pri')->from($this->config->objectTables[$todo->type])->where('id')->eq($todo->objectID)->fetch('pri');

            if($todo->pri == 'high')   $todo->pri = 1;
            if($todo->pri == 'middle') $todo->pri = 2;
            if($todo->pri == 'low')    $todo->pri = 3;
        }

        if($todo->type != 'custom' and $todo->objectID)
        {
            $type   = $todo->type;
            $object = $this->loadModel($type)->getByID($todo->{$type});
            if(isset($object->name))  $todo->name = $object->name;
            if(isset($object->title)) $todo->name = $object->title;
        }

        if($todo->end < $todo->begin)
        {
            dao::$errors[] = sprintf($this->lang->error->gt, $this->lang->todo->end, $this->lang->todo->begin);
            return false;
        }

        if(!empty($todo->cycle))
        {
            $todo = $this->setCycle($todo);
            if(!$todo) return false;
        }
        if(empty($todo->cycle)) unset($todo->config);

        if(isset($formData->rawdata->uid)) $this->loadModel('file')->processImgURL($todo, $this->config->todo->editor->create['id'], $formData->rawdata->uid);

        return $todo;
    }

    /**
     * 获取周期待办列表。
     * Get cycle list.
     *
     * @param  array  $todoList
     * @param  string $orderBy
     * @access protected
     * @return array
     */
    protected function getCycleList(array $todoList, string $orderBy = 'date_asc'): array
    {
        return $this->dao->select('*')->from(TABLE_TODO)
            ->where('type')->eq('cycle')
            ->andWhere('deleted')->eq('0')
            ->andWhere('objectID')->in(array_keys($todoList))
            ->orderBy($orderBy)
            ->fetchAll('objectID');
    }

    /**
     * 通过待办构建周期待办数据
     * Build cycle todo.
     *
     * @param  object $todo
     * @access protected
     * @return stdclass
     */
    protected function buildCycleTodo(object $todo): object
    {
        $newTodo = new stdclass();
        $newTodo->account    = $todo->account;
        $newTodo->begin      = $todo->begin;
        $newTodo->end        = $todo->end;
        $newTodo->type       = 'cycle';
        $newTodo->objectID   = $todo->id;
        $newTodo->pri        = $todo->pri;
        $newTodo->name       = $todo->name;
        $newTodo->desc       = $todo->desc;
        $newTodo->status     = 'wait';
        $newTodo->private    = $todo->private;
        $newTodo->assignedTo = $todo->assignedTo;
        $newTodo->assignedBy = $todo->assignedBy ;

        return $newTodo;
    }

    /**
     * 通过周期待办，获取要生成待办的日期。
     * Gets the date by the cycle todo.
     *
     * @param  object        $todo
     * @param  object|string $lastCycle
     * @param  string        $today
     * @access protected
     * @return false|string
     */
    protected function getCycleTodoDate(object $todo, object|string $lastCycle, string $today): false|string
    {
        $date = '';
        if($todo->config->type == 'day')
        {
            return $this->getCycleDailyTodoDate($todo, $lastCycle, $today);
        }
        elseif($todo->config->type == 'week')
        {
            $week = date('w', strtotime($today));
            if(strpos(",{$todo->config->week},", ",{$week},") !== false)
            {
                if(empty($lastCycle)) $date = $today;
                if($lastCycle and $lastCycle->date < $today) $date = $today;
            }
        }
        elseif($todo->config->type == 'month')
        {
            $day = date('j', strtotime($today));
            if(strpos(",{$todo->config->month},", ",{$day},") !== false)
            {
                if(empty($lastCycle))         $date = $today;
                if($lastCycle and $lastCycle->date < $today) $date = $today;
            }
        }

        return $date;
    }

    /**
     * 获取批量创建待办的有效数据。
     * Get valid todos of batch create.
     *
     * @param  object $todos
     * @param  object $formData
     * @param  int    $loop
     * @param  string $assignedTo
     * @access protected
     * @return object
     */
    protected function getValidsOfBatchCreate(object $todos, object $formData, int $loop , string $assignedTo): object
    {
        $todo = new stdclass();
        $todo->account = $this->app->user->account;

        $todo->date = $formData->rawdata->date;
        if($formData->rawdata->switchDate == 'on' || $formData->rawdata->date == false) $todo->date = '2030-01-01';

        $todo->type         = $todos->types[$loop];
        $todo->pri          = $todos->pris[$loop];
        $todo->name         = isset($todos->names[$loop]) ? $todos->names[$loop] : '';
        $todo->desc         = $todos->descs[$loop];
        $todo->begin        = isset($todos->begins[$loop]) ? $todos->begins[$loop] : 2400;
        $todo->end          = isset($todos->ends[$loop]) ? $todos->ends[$loop] : 2400;
        $todo->status       = 'wait';
        $todo->private      = 0;
        $todo->objectID     = 0;
        $todo->assignedTo   = $assignedTo;
        $todo->assignedBy   = $this->app->user->account;
        $todo->assignedDate = helper::now();
        $todo->vision       = $this->config->vision;

        if(in_array($todo->type, $this->config->todo->moduleList))
        {
            $todo->objectID = isset($todos->{$this->config->todo->objectList[$todo->type]}[$loop + 1]) ? $todos->{$this->config->todo->objectList[$todo->type]}[$loop + 1] : 0;
        }

        if($todo->type != 'custom' && $todo->objectID)
        {
            $type   = $todo->type;
            $object = $this->loadModel($type)->getByID($todo->objectID);
            if(isset($object->name))  $todo->name = $object->name;
            if(isset($object->title)) $todo->name = $object->title;
        }

        if($todo->end < $todo->begin) dao::$errors['message'][] = sprintf($this->lang->error->gt, $this->lang->todo->end, $this->lang->todo->begin);

        return $todo;
    }

    /**
     * 通过周期待办，获取要生成每日待办的日期。
     * Gets the daily todo date by the cycle todo.
     *
     * @param  object $todo
     * @param  object|string $lastCycle
     * @param  string $today
     * @access protected
     * @return false|string
     */
    private function getCycleDailyTodoDate(object $todo, object|string $lastCycle, string $today): false|string
    {
        $date = '';
        if(isset($todo->config->day))
        {
            $day = (int)$todo->config->day;
            if($day <= 0) return false;

            /* If no data, judge the interval from the beginning time. */
            if(empty($lastCycle))
            {
                $todayTime = new DateTime($today);
                $beginTime = new DateTime($todo->config->begin);
                $interval  = $todayTime->diff($beginTime)->days;

                if($interval != $day) return false;
                $date = $today;
            }

            /* If data is available, determine the interval of time since the previous cycle. */
            if(!empty($lastCycle->date))
            {
                $todayTime     = new DateTime($today);
                $lastCycleTime = new DateTime($lastCycle->date);
                $interval      = $todayTime->diff($lastCycleTime)->days;

                if($interval != $day) return false;
                $date = date('Y-m-d', strtotime("{$lastCycle->date} +{$day} days"));
            }
        }
        if(isset($todo->config->specifiedDate))
        {
            $date          = $today;
            $specifiedDate = $todo->config->specify->month + 1 . '-' . $todo->config->specify->day;

            /* If not set cycle every year and have data, continue. */
            if(!empty($lastCycle) and !isset($todo->config->cycleYear)) return false;
            /* If set specified date, only judge month and day. */
            if(date('m-d', strtotime($date)) != $specifiedDate) return false;
        }

        return $date;
    }

    /**
     * Set cycle todo data.
     * 设置周期待办数据
     *
     * @param  object $todoData
     * @access private
     * @return false|object
     */
    private function setCycle(object $todoData): false|object
    {
        $todoData->date = helper::today();
        $todoData->config['begin'] = $todoData->date;

        if($todoData->config['type'] == 'day')
        {
            unset($todoData->config['week'], $todoData->config['month']);
            if(!$todoData->config['day'])
            {
                dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->todo->cycleDaysLabel);
                return false;
            }
            if(!validater::checkInt($todoData->config['day']))
            {
                dao::$errors[] = sprintf($this->lang->error->int[0], $this->lang->todo->cycleDaysLabel);
                return false;
            }
        }
        if($todoData->config['type'] == 'week')
        {
            unset($todoData->config['day'], $todoData->config['month']);
            $todoData->config['week'] = join(',', $todoData->config['week']);
        }
        if($todoData->config['type'] == 'month')
        {
            unset($todoData->config['day'], $todoData->config['week']);
            $todoData->config['month'] = join(',', $todoData->config['month']);
        }

        if($todoData->config['beforeDays'] and !validater::checkInt($todoData->config['beforeDays']))
        {
            dao::$errors[] = sprintf($this->lang->error->int[0], $this->lang->todo->beforeDaysLabel);
            return false;
        }
        $todoData->config['beforeDays'] = (int)$todoData->config['beforeDays'];

        $todoData->config = json_encode($todoData->config);
        $todoData->type   = 'cycle';

        return $todoData;
    }

    /**
     * 修改待办事项的时间。
     * Update the date of todo.
     *
     * @param  array  $todoIdList
     * @param  string $date
     * @return bool
     */
    public function updateDate(array $todoIdList, string $date): bool
    {
        $this->dao->update(TABLE_TODO)->set('date')->eq($date)->where('id')->in($todoIdList)->exec();
        return !dao::isError();
    }

    /**
     * 获取用户的待办事项数量。
     * Get todo count on the account.
     *
     * @param  string $account
     * @access protected
     * @return int
     */
    protected function getTodoCountByAccount(string $account): int
    {
        return $this->dao->select('count(*) as count')->from(TABLE_TODO)
            ->where('cycle')->eq('0')
            ->andWhere('deleted')->eq('0')
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('account', true)->eq($account)
            ->orWhere('assignedTo')->eq($account)
            ->orWhere('finishedBy')->eq($account)
            ->markRight(1)
            ->fetch('count');
    }

    /**
     * 根据待办类型设置待办名称。
     * Set todo name by its type.
     *
     * @param  object    $todo
     * @access protected
     * @return object
     */
    protected function setTodoNameByType(object $todo): object
    {
        if($todo->type == 'story')    $todo->name = $this->dao->findByID($todo->objectID)->from(TABLE_STORY)->fetch('title');
        if($todo->type == 'task')     $todo->name = $this->dao->findByID($todo->objectID)->from(TABLE_TASK)->fetch('name');
        if($todo->type == 'bug')      $todo->name = $this->dao->findByID($todo->objectID)->from(TABLE_BUG)->fetch('title');
        if($todo->type == 'testtask') $todo->name = $this->dao->findByID($todo->objectID)->from(TABLE_TESTTASK)->fetch('name');

        if($this->config->edition == 'max')
        {
            if($todo->type == 'risk' )       $todo->name = $this->dao->findByID($todo->objectID)->from(TABLE_RISK)->fetch('name');
            if($todo->type == 'issue')       $todo->name = $this->dao->findByID($todo->objectID)->from(TABLE_ISSUE)->fetch('title');
            if($todo->type == 'review')      $todo->name = $this->dao->findByID($todo->objectID)->from(TABLE_REVIEW)->fetch('title');
            if($todo->type == 'opportunity') $todo->name = $this->dao->findByID($todo->objectID)->from(TABLE_OPPORTUNITY)->fetch('name');
        }

        if($this->config->edition != 'open')
        {
            if($todo->type == 'feedback') $todo->name = $this->dao->findByID($todo->objectID)->from(TABLE_FEEDBACK)->fetch('title');
        }

        return $todo;
    }

    /**
     * 获取待办列表数据。
     * Get the todo list data.
     *
     * @param string       $type
     * @param string       $account
     * @param string|array $status
     * @param string       $begin
     * @param string       $end
     * @param int          $limit
     * @param string       $orderBy
     * @param object       $pager
     * @access protected
     * @return array
     */
    protected function getListBy(string $type, string $account, array|string $status, string $begin, string $end, int $limit, string $orderBy, object $pager = null): array
    {
        return $this->dao->select('*')->from(TABLE_TODO)
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
            ->fetchAll();
    }
}
