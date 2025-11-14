#!/usr/bin/env php
<?php

/**

title=测试 todoZen::beforeAssignTo();
timeout=0
cid=19289

- 步骤1：正常指派数据处理属性assignedBy @admin
- 步骤2：未来日期设置属性date @2030-01-01
- 步骤3：禁用日期时间
 - 属性begin @2400
 - 属性end @2400
- 步骤4：空表单数据处理属性assignedBy @admin
- 步骤5：完整指派流程验证
 - 属性assignedTo @user1
 - 属性assignedBy @admin

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('todo');
$table->id->range('1-10');
$table->account->range('admin,user1,user2');
$table->name->range('待办1,待办2,待办3,待办4,待办5,待办6,待办7,待办8,待办9,待办10');
$table->type->range('custom{5},task{3},bug{2}');
$table->status->range('wait{8},done{2}');
$table->pri->range('1-3');
$table->assignedTo->range('admin,user1,user2');
$table->assignedBy->range('admin');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$todoTest = new todoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($todoTest->beforeAssignToTest((object)array('assignedTo' => 'user1'))) && p('assignedBy') && e('admin'); // 步骤1：正常指派数据处理
r($todoTest->beforeAssignToTest((object)array('assignedTo' => 'user2', 'future' => true))) && p('date') && e('2030-01-01'); // 步骤2：未来日期设置
r($todoTest->beforeAssignToTest((object)array('assignedTo' => 'user1', 'lblDisableDate' => true))) && p('begin,end') && e('2400,2400'); // 步骤3：禁用日期时间
r($todoTest->beforeAssignToTest((object)array())) && p('assignedBy') && e('admin'); // 步骤4：空表单数据处理
r($todoTest->beforeAssignToTest((object)array('assignedTo' => 'user1', 'date' => '2023-12-01', 'begin' => '0900', 'end' => '1800'))) && p('assignedTo,assignedBy') && e('user1,admin'); // 步骤5：完整指派流程验证