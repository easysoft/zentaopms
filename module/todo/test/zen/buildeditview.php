#!/usr/bin/env php
<?php

/**

title=测试 todoZen::buildEditView();
timeout=0
cid=19296

- 步骤1：正常情况属性result @success
- 步骤2：时间戳日期属性dateFormatted @1
- 步骤3：空对象属性result @success
- 步骤4：不同类型属性result @success
- 步骤5：视图属性验证
 - 属性times @1
 - 属性users @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

// 2. zendata数据准备（简化数据准备，避免日期格式问题）
$table = zenData('user');
$table->id->range('1-3');
$table->account->range('admin,user1,user2');
$table->realname->range('管理员,用户1,用户2');
$table->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$todoTest = new todoTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($todoTest->buildEditViewTest()) && p('result') && e('success'); // 步骤1：正常情况
r($todoTest->buildEditViewTest((object)array('id' => 1, 'name' => 'Test', 'date' => '2023-12-01 10:30:00', 'account' => 'admin', 'type' => 'custom', 'status' => 'wait', 'pri' => 2, 'begin' => '0830', 'end' => '1730'))) && p('dateFormatted') && e('1'); // 步骤2：时间戳日期
r($todoTest->buildEditViewTest((object)array())) && p('result') && e('success'); // 步骤3：空对象
r($todoTest->buildEditViewTest((object)array('id' => 2, 'name' => 'Bug Todo', 'date' => '2023-12-02', 'account' => 'user1', 'type' => 'bug', 'status' => 'doing', 'pri' => 1, 'begin' => '0900', 'end' => '1800'))) && p('result') && e('success'); // 步骤4：不同类型
r($todoTest->buildEditViewTest((object)array('id' => 3, 'name' => 'Task Todo', 'date' => '2023-12-03', 'account' => 'user2', 'type' => 'task', 'status' => 'done', 'pri' => 3, 'begin' => '1000', 'end' => '1900'))) && p('times,users') && e('1,1'); // 步骤5：视图属性验证