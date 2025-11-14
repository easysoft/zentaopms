#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('case')->gen('20');
zenData('casestep')->loadYaml('casestep')->gen('20');
zenData('user')->gen('1');

su('admin');

/**

title=测试 testcaseTao->importSteps();
cid=19048

- 测试获取case id 21 插入 case id 1 的步骤 @21,22,23,24,25,26,27,28,29,30,31,32

- 测试获取case id 22 插入 case id 2 的步骤 @33
- 测试获取case id 23 插入 case id 3 的步骤 @34
- 测试获取case id 24 插入 case id 4 的步骤 @35
- 测试获取case id 25 插入 case id 5 的步骤 @36
- 测试获取case id 26 插入 不存在的 case id 的步骤 @0

*/

$testcase = new testcaseTest();

$caseIdList    = array(21, 22, 23, 24, 25, 26);
$oldCaseIdList = array(1, 2, 3, 4, 5, 30);

r($testcase->importStepsTest($caseIdList[0], $oldCaseIdList[0])) && p() && e('21,22,23,24,25,26,27,28,29,30,31,32'); // 测试获取case id 21 插入 case id 1 的步骤
r($testcase->importStepsTest($caseIdList[1], $oldCaseIdList[1])) && p() && e('33'); // 测试获取case id 22 插入 case id 2 的步骤
r($testcase->importStepsTest($caseIdList[2], $oldCaseIdList[2])) && p() && e('34'); // 测试获取case id 23 插入 case id 3 的步骤
r($testcase->importStepsTest($caseIdList[3], $oldCaseIdList[3])) && p() && e('35'); // 测试获取case id 24 插入 case id 4 的步骤
r($testcase->importStepsTest($caseIdList[4], $oldCaseIdList[4])) && p() && e('36'); // 测试获取case id 25 插入 case id 5 的步骤
r($testcase->importStepsTest($caseIdList[5], $oldCaseIdList[5])) && p() && e('0');  // 测试获取case id 26 插入 不存在的 case id 的步骤
