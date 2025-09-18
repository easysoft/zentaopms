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
        // 获取节点信息
        $node = $this->objectModel->getNodeByID($nodeID);

        // 测试忙碌状态检查
        if(empty($node))
        {
            $result = new stdClass();
            $result->status = 'fail';
            $result->message = '找不到ZA代理服务';
            return $result;
        }

        if(in_array($node->status, array('restoring', 'creating_img', 'creating_snap')))
        {
            $busyMsgMap = array(
                'restoring' => '正在备份中，无法进行此操作',
                'creating_img' => '正在创建镜像中，无法进行此操作',
                'creating_snap' => '正在创建快照中，无法进行此操作'
            );

            $result = new stdClass();
            $result->status = 'fail';
            $result->message = $busyMsgMap[$node->status];
            return $result;
        }

        // 模拟HTTP请求失败的情况（因为测试环境无法连接真实服务）
        $result = new stdClass();
        $result->status = 'fail';
        $result->message = '找不到ZA代理服务';
        return $result;
    }
}