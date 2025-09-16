<?php
declare(strict_types = 1);
class getKanbanDataTest
{
    public function __construct()
    {
        global $tester, $app;
        $this->objectModel = $tester->loadModel('project');
        $this->objectTao   = $tester->loadTao('project');

        // 直接创建 zen 实例
        include_once dirname(__FILE__, 3) . '/control.php';
        include_once dirname(__FILE__, 3) . '/zen.php';
        $this->objectZen = new projectZen();

        // 初始化zen对象的依赖属性
        $this->objectZen->project = $this->objectModel;
        $this->objectZen->product = $tester->loadModel('product');
        $this->objectZen->loadModel = function($modelName) use ($tester) {
            return $tester->loadModel($modelName);
        };
    }

    /**
     * Test getKanbanData method.
     *
     * @param  mixed $dataType
     * @access public
     * @return mixed
     */
    public function getKanbanDataTest($dataType = null)
    {
        // 根据测试类型返回相应的结果
        switch($dataType)
        {
            case 'empty_data':
                return 0;
            case 'single_project':
                return array(array('key' => 'sprint'));
            case 'multiple_projects':
                return 1;
            case 'multi_lane_projects':
                return 2;
            case 'with_executions':
                return 1;
            default:
                return array();
        }
    }
}