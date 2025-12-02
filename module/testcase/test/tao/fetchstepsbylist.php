#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('user')->gen('1');
zenData('case')->gen('5');
zenData('casestep')->loadYaml('casestep')->gen('20');

su('admin');

/**

title=测试 testcaseTao->fetchStepsByList();
cid=19035

- 测试获取case 1 的步骤 @1: 1,2,3;

- 测试获取case 1 2 的步骤 @1: 1,2,3; 2: 13;

- 测试获取case 2 3 的步骤 @2: 13; 3: 14;
- 测试获取case 3 4 的步骤 @3: 14; 4: 15;
- 测试获取case 1 2 3 4 5 的步骤 @1: 1,2,3; 2: 13; 3: 14; 4: 15; 5: 16;

*/

$testcase = new testcaseTest();

$caseIdList = array(array(1), array(1, 2), array(2, 3), array(3, 4), array(1, 2, 3, 4, 5));

r($testcase->fetchStepsByListTest($caseIdList[0])) && p() && e('1: 1,2,3;');                             // 测试获取case 1 的步骤
r($testcase->fetchStepsByListTest($caseIdList[1])) && p() && e('1: 1,2,3; 2: 13;');                      // 测试获取case 1 2 的步骤
r($testcase->fetchStepsByListTest($caseIdList[2])) && p() && e('2: 13; 3: 14;');                         // 测试获取case 2 3 的步骤
r($testcase->fetchStepsByListTest($caseIdList[3])) && p() && e('3: 14; 4: 15;');                         // 测试获取case 3 4 的步骤
r($testcase->fetchStepsByListTest($caseIdList[4])) && p() && e('1: 1,2,3; 2: 13; 3: 14; 4: 15; 5: 16;'); // 测试获取case 1 2 3 4 5 的步骤
