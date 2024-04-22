<?php
class entryTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('entry');
    }

    /**
     * 测试创建应用。
     * Test create a object.
     *
     * @param  array $params
     * @access public
     * @return object|array
     */
    public function createObject(array $params): object|array
    {
        $createFields = new stdclass();
        foreach($params as $key => $value)
        {
            if($key == 'allIP' and $value == 'on')
            {
                $createFields->ip = '*';
                continue;
            }

            if($key == 'freePasswd' and $value == '1') $createFields->account = '';

            $createFields->$key = $value;
        }

        $objectID = $this->objectModel->create($createFields);
        if(dao::isError()) return dao::getError();

        $object = $this->objectModel->getById($objectID);
        return $object;
    }

    /**
     * 测试更新应用。
     * Test update a entry.
     *
     * @param  int    $entryID
     * @param  int    $params
     * @access public
     * @return object|array
     */
    public function updateObject(int $entryID, array $params): object|array
    {
        $editFields = new stdclass();
        foreach($params as $key => $value)
        {
            if($key == 'allIP' and $value == 'on')
            {
                $editFields->ip = '*';
                continue;
            }

            if($key == 'freePasswd' and $value == '1') $editFields->account = '';

            $editFields->$key = $value;
        }

        $change = $this->objectModel->update($entryID, $editFields);
        if(dao::isError()) return dao::getError();

        return $change;
    }

    /**
     * 测试更新调用时间。
     * Test update calledTime.
     *
     * @param  int    $code
     * @param  int    $time
     * @access public
     * @return object|false
     */
    public function updateCalledTimeTest(string $code, int $time): object|false
    {
        $this->objectModel->updateCalledTime($code, $time);
        if(dao::isError()) return dao::getError();

        $object = $this->objectModel->getByCode($code);
        return $object;
    }

    /**
     * 测试保存日志。
     * Test save a log.
     *
     * @param  int    $entryID
     * @param  string $url
     * @access public
     * @return object
     */
    public function saveLogTest(int $entryID, string $url): object
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
