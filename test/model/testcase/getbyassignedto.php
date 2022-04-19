#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->getByAssignedTo();
cid=1
pid=1

测试获取指派给 admin 的用例执行 >> 37,33,29,21,17,13,5,1
测试获取指派给 user2 的用例执行 >> 38,34,30,22,18,14,6,2
测试获取指派给 test3 的用例执行 >> 39,35,31,23,19,15,7,3
测试获取指派给 dev4 的用例执行 >> 40,36,32,24,20,16,8,4

*/

$accountList = array('admin', 'user2', 'test3', 'dev4');

$testcase = new testcaseTest();

r($testcase->getByAssignedToTest($accountList[0])) && p() && e('37,33,29,21,17,13,5,1'); // 测试获取指派给 admin 的用例执行
r($testcase->getByAssignedToTest($accountList[1])) && p() && e('38,34,30,22,18,14,6,2'); // 测试获取指派给 user2 的用例执行
r($testcase->getByAssignedToTest($accountList[2])) && p() && e('39,35,31,23,19,15,7,3'); // 测试获取指派给 test3 的用例执行
r($testcase->getByAssignedToTest($accountList[3])) && p() && e('40,36,32,24,20,16,8,4'); // 测试获取指派给 dev4 的用例执行