#!/usr/bin/env php
<?php

/**

title=测试 taskZen::prepareManageTeam();
timeout=0
cid=18936

- 步骤1：正常表单数据和有效任务ID
 - 属性id @1
 - 属性lastEditedBy @admin
- 步骤2：空表单数据和有效任务ID
 - 属性id @5
 - 属性lastEditedBy @admin
- 步骤3：任务ID为0的边界值测试
 - 属性id @0
 - 属性lastEditedBy @admin
- 步骤4：负数任务ID测试
 - 属性id @-1
 - 属性lastEditedBy @admin
- 步骤5：大数值任务ID测试
 - 属性id @99999
 - 属性lastEditedBy @admin

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('task');
$table->id->range('1-100');
$table->name->range('任务{1-100}');
$table->lastEditedBy->range('admin,user1,user2');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskTest = new taskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($taskTest->prepareManageTeamTest(null, 1)) && p('id,lastEditedBy') && e('1,admin'); // 步骤1：正常表单数据和有效任务ID
r($taskTest->prepareManageTeamTest((object)array(), 5)) && p('id,lastEditedBy') && e('5,admin'); // 步骤2：空表单数据和有效任务ID
r($taskTest->prepareManageTeamTest(null, 0)) && p('id,lastEditedBy') && e('0,admin'); // 步骤3：任务ID为0的边界值测试
r($taskTest->prepareManageTeamTest(null, -1)) && p('id,lastEditedBy') && e('-1,admin'); // 步骤4：负数任务ID测试
r($taskTest->prepareManageTeamTest(null, 99999)) && p('id,lastEditedBy') && e('99999,admin'); // 步骤5：大数值任务ID测试