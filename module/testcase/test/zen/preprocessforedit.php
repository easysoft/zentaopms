#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::preProcessForEdit();
timeout=0
cid=0

- 步骤1：已有steps的case对象保持不变属性firstStepDesc @existing desc
- 步骤2：空steps数组初始化默认步骤属性firstStepType @step
- 步骤3：null steps初始化默认步骤属性stepsCount @1
- 步骤4：false steps初始化默认步骤属性firstStepDesc @~~
- 步骤5：无steps属性初始化默认步骤属性testType @noSteps

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 4. 测试步骤（强制要求：必须包含至少5个测试步骤）

r($testcaseTest->preProcessForEditTest('withSteps')) && p('firstStepDesc') && e('existing desc'); // 步骤1：已有steps的case对象保持不变
r($testcaseTest->preProcessForEditTest('emptySteps')) && p('firstStepType') && e('step'); // 步骤2：空steps数组初始化默认步骤
r($testcaseTest->preProcessForEditTest('nullSteps')) && p('stepsCount') && e('1'); // 步骤3：null steps初始化默认步骤
r($testcaseTest->preProcessForEditTest('falseSteps')) && p('firstStepDesc') && e('~~'); // 步骤4：false steps初始化默认步骤
r($testcaseTest->preProcessForEditTest('noSteps')) && p('testType') && e('noSteps'); // 步骤5：无steps属性初始化默认步骤