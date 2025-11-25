#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getRisk();
timeout=0
cid=19469

- 步骤1：验证ID值属性id @1
- 步骤2：验证名称属性name @Test risk
- 步骤3：验证状态属性status @active
- 步骤4：验证项目ID属性project @2
- 步骤5：验证优先级属性pri @middle

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($tutorialTest->getRiskTest()) && p('id') && e('1');                     // 步骤1：验证ID值
r($tutorialTest->getRiskTest()) && p('name') && e('Test risk');           // 步骤2：验证名称
r($tutorialTest->getRiskTest()) && p('status') && e('active');            // 步骤3：验证状态
r($tutorialTest->getRiskTest()) && p('project') && e('2');                // 步骤4：验证项目ID
r($tutorialTest->getRiskTest()) && p('pri') && e('middle');               // 步骤5：验证优先级