#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getRelease();
timeout=0
cid=19459

- 步骤1：正常情况，验证ID属性id @1
- 步骤2：验证名称属性name @Test release
- 步骤3：验证状态属性status @wait
- 步骤4：验证产品ID属性product @1
- 步骤5：验证系统ID属性system @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($tutorialTest->getReleaseTest()) && p('id') && e('1'); // 步骤1：正常情况，验证ID
r($tutorialTest->getReleaseTest()) && p('name') && e('Test release'); // 步骤2：验证名称
r($tutorialTest->getReleaseTest()) && p('status') && e('wait'); // 步骤3：验证状态
r($tutorialTest->getReleaseTest()) && p('product') && e('1'); // 步骤4：验证产品ID
r($tutorialTest->getReleaseTest()) && p('system') && e('1'); // 步骤5：验证系统ID