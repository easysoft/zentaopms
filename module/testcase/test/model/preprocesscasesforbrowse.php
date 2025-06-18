#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 testcaseModel->preProcessCasesForBrowse();
timeout=0
cid=1

- 获取用例1额外的信息
 - 第1条的id属性 @case_1
 - 第1条的bugs属性 @6
 - 第1条的results属性 @1
 - 第1条的caseFails属性 @0
 - 第1条的stepNumber属性 @1
 - 第1条的caseID属性 @1
- 获取用例3额外的信息
 - 第3条的id属性 @case_3
 - 第3条的bugs属性 @6
 - 第3条的results属性 @1
 - 第3条的caseFails属性 @0
 - 第3条的stepNumber属性 @1
 - 第3条的caseID属性 @3
- 获取用例4额外的信息
 - 第4条的id属性 @case_4
 - 第4条的bugs属性 @0
 - 第4条的results属性 @1
 - 第4条的caseFails属性 @1
 - 第4条的stepNumber属性 @1
 - 第4条的caseID属性 @4
- 获取用例5额外的信息
 - 第5条的id属性 @case_5
 - 第5条的bugs属性 @0
 - 第5条的results属性 @1
 - 第5条的caseFails属性 @0
 - 第5条的stepNumber属性 @1
 - 第5条的caseID属性 @5
- 获取用例2额外的信息
 - 第2条的id属性 @case_2
 - 第2条的needconfirm属性 @1
 - 第2条的bugs属性 @6
 - 第2条的results属性 @1
 - 第2条的caseFails属性 @1
 - 第2条的stepNumber属性 @1
 - 第2条的caseID属性 @2

*/

su('admin');
zenData('case')->gen('100');
zenData('casestep')->gen('100');
zenData('bug')->loadYaml('casebug')->gen('50');
zenData('testresult')->gen('50');
zenData('user')->gen('1');
zenData('testrun')->gen('10');
zenData('story')->gen('10');

$case1 = new stdclass();
$case1->id           = 1;
$case1->story        = 1;
$case1->storyVersion = 1;

$case2 = new stdclass();
$case2->id           = 2;
$case2->story        = 2;
$case2->storyVersion = 2;

$case3 = new stdclass();
$case3->id           = 3;
$case3->story        = 3;
$case3->storyVersion = 3;

$case4 = new stdclass();
$case4->id           = 4;
$case4->story        = 4;
$case4->storyVersion = 4;

$case5 = new stdclass();
$case5->id           = 5;
$case5->story        = 5;
$case5->storyVersion = 5;

global $tester;
$caseModel = $tester->loadModel('testcase');
$caseList  = array(array(1 => $case1), array(2 => $case2), array(3 => $case3), array(4 => $case4), array(5 => $case5));

r($caseModel->preProcessCasesForBrowse($caseList[0])) && p('1:id,bugs,results,caseFails,stepNumber,caseID') && e('case_1,6,1,0,1,1'); // 获取用例1额外的信息
r($caseModel->preProcessCasesForBrowse($caseList[2])) && p('3:id,bugs,results,caseFails,stepNumber,caseID') && e('case_3,6,1,0,1,3'); // 获取用例3额外的信息
r($caseModel->preProcessCasesForBrowse($caseList[3])) && p('4:id,bugs,results,caseFails,stepNumber,caseID') && e('case_4,0,1,1,1,4'); // 获取用例4额外的信息
r($caseModel->preProcessCasesForBrowse($caseList[4])) && p('5:id,bugs,results,caseFails,stepNumber,caseID') && e('case_5,0,1,0,1,5'); // 获取用例5额外的信息
r($caseModel->preProcessCasesForBrowse($caseList[1])) && p('2:id,needconfirm,bugs,results,caseFails,stepNumber,caseID') && e('case_2,1,6,1,1,1,2'); // 获取用例2额外的信息
