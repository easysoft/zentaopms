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
cid=19046

- 测试获取case 1 version 1 的步骤 @1 1.1 1.1.1
- 测试获取case 1 version 2 的步骤 @1 1.1 1.1.1 1.1.2 1.2 1.2.1 2 2.1 3
- 测试获取case 2 version 1 的步骤 @1
- 测试获取case 2 version 2 的步骤 @0
- 测试获取case 3 version 1 的步骤 @1
- 测试获取case 3 version 2 的步骤 @0
- 测试获取不存在的 case version 1的步骤 @0
- 测试获取不存在的 case version 2的步骤 @0

*/

$caseIDList = array(1, 2, 3, 1001);
$version    = array(1, 2);

$testcase = new testcaseTaoTest();

r($testcase->getStepsTest($caseIDList[0], $version[0])) && p() && e('1 1.1 1.1.1');                         // 测试获取case 1 version 1 的步骤
r($testcase->getStepsTest($caseIDList[0], $version[1])) && p() && e('1 1.1 1.1.1 1.1.2 1.2 1.2.1 2 2.1 3'); // 测试获取case 1 version 2 的步骤
r($testcase->getStepsTest($caseIDList[1], $version[0])) && p() && e('1'); // 测试获取case 2 version 1 的步骤
r($testcase->getStepsTest($caseIDList[1], $version[1])) && p() && e('0'); // 测试获取case 2 version 2 的步骤
r($testcase->getStepsTest($caseIDList[2], $version[0])) && p() && e('1'); // 测试获取case 3 version 1 的步骤
r($testcase->getStepsTest($caseIDList[2], $version[1])) && p() && e('0'); // 测试获取case 3 version 2 的步骤
r($testcase->getStepsTest($caseIDList[3], $version[0])) && p() && e('0'); // 测试获取不存在的 case version 1的步骤
r($testcase->getStepsTest($caseIDList[3], $version[1])) && p() && e('0'); // 测试获取不存在的 case version 2的步骤
