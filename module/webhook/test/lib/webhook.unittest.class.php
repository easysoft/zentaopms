<?php
class webhookTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('webhook');
         $this->objectTao   = $tester->loadTao('webhook');
    }

    /**
     * Get by ID Test
     *
     * @param  int    $id
     * @access public
     * @return array
     */
    public function getByIDTest($id)
    {
        $objects = $this->objectModel->getByID($id);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get by type Test
     *
     * @param  string $type
     * @access public
     * @return array
     */
    public function getByTypeTest($type)
    {
        $objects = $this->objectModel->getByType($type);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get bind account Test
     *
     * @param  int    $webhookID
     * @param  string $webhookType
     * @param  int    $openID
     * @access public
     * @return string
     */
    public function getBindAccountTest($webhookID, $webhookType, $openID)
    {
        $objects = $this->objectModel->getBindAccount($webhookID, $webhookType, $openID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get list Test
     *
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getListTest($orderBy = 'id_desc', $pager = null)
    {
        $objects = $this->objectModel->getList($orderBy, $pager);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get log list Test
     *
     * @param  int    $id
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getLogListTest($id, $orderBy = 'date_desc', $pager = null)
    {
        $objects = $this->objectModel->getLogList($id, $orderBy, $pager);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get data list Test
     *
     * @access public
     * @return array
     */
    public function getDataListTest()
    {
        global $tester;
        $objects = $this->objectModel->getDataList();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get bound users Test
     *
     * @param  int   $webhookID
     * @param  array $users
     * @access public
     * @return int
     */
    public function getBoundUsersTest($webhookID, $users = array())
    {
        $objects = $this->objectModel->getBoundUsers($webhookID, $users);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createTest($webhook)
    {
        $objectID = $this->objectModel->create($webhook);

        if(dao::isError()) return dao::getError();

        return $objectID;
    }

    /**
     * Update Test.
     *
     * @param  int    $id
     * @param  object $webhook
     * @access public
     * @return int
     */
    public function updateTest($id, $webhook)
    {
        $this->objectModel->update($id, $webhook);

        if(dao::isError()) return dao::getError();

        return $this->objectModel->getByID($id);
    }

    /**
     * Bind Test
     *
     * @param  int   $webhookID
     * @param  array $userList
     * @access public
     * @return mixed
     */
    public function bindTest($webhookID, $userList)
    {
        $_POST['userid'] = $userList;

        $result = $this->objectModel->bind($webhookID);

        if(dao::isError()) return dao::getError();

        return $result ? 1 : 0;
    }

    /**
     * Send Test
     *
     * @param string $objectType
     * @param int    $objectID
     * @param string $actionType
     * @param int    $actionID
     * @param string $actor
     * @access public
     * @return bool
     */
    public function sendTest($objectType, $objectID, $actionType, $actionID, $actor = '')
    {
        $objects = $this->objectModel->send($objectType, $objectID, $actionType, $actionID, $actor);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Build data Test
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $actionType
     * @param  int    $actionID
     * @access public
     * @return mixed
     */
    public function buildDataTest($objectType, $objectID, $actionType, $actionID)
    {
        static $webhooks = array();
        if(!$webhooks) $webhooks = $this->getListTest();
        if(!$webhooks) return false;

        $result = false;
        foreach($webhooks as $id => $webhook)
        {
            $data = $this->objectModel->buildData($objectType, $objectID, $actionType, $actionID, $webhook);
            if($data !== false)
            {
                $result = $data;
                break;
            }
        }

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Get view link Test
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return string
     */
    public function getViewLinkTest($objectType, $objectID)
    {
        $objects = $this->objectModel->getViewLink($objectType, $objectID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get dingding data Test
     *
     * @param  string $title
     * @param  string $text
     * @param  string $mobile
     * @access public
     * @return array
     */
    public function getDingdingDataTest($title, $text, $mobile)
    {
        $objects = $this->objectModel->getDingdingData($title, $text, $mobile);
        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get bearychat data Test
     *
     * @param  string $text
     * @param  string $mobile
     * @param  string $email
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function getBearychatDataTest($text, $mobile, $email, $objectType, $objectID)
    {
        $objects = $this->objectModel->getBearychatData($text, $mobile, $email, $objectType, $objectID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get weixin data Test
     *
     * @param  string $text
     * @param  string $mobile
     * @access public
     * @return array
     */
    public function getWeixinDataTest($text, $mobile)
    {
        $objects = $this->objectModel->getWeixinData($text, $mobile);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get feishu data Test
     *
     * @param  string $title
     * @param  string $text
     * @access public
     * @return array
     */
    public function getFeishuDataTest($title, $text)
    {
        $objects = $this->objectModel->getFeishuData($title, $text);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get open id list Test
     *
     * @param  int    $actionID
     * @access public
     * @return void
     */
    public function getOpenIdListTest($webhookID, $actionID)
    {
        static $webhooks = array();
        if(!$webhooks) $webhooks = $this->getListTest();
        if(!$webhooks) return true;

        foreach($webhooks as $id => $webhook)
        {
            $objects = $this->objectModel->getOpenIdList($webhook->id, $actionID);
        }
        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Save data Test
     *
     * @param  int    $webhookID
     * @param  int    $actionID
     * @param  string $data
     * @param  string $actor
     * @access public
     * @return void
     */
    public function saveDataTest($objectType, $objectID, $actionType, $webhookID, $actionID, $actor = '')
    {
        $this->objectModel->loadModel('action');

        static $webhooks = array();
        if(!$webhooks) $webhooks = $this->getListTest();
        if(!$webhooks) return true;

        foreach($webhooks as $id => $webhook)
        {
            $postData = $this->objectModel->buildData($objectType, $objectID, $actionType, $actionID, $webhook);
            $objects  = $this->objectModel->saveData($webhookID, $actionID, $postData, $actor);
        }
        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Save log Test
     *
     * @param  object $webhook
     * @param  int    $actionID
     * @param  string $data
     * @param  string $result
     * @access public
     * @return void
     */
    public function saveLogTest($webhook, $actionID, $data, $result)
    {
        $this->objectModel->saveLog($webhook, $actionID, $data, $result);

        if(dao::isError()) return dao::getError();

        return $this->objectModel->dao->select('*')->from(TABLE_LOG)->where('objectID')->eq($webhook->id)->andWhere('objectType')->eq('webhook')->fetch();
    }

    /**
     * Set sent status Test
     *
     * @param  array  $idList
     * @param  string $status
     * @param  string $time
     * @access public
     * @return void
     */
    public function setSentStatusTest($idList, $status, $time = '')
    {
        $this->objectModel->setSentStatus($idList, $status, $time);

        if(dao::isError()) return dao::getError();

        return $this->objectModel->dao->select('*')->from(TABLE_NOTIFY)->where('id')->in($idList)->fetchAll('id');
    }

    /**
     * Test getDataByType method.
     *
     * @param  string $type
     * @param  string $title
     * @param  string $text
     * @param  string $mobile
     * @param  string $email
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return string
     */
    public function getDataByTypeTest($type, $title, $text, $mobile, $email, $objectType, $objectID)
    {
        // 创建模拟的 webhook 对象
        $webhook = new stdclass();
        $webhook->type = $type;
        $webhook->params = 'text,title,objectType';

        // 创建模拟的 action 对象
        $action = new stdclass();
        $action->text = '测试动作文本';
        $action->title = $title;
        $action->objectType = $objectType;

        $result = $this->objectModel->getDataByType($webhook, $action, $title, $text, $mobile, $email, $objectType, $objectID);

        if(dao::isError()) return dao::getError();

        // 解析JSON为对象以便测试框架可以使用p()语法检查属性
        return json_decode($result);
    }

    /**
     * Test fetchHook method.
     *
     * @param  object       $webhook
     * @param  string       $sendData
     * @param  int          $actionID
     * @param  string|array $appendUser
     * @access public
     * @return mixed
     */
    public function fetchHookTest($webhook, $sendData, $actionID = 0, $appendUser = '')
    {
        $result = $this->objectModel->fetchHook($webhook, $sendData, $actionID, $appendUser);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test sendToUser method.
     *
     * @param  object       $webhook
     * @param  string       $sendData
     * @param  int          $actionID
     * @param  string|array $appendUser
     * @access public
     * @return string|false
     */
    public function sendToUserTest($webhook, $sendData, $actionID, $appendUser = '')
    {
        $result = $this->objectModel->sendToUser($webhook, $sendData, $actionID, $appendUser);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getDingdingSecret method.
     *
     * @param  object $webhook
     * @access public
     * @return object|false
     */
    public function getDingdingSecretTest($webhook)
    {
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('getDingdingSecret');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->objectTao, $webhook);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getWeixinSecret method.
     *
     * @param  object $webhook
     * @access public
     * @return object|false
     */
    public function getWeixinSecretTest($webhook)
    {
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('getWeixinSecret');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->objectTao, $webhook);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getFeishuSecret method.
     *
     * @param  object $webhook
     * @access public
     * @return object|false
     */
    public function getFeishuSecretTest($webhook)
    {
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('getFeishuSecret');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->objectTao, $webhook);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getActionText method.
     *
     * @param  object $data
     * @param  object $action
     * @param  object $object
     * @param  array  $users
     * @access public
     * @return string
     */
    public function getActionTextTest($data, $action, $object, $users)
    {
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('getActionText');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->objectTao, $data, $action, $object, $users);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
