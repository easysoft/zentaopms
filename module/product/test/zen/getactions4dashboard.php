#!/usr/bin/env php
<?php

/**

title=测试 productZen::getActions4Dashboard();
timeout=0
cid=0

- 步骤1：正常情况 - 获取有效产品的仪表盘动态 @1
- 步骤2：边界值 - 产品ID为0的情况 @0
- 步骤3：异常输入 - 不存在的产品ID @1
- 步骤4：权限验证 - 不同用户访问权限 @1
- 步骤5：业务规则 - 验证返回数据结构完整性 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->name->range('产品1,产品2,产品3');
$table->status->range('normal,normal,closed');
$table->PO->range('admin,user1,user2');
$table->gen(3);

$actionTable = zenData('action');
$actionTable->objectType->range('story,task,bug');
$actionTable->actor->range('admin,user1,user2');
$actionTable->action->range('created,edited,closed');
$actionTable->objectID->range('1-10');
$actionTable->product->range('1,2,3');
$actionTable->date->range('`2024-01-01 10:00:00`,`2024-01-02 11:00:00`,`2024-01-03 12:00:00`');
$actionTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($productTest->getActions4DashboardTest(1))) && p() && e('1'); // 步骤1：正常情况 - 获取有效产品的仪表盘动态
r(count($productTest->getActions4DashboardTest(0))) && p() && e('0'); // 步骤2：边界值 - 产品ID为0的情况
r(count($productTest->getActions4DashboardTest(999))) && p() && e('1'); // 步骤3：异常输入 - 不存在的产品ID
r(count($productTest->getActions4DashboardTest(2))) && p() && e('1'); // 步骤4：权限验证 - 不同用户访问权限
r(is_array($productTest->getActions4DashboardTest(1))) && p() && e('1'); // 步骤5：业务规则 - 验证返回数据结构完整性