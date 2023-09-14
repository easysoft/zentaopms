#!/usr/bin/php 
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->getCasesToExport();
timeout=0
cid=1

- 获取用例1,2,3的测试结果 @3,2,1

- 获取用例1,2,3并且属于测试单1的用例的测试结果 @2,1

*/

$caseIdList = array('1', '2', '3');
$taskIdList = array('0', '1');

$testcase = new testcaseTest();
r($testcase->getCaseResultsForExportTest($caseIdList, $taskIdList[0])) && p() && e('3,2,1'); // 获取用例1,2,3的测试结果
r($testcase->getCaseResultsForExportTest($caseIdList, $taskIdList[1])) && p() && e('2,1');   // 获取用例1,2,3并且属于测试单1的用例的测试结果