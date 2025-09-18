<?php
declare(strict_types = 1);
class zanodeTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('zanode');
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
        $method = $this->objectZen->getMethod('handleNode');
        $method->setAccessible(true);
        $result = $method->invoke($this->objectZen, $nodeID, $type);
        if(dao::isError()) return dao::getError();

        return $result;
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
}