<?php
class webhookTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('webhook');
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
     * @param  bool   $decode
     * @access public
     * @return array
     */
    public function getListTest($orderBy = 'id_desc', $pager = null, $decode = true)
    {
        $objects = $this->objectModel->getList($orderBy, $pager, $decode);

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
     * @param  array $create
     * @param  array $bind
     * @access public
     * @return array
     */
    public function bindTest($create, $bind)
    {
        global $tester;
        $result = $this->createTest($create);
        $id = $tester->dao->select('id')->from(TABLE_WEBHOOK)->where('name')->eq($create['name'])->fetch('id');

        if($id == null)
        {
            a($result);
            return;
        }else
        {
            foreach($bind as $key => $value) $_POST[$key] = $value;

            $objects = $this->objectModel->bind($id);

            if(dao::isError()) return dao::getError();

            return $objects;
        }
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
     * @return bool
     */
    public function buildDataTest($objectType, $objectID, $actionType, $actionID)
    {
        static $webhooks = array();
        if(!$webhooks) $webhooks = $this->getListTest();
        if(!$webhooks) return true;

        foreach($webhooks as $id => $webhook)
        {
            $objects = $this->objectModel->buildData($objectType, $objectID, $actionType, $actionID, $webhook);
        }
        if(dao::isError()) return dao::getError();

        return $objects;
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
     * Fetch hook Test
     *
     * @param  object $webhook
     * @param  object $sendData
     * @param  int    $actionID
     * @access public
     * @return int
     */
    public function fetchHookTest($objectType, $objectID, $actionType, $actionID = 0)
    {
        static $webhooks = array();
        if(!$webhooks) $webhooks = $this->getListTest();
        if(!$webhooks) return true;

        foreach($webhooks as $id => $webhook)
        {
            $postData = $this->objectModel->buildData($objectType, $objectID, $actionType, $actionID, $webhook);
            $objects = $this->objectModel->fetchHook($webhook, $postData, $actionID);
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
        static $webhooks = array();
        if(!$webhooks) $webhooks = $this->getListTest();
        if(!$webhooks) return true;

        foreach($webhooks as $id => $webhook)
        {
            $postData = $this->objectModel->buildData($objectType, $objectID, $actionType, $actionID, $webhook);
            $objects = $this->objectModel->saveData($webhookID, $actionID, $postData, $actor);
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
        $objects = $this->objectModel->saveLog($webhook, $actionID, $data, $result);

        if(dao::isError()) return dao::getError();

        return $objects;
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
        $objects = $this->objectModel->setSentStatus($idList, $status, $time);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
