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
     * @param  object $formData
     * @access public
     * @return int
     */
    public function createTest($todoData, $formData)
    {
        $objectID = $this->objectModel->create($todoData, $formData);

        return $objectID ?: 0;
    }

    /**
     * Test batch create todos.
     *
     * @param  array $param
     * @access public
     * @return array
     */
    public function batchCreateTest($param = array())
    {
        $createFields['date'] = date('Y-m-d',time());

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;

        foreach($param as $key => $value) $_POST[$key] = $value;

        $objects = $this->objectModel->batchCreate();

        $todos = $this->objectModel->getByList($objects);

        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $todos;
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
        $todoIDList = array($todoID => $todoID);
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

    public function createByCycleTest($todo)
    {
        $todo->cycle        = '1';
        $todo->type         = 'cycle';
        $todo->pri          = 3;
        $todo->desc         = '';
        $todo->status       = 'wait';
        $todo->begin        = '0830';
        $todo->end          = '0900';
        $todo->account      = 'admin';
        $todo->objectID     = '0';
        $todo->vision       = 'rnd';
        $todo->assignedTo   = 'admin';
        $todo->assignedBy   = 'admin';
        $todo->assignedDate = date('Y-m-d', time());
        $todo->date         = date('Y-m-d', time());

        $todo->config = str_replace('2022-03-23', date('Y-m-d', time()), $todo->config);

        global $tester;
        $tester->dao->insert(TABLE_TODO)->data($todo)->autoCheck()->exec();
        $todoID = $tester->dao->lastInsertID();

        $this->objectModel->createByCycle(array($todoID => $todo));

        $objects = $tester->dao->select('id')->from(TABLE_TODO)->where('objectID')->eq($todoID)->andWhere('deleted')->eq('0')->fetchAll();

        if(dao::isError()) return dao::getError();

        return count($objects);
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
