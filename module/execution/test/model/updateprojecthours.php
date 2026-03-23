#!/usr/bin/env php
<?php
/**

title=测试executionModel->updateProjectHours();
timeout=0
cid=16258

- 测试空数据 @1
- 测试将ID为3的执行编辑到ID为2的项目后，更新项目的工时 @1
- 测试将ID为4的执行编辑到ID为2的项目后，更新项目的工时 @1
- 测试将ID为3的执行编辑到ID为1的项目后，更新项目的工时 @1
- 测试将ID为5的执行编辑到ID为1的项目后，更新项目的工时 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

zenData('effort')->gen(0);
zenData('project')->loadYaml('project')->gen(5);
zenData('user')->gen(5);
su('admin');

$executionTester = new executionTest();
r($executionTester->updateProjectHoursTest(0, 0, 0)) && p() && e('1'); // 测试空数据
r($executionTester->updateProjectHoursTest(2, 1, 3)) && p() && e('1'); // 测试将ID为3的执行编辑到ID为2的项目后，更新项目的工时
r($executionTester->updateProjectHoursTest(2, 1, 4)) && p() && e('1'); // 测试将ID为4的执行编辑到ID为2的项目后，更新项目的工时
r($executionTester->updateProjectHoursTest(1, 2, 3)) && p() && e('1'); // 测试将ID为3的执行编辑到ID为1的项目后，更新项目的工时
r($executionTester->updateProjectHoursTest(1, 2, 5)) && p() && e('1'); // 测试将ID为5的执行编辑到ID为1的项目后，更新项目的工时
