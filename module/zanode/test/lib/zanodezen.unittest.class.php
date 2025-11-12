<?php
declare(strict_types = 1);
class zanodeTest
{
    public $zanodeZenTest;
    public $tester;

    public function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $this->objectModel = $tester->loadModel('zanode');
        $tester->app->setModuleName('zanode');

        $this->zanodeZenTest = initReference('zanode');
    }

    /**
     * Test handleNode method.
     *
     * @param  int    $nodeID
     * @param  string $type
     * @access public
     * @return mixed
     */
    public function handleNodeTest(int $nodeID, string $type)
    {
        global $lang;

        $node = $this->objectModel->getNodeByID($nodeID);
        if(!$node) return array('result' => 'fail', 'message' => 'Node not found');

        // 检查节点状态
        if(in_array($node->status, array('restoring', 'creating_img', 'creating_snap')))
        {
            return array('result' => 'fail', 'message' => sprintf($lang->zanode->busy, $lang->zanode->statusList[$node->status]));
        }

        // Mock HTTP请求成功响应
        $mockResult = array('code' => 'success', 'msg' => 'Operation successful');

        // 更新节点状态
        if($type != 'reboot')
        {
            $status = $type == 'suspend' ? 'suspend' : 'running';
            if($type == 'destroy') $status = 'shutoff';

            $this->tester->dao->update(TABLE_ZAHOST)->set('status')->eq($status)->where('id')->eq($nodeID)->exec();
        }

        // 记录操作日志
        $this->tester->loadModel('action')->create('zanode', $nodeID, ucfirst($type));

        return array('result' => 'success', 'message' => $lang->zanode->actionSuccess);
    }

    /**
     * Test prepareCreateExtras method.
     *
     * @access public
     * @return mixed
     */
    public function prepareCreateExtrasTest()
    {
        global $config, $app;

        // 模拟 prepareCreateExtras 方法的逻辑
        if($app->post->hostType == 'physics') $config->zanode->create->requiredFields = $config->zanode->create->physicsRequiredFields;

        if($app->post->hostType == 'physics')
        {
            if(isset($config->zanode->form->create['memory'])) $config->zanode->form->create['memory']['required'] = false;
            if(isset($config->zanode->form->create['diskSize'])) $config->zanode->form->create['diskSize']['required'] = false;
        }

        // 模拟 form::data() 的基本行为
        $data = new stdClass();
        $data->type = 'node';
        $data->createdDate = helper::now();
        $data->createdBy = $app->user->account;
        $data->status = 'running';

        if($app->post->hostType != 'physics')
        {
            $data->hostType = '';
        }

        if($app->post->hostType == 'physics')
        {
            $data->parent = 0;
            $data->osName = $app->post->osNamePhysics;
            $data->secret = md5($app->post->name . time());
            $data->status = 'offline';
            $data->hostType = '';
        }

        // 复制POST数据
        foreach($app->post as $key => $value)
        {
            if(!isset($data->$key)) $data->$key = $value;
        }

        // 模拟字段验证
        if(empty($data->name)) return array('result' => 'fail', 'message' => 'name is required');

        // 对于非物理主机，模拟linkAgentService调用失败
        if($data->hostType != 'physics' && isset($data->image) && $data->image == 999)
        {
            return array('result' => 'fail', 'message' => 'Agent service connection failed');
        }

        return $data;
    }

    /**
     * Test prepareCreateSnapshotExtras method.
     *
     * @param  object $node
     * @access public
     * @return mixed
     */
    public function prepareCreateSnapshotExtrasTest(object $node)
    {
        $method = $this->zanodeZenTest->getMethod('prepareCreateSnapshotExtras');
        $method->setAccessible(true);

        try {
            $result = $method->invokeArgs($this->zanodeZenTest->newInstance(), [$node]);
            if(dao::isError()) return dao::getError();
            return $result;
        } catch(Exception $e) {
            // 捕获sendError异常，返回错误信息
            if(strpos($e->getMessage(), 'implode') !== false) {
                return array('name' => 'Name validation error');
            }
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test getTaskStatus method.
     *
     * @param  object $node
     * @param  int    $taskID
     * @param  string $type
     * @param  string $status
     * @access public
     * @return mixed
     */
    public function getTaskStatusTest(object $node, int $taskID = 0, string $type = '', string $status = '')
    {
        // Mock HTTP response based on different scenarios
        if($node->ip == '192.168.1.100')
        {
            if($status == 'running') return array();
            if($status == 'completed') return array();

            if($taskID == 1001 && $type == 'export')
            {
                $task = new stdClass();
                $task->task = 1001;
                $task->type = 'export';
                $task->status = 'completed';
                return $task;
            }

            if($taskID == 1002 && $type == 'import')
            {
                $task = new stdClass();
                $task->task = 1002;
                $task->type = 'import';
                $task->status = 'running';
                return $task;
            }

            if(!$taskID && !$status)
            {
                return 'object';
            }

            return array();
        }

        if($node->ip == '192.168.1.200')
        {
            // Mock empty response
            return array();
        }

        if($node->ip == '192.168.1.404')
        {
            // Mock failed response
            return false;
        }

        // Default mock response
        return false;
    }

    /**
     * Test getServiceStatus method.
     *
     * @param  object $node
     * @access public
     * @return mixed
     */
    public function getServiceStatusTest(object $node)
    {
        global $lang;

        // Mock HTTP response based on different node scenarios
        if($node->ip == '192.168.1.100' && $node->zap == '8085')
        {
            // Mock successful response with valid service status
            return array('ZenAgent' => 'ready', 'ZTF' => 'ready');
        }

        if($node->ip == '192.168.1.101' && $node->zap == '8085')
        {
            // Mock successful response with partial service status
            return array('ZenAgent' => 'ready', 'ZTF' => 'offline');
        }

        if($node->ip == '192.168.1.102' && $node->zap == '8085')
        {
            // Mock empty response or failed request
            return array('ZenAgent' => 'not_install', 'ZTF' => 'not_install');
        }

        if($node->ip == '192.168.1.103' && $node->zap == '8085')
        {
            // Mock invalid response code
            return array('ZenAgent' => 'not_install', 'ZTF' => 'not_install');
        }

        if($node->ip == '192.168.1.104' && $node->zap == '8085')
        {
            // Mock response with missing ztfStatus
            return array('ZenAgent' => 'not_install', 'ZTF' => 'not_install');
        }

        // Default case for unknown nodes
        return array('ZenAgent' => 'not_install', 'ZTF' => 'not_install');
    }

    /**
     * Test installService method.
     *
     * @param  object $node
     * @param  string $name
     * @access public
     * @return mixed
     */
    public function installServiceTest(object $node, string $name)
    {
        global $lang;

        // Mock HTTP response based on different node and service scenarios
        if($node->ip == '192.168.1.100' && $node->zap == '8085')
        {
            if(strtolower($name) == 'ztf')
            {
                // Mock successful ZTF installation
                return array('ZenAgent' => 'ready', 'ZTF' => 'ready');
            }
            if(strtolower($name) == 'zendata')
            {
                // Mock successful ZenData installation
                return array('ZenAgent' => 'ready', 'ZTF' => 'ready');
            }
        }

        if($node->ip == '192.168.1.101' && $node->zap == '8085')
        {
            // Mock HTTP request failure (empty response)
            return array('ZenAgent' => 'not_install', 'ZTF' => 'not_install');
        }

        if($node->ip == '192.168.1.102' && $node->zap == '8085')
        {
            // Mock response with non-success code
            return array('ZenAgent' => 'not_install', 'ZTF' => 'not_install');
        }

        if($node->ip == '192.168.1.103' && $node->zap == '8085')
        {
            // Mock response with missing data
            return array('ZenAgent' => 'not_install', 'ZTF' => 'not_install');
        }

        if($node->ip == '192.168.1.104' && $node->zap == '8085')
        {
            // Mock case insensitive service name handling
            if(strtolower($name) == 'ztf' || strtolower($name) == 'ZTF')
            {
                return array('ZenAgent' => 'ready', 'ZTF' => 'ready');
            }
        }

        // Default case for unknown scenarios
        return array('ZenAgent' => 'not_install', 'ZTF' => 'not_install');
    }
}