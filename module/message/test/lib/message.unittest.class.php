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
    public function getToListTest(string $objectType, int $objectID, int $actionID): string|array
    {
        global $tester;
        $table  = $tester->config->objectTables[$objectType];
        $object = $tester->dao->select('*')->from($table)->where('id')->eq($objectID)->fetch();
        $toList = $this->objectModel->getToList($object, $objectType, $actionID);

        if(dao::isError()) return dao::getError();

        return trim($toList, ',');
    }

    /**
     * 测试获取要提示的待办信息。
     * Test get notice todos.
     *
     * @param  string       $account
     * @access public
     * @return string|array
     */
    public function getNoticeTodosTest(string $account): string|array
    {
        su($account);
        $objects = $this->objectModel->getNoticeTodos();

        if(dao::isError()) return dao::getError();

        return implode(',', array_keys($objects));
    }

    /**
     * Test batchSaveTodoNotice method.
     *
     * @param  string $account
     * @access public
     * @return mixed
     */
    public function batchSaveTodoNoticeTest(string $account): mixed
    {
        su($account);
        $result = $this->objectModel->batchSaveTodoNotice();
        if(dao::isError()) return dao::getError();

        return count($result);
    }

    /**
     * 测试获取浏览器通知的相关配置信息。
     * Test get browser message config.
     *
     * @param  string $turnon
     * @param  string $pollTime
     * @access public
     * @return array
     */
    public function getBrowserMessageConfigTest(string $turnon, string $pollTime): array
    {
        global $tester;
        if(!isset($tester->config->message)) $tester->config->message = new stdclass();
        if(!isset($tester->config->message->browser)) $tester->config->message->browser = new stdclass();
        $tester->config->message->browser->turnon   = $turnon;
        $tester->config->message->browser->pollTime = $pollTime;
        $settings = $this->objectModel->getBrowserMessageConfig();

        if(dao::isError()) return dao::getError();
        unset($tester->config->message->browser->turnon);
        unset($tester->config->message->browser->pollTime);
        return $settings;
    }

    /**
     * 测试设置下拉菜单的相关变量。
     * Test assign for dropmenu vars.
     *
     * @param  string $active
     * @access public
     * @return array
     */
    public function assignDropmenuVarsTest(string $active = 'unread'): array
    {
        global $tester;

        $messageZen = $tester->loadZen('message');

        /* Mock view object */
        if(!isset($messageZen->view)) $messageZen->view = new stdclass();

        /* Call the method being tested */
        $messageZen->assignDropmenuVars($active);

        if(dao::isError()) return dao::getError();

        /* Return view variables for verification */
        $result = array();
        if(isset($messageZen->view->allMessages)) $result['allMessages'] = $messageZen->view->allMessages;
        if(isset($messageZen->view->unreadCount)) $result['unreadCount'] = $messageZen->view->unreadCount;
        if(isset($messageZen->view->unreadMessages)) $result['unreadMessages'] = $messageZen->view->unreadMessages;
        if(isset($messageZen->view->active)) $result['active'] = $messageZen->view->active;

        return $result;
    }
}
