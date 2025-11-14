#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('case')->gen('20');
zenData('user')->gen('1');

su('admin');

/**

title=测试 testcaseModel->summary();
cid=19024
pid=1

*/

$caseIdList = array('1,2,3', '4,5,6', '7,8,9', '10,11,12', '13,14,15', '');

$testcase = new testcaseTest();

r($testcase->summaryTest($caseIdList[0])) && p() && e('本页共 3 个顶级场景，2 个独立用例。'); // 测试获取case 1 2 3 的总结信息
r($testcase->summaryTest($caseIdList[1])) && p() && e('本页共 3 个顶级场景，2 个独立用例。'); // 测试获取case 4 5 6 的总结信息
r($testcase->summaryTest($caseIdList[2])) && p() && e('本页共 3 个顶级场景，1 个独立用例。'); // 测试获取case 7 8 9 的总结信息
r($testcase->summaryTest($caseIdList[3])) && p() && e('本页共 3 个顶级场景，1 个独立用例。'); // 测试获取case 10 11 12 的总结信息
r($testcase->summaryTest($caseIdList[4])) && p() && e('本页共 3 个顶级场景，2 个独立用例。'); // 测试获取case 13 14 15 的总结信息
r($testcase->summaryTest($caseIdList[5])) && p() && e('本页共 0 个顶级场景，0 个独立用例。'); // 测试获取case 空 的总结信息
