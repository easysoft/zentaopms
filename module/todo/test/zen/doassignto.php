#!/usr/bin/env php
<?php

/**

title=测试 todoZen::doAssignTo();
timeout=0
cid=19297

- 步骤1：正常指派待办给指定用户 @1
- 步骤2：指派不存在的待办ID @0
- 步骤3：指派待办但未设置assignedTo @0
- 步骤4：传入空对象进行指派 @0
- 步骤5：传入非对象参数进行指派 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('todo')->loadYaml('todo_doassignto', false, 2)->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$todoTest = new todoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($todoTest->doAssignToTest((object)array('id' => 1, 'assignedTo' => 'test1', 'assignedBy' => 'admin', 'assignedDate' => date('Y-m-d H:i:s')))) && p() && e('1'); // 步骤1：正常指派待办给指定用户
r($todoTest->doAssignToTest((object)array('id' => 0, 'assignedTo' => 'test1', 'assignedBy' => 'admin', 'assignedDate' => date('Y-m-d H:i:s')))) && p() && e('0'); // 步骤2：指派不存在的待办ID
r($todoTest->doAssignToTest((object)array('id' => 2, 'assignedBy' => 'admin', 'assignedDate' => date('Y-m-d H:i:s')))) && p() && e('0'); // 步骤3：指派待办但未设置assignedTo
r($todoTest->doAssignToTest(new stdClass())) && p() && e('0'); // 步骤4：传入空对象进行指派
r($todoTest->doAssignToTest('invalid_parameter')) && p() && e('0'); // 步骤5：传入非对象参数进行指派