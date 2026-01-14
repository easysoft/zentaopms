#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('case')->gen(10);
zenData('testrun')->gen(10);
zenData('testtask')->gen(10);
zenData('user')->gen(1);

su('admin');

/**

title=测试 testcaseModel->getByAssignedTo();
timeout=0
cid=18975

- 测试获取指派给 admin 的用例执行 @5,1

- 测试获取指派给 user2 的用例执行 @6,2

- 测试获取指派给 test3 的用例执行 @7,3

- 测试获取指派给 dev4 的用例执行 @8,4

- 测试获取指派给 test5 的用例执行 @0

*/

$accountList = array('admin', 'user2', 'test3', 'dev4', 'test5');

$testcase = new testcaseModelTest();

r($testcase->getByAssignedToTest($accountList[0])) && p() && e('5,1'); // 测试获取指派给 admin 的用例执行
r($testcase->getByAssignedToTest($accountList[1])) && p() && e('6,2'); // 测试获取指派给 user2 的用例执行
r($testcase->getByAssignedToTest($accountList[2])) && p() && e('7,3'); // 测试获取指派给 test3 的用例执行
r($testcase->getByAssignedToTest($accountList[3])) && p() && e('8,4'); // 测试获取指派给 dev4 的用例执行
r($testcase->getByAssignedToTest($accountList[4])) && p() && e(0);     // 测试获取指派给 test5 的用例执行
