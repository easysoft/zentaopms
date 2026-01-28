#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('user')->gen('1');
zenData('case')->gen('5');
zenData('casestep')->loadYaml('casestep')->gen('20');

su('admin');

/**

title=测试 testcaseModel->getSteps();
cid=19051

- 测试处理case 1 version 2 的步骤 @1 1.1 1.1.1
- 测试处理case 2 version 2 的步骤 @1
- 测试处理case 3 version 2 的步骤 @1
- 测试处理不存在的 case version 2的步骤 @0
- 测试处理空数组的步骤 @0

*/

$caseIDList = array(1, 2, 3, 1001);

$testcase = new testcaseTaoTest();

$steps = $testcase->objectModel->fetchStepsByList($caseIDList);

r($testcase->processStepsTest(zget($steps, $caseIDList[0], array()))) && p() && e('1 1.1 1.1.1'); // 测试处理case 1 version 2 的步骤
r($testcase->processStepsTest(zget($steps, $caseIDList[1], array()))) && p() && e('1');           // 测试处理case 2 version 2 的步骤
r($testcase->processStepsTest(zget($steps, $caseIDList[2], array()))) && p() && e('1');           // 测试处理case 3 version 2 的步骤
r($testcase->processStepsTest(zget($steps, $caseIDList[3], array()))) && p() && e('0');           // 测试处理不存在的 case version 2的步骤
r($testcase->processStepsTest(array()))                               && p() && e('0');           // 测试处理空数组的步骤
