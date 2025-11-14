#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getBuild();
timeout=0
cid=19407

- 步骤1：正常情况，验证ID字段值属性id @1
- 步骤2：验证name字段值属性name @Test build
- 步骤3：验证product和project字段
 - 属性product @1
 - 属性project @2
- 步骤4：验证关联字段
 - 属性executionName @Test execution
 - 属性productName @Test product
- 步骤5：验证builder和deleted字段
 - 属性builder @test
 - 属性deleted @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($tutorialTest->getBuildTest()) && p('id') && e('1'); // 步骤1：正常情况，验证ID字段值
r($tutorialTest->getBuildTest()) && p('name') && e('Test build'); // 步骤2：验证name字段值
r($tutorialTest->getBuildTest()) && p('product,project') && e('1,2'); // 步骤3：验证product和project字段
r($tutorialTest->getBuildTest()) && p('executionName,productName') && e('Test execution,Test product'); // 步骤4：验证关联字段
r($tutorialTest->getBuildTest()) && p('builder,deleted') && e('test,0'); // 步骤5：验证builder和deleted字段