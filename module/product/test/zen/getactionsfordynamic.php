#!/usr/bin/env php
<?php

/**

title=测试 productZen::getActionsForDynamic();
timeout=0
cid=0

- 步骤1：正常情况 - 管理员获取今日动态 @2
- 步骤2：边界值 - 空用户获取所有动态 @2
- 步骤3：异常输入 - 不存在的产品ID @2
- 步骤4：权限验证 - 访客用户权限 @2
- 步骤5：业务规则 - 指定日期时间戳 @2

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
$actionTable->date->range('`2024-01-01 10:00:00`,`2024-01-02 11:00:00`,`2024-01-03 12:00:00`');
$actionTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($productTest->getActionsForDynamicTest('admin', 'date_desc', 1, 'today', '', 'next'))) && p() && e('2'); // 步骤1：正常情况 - 管理员获取今日动态
r(count($productTest->getActionsForDynamicTest('', 'date_desc', 1, 'all', '', 'next'))) && p() && e('2'); // 步骤2：边界值 - 空用户获取所有动态  
r(count($productTest->getActionsForDynamicTest('user1', 'id_asc', 999, 'week', '', 'next'))) && p() && e('2'); // 步骤3：异常输入 - 不存在的产品ID
r(count($productTest->getActionsForDynamicTest('guest', 'date_desc', 1, 'month', '', 'pre'))) && p() && e('2'); // 步骤4：权限验证 - 访客用户权限
r(count($productTest->getActionsForDynamicTest('admin', 'date_desc', 1, 'account', '1640995200', 'next'))) && p() && e('2'); // 步骤5：业务规则 - 指定日期时间戳