<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class messageModelTest extends baseTest
{
    protected $moduleName = 'message';
    protected $className  = 'model';

    /**
     * 测试获取消息。
     * Test get messages.
     *
     * @param  string       $status
     * @param  string       $orderBy
     * @param  string       $returnType
     * @access public
     * @return string|array|int
     */
    public function getMessagesTest(string $status = 'all', string $orderBy = 'createdDate', string $returnType = 'ids'): mixed
    {
        global $tester;
        $objects = $this->instance->getMessages($status, $orderBy);

        if(dao::isError()) return dao::getError();

        switch($returnType)
        {
            case 'count':
                return count($objects);
            case 'ids':
                return empty($objects) ? '0' : implode(',', array_keys($objects));
            case 'first':
                return empty($objects) ? array() : reset($objects);
            case 'structure':
                if(empty($objects)) return array();
                $first = reset($objects);
                return array(
                    'hasId' => isset($first->id),
                    'hasObjectType' => isset($first->objectType),
                    'hasToList' => isset($first->toList),
                    'hasStatus' => isset($first->status),
                    'hasData' => isset($first->data)
                );
            case 'debug':
                // 用于调试：返回实际数据结构
                $debug = array();
                $debug['totalCount'] = count($objects);
                $debug['user'] = $tester->app->user->account;
                $debug['vision'] = $tester->config->vision;

                // 检查原始数据
                $notifyCount = $tester->dao->select('COUNT(*) as count')->from(TABLE_NOTIFY)->where('objectType')->eq('message')->fetch('count');
                $debug['notifyCount'] = $notifyCount;

                $actionCount = $tester->dao->select('COUNT(*) as count')->from(TABLE_ACTION)->where('vision')->eq($tester->config->vision)->fetch('count');
                $debug['actionCount'] = $actionCount;

                // 测试具体的查询条件
                $userToList = ",{$tester->app->user->account},";
                $matchingNotify = $tester->dao->select('COUNT(*) as count')->from(TABLE_NOTIFY)
                    ->where('objectType')->eq('message')
                    ->andWhere('toList')->eq($userToList)
                    ->fetch('count');
                $debug['matchingNotify'] = $matchingNotify;
                $debug['userToList'] = $userToList;

                return $debug;
            default:
                return $objects;
        }
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
        $objects = $this->instance->getObjectTypes();

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
        $objects = $this->instance->getObjectActions();

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
        $this->instance->send($objectType, $objectID, $actionType, $actionID, $actor);

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
        $result = $this->instance->saveNotice($objectType, $objectID, $actionType, $actionID, $actor);

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
        $toList = $this->instance->getToList($object, $objectType, $actionID);

        if(dao::isError()) return dao::getError();

        return trim($toList, ',');
    }

    /**
     * 测试获取要提示的待办信息。
     * Test get notice todos.
     *
     * @param  string       $account
     * @param  string       $returnType
     * @access public
     * @return string|array|int
     */
    public function getNoticeTodosTest(string $account, string $returnType = 'ids'): string|array|int
    {
        su($account);
        $objects = $this->instance->getNoticeTodos();

        if(dao::isError()) return dao::getError();

        switch($returnType)
        {
            case 'count':
                return count($objects);
            case 'ids':
                return empty($objects) ? '0' : implode(',', array_keys($objects));
            case 'first':
                return empty($objects) ? array() : reset($objects);
            case 'structure':
                if(empty($objects)) return array();
                $first = reset($objects);
                return array(
                    'hasId' => isset($first->id),
                    'hasData' => isset($first->data),
                    'idFormat' => substr($first->id, 0, 4) === 'todo' ? 'correct' : 'incorrect'
                );
            case 'dataContent':
                if(empty($objects)) return '';
                $first = reset($objects);
                return isset($first->data) ? 'hasContent' : 'empty';
            default:
                return $objects;
        }
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
        $result = $this->instance->batchSaveTodoNotice();
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
        $settings = $this->instance->getBrowserMessageConfig();

        if(dao::isError()) return dao::getError();
        unset($tester->config->message->browser->turnon);
        unset($tester->config->message->browser->pollTime);
        return $settings;
    }

    /**
     * Test deleteExpired method.
     *
     * @param  int $maxDays
     * @access public
     * @return int
     */
    public function deleteExpiredTest(int $maxDays = 7): int
    {
        global $tester;
        $tester->config->message->browser->maxDays = $maxDays;

        $countBefore = $tester->dao->select('COUNT(*) as count')->from(TABLE_NOTIFY)
            ->where('toList')->like('%,' . $tester->app->user->account . ',%')
            ->andWhere('objectType')->eq('message')
            ->fetch('count');

        $this->instance->deleteExpired();

        if(dao::isError()) return dao::getError();

        $countAfter = $tester->dao->select('COUNT(*) as count')->from(TABLE_NOTIFY)
            ->where('toList')->like('%,' . $tester->app->user->account . ',%')
            ->andWhere('objectType')->eq('message')
            ->fetch('count');

        return $countAfter;
    }

    /**
     * Test getUnreadCount method.
     *
     * @param  string $account
     * @access public
     * @return int
     */
    public function getUnreadCountTest(string $account = ''): int
    {
        global $tester;
        if(!empty($account))
        {
            $originalAccount = $tester->app->user->account;
            $tester->app->user->account = $account;
        }

        $result = $this->instance->getUnreadCount();

        if(dao::isError()) return dao::getError();

        if(!empty($account))
        {
            $tester->app->user->account = $originalAccount;
        }

        return $result;
    }
}
