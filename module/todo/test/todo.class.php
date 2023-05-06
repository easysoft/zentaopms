<?php
class todoTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('todo');
        $tester->app->loadClass('dao');
        $_SERVER['HTTP_HOST'] = 'test.zentao.net';
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
     * @param  array  $todos
     * @param  object $formData
     * @access public
     * @return array|int
     */
    public function batchCreateTest($todos, $formData)
    {
        $todos      = json_decode(json_encode($todos));
        $todoIDList = $this->objectModel->batchCreate($todos, $formData);

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

        $todo = new stdClass();
        foreach($object as $field => $value)
        {
            if(in_array($field, array_keys($param)))
            {
                $todo->$field = $param[$field];
            }
            else
            {
                $todo->$field = $value;
            }
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
     * @param string $type
     * @param string $account
     * @param string $status
     * @param int $limit
     * @param mixed $pager
     * @param string $orderBy
     * @param status $status
     * @param begin" $begin"
     * @access public
     * @return void
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
     * Create by cycle test.
     *
     * @access public
     * @return int
     */
    public function createByCycleTest()
    {
        $todoList = $this->objectModel->getValidCycleList();
        $this->objectModel->createByCycle($todoList);

        global $tester;
        $todoIDList = array_keys($todoList);
        $count      = $tester->dao->select('count(`id`) as count')->from(TABLE_TODO)->where('objectID')->in($todoIDList)->andWhere('deleted')->eq('0')->fetch('count');

        return dao::isError() ? 0 :1;
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
		$todo = new stdClass();
		$todo->assignedDate = helper::now();
		$todo->date         = '';
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
    public function editDateTest(array $todoIDList, string $date)
    {
	$result = $this->objectModel->editDate($todoIDList, $date);
	return $result ? '1' : '0';
    }
}
