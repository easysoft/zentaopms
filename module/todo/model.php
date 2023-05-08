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
     * @access public
     * @return int|false
     */
    public function create(object $todo): int|false
    {
        $todoID = $this->todoTao->insert($todo);
        if(dao::isError()) return false;

        return $todoID;
    }

    /**
     * 批量创建待办。
     * Batch create todo.
     *
     * @param  object $todos
     * @access public
     * @return array|false
     */
    public function batchCreate(object $todos): array|false
    {
        $validTodos = array();
        $assignedTo = $this->app->user->account;

        for($loop = 0; $loop < $this->config->todo->maxBatchCreate; $loop++)
        {
            $isExist    = false;
            $assignedTo = $todos->assignedTos[$loop] == 'ditto' ? $assignedTo : $todos->assignedTos[$loop];
            foreach($this->config->todo->objectList as $objects)
            {
                if(isset($todos->{$objects}[$loop + 1]))
                {
                    $isExist = true;
                    break;
                }
            }

            if($todos->names[$loop] != '' || $isExist)
            {
                $todo = $this->todoTao->getValidsOfBatchCreate($todos, $loop, $assignedTo);
                if(dao::isError()) return false;

                $validTodos[] = $todo;
            }
            else
            {
                unset($todos->types[$loop], $todos->pris[$loop], $todos->names[$loop], $todos->descs[$loop], $todos->begins[$loop], $todos->ends[$loop]);
            }
        }

        $todoIdList = array();
        foreach($validTodos as $todo)
        {
            $todoID = $this->todoTao->insert($todo);
            if(!$todoID) return false;

            $todoIdList[] = $todoID;
            $this->loadModel('score')->create('todo', 'create', $todoID);
            $this->loadModel('action')->create('todo', $todoID, 'opened');
        }

        return $todoIdList;
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
        if($this->config->edition != 'open' && $todo->type == 'feedback' && $todo->objectID) $this->loadModel('feedback')->updateStatus('todo', $todo->objectID, $todo->status);
        return common::createChanges($oldTodo, (array)$todo);
    }

    /**
     * 更新批量编辑待办数据。
     * Update batch edit todo data.
     *
     * @param array $todos
     * @param array $todoIdList
     * @access public
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
            $this->todoTao->updateRow($todoID, $todo);

            if($oldTodo->status != 'done' and $todo->status == 'done') $this->loadModel('action')->create('todo', $todoID, 'finished', '', 'done');

            if(!dao::isError())
            {
                if($this->config->edition != 'open' && $todo->type == 'feedback' && $todo->objectID && !isset($feedbacks[$todo->objectID]))
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
     * 完成一个待办。
     * Finish one todo.
     *
     * @param  int     $todoID
     * @access public
     * @return bool
     */
    public function finish(int $todoID): bool
    {
        $todo = new stdClass();
        $todo->id           = $todoID;
        $todo->status       = 'done';
        $todo->finishedBy   = $this->app->user->account;
        $todo->finishedDate = helper::now();
        $this->todoTao->updateRow($todoID, $todo);

        if(dao::isError()) return false;

        $this->loadModel('action')->create('todo', $todoID, 'finished', '', 'done');

        if($this->config->edition != 'open')
        {
            $todo       = $this->todoTao->fetch($todoID);
            $feedbackID = $todo->objectID ? $todo->objectID : '' ;
            if($feedbackID) $this->loadModel('feedback')->updateStatus('todo', $feedbackID, 'done');
        }
        return true;
    }

    /**
     * 批量完成待办。
     * Batch finish todos.
     *
     * @param  int[]   $todoIdList
     * @access public
     * @return bool
     */
    public function batchFinish(array $todoIdList): bool
    {
        foreach($todoIdList as $todoID)
        {
            $isFinished = $this->finish($todoID);
            if(!$isFinished) return $isFinished;
        }
        return true;
    }

    /**
     * 获取待办事项详情数据。
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
        $todo->date = str_replace('-', '', $todo->date);

        return $this->todoTao->setTodoNameByType($todo);
    }

    /**
     * Get todo list of a user.
     *
     * @param  string  $type
     * @param  string  $account
     * @param  string|array $status   all|today|thisweek|lastweek|before, or a date.
     * @param  int     $limit
     * @param  ?object $pager
     * @param  string  $orderBy
     * @access public
     * @return array
     */
    public function getList(string $type = 'today', string $account = '', string|array $status = 'all', int $limit = 0, object $pager = null, string $orderBy="date, status, begin"): array
    {
        $type  = strtolower($type);

        $dateRange = $this->dateRange($type);

        if(empty($account)) $account = $this->app->user->account;

        $todos = $this->todoTao->getListBy($type, $account, $status, (string)$dateRange['begin'], (string)$dateRange['end'], $limit, $orderBy, $pager);

        /* Set session. */
        $sql = explode('WHERE', $this->dao->get());
        $sql = explode('ORDER', $sql[1]);
        $this->session->set('todoReportCondition', $sql[0]);

        foreach($todos as $todo)
        {
            $todo = $this->todoTao->setTodoNameByType($todo);
            $todo->begin = date::formatTime($todo->begin);
            $todo->end   = date::formatTime($todo->end);

            if($todo->private and $this->app->user->account != $todo->account) $todo->name = $this->lang->todo->thisIsPrivate;
        }

        return $todos;
    }

    /**
     * 根据类型获取时间范围。
     * Date range.
     *
     * @param  string    $type
     * @access protected
     * @return array
     */
    protected function dateRange($type): array
    {
        $this->app->loadClass('date');
        $dateRange['all']             = array('begin' => '1970-01-01',  'end' => '2109-01-01');
        $dateRange['assignedtoother'] = array('begin' => '1970-01-01',  'end' => '2109-01-01');
        $dateRange['today']           = array('begin' => date::today(), 'end' => date::today());
        $dateRange['future']          = array('begin' => '2030-01-01',  'end' => '2030-01-01');
        $dateRange['before']          = array('begin' => '1970-01-01',  'end' => date::today());
        $dateRange['cycle']           = array('begin' => '', 'end' => '');
        $dateRange['yesterday']       = array('begin' => date::yesterday(), 'end' => date::yesterday());
        $dateRange['thisweek']        = array('begin' => date::getThisWeek()['begin'],   'end' => date::getThisWeek()['end']);
        $dateRange['lastweek']        = array('begin' => date::getLastWeek()['begin'],   'end' => date::getLastWeek()['end']);
        $dateRange['thismonth']       = array('begin' => date::getThisMonth()['begin'],  'end' => date::getThisMonth()['end']);
        $dateRange['lastmonth']       = array('begin' => date::getLastMonth()['begin'],  'end' => date::getLastMonth()['end']);
        $dateRange['thisseason']      = array('begin' => date::getThisSeason()['begin'], 'end' => date::getThisSeason()['end']);
        $dateRange['thisyear']        = array('begin' => date::getThisYear()['begin'],   'end' => date::getThisYear()['end']);

        return isset($dateRange[$type]) ? $dateRange[$type] : array('begin' => $type, 'end' => $type);
    }

    /**
     * 通过包含一个或多个待办ID的列表获取待办列表，这个列表是以todoID为key、以todo对象为value的数组。
     * 如果待办ID列表为空则返回所有待办。
     * Get a array with todos which todoID as key, todo object as value by todo id list.
     * Return all todos if the todo id list is empty.
     *
     * @param  array $todoIdList
     * @access public
     * @return array
     */
    public function getByList($todoIdList = 0): array
    {
        return $this->dao->select('*')->from(TABLE_TODO)
            ->beginIF($todoIdList)->where('id')->in($todoIdList)->fi()
            ->fetchAll('id');
    }

    /**
     * 判断当前动作是否可以点击。
     * Judge an action is clickable or not.
     *
     * @param  object $todo
     * @param  string $action
     * @access public
     * @return bool
     */
    public static function isClickable(object $todo, string $action): bool
    {
        $action = strtolower($action);

        if($action == 'finish' || $action == 'start')
        {
            if(!empty($todo->cycle)) return false;
            return $action == 'finish' ? ($todo->status != 'done') : ($todo->status == 'wait');
        }

        return true;
    }

    /**
     * 根据周期待办创建待办。
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
            if($today < $begin || (!empty($end) && $today > $end)) continue;

            $newTodo = $this->todoTao->buildCycleTodo($todo);
            if(isset($todo->assignedTo) && $todo->assignedTo) $newTodo->assignedDate = $now;

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

                $todoID = $this->todoTao->insert($newTodo);
                if($todoID) $this->action->create('todo', $todoID, 'opened', '', '', $newTodo->account);
            }
        }
    }

    /**
     * 激活待办事项。
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

        if($this->config->edition != 'open')
        {
            $feedbackID = $this->dao->select('objectID')->from(TABLE_TODO)->where('id')->eq($todoID)->andWhere('type')->eq('feedback')->fetch('objectID');
            if($feedbackID) $this->loadModel('feedback')->updateStatus('todo', $feedbackID, 'closed');
        }
        return true;
    }

    /**
     * 指派待办。
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
        $count = $this->todoTao->getCountByAccount($account, $this->config->vision);
        if(dao::isError()) return 0;
        return $count;
    }

    /**
     * 根据类型获取各个模块的列表。
     * Gets the project ID of the to-do object.
     *
     * @param  array $todoList
     * @access public
     * @return array
     */
    public function getTodoProjects(array $todoList): array
    {
        $projectIDList = array();
        $projects      = $this->config->todo->project;
        foreach($todoList as $type => $todos)
        {
            $todoIdList = array_keys($todos);
            if(empty($todoIdList))
            {
                $projectIDList[$type] = array();
                continue;
            }

            $todoIdList = array_unique($todoIdList);

            if(isset($projects[$type])) $projectIDList[$type] = $this->todoTao->getProjectList($projects[$type], $todoIdList);
            if(dao::isError()) return array();
        }

        return $projectIDList;
    }

    /**
     * 修改待办事项的时间。
     * Edit the date of todo.
     *
     * @param  array  $todoIdList
     * @param  string $date
     * @access public
     * @return bool
     */
    public function editDate(array $todoIdList, string $date): bool
    {
        return $this->todoTao->updateDate($todoIdList, $date);
    }

    /**
     * 获取导出的待办数据。
     * Get data for export todo.
     *
     * @param  string $orderBy
     * @param  string $queryCondition
     * @param  string $checkedItem
     * @access public
     * @return array
     */
    public function getByExportList(string $orderBy, string $queryCondition, string $checkedItem): array
    {
        return $this->dao->select('*')->from(TABLE_TODO)
            ->where($queryCondition)
            ->beginIF($checkedItem)->andWhere('id')->in($checkedItem)->fi()
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
        return $this->dao->select('*')->from(TABLE_TODO)->where('id')->in(array_values($todoIdList))->fetchAll('id');
    }

    /**
     * 获取所有有效的周期待办列表。
     * Get valid cycle todo list.
     *
     * @access public
     * @return array
     */
    public function getValidCycleList(): array
    {
        return $this->dao->select('*')->from(TABLE_TODO)->where('cycle')->eq(1)->andWhere('deleted')->eq(0)->fetchAll('id');
    }

    /**
     * 根据待办类型获取优先级。
     * Get pri by todo type.
     *
     * @param  string $todoType
     * @param  int    $todoObjectID
     * @access public
     * @return int
     */
    public function getPriByTodoType(string $todoType, int $todoObjectID): int
    {
        if(!isset($this->config->objectTables[$todoType])) return $this->config->todo->defaultPri;

        $pri = $this->dao->select('pri')->from($this->config->objectTables[$todoType])->where('id')->eq($todoObjectID)->fetch('pri');
        return $pri ?: $this->config->todo->defaultPri;
    }
}
