#!/usr/bin/php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('testresult')->gen('10');
zenData('testrun')->gen('10');

su('admin');

/**

title=测试 testcaseModel->getCasesToExport();
timeout=0
cid=18985

*/

$caseIdList = array(array(1,3,5), array(2,4,6), array(7,8,9));
$taskIdList = array('0', '1');

$testcase = new testcaseTest();
$testcase->initResult();

r($testcase->getCaseResultsForExportTest($caseIdList[0], $taskIdList[0])) && p() && e('5,3,1'); // 获取用例 1,3,5 任务 0 的测试结果
r($testcase->getCaseResultsForExportTest($caseIdList[0], $taskIdList[1])) && p() && e('3,1');   // 获取用例 1,3,5 任务 1 的测试结果
r($testcase->getCaseResultsForExportTest($caseIdList[1], $taskIdList[0])) && p() && e('6,4,2'); // 获取用例 2,4,6 任务 0 的测试结果
r($testcase->getCaseResultsForExportTest($caseIdList[1], $taskIdList[1])) && p() && e('4,2');   // 获取用例 2,4,6 任务 1 的测试结果
r($testcase->getCaseResultsForExportTest($caseIdList[2], $taskIdList[0])) && p() && e('9,8,7'); // 获取用例 7,8,9 任务 0 的测试结果
r($testcase->getCaseResultsForExportTest($caseIdList[2], $taskIdList[1])) && p() && e('0');     // 获取用例 7,8,9 任务 1 的测试结果
