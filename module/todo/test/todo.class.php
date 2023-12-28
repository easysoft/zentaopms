<?php
class todoTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('todo');
        $tester->app->loadClass('dao');
    }

    /**
     * Test create a todo.
     *
     * @param  object $todoData
     * @access public
     * @return int
     */
    public function createTest($todoData)
    {
        $objectID = $this->objectModel->create($todoData);

        return $objectID ?: 0;
    }

    /**
     * Test batch create todos.
     *
     * @param  array  $datas
     * @access public
     * @return array|int
     */
    public function batchCreateTest($datas)
    {
        $todos = array();
        foreach($datas['names'] as $key => $name)
        {
            $todo = new stdclass();
            $todo->name       = $name;
            $todo->type       = $datas['types'][$key];
            $todo->pri        = $datas['pris'][$key];
            $todo->desc       = $datas['descs'][$key];
            $todo->begin      = $datas['begins'][$key];
            $todo->end        = $datas['ends'][$key];
            $todo->assignedTo = $datas['assignedTos'][$key];
            $todo->date       = $datas['date'];
            $todos[] = $todo;
        }
        $todoIDList = $this->objectModel->batchCreate($todos);

        if(dao::isError()) return 0;
        return $todoIDList;
    }

    /**
     * Test update a todo.
     *
     * @param  int    $todoID
     * @param  array  $param
     * @access public
     * @return array
     */
    public function updateTest(int $todoID, array $param)
    {
        global $tester;
        $object = $tester->dbh->query("SELECT * FROM " . TABLE_TODO ." WHERE id = $todoID")->fetch();

        $todo = new stdclass();
        foreach($object as $field => $value)
        {
            if(in_array($field, array_keys($param))) $todo->$field = $param[$field];
        }

        $change = $this->objectModel->update($todoID, $todo);
        if($change == array()) $change = '没有数据更新';

        unset($_POST);
        if(dao::isError()) return dao::getError();

        return $change;
    }

    /**
     * Test batch update todos.
     *
     * @param  array  $param
     * @param  int    $todoID
     * @access public
     * @return array
     */
    public function batchUpdateTest(array $todos, int $todoID)
    {
        $todoIDList = array($todoID);
        $changes = $this->objectModel->batchUpdate($todos, $todoIDList);

        if(dao::isError()) return dao::getError();

        return $changes[$todoID];
    }

    /**
     * Test start a todo.
     *
     * @param  int    $todoID
     * @access public
     * @return object
     */
    public function startTest($todoID)
    {
        $this->objectModel->start($todoID);
        $object = $this->objectModel->getByID($todoID);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * 测试删除待办.
     * Test delete a todo.
     *
     * @param  int     $todoID
     * @param  string  $confirm yes|no
     * @access public
     * @return object|false
     */
    public function deleteTest(int $todoID, string $confirm = 'no'): object|false
    {
        if($confirm == 'no')
        {
            return $this->objectModel->getById($todoID);
        }
        else
        {
            $this->objectModel->delete(TABLE_TODO, $todoID);
            if(dao::isError()) return false;

            return $this->objectModel->getById($todoID);
        }
    }

    /**
     * 测试完成待办.
     * Test finish a todo.
     *
     * @param  int     $todoID
     * @access public
     * @return object|false
     */
    public function finishTest(int $todoID): object|false
    {
        $this->objectModel->finish($todoID);
        $object = $this->objectModel->getByID($todoID);

        if(dao::isError()) return false;

        return $object;
    }

    /**
     * 测试批量完成待办.
     * Batch finish todos.
     *
     * @param  array   $todoIDList
     * @access public
     * @return bool
     */
    public function batchFinishTest(array $todoIDList): bool
    {
        return $this->objectModel->batchFinish($todoIDList);
    }

    /**
     * Test get info of a todo.
     *
     * @param  int    $todoID
     * @param  bool   $setImgSize
     * @access public
     * @return object
     */
    public function getByIdTest($todoID, $setImgSize = false)
    {
        $object = $this->objectModel->getById($todoID);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Test get todo list of a user.
     *
     * @param  string $type
     * @param  string $account
     * @param  string $status
     * @param  int    $limit
     * @param  object $pager
     * @param  string $orderBy
     * @access public
     * @return int
     */
    public function getListTest($type = 'today', $account = '', $status = 'all', $limit = 0, $pager = null, $orderBy = "date, status, begin")
    {
        $objects = $this->objectModel->getList($type, $account, $status, $limit, $pager, $orderBy);

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    /**
     * Test todoModel::getByList.
     *
     * @param array  $todoIDList
     * @access public
     * @return string
     */
    public function getByListTest($todoIDList = 0)
    {
        $objects = $this->objectModel->getByList($todoIDList);
        $result = '';
        foreach($objects as $id => $todo)
        {
            $result .= (string) $todo->id;
        }
        if(empty($result)) return "pass";
        return $result;
    }

    /**
     * isClickableTest
     *
     * @param  object $todo
     * @param  string $action
     * @access public
     * @return int
     */
    public function isClickableTest($todo, $action)
    {
        $object = $this->objectModel->isClickable($todo, $action);

        if(dao::isError()) return dao::getError();

        return $object ? 1 : 2;
    }

    /**
     * 创建周期的待办。
     * Create by cycle test.
     *
     * @access public
     * @return int
     */
    public function createByCycleTest(): int
    {
        $todoList = $this->objectModel->getValidCycleList();
        $this->objectModel->createByCycle($todoList);

        global $tester;
        $todoIDList = array_keys($todoList);
        $count      = $tester->dao->select('count(`id`) as count')->from(TABLE_TODO)->where('objectID')->in($todoIDList)->andWhere('deleted')->eq('0')->fetch('count');

        if(dao::isError()) return 0;
        return $count > 0 ? 1 : 0;
    }

    /**
     * Test activate todo.
     *
     * @param  int    $todoID
     * @access public
     * @return object
     */
    public function activateTest($todoID)
    {
        $this->objectModel->activate($todoID);

        if(dao::isError()) return dao::getError();

        $object = $this->objectModel->getById($todoID);
        return $object;
    }

    /**
     * Test close a todo.
     *
     * @param  int    $todoID
     * @access public
     * @return object
     */
    public function closeTest($todoID)
    {
        $this->objectModel->close($todoID);

        if(dao::isError()) return dao::getError();

        $object = $this->objectModel->getById($todoID);
        return $object;
    }

    /**
     * 测试指派待办.
     * Test assign todo.
     *
     * @param  int     $todoID
     * @param  object  $param
     * @access public
     * @return object
     */
    public function assignToTest(int $todoID, object $param = new stdclass()): object
    {
        $todo = new stdclass();
        $todo->assignedDate = helper::now();
        $todo->begin        = 0;
        $todo->end          = 0;

        foreach($param as $key => $value)
        {
            $todo->{$key} = $value;
            if($key == 'future' && $value == 'on')
            {
                $todo->date = '2030-01-01';
                unset($todo->{$key});
            }
            if($key == 'lblDisableDate' && $value == 'on')
            {
                $todo->begin = '2400';
                $todo->end   = '2400';
                unset($todo->{$key});
            }
        }

        $todo->id = $todoID;
        $this->objectModel->assignTo($todo);

        $object = $this->objectModel->getById($todoID);
        return $object;
    }

    /**
     * Test get todo count.
     *
     * @param  string $account
     * @access public
     * @return int
     */
    public function getCountTest($account = '')
    {
        $count = $this->objectModel->getCount($account);

        if(dao::isError()) return dao::getError();

        return $count;
    }

    /**
     * 修改待办事项时间。
     * Edit todo date.
     *
     * @param  array  $todoIDList
     * @param  string $date
     * @access public
     * @return string
     */
    public function editDateTest(array $todoIDList, string $date): string
    {
        $result = $this->objectModel->editDate($todoIDList, $date);
        return $result ? '1' : '0';
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
    public function getByExportListTest(string $orderBy, string $queryCondition, string $checkedItem): array
    {
        return $this->objectModel->getByExportList($orderBy, $queryCondition, $checkedItem);
    }

    /**
     * 根据待办类型，对象ID获取优先级。
     * Get pri by todo type and object id.
     *
     * @param  string $todoType
     * @param  int    $objectID
     * @access public
     * @return int
     */
    public function getPriByTodoTypeTest(string $todoType, int $objectID): int
    {
        $pri = $this->objectModel->getPriByTodoType($todoType, $objectID);

        if(dao::isError()) return 0;

        return $pri ? 1 : 0;
    }

    /**
     * 根据周期待办，获取这些待办生成的待办数据。
     * Get created cycle list by todo list.
     *
     * @param  bool    $initCycle
     * @access public
     * @return int
     */
    public function getCycleListTest(bool $initCycle = true): int
    {
        $todoList = $this->objectModel->getValidCycleList();

        if($initCycle) $this->objectModel->createBycycle($todoList);

        $cycleList = $this->objectModel->getCycleList($todoList);

        return count($cycleList) > 0 ? 1 : 0;
    }

    /**
     * 测试Tao层中的关闭待办函数。
     * Test function to close one todo in Tao level.
     *
     * @param  int $todoID
     * @access public
     * @return object
     */
    public function closeTodoTest(int $todoID): object
    {
        $oldTodo  = $this->objectModel->getById($todoID);
        $isClosed = $this->objectModel->closeTodo($todoID);
        $newTodo  = $this->objectModel->getById($todoID);

        $testResult = new stdclass();
        $testResult->oldStatus = $oldTodo->status;
        $testResult->newStatus = $newTodo->status;
        $testResult->isClosed  = $isClosed;

        return $testResult;
    }

    /**
     * 获取批量创建待办的有效数据。
     * Get valid todo list of batch create.
     *
     * @param  array   $todos
     * @param  int     $loop
     * @param  string  $assignedTo
     * @access public
     * @return int
     */
    public function getValidsOfBatchCreateTest(array $todos, int $loop, string $assignedTo): int
    {
        $todos = json_decode(json_encode($todos));
        $todo  = $this->objectModel->getValidsOfBatchCreate($todos, $loop, $assignedTo);

        if(dao::isError()) return 0;

        return empty($todo) ? 0 : 1;
    }

    /**
     * 根据配置类型获取周期待办的日期。
     * Get cycle todo date by config type.
     *
     * @param  string   $configType
     * @access public
     * @return bool|string
     */
    public function getCycleTodoDateTest(string $configType): bool|string
    {
        global $tester;
        $typeMap  = array('day' => 1, 'week' => 2, 'month' => 3);
        $todoList = $tester->dao->select('*')->from(TABLE_TODO)->where('id')->eq($typeMap[$configType])->fetchAll('id');

        $todo = current($todoList);
        $todo->config = json_decode($todo->config);

        $today     = date('Y-m-d');
        $cycleList = $this->objectModel->getCycleList($todoList);
        $lastCycle = zget($cycleList, $todo->id, '');

        $date = $this->objectModel->getCycleTodoDate($todo, $lastCycle, $today);

        if($configType == 'day')   return $date == false;
        if($configType == 'week')  return $date == $today;
        if($configType == 'month') return $date == $today;

        return $date;
    }
}
