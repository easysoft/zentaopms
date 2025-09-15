#!/usr/bin/env php
<?php

/**

title=测试 productZen::getEmptyHour();
timeout=0
cid=0

- 步骤1：正常调用情况 - 获取空小时对象
 - 属性totalEstimate @0
 - 属性totalConsumed @0
 - 属性totalLeft @0
 - 属性progress @0
- 步骤2：验证totalEstimate字段 - 检查预估工时初始值属性totalEstimate @0
- 步骤3：验证totalConsumed字段 - 检查消耗工时初始值属性totalConsumed @0
- 步骤4：验证totalLeft字段 - 检查剩余工时初始值属性totalLeft @0
- 步骤5：验证progress字段 - 检查进度初始值属性progress @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（不需要额外数据，因为getEmptyHour方法不依赖数据库）

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($productTest->getEmptyHourTest()) && p('totalEstimate,totalConsumed,totalLeft,progress') && e('0,0,0,0'); // 步骤1：正常调用情况 - 获取空小时对象
r($productTest->getEmptyHourTest()) && p('totalEstimate') && e('0'); // 步骤2：验证totalEstimate字段 - 检查预估工时初始值
r($productTest->getEmptyHourTest()) && p('totalConsumed') && e('0'); // 步骤3：验证totalConsumed字段 - 检查消耗工时初始值
r($productTest->getEmptyHourTest()) && p('totalLeft') && e('0'); // 步骤4：验证totalLeft字段 - 检查剩余工时初始值
r($productTest->getEmptyHourTest()) && p('progress') && e('0'); // 步骤5：验证progress字段 - 检查进度初始值