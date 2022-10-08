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
     * @param  string $account
     * @param  array $param
     * @access public
     * @return object
     */
    public function createTest($account, $param = array())
    {
        $config = array('day' => '', 'specify' => array('month' => 0, 'day' => 1), 'type' => 'day', 'beforeDays' => 0, 'end' => '');
        if(isset($param->date)) $param->date = $param->date == 'today' ? date('Y-m-d',time()) : date('Y-m-d',strtotime('+3 days'));

        $createFields['config'] = $config;
        $createFields['type']   = 'custom';
        $createFields['name']   = '';
        $createFields['pri']    = 3;
        $createFields['desc']   = '';
        $createFields['status'] = 'wait';
        $createFields['begin']  = '0830';
        $createFields['end']    = '0900';

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;

        foreach($param as $key => $value) $_POST[$key] = $value;

        $objectID = $this->objectModel->create(date('Y').date('m'), $account);

        unset($_POST);

        if(dao::isError()) return array_values(dao::getError())[0][0];

        $object = $objectID ? $this->objectModel->getByID($objectID) : 0;
        return $object;
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
    public function updateTest($todoID, $param)
    {
        global $tester;
        $object = $tester->dbh->query("SELECT * FROM " . TABLE_TODO ." WHERE id = $todoID")->fetch();

        foreach($object as $field => $value)
        {
            if(in_array($field, array_keys($param)))
            {
                $_POST[$field] = $param[$field];
            }
            else
            {
                $_POST[$field] = $value;
            }
        }

        $change = $this->objectModel->update($todoID);
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
    public function batchUpdateTest($param, $todoID)
    {
        $todoIDList = array('1' => '1', '2' => '2', '3' => '3');
        $dates      = array('1' => date('Y-m-d',strtotime('+1 month')), '2' => date('Y-m-d',strtotime('-1 month +1 day')), '3' => date('Y-m-d',strtotime('-1 month +2 day')));
        $types      = array('1' => 'custom', '2' => 'bug', '3' => 'task');
        $pris       = array('1' => '1', '2' => '2', '3' => '3');
        $names      = array('1' => '自定义1的待办', '2' => 'BUG2的待办', '3' => '任务3的待办');
        $descs      = array('1' => '这是一个待办的描述1', '2' => '这是一个待办的描述2', '3' => '这是一个待办的描述3');
        $begins     = array('1' => '1000', '2' => '1002', '3' => '1004');
        $ends       = array('1' => '1400', '2' => '1402', '3' => '1404');
        $status     = array('1' => 'wait', '2' => 'doing', '3' => 'done');

        $batchUpdateFields['todoIDList'] = $todoIDList;
        $batchUpdateFields['dates']      = $dates;
        $batchUpdateFields['types']      = $types;
        $batchUpdateFields['pris']       = $pris;
        $batchUpdateFields['names']      = $names;
        $batchUpdateFields['descs']      = $descs;
        $batchUpdateFields['begins']     = $begins;
        $batchUpdateFields['ends']       = $ends;
        $batchUpdateFields['status']     = $status;

        foreach($batchUpdateFields as $field => $value) $_POST[$field] = $value;

        foreach($param as $key => $value) $_POST[$key] = $value;

        $changes = $this->objectModel->batchUpdate();

        unset($_POST);

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
     * Test finish a todo.
     *
     * @param  int    $todoID
     * @access public
     * @return object
     */
    public function finishTest($todoID)
    {
        $this->objectModel->finish($todoID);
        $object = $this->objectModel->getByID($todoID);

        if(dao::isError()) return dao::getError();

        return $object;
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
     * Test get todo by id list.
     *
     * @parami array  $todoIDList
     * @access public
     * @return void
     */
    public function getByListTest($todoIDList = 0)
    {
        $objects = $this->objectModel->getByList($todoIDList);

        $name = '';
        foreach($objects as $todo) $name .= ',' . $todo->name;
        $name = trim($name, ',');

        if(dao::isError()) return dao::getError();

        return $name;
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
        $todo->idvalue      = '0';
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

        $objects = $tester->dao->select('id')->from(TABLE_TODO)->where('idvalue')->eq($todoID)->andWhere('deleted')->eq('0')->fetchAll();

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
     * Test assign todo.
     *
     * @param  int    $todoID
     * @param  array  $param
     * @access public
     * @return object
     */
    public function assignToTest($todoID, $param = array())
    {
        foreach($param as $key => $value) $_POST[$key] = $value;

        if(!isset($_POST['future']) and !isset($_POST['date'])) $_POST['date'] = date('Y-m-d', time());

        $this->objectModel->assignTo($todoID);

        unset($_POST);

        if(dao::isError()) return dao::getError();

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
}
