#!/usr/bin/env php
<?php

/**

title=测试 reportModel::getUserTestTasks();
timeout=0
cid=18171

- 测试步骤1：正常情况下获取用户测试任务 @user3:2;user4:2;user7:2;user8:2;user11:1;user12:1;
- 测试步骤2：验证用户3的测试任务数量属性user3 @2
- 测试步骤3：验证用户4的测试任务数量属性user4 @2
- 测试步骤4：验证用户7的测试任务数量属性user7 @2
- 测试步骤5：验证用户8的测试任务数量属性user8 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$testtaskTable = zenData('testtask');
$testtaskTable->loadYaml('testtask_getusertesttasks', false, 2)->gen(20);

$userTable = zenData('user');
$userTable->loadYaml('user_getusertesttasks', false, 2)->gen(20);

su('admin');

$reportTest = new reportModelTest();

r($reportTest->getUserTestTasksTest()) && p() && e('user3:2;user4:2;user7:2;user8:2;user11:1;user12:1;'); // 测试步骤1：正常情况下获取用户测试任务
r($reportTest->getUserTestTasksTest('array')) && p('user3') && e('2'); // 测试步骤2：验证用户3的测试任务数量
r($reportTest->getUserTestTasksTest('array')) && p('user4') && e('2'); // 测试步骤3：验证用户4的测试任务数量
r($reportTest->getUserTestTasksTest('array')) && p('user7') && e('2'); // 测试步骤4：验证用户7的测试任务数量
r($reportTest->getUserTestTasksTest('array')) && p('user8') && e('2'); // 测试步骤5：验证用户8的测试任务数量