#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::processStepsForMindMap();
timeout=0
cid=19107

- 步骤1：正常用例处理脑图步骤第mindMapSteps条的id属性 @case_1
- 步骤2：空步骤数组处理第mindMapSteps条的id属性 @case_2
- 步骤3：多层级步骤处理第mindMapSteps条的text属性 @测试用例3
- 步骤4：包含分组步骤处理第mindMapSteps条的id属性 @case_4
- 步骤5：期望值为空的步骤处理第mindMapSteps条的type属性 @root

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->processStepsForMindMapTest($testcaseTest->getCaseWithSteps(1))) && p('mindMapSteps:id') && e('case_1'); // 步骤1：正常用例处理脑图步骤
r($testcaseTest->processStepsForMindMapTest($testcaseTest->getCaseWithSteps(2, array()))) && p('mindMapSteps:id') && e('case_2'); // 步骤2：空步骤数组处理
r($testcaseTest->processStepsForMindMapTest($testcaseTest->getCaseWithSteps(3))) && p('mindMapSteps:text') && e('测试用例3'); // 步骤3：多层级步骤处理
r($testcaseTest->processStepsForMindMapTest($testcaseTest->getCaseWithSteps(4))) && p('mindMapSteps:id') && e('case_4'); // 步骤4：包含分组步骤处理
r($testcaseTest->processStepsForMindMapTest($testcaseTest->getCaseWithSteps(5))) && p('mindMapSteps:type') && e('root'); // 步骤5：期望值为空的步骤处理