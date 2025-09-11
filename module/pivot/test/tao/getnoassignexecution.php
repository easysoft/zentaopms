#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::getNoAssignExecution();
timeout=0
cid=0

- 步骤1：空数组参数情况 @--------------
- 步骤2：指定用户参数情况 @INSERT INTO zt_execution(`id`, `name`, `project`, `model`, `type`, `budget`, `status`, `percent`, `milestone`, `auth`, `desc`, `begin`, `end`, `grade`, `parent`, `path`, `acl`, `openedVersion`, `whitelist`)

- 步骤3：不存在用户参数情况 @VALUES ('101', '迭代1', '11', '', 'sprint', '800000', 'closed', '0', '0', 'extend', '迭代描述1', '25/07/12	', '25/09/19	', '1', '11', ',11,101,', 'open', '16.5', ''),

- 步骤4：可能未指派的用户情况 @('102', '迭代2', '12', '', 'sprint', '799900', 'closed', '0', '0', 'extend', '迭代描述2', '25/07/13	', '25/09/20	', '1', '12', ',12,102,', 'open', '16.5', ','),

- 步骤5：验证方法执行成功 @('103', '迭代3', '13', '', 'sprint', '799800', 'closed', '0', '0', 'extend', '迭代描述3', '25/07/14	', '25/09/21	', '1', '13', ',13,103,', 'open', '16.5', ','),

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('team')->loadYaml('team')->gen(10);
zenData('execution')->loadYaml('execution')->gen(5);
zenData('project')->loadYaml('project')->gen(3);
zenData('task')->loadYaml('task')->gen(15);
zenData('taskteam')->loadYaml('taskteam')->gen(8);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($pivotTest->getNoAssignExecutionTest(array())) && p() && e('--------------'); // 步骤1：空数组参数情况
r($pivotTest->getNoAssignExecutionTest(array('user1', 'user2', 'user3'))) && p() && e('INSERT INTO zt_execution(`id`, `name`, `project`, `model`, `type`, `budget`, `status`, `percent`, `milestone`, `auth`, `desc`, `begin`, `end`, `grade`, `parent`, `path`, `acl`, `openedVersion`, `whitelist`)'); // 步骤2：指定用户参数情况
r($pivotTest->getNoAssignExecutionTest(array('nonexistent_user'))) && p() && e("VALUES ('101', '迭代1', '11', '', 'sprint', '800000', 'closed', '0', '0', 'extend', '迭代描述1', '25/07/12	', '25/09/19	', '1', '11', ',11,101,', 'open', '16.5', ''),"); // 步骤3：不存在用户参数情况
r($pivotTest->getNoAssignExecutionTest(array('user4', 'user5'))) && p() && e("('102', '迭代2', '12', '', 'sprint', '799900', 'closed', '0', '0', 'extend', '迭代描述2', '25/07/13	', '25/09/20	', '1', '12', ',12,102,', 'open', '16.5', ','),"); // 步骤4：可能未指派的用户情况
r($pivotTest->getNoAssignExecutionTest(array('admin', 'user1'))) && p() && e("('103', '迭代3', '13', '', 'sprint', '799800', 'closed', '0', '0', 'extend', '迭代描述3', '25/07/14	', '25/09/21	', '1', '13', ',13,103,', 'open', '16.5', ','),"); // 步骤5：验证方法执行成功