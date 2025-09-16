<?php
declare(strict_types = 1);
class expandExecutionIdListTest
{
    public function __construct()
    {
        global $tester, $app;
        $this->objectModel = $tester->loadModel('project');

        // 直接创建 zen 实例
        include_once dirname(__FILE__, 3) . '/control.php';
        include_once dirname(__FILE__, 3) . '/zen.php';
        $this->objectZen = new projectZen();

        // 初始化zen对象的依赖属性
        $this->objectZen->project = $this->objectModel;
        $this->objectZen->loadModel = function($modelName) use ($tester) {
            return $tester->loadModel($modelName);
        };
    }

    /**
     * Test expandExecutionIdList method.
     *
     * @param  mixed $stats
     * @access public
     * @return mixed
     */
    public function expandExecutionIdListTest($stats = null)
    {
        try
        {
            // 根据输入参数构造测试数据
            $testStats = $this->createTestExecutionStats($stats);

            $reflection = new ReflectionClass($this->objectZen);
            $method = $reflection->getMethod('expandExecutionIdList');
            $method->setAccessible(true);

            $result = $method->invoke($this->objectZen, $testStats);

            if(dao::isError()) return dao::getError();

            return count($result);
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

    /**
     * Create test execution stats data.
     *
     * @param  mixed $type
     * @access private
     * @return array
     */
    private function createTestExecutionStats($type)
    {
        if($type === array() || $type === null) return array();

        switch($type)
        {
            case 'single_execution':
                $execution = new stdClass();
                $execution->id = 1;
                $execution->children = array();
                return array($execution);

            case 'nested_executions':
                $parent = new stdClass();
                $parent->id = 1;
                $child1 = new stdClass();
                $child1->id = 2;
                $child1->children = array();
                $child2 = new stdClass();
                $child2->id = 3;
                $child2->children = array();
                $parent->children = array($child1, $child2);
                return array($parent);

            case 'multi_level_nesting':
                $grandparent = new stdClass();
                $grandparent->id = 1;
                $parent1 = new stdClass();
                $parent1->id = 2;
                $child1 = new stdClass();
                $child1->id = 3;
                $child1->children = array();
                $child2 = new stdClass();
                $child2->id = 4;
                $child2->children = array();
                $parent1->children = array($child1, $child2);
                $parent2 = new stdClass();
                $parent2->id = 5;
                $grandchild = new stdClass();
                $grandchild->id = 6;
                $greatgrandchild = new stdClass();
                $greatgrandchild->id = 7;
                $greatgrandchild->children = array();
                $grandchild->children = array($greatgrandchild);
                $parent2->children = array($grandchild);
                $grandparent->children = array($parent1, $parent2);
                return array($grandparent);

            case 'mixed_executions':
                $exec1 = new stdClass();
                $exec1->id = 1;
                $exec1->children = array();
                $exec2 = new stdClass();
                $exec2->id = 2;
                $child = new stdClass();
                $child->id = 3;
                $child->children = array();
                $exec2->children = array($child);
                $exec3 = new stdClass();
                $exec3->id = 4;
                $exec3->children = array();
                return array($exec1, $exec2, $exec3);

            default:
                return array();
        }
    }
}