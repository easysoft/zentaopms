<?php
class commonTest
{
    public $objectModel;

    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('common');
    }

    /**
     * 检查详情页操作按钮的权限。
     * Check the privilege of the operate action.
     *
     * @param  string     $moduleName
     * @param  object     $data
     * @param  object     $menu
     * @access protected
     * @return array|bool
     */
    public function checkPrivForOperateActionTest(string $moduleName, string $action, array $actionData)
    {
        global $config;
        $object = $this->objectModel->dao->select('*')->from($config->objectTables[$moduleName])->where('id')->eq(1)->fetch();

        $result = $this->objectModel->checkPrivForOperateAction($actionData, $action, $moduleName, $object, 'mainActions');
        return is_array($result) ? !empty($result['url']) : $result;
    }

    /**
     * Test sendHeader method.
     *
     * @param  array $configData
     * @access public
     * @return array
     */
    public function sendHeaderTest($configData = array())
    {
        global $config;
        
        // 备份原始配置
        $originalConfig = array();
        $originalConfig['charset'] = $config->charset;
        $originalConfig['framework'] = clone $config->framework;
        $originalConfig['CSPs'] = isset($config->CSPs) ? $config->CSPs : array();
        $originalConfig['xFrameOptions'] = isset($config->xFrameOptions) ? $config->xFrameOptions : '';
        
        // 应用测试配置
        if(!empty($configData))
        {
            foreach($configData as $key => $value)
            {
                if($key == 'framework')
                {
                    foreach($value as $fKey => $fValue)
                    {
                        $config->framework->$fKey = $fValue;
                    }
                }
                else
                {
                    $config->$key = $value;
                }
            }
        }
        
        // 捕获输出的HTTP头信息
        $sentHeaders = array();
        
        // 模拟header函数的输出
        $this->mockHeaderFunction();
        
        // 调用被测试方法
        $this->objectModel->sendHeader();
        
        // 获取发送的头信息
        $sentHeaders = $this->getMockedHeaders();
        
        // 恢复原始配置
        $config->charset = $originalConfig['charset'];
        $config->framework = $originalConfig['framework'];
        $config->CSPs = $originalConfig['CSPs'];
        $config->xFrameOptions = $originalConfig['xFrameOptions'];
        
        return $sentHeaders;
    }
    
    private function mockHeaderFunction()
    {
        global $mockHeaders;
        $mockHeaders = array();
        
        if (!function_exists('mockHelper')) {
            // 创建helper类的mock
            global $app;
            if(!isset($app->mockHelper)) $app->mockHelper = new stdClass();
        }
    }
    
    private function getMockedHeaders()
    {
        global $mockHeaders;
        return isset($mockHeaders) ? $mockHeaders : array();
    }
}
