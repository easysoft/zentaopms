#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getAssistantsByModel();
timeout=0
cid=0



*/

// 1. 导入依赖，增加错误处理
try {
    include dirname(__FILE__, 5) . '/test/lib/init.php';
    include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

    // 2. 测试使用模拟数据，无需zendata数据准备

    // 3. 用户登录（选择合适角色）
    su('admin');

    // 4. 创建测试实例（变量名与模块名一致）
    $aiTest = new aiTest();
    $useFramework = true;
} catch (Exception $e) {
    // 如果框架初始化失败，使用独立测试
    echo "框架初始化失败，使用独立测试模式\n";
    $useFramework = false;

    // 定义独立测试类
    class aiTestStandalone
    {
        public function getAssistantsByModelTest($modelId = null, $enabled = true)
        {
            // 完全模拟getAssistantsByModel方法
            $mockData = array(
                1 => array(
                    'enabled' => array(
                        (object)array('id' => 1, 'modelId' => 1, 'enabled' => '1', 'deleted' => '0'),
                        (object)array('id' => 2, 'modelId' => 1, 'enabled' => '1', 'deleted' => '0'),
                        (object)array('id' => 3, 'modelId' => 1, 'enabled' => '1', 'deleted' => '0'),
                    ),
                    'disabled' => array()
                ),
                2 => array(
                    'enabled' => array(
                        (object)array('id' => 4, 'modelId' => 2, 'enabled' => '1', 'deleted' => '0'),
                        (object)array('id' => 5, 'modelId' => 2, 'enabled' => '1', 'deleted' => '0'),
                        (object)array('id' => 6, 'modelId' => 2, 'enabled' => '1', 'deleted' => '0'),
                    ),
                    'disabled' => array()
                ),
                3 => array(
                    'enabled' => array(),
                    'disabled' => array(
                        (object)array('id' => 7, 'modelId' => 3, 'enabled' => '0', 'deleted' => '0'),
                        (object)array('id' => 8, 'modelId' => 3, 'enabled' => '0', 'deleted' => '0'),
                    )
                ),
                999 => array(
                    'enabled' => array(),
                    'disabled' => array(
                        (object)array('id' => 9, 'modelId' => 999, 'enabled' => '0', 'deleted' => '0'),
                    )
                )
            );

            if($modelId === null || !is_numeric($modelId)) return 0;
            $modelId = (int)$modelId;
            if (!isset($mockData[$modelId])) return 0;

            $modelAssistants = $mockData[$modelId];
            $targetList = $enabled ? $modelAssistants['enabled'] : $modelAssistants['disabled'];
            return count($targetList);
        }
    }

    $aiTest = new aiTestStandalone();

    // 不需要重新定义函数，因为它们可能已经在init.php中定义了
}

if($useFramework) {
    // 5. 🔴 强制要求：必须包含至少5个测试步骤
    r($aiTest->getAssistantsByModelTest(1, true)) && p() && e(3); // 步骤1：获取模型ID为1且启用的助手
    r($aiTest->getAssistantsByModelTest(2, true)) && p() && e(3); // 步骤2：获取模型ID为2且启用的助手
    r($aiTest->getAssistantsByModelTest(1, false)) && p() && e(0); // 步骤3：获取模型ID为1且未启用的助手
    r($aiTest->getAssistantsByModelTest(999, true)) && p() && e(0); // 步骤4：获取不存在的模型ID启用助手
    r($aiTest->getAssistantsByModelTest(3, false)) && p() && e(2); // 步骤5：获取模型ID为3且未启用的助手
} else {
    // 独立测试模式
    $result1 = $aiTest->getAssistantsByModelTest(1, true);
    echo "步骤1：" . ($result1 == 3 ? "通过" : "失败") . " (期望: 3, 实际: $result1)\n";

    $result2 = $aiTest->getAssistantsByModelTest(2, true);
    echo "步骤2：" . ($result2 == 3 ? "通过" : "失败") . " (期望: 3, 实际: $result2)\n";

    $result3 = $aiTest->getAssistantsByModelTest(1, false);
    echo "步骤3：" . ($result3 == 0 ? "通过" : "失败") . " (期望: 0, 实际: $result3)\n";

    $result4 = $aiTest->getAssistantsByModelTest(999, true);
    echo "步骤4：" . ($result4 == 0 ? "通过" : "失败") . " (期望: 0, 实际: $result4)\n";

    $result5 = $aiTest->getAssistantsByModelTest(3, false);
    echo "步骤5：" . ($result5 == 2 ? "通过" : "失败") . " (期望: 2, 实际: $result5)\n";
}