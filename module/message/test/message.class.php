<?php
declare(strict_types=1);
class messageTest
{
    /**
     * 构造函数。
     * Construct function.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('message');
    }

    /**
     * 测试获取消息。
     * Test get messages.
     *
     * @param  string       $status
     * @access public
     * @return string|array
     */
    public function getMessagesTest(string $status): string|array
    {
        $objects = $this->objectModel->getMessages($status);

        if(dao::isError()) return dao::getError();

        return implode(',', array_keys($objects));
    }

    /**
     * 获取对象类型。
     * Get objectTypes.
     *
     * @access public
     * @return void
     */
    public function getObjectTypesTest(): array
    {
        $objects = $this->objectModel->getObjectTypes();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 测试获取对象操作。
     * Test get object actions.
     *
     * @access public
     * @return void
     */
    public function getObjectActionsTest(): array
    {
        $objects = $this->objectModel->getObjectActions();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 测试发送方法。
     * Test send.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $actionType
     * @param  int    $actionID
     * @param  string $actor
     * @access public
     * @return array
     */
    public function sendTest(string $objectType, int $objectID, string $actionType, int $actionID, string $actor = ''): array
    {
        $this->objectModel->send($objectType, $objectID, $actionType, $actionID, $actor);

        if(dao::isError()) return dao::getError();

        return array();
    }

    /**
     * 测试存储提示消息。
     * Test save notice.
     *
     * @param  string       $objectType
     * @param  int          $objectID
     * @param  string       $actionType
     * @param  int          $actionID
     * @param  string       $actor
     * @access public
     * @return object|array
     */
    public function saveNoticeTest(string $objectType, int $objectID, string $actionType, int $actionID, string $actor = ''): object|array
    {
        global $tester;
        if($actor == 'empty')
        {
            $actor = '';
            $tester->app->user->account = '';
        }
        $result = $this->objectModel->saveNotice($objectType, $objectID, $actionType, $actionID, $actor);

        if(dao::isError()) return dao::getError();


        if($result) $notify = $tester->dao->select('*')->from(TABLE_NOTIFY)->orderBy('id_desc')->fetch();
        return !empty($notify) ? $notify : array();
    }

    /**
     * 测试获取要发送的人列表。
     * Test get toList.
     *
     * @param  string        $objectType
     * @param  int           $objectID
     * @param  int           $actionID
     * @access public
     * @return string|array
     */
    public function getToListTest(string $objectType, string $objectID, int $actionID): string|array
    {
        global $tester;
        $table  = $tester->config->objectTables[$objectType];
        $object = $tester->dao->select('*')->from($table)->where('id')->eq($objectID)->fetch();
        $toList = $this->objectModel->getToList($object, $objectType, $actionID);

        if(dao::isError()) return dao::getError();

        return trim($toList, ',');
    }

    /**
     * Get notice todos.
     *
     * @access public
     * @return void
     */
    public function getNoticeTodosTest()
    {
        $objects = $this->objectModel->getNoticeTodos();

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
