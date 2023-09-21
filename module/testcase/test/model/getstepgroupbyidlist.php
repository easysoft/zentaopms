#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';

zdTable('case')->config('normalcase')->gen('10');
zdTable('casestep')->config('casestep')->gen('30');
zdTable('user')->gen('1');

su('admin');

/**

title=测试 testcaseModel->getStepGroupByIdList();
cid=1
pid=1

*/

$caseIdList = array('1,2,3', '4,5,6', '7,8,9');

$testcase = new testcaseTest();

r($testcase->getStepGroupByIdListTest($caseIdList[0])) && p() && e('1:4,5,6,7,8,9,10,11,12; 2:13; 3:14;'); // 获取用例 1 2 3 的步骤
r($testcase->getStepGroupByIdListTest($caseIdList[1])) && p() && e('4:15; 5:16; 6:17;');                   // 获取用例 4 5 6 的步骤
r($testcase->getStepGroupByIdListTest($caseIdList[2])) && p() && e('7:18; 8:19; 9:20;');                   // 获取用例 7 8 9 的步骤
