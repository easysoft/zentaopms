#!/usr/bin/env php
<?php

/**

title=测试 todoZen::buildBatchCreateView();
timeout=0
cid=19294

- 步骤1：正常日期格式属性result @success
- 步骤2：空字符串日期属性result @success
- 步骤3：数字0日期属性result @success
- 步骤4：未来日期格式属性result @success
- 步骤5：无效日期格式属性result @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('user');
$table->id->range('1-10');
$table->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$table->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$table->password->range('123456{10}');
$table->role->range('qa{3},dev{5},pm{2}');
$table->deleted->range('0{10}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$todoTest = new todoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($todoTest->buildBatchCreateViewTest('2024-01-01')) && p('result') && e('success'); // 步骤1：正常日期格式
r($todoTest->buildBatchCreateViewTest('')) && p('result') && e('success'); // 步骤2：空字符串日期
r($todoTest->buildBatchCreateViewTest('0')) && p('result') && e('success'); // 步骤3：数字0日期
r($todoTest->buildBatchCreateViewTest('2025-12-31')) && p('result') && e('success'); // 步骤4：未来日期格式
r($todoTest->buildBatchCreateViewTest('invalid-date')) && p('result') && e('success'); // 步骤5：无效日期格式