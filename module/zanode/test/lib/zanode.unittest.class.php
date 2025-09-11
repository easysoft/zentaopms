<?php
declare(strict_types = 1);
class zanodeTest
{
    private $objectModel;

    public function __construct()
    {
        global $tester, $app;
        $app->rawModule = 'zanode';
        $app->rawMethod = 'browse';
        $this->objectModel = $tester->loadModel('zanode');
    }

    /**
     * 魔术方法，调用zanode模型中的方法。
     * Magic method, call the method in the zanode model.
     *
     * @param  string $name
     * @param  array  $arguments
     * @access public
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->objectModel->$name(...$arguments);
    }

    /**
     * 测试构造方法。
     * Test __construct method.
     *
     * @access public
     * @return object
     */
    public function constructTest(): object
    {
        $zanodeModel = new zanodeModel();
        $result = new stdClass();
        $result->parentCalled = method_exists($zanodeModel, '__construct');
        $result->langSet = isset($zanodeModel->app->lang->host);
        $result->inheritance = is_a($zanodeModel, 'model');
        $result->objectModel = is_a($this->objectModel, 'zanodeModel');
        return $result;
    }

    /**
     * 测试自动化设置。
     * Test set automation setting.
     *
     * @param  object       $object
     * @access public
     * @return object|array
     */
    public function setAutomationSettingTest(object $object): object|array
    {
        $resultID = $this->setAutomationSetting($object);
        if(dao::isError()) return dao::getError();
        $return = $this->objectModel->getAutomationByID($resultID);
        return $return;
        return $this->objectModel->getAutomationByID($resultID);
    }

    /**
     * 测试判断按钮是否可点击。
     * Test judge an action is clickable or not.
     *
     * @param  string $action
     * @param  string $status
     * @param  string $hostType
     * @access public
     * @return bool
     */
    public function isClickableTest(string $action, string $status, string $hostType): bool
    {
        $node = new stdclass();
        $node->status   = $status;
        $node->hostType = $hostType;
        return $this->isClickable($node, $action);
    }

    /**
     * 测试检查创建字段。
     * Test check fields of create.
     *
     * @param  array $testData
     * @access public
     * @return array|bool
     */
    public function checkFields4CreateTest(array $testData): array|bool
    {
        $data = new stdclass();
        $data->type     = 'node';
        $data->status   = 'running';

        foreach($testData as $key => $value) $data->$key = $value;
        $result = $this->checkFields4Create($data);
        if(!$result) return dao::getError();

        return $result;
    }

    /**
     * 测试创建执行节点。
     * Test create an Node.
     *
     * @access public
     * @return int|bool
     */
    public function createTest(object $data): object|array
    {
        $nodeID = $this->create($data);
        return $this->objectModel->dao->select('*')->from(TABLE_HOST)->where('id')->eq($nodeID)->fetch();
    }

    /**
     * 测试获取快照列表。
     * Test get snapshot list.
     *
     * @access public
     * @return string
     */
    public function getSnapshotListTest(int $nodeID, string $orderBy = 'id', object $pager = null): string
    {
        $snapshotList = $this->getSnapshotList($nodeID, $orderBy, $pager);

        $return = '';
        foreach($snapshotList as $name => $snapshot) $return .= "{$snapshot->id}:{$name},{$snapshot->status};";
        return trim($return, ';');
    }

    /**
     * 测试创建快照。
     * Test create snapshot.
     *
     * @param  int    $nodeID
     * @param  string $nodeIP
     * @param  int    $hzap
     * @param  string $token
     * @param  array $data
     * @access public
     * @return object
     */
    public function createSnapshotTest(int $nodeID, string $nodeIP, int $hzap, string $token, array $data): object|string
    {
        $node = $this->getNodeByID($nodeID);
        $node->ip      = $nodeIP;
        $node->hzap    = $hzap;
        $node->tokenSN = $token;

        $snapshot = new stdClass();
        $snapshot->host        = $node->id;
        $snapshot->name        = $data['name'];
        $snapshot->desc        = $data['desc'];
        $snapshot->status      = 'creating';
        $snapshot->osName      = $node->osName;
        $snapshot->memory      = 0;
        $snapshot->disk        = 0;
        $snapshot->fileSize    = 0;
        $snapshot->from        = 'snapshot';

        $snapshotID = $this->createSnapshot($node, $snapshot);
        if(dao::isError()) return dao::getError();

        $createdSnapshot = $this->objectModel->dao->select('*')->from(TABLE_IMAGE)->where('id')->eq($snapshotID)->fetch();
        $this->deleteSnapshot($snapshotID);
        return $createdSnapshot;
    }

    /**
     * 测试创建默认的快照。
     * Test create default snapshot.
     *
     * @param  int    $nodeID
     * @access public
     * @return object
     */
    public function createDefaultSnapshotTest(int $nodeID): object|array
    {
        $snapshotID = $this->createDefaultSnapshot($nodeID);
        if(dao::isError()) return dao::getError();

        return $this->objectModel->dao->select('*')->from(TABLE_IMAGE)->where('id')->eq($snapshotID)->fetch();
    }

    /**
     * 测试编辑快照。
     * Test edit snapshot.
     *
     * @param int    $snapshotID
     * @param object $data
     * @access public
     * @return void
     */
    public function editSnapshotTest(int $snapshotID, object $data): object|array
    {
        $this->editSnapshot($snapshotID, $data);
        if(dao::isError()) return dao::getError();

        return $this->objectModel->dao->select('*')->from(TABLE_IMAGE)->where('id')->eq($snapshotID)->fetch();
    }

    /**
     * 测试将执行节点还原到此快照。
     * Test restore zanode to snapshot.
     *
     * @param  int    $zanodeID
     * @param  int    $snapshotID
     * @access public
     * @return string|object
     */
    public function restoreSnapshotTest(int $zanodeID = 0, int $snapshotID = 0): string|object
    {
        $result = $this->restoreSnapshot($zanodeID, $snapshotID);
        if(!$result) return dao::getError();

        return $this->objectModel->dao->select('*')->from(TABLE_ZAHOST)->where('id')->eq($zanodeID)->fetch();
    }

    /**
     * 测试删除快照。
     * Test Delete snapshot.
     *
     * @param  int $snapshotID
     * @access public
     * @return string|bool
     */
    public function deleteSnapshotTest(int $snapshotID)
    {
        if($snapshotID <= 0) return '~~';
        
        $snapshot = $this->getImageByID($snapshotID);
        if(!$snapshot) return '~~';
        
        $result = $this->deleteSnapshot($snapshotID);
        if($result !== true) return $result;

        return $this->objectModel->dao->select('*')->from(TABLE_IMAGE)->where('id')->eq($snapshotID)->fetch();
    }

    /**
     * 测试获取远程操控信息。
     * Test get vnc url.
     *
     * @param  int    $nodeID
     * @access public
     * @return object
     */
    public function getVncUrlTest(int $nodeID): bool|object
    {
        $node = $this->getNodeByID($nodeID);
        return $this->getVncUrl($node);
    }

    /**
     * 测试更新导出镜像的状态。
     * Test update Image status.
     *
     * @param  int    $imageID
     * @param  object $data
     * @access public
     * @return void
     */
    public function updateImageStatusTest(int $imageID, object $data): object
    {
        $this->updateImageStatus($imageID, $data);
        return $this->objectModel->dao->select('status,path')->from(TABLE_IMAGE)->where('id')->eq($imageID)->fetch();
    }

    /**
     * 测试通过执行节点创建镜像。
     * Test create Image by zanode.
     *
     * @param  int    $zanodeID
     * @param  object $data
     * @access public
     * @return mixed
     */
    public function createImageTest(int $zanodeID, object $data): mixed
    {
        $result = $this->objectModel->createImage($zanodeID, $data);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试销毁执行节点。
     * Test destroy method.
     *
     * @param  int    $id
     * @access public
     * @return mixed
     */
    public function destroyTest(int $id): mixed
    {
        if($id <= 0) return '0';

        $oldNode = $this->getNodeByID($id);
        if(!$oldNode) return '0';

        $result = $this->destroy($id);
        
        if($result === '') return 'success';
        return $result ? $result : '0';
    }

    /**
     * 测试计算执行节点状态。
     * Test process node status.
     *
     * @param  int    $nodeID
     * @access public
     * @return object
     */
    public function processNodeStatusTest(int $nodeID): object|bool
    {
        $node = $this->objectModel->dao->select("t1.*, t2.name as hostName, if(t1.hostType='', t2.extranet, t1.extranet) ip,t2.zap as hzap,if(t1.hostType='', t3.osName, t1.osName) osName, if(t1.hostType='', t2.tokenSN, t1.tokenSN) tokenSN, if(t1.hostType='', t2.secret, t1.secret) secret")
            ->from(TABLE_ZAHOST)->alias('t1')
            ->leftJoin(TABLE_ZAHOST)->alias('t2')->on('t1.parent = t2.id')
            ->leftJoin(TABLE_IMAGE)->alias('t3')->on('t3.id = t1.image')
            ->where('t1.id')->eq($nodeID)
            ->fetch();
        
        if(empty($node)) return false;
        
        // 调用protected方法processNodeStatus
        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('processNodeStatus');
        $method->setAccessible(true);
        
        return $method->invoke($this->objectModel, $node);
    }
}
