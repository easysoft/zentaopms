<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class entryModelTest extends baseTest
{
    protected $moduleName = 'entry';
    protected $className  = 'model';

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

        $objectID = $this->instance->create($createFields);
        if(dao::isError()) return dao::getError();

        $object = $this->instance->getById($objectID);
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

        $change = $this->instance->update($entryID, $editFields);
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
        $this->instance->updateCalledTime($code, $time);
        if(dao::isError()) return dao::getError();

        $object = $this->instance->getByCode($code);
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
        $this->instance->saveLog($entryID, $url);
        if(dao::isError()) return dao::getError();

        global $tester;
        $object = $tester->dbh->query("SELECT * FROM " . TABLE_LOG  ." WHERE objectID = $entryID AND objectType = 'entry'")->fetch();
        return $object;
    }

    /**
     * Test getById method.
     *
     * @param  int $entryID
     * @access public
     * @return object|false|array
     */
    public function getByIdTest(int $entryID): object|false|array
    {
        $result = $this->instance->getById($entryID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getByKey method.
     *
     * @param  string $key
     * @access public
     * @return object|false|array
     */
    public function getByKeyTest(string $key): object|false|array
    {
        $result = $this->instance->getByKey($key);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getLogs method.
     *
     * @param  int    $id
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getLogsTest(int $id, string $orderBy = 'date_desc', ?object $pager = null): array
    {
        $result = $this->instance->getLogs($id, $orderBy, $pager);
        if(dao::isError()) return dao::getError();

        return $result;
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
        $isClickable = $this->instance->isClickable($entry, $action);
        if(dao::isError()) return dao::getError();
        return $isClickable ? 1 : 0;
    }
}
