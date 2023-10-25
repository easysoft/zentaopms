<?php
class entryTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('entry');
    }

    /**
     * Test get entry by id.
     *
     * @param  int    $entryID
     * @access public
     * @return object
     */
    public function getByIdTest($entryID)
    {
        $object = $this->objectModel->getById($entryID);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Test get entry by code.
     *
     * @param  int    $code
     * @access public
     * @return object
     */
    public function getByCodeTest($code)
    {
        $object = $this->objectModel->getByCode($code);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Test get entry by key.
     *
     * @param  int    $key
     * @access public
     * @return object
     */
    public function getByKeyTest($key)
    {
        $object = $this->objectModel->getByKey($key);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Get list test.
     *
     * @param  string $orderBy
     * @param  int    $pager
     * @access public
     * @return object
     */
    public function getListTest($orderBy = 'id_desc', $pager = null)
    {
        $objects = $this->objectModel->getList($orderBy, $pager);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get logs test.
     *
     * @param  int    $id
     * @param  string $orderBy
     * @param  int    $pager
     * @access public
     * @return object
     */
    public function getLogsTest($id, $orderBy = 'date_desc', $pager = null)
    {
        $object = $this->objectModel->getLogs($id, $orderBy, $paper);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Test create a object.
     *
     * @param  array  $params
     * @access public
     * @return object|array|string
     */
    public function createObject(string $name, string $code, string $account, int $freePasswd = 0): object|array|string
    {
        $createFields = new stdclass();
        $createFields->name       = $name;
        $createFields->code       = $code;
        $createFields->freePasswd = $freePasswd;
        $createFields->key        = md5(time());

        if($account) $createFields->account = $account;
        $objectID = $this->objectModel->create($createFields);

        if(dao::isError()) return dao::getError();

        $object = $this->objectModel->getById($objectID);
        return $object;
    }

    /**
     * Test update a entry.
     *
     * @param  int    $entryID
     * @param  int    $params
     * @access public
     * @return object
     */
    public function updateObject($entryID, $params)
    {
        global $tester;

        $object = $tester->dbh->query("SELECT * FROM " . TABLE_ENTRY  ." WHERE id = $entryID")->fetch();

        foreach($object as $field => $value)
        {
            if(in_array($field, array_keys($params)))
            {
                $_POST[$field] = $params[$field];
            }
            else
            {
                $_POST[$field] = $value;
            }
        }

        $change = $this->objectModel->update($entryID);
        if($change == array()) $change = '没有数据更新';
        unset($_POST);

        if(dao::isError()) return dao::getError();
        return $change;
    }

    /**
     * Test update calledTime.
     *
     * @param  int    $code
     * @param  int    $time
     * @access public
     * @return object
     */
    public function updateCalledTimeTest($code, $time)
    {
        $this->objectModel->updateCalledTime($code, $time);

        if(dao::isError()) return dao::getError();

        $object = $this->objectModel->getByCode($code);
        return $object;
    }

    /**
     * Test save a log.
     *
     * @param  int    $entryID
     * @param  int    $url
     * @access public
     * @return object
     */
    public function saveLogTest($entryID, $url)
    {
        $this->objectModel->saveLog($entryID, $url);

        if(dao::isError()) return dao::getError();

        global $tester;
        $object = $tester->dbh->query("SELECT * FROM " . TABLE_LOG  ." WHERE objectID = $entryID AND objectType = 'entry'")->fetch();
        return $object;
    }

    /**
     * 测试判断操作是否可以点击。
     * Test judge an action is clickable or not.
     *
     * @param  object     $entry
     * @param  string     $action
     * @access public
     * @return int|array
     */
    public function isClickableTest(object $entry, string $action): int|array
    {
        $isClickable = $this->objectModel->isClickable($entry, $action);
        if(dao::isError()) return dao::getError();
        return $isClickable ? 1 : 0;
    }
}
