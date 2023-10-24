#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->syncCase2Project();
cid=1
pid=1

测试同步用例 1 到关联项目中 >> 2
测试同步用例 2 到关联项目中 >> 2
测试同步用例 3 到关联项目中 >> 2
测试同步用例 4 到关联项目中 >> 2
测试同步用例 5 到关联项目中 >> 2

*/

$caseIDList = array(1, 5, 9, 13, 17, 2);

$testcase = new testcaseTest();

r($testcase->syncCase2ProjectTest($caseIDList[0])) && p() && e('2'); // 测试同步用例 1 到关联项目中
r($testcase->syncCase2ProjectTest($caseIDList[1])) && p() && e('2'); // 测试同步用例 2 到关联项目中
r($testcase->syncCase2ProjectTest($caseIDList[2])) && p() && e('2'); // 测试同步用例 3 到关联项目中
r($testcase->syncCase2ProjectTest($caseIDList[3])) && p() && e('2'); // 测试同步用例 4 到关联项目中
r($testcase->syncCase2ProjectTest($caseIDList[4])) && p() && e('2'); // 测试同步用例 5 到关联项目中
