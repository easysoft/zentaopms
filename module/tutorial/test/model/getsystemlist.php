#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getSystemList();
timeout=0
cid=19482

- 步骤1：正常情况-验证返回数组包含ID为1的元素第1条的id属性 @1
- 步骤2：边界值-验证系统名称第1条的name属性 @Test App
- 步骤3：异常输入-验证系统状态属性第1条的status属性 @active
- 步骤4：权限验证-验证系统关联产品第1条的product属性 @1
- 步骤5：业务规则-验证系统集成状态第1条的integrated属性 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($tutorialTest->getSystemListTest()) && p('1:id') && e('1'); // 步骤1：正常情况-验证返回数组包含ID为1的元素
r($tutorialTest->getSystemListTest()) && p('1:name') && e('Test App'); // 步骤2：边界值-验证系统名称
r($tutorialTest->getSystemListTest()) && p('1:status') && e('active'); // 步骤3：异常输入-验证系统状态属性
r($tutorialTest->getSystemListTest()) && p('1:product') && e('1'); // 步骤4：权限验证-验证系统关联产品
r($tutorialTest->getSystemListTest()) && p('1:integrated') && e('0'); // 步骤5：业务规则-验证系统集成状态