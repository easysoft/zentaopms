#!/usr/bin/env php
<?php

/**

title=测试 todoZen::buildAssignToView();
timeout=0
cid=19293

- 步骤1：正常情况属性result @success
- 步骤2：边界值属性result @success
- 步骤3：异常输入属性result @success
- 步骤4：不存在记录属性result @success
- 步骤5：验证完整性属性result @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('todo');
$table->id->range('1-5');
$table->account->range('admin');
$table->date->range('20230101');
$table->begin->range('0800');
$table->end->range('1700');
$table->type->range('custom');
$table->name->range('测试待办');
$table->status->range('wait');
$table->pri->range('1');
$table->assignedTo->range('admin');
$table->assignedBy->range('admin');
$table->private->range('0');
$table->deleted->range('0');
$table->vision->range('rnd');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$todoTest = new todoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($todoTest->buildAssignToViewTest(1)) && p('result') && e('success'); // 步骤1：正常情况
r($todoTest->buildAssignToViewTest(0)) && p('result') && e('success'); // 步骤2：边界值
r($todoTest->buildAssignToViewTest(-1)) && p('result') && e('success'); // 步骤3：异常输入
r($todoTest->buildAssignToViewTest(999999)) && p('result') && e('success'); // 步骤4：不存在记录
r($todoTest->buildAssignToViewTest(5)) && p('result') && e('success'); // 步骤5：验证完整性