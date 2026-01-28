<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class todoTaoTest extends baseTest
{
    protected $moduleName = 'todo';
    protected $className  = 'tao';

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
     * @return bool
     */
    public function createByCycleTest()
    {
        $todoList = $this->objectModel->getValidCycleList();
        $this->objectModel->createByCycle($todoList);

        return true;
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

        return $pri;
    }

    /**
     * 获取周期待办列表。
     * Get created cycle list by todo list.
     *
     * @param  bool    $initCycle
     * @access public
     * @return int
     */
    public function getCycleListTest(bool $initCycle = true): array
    {
        $todoList = $this->objectModel->getValidCycleList();

        if($initCycle) $this->objectModel->createBycycle($todoList);

        $cycleList = $this->objectModel->getCycleList($todoList);

        return $cycleList;
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
     * @return int|object
     */
    public function getValidsOfBatchCreateTest(array $todos, int $loop, string $assignedTo): int|object
    {
        $todos = json_decode(json_encode($todos));
        $todo  = $this->objectModel->getValidsOfBatchCreate($todos, $loop, $assignedTo);

        if(dao::isError()) return 0;

        return empty($todo) ? 0 : $todo;
    }

    /**
     * 根据配置类型获取周期待办的日期。
     * Get cycle todo date by config type.
     *
     * @param  string   $configType
     * @access public
     * @return bool|string
     */
    public function getCycleTodoDateTest(string $configType, int $todoId = 0, string $lastCycleDate = '', string $today = ''): mixed
    {
        global $tester;

        // 如果没有指定todoId，使用默认映射
        if($todoId == 0)
        {
            $typeMap = array('day' => 1, 'week' => 2, 'month' => 3);
            $todoId = $typeMap[$configType];
        }

        $todoList = $tester->dao->select('*')->from(TABLE_TODO)->where('id')->eq($todoId)->fetchAll('id');
        $todo = current($todoList);
        if(!$todo) return false;

        $todo->config = json_decode($todo->config);
        if(dao::isError()) return dao::getError();
        if(!$todo->config) return 'config_decode_error';

        // 如果没有指定today，使用当前日期
        if(empty($today)) $today = date('Y-m-d');

        // 构造lastCycle对象
        $lastCycle = '';
        if(!empty($lastCycleDate))
        {
            $lastCycle = new stdClass();
            $lastCycle->date = $lastCycleDate;
        }

        $date = $this->objectModel->getCycleTodoDate($todo, $lastCycle, $today);

        // 对于按天类型，返回0表示false，1表示有日期
        if($configType == 'day')
        {
            return $date === false ? '0' : ($date ? '1' : '0');
        }

        // 对于其他类型，直接返回日期或空字符串
        return $date === false ? '0' : ($date ? $date : '');
    }

    /**
     * Test getCycleTodoDate method with simple approach.
     *
     * @param  string $configType
     * @param  int    $todoId
     * @access public
     * @return string
     */
    public function getCycleTodoDateTestSimple(string $configType, int $todoId): string
    {
        global $tester;
        $todoList = $tester->dao->select('*')->from(TABLE_TODO)->where('id')->eq($todoId)->fetchAll('id');

        $todo = current($todoList);
        if(!$todo) return '0';

        $todo->config = json_decode($todo->config);
        if(!$todo->config) return '0';

        $today     = date('Y-m-d');
        $cycleList = $this->objectModel->getCycleList($todoList);
        $lastCycle = zget($cycleList, $todo->id, '');

        $date = $this->objectModel->getCycleTodoDate($todo, $lastCycle, $today);

        if($configType == 'day')   return $date === false ? '0' : '1';
        if($configType == 'week')  return $date == $today ? '1' : '0';
        if($configType == 'month') return $date == $today ? '1' : '0';

        return $date ? '1' : '0';
    }

    /**
     * Test getCycleTodoDate method with edge cases.
     *
     * @param  string $caseType
     * @access public
     * @return string
     */
    public function getCycleTodoDateTestEdgeCase(string $caseType): string
    {
        global $tester;

        switch($caseType)
        {
            case 'valid_empty_result':
                // 创建一个有效但会返回空结果的配置（周类型，但今天不匹配）
                $todo = new stdClass();
                $todo->config = new stdClass();
                $todo->config->type = 'week';
                $todo->config->week = '0,6'; // 只在周日和周六生效
                $today = date('Y-m-d'); // 假设今天不是周日或周六
                $date = $this->objectModel->getCycleTodoDate($todo, '', $today);
                return empty($date) ? '0' : '1';

            case 'invalid_type':
                // 创建一个无效类型的todo对象
                $todo = new stdClass();
                $todo->config = new stdClass();
                $todo->config->type = 'invalid';
                $date = $this->objectModel->getCycleTodoDate($todo, '', date('Y-m-d'));
                return empty($date) ? '0' : '1';

            case 'empty_lastcycle':
                // 使用第一个todo但空lastCycle
                $todoList = $tester->dao->select('*')->from(TABLE_TODO)->where('id')->eq(1)->fetchAll('id');
                $todo = current($todoList);
                $todo->config = json_decode($todo->config);
                $date = $this->objectModel->getCycleTodoDate($todo, '', date('Y-m-d'));
                return $date === false ? '0' : '1';

            case 'past_config':
                // 创建一个过期的配置
                $todo = new stdClass();
                $todo->config = new stdClass();
                $todo->config->type = 'day';
                $todo->config->day = '1';
                $todo->config->begin = '2020-01-01';
                $todo->config->end = '2020-12-31';
                $date = $this->objectModel->getCycleTodoDate($todo, '', date('Y-m-d'));
                return $date === false ? '0' : '1';

            default:
                return '0';
        }
    }

    /**
     * 测试Tao层中的创建待办函数。
     * Test function to create one todo in Tao level.
     *
     * @param  object $todo
     * @access public
     * @return array|int
     */
    public function insertTest(object $todo): array|int
    {
        $result = $this->objectModel->insert($todo);
        return dao::isError() ? dao::getError() : $result;
    }

    /**
     * Test dateRange method.
     *
     * @param  string $type
     * @access public
     * @return array
     */
    public function dateRangeTest(string $type): array
    {
        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('dateRange');
        $method->setAccessible(true);

        $result = $method->invoke($this->objectModel, $type);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getCycleDailyTodoDate method.
     *
     * @param  object $todo
     * @param  object|string $lastCycle
     * @param  string $today
     * @access public
     * @return false|string
     */
    public function getCycleDailyTodoDateTest(object $todo, object|string $lastCycle, string $today): false|string
    {
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('getCycleDailyTodoDate');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance, $todo, $lastCycle, $today);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test fetchRows method.
     *
     * @param  array $todoIdList
     * @access public
     * @return array|int
     */
    public function fetchRowsTest(array $todoIdList): array|int
    {
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('fetchRows');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance, $todoIdList);
        if(dao::isError()) return dao::getError();

        // 如果结果为空数组，返回0以便于测试断言
        if(empty($result)) return 0;

        return $result;
    }

    /**
     * Test getCycleList method in Tao layer.
     *
     * @param  array $todoList
     * @param  string $orderBy
     * @access public
     * @return array|int
     */
    public function getCycleListTaoTest(array $todoList, string $orderBy = 'date_asc'): array|int
    {
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('getCycleList');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance, $todoList, $orderBy);
        if(dao::isError()) return dao::getError();

        // 如果结果为空数组，返回0以便于测试断言
        if(empty($result)) return 0;

        return $result;
    }

    /**
     * Test getListBy method.
     *
     * @param  string       $type
     * @param  string       $account
     * @param  string|array $status
     * @param  string       $begin
     * @param  string       $end
     * @param  int          $limit
     * @param  string       $orderBy
     * @param  object       $pager
     * @access public
     * @return array
     */
    public function getListByTest(string $type, string $account, array|string $status, string $begin, string $end, int $limit, string $orderBy, ?object $pager = null): array
    {
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('getListBy');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance, $type, $account, $status, $begin, $end, $limit, $orderBy, $pager);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getProjectList method.
     *
     * @param  string $table
     * @param  array  $idList
     * @access public
     * @return array|int
     */
    public function getProjectListTest(string $table, array $idList): array|int
    {
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('getProjectList');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance, $table, $idList);
        if(dao::isError()) return dao::getError();

        // 如果结果为空数组，返回0以便于测试断言
        if(empty($result)) return 0;

        return $result;
    }

    /**
     * Test getTodoCountByAccount method.
     *
     * @param  string $account
     * @access public
     * @return int
     */
    public function getTodoCountByAccountTest(string $account): int
    {
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('getTodoCountByAccount');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance, $account);
        if(dao::isError()) return 0;

        return (int)$result;
    }

    /**
     * Test setTodoNameByType method.
     *
     * @param  int $todoID
     * @access public
     * @return object
     */
    public function setTodoNameByTypeTest(int $todoID): object
    {
        $todo = $this->objectModel->getByID($todoID);
        if(!$todo) return new stdclass();

        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('setTodoNameByType');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance, $todo);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test updateDate method.
     *
     * @param  array  $todoIdList
     * @param  string $date
     * @access public
     * @return bool|array
     */
    public function updateDateTest(array $todoIdList, string $date): bool|array
    {
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('updateDate');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance, $todoIdList, $date);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test updateRow method.
     *
     * @param  int    $todoID
     * @param  object $todo
     * @access public
     * @return bool|array
     */
    public function updateRowTest(int $todoID, object $todo): bool|array
    {
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('updateRow');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance, $todoID, $todo);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
