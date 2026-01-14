#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('case')->loadYaml('normalcase')->gen('10');
zenData('casestep')->loadYaml('casestep')->gen('30');
zenData('user')->gen('1');

su('admin');

/**

title=测试 testcaseModel->getStepGroupByIdList();
timeout=0
cid=19003

- 获取用例 1 2 3 的步骤 @1:4,5,6,7,8,9,10,11,12; 2:13; 3:14;

- 获取用例 4 5 6 的步骤 @4:15; 5:16; 6:17;
- 获取用例 7 8 9 的步骤 @7:18; 8:19; 9:20;
- 获取用例 10 的步骤 @10:21;
- 获取用例 0 的步骤 @0

*/

$caseIdList = array('1,2,3', '4,5,6', '7,8,9', '10', '0');

$testcase = new testcaseModelTest();

r($testcase->getStepGroupByIdListTest($caseIdList[0])) && p() && e('1:4,5,6,7,8,9,10,11,12; 2:13; 3:14;'); // 获取用例 1 2 3 的步骤
r($testcase->getStepGroupByIdListTest($caseIdList[1])) && p() && e('4:15; 5:16; 6:17;'); // 获取用例 4 5 6 的步骤
r($testcase->getStepGroupByIdListTest($caseIdList[2])) && p() && e('7:18; 8:19; 9:20;'); // 获取用例 7 8 9 的步骤
r($testcase->getStepGroupByIdListTest($caseIdList[3])) && p() && e('10:21;'); // 获取用例 10 的步骤
r($testcase->getStepGroupByIdListTest($caseIdList[4])) && p() && e('0'); // 获取用例 0 的步骤