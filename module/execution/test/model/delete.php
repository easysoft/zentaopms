#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';
zenData('user')->gen(5);
su('admin');

/**

title=测试executionModel->activateTest();
cid=16290

- 测试删除迭代5 @1
- 测试删除迭代6 @1
- 测试删除迭代7 @1
- 测试删除迭代8 @1
- 测试删除迭代9 @1

*/

zenData('project')->loadYaml('execution')->gen(10);

$execution = new executionTest();
r($execution->deleteTest(101)) && p() && e('1'); //测试删除迭代5
r($execution->deleteTest(102)) && p() && e('1'); //测试删除迭代6
r($execution->deleteTest(103)) && p() && e('1'); //测试删除迭代7
r($execution->deleteTest(104)) && p() && e('1'); //测试删除迭代8
r($execution->deleteTest(105)) && p() && e('1'); //测试删除迭代9
