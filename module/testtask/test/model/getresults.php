#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testtask.class.php';

zdTable('case')->gen(5);
zdTable('casestep')->gen(5);
zdTable('testresult')->config('testresult')->gen(5);
zdTable('testrun')->gen(5);
zdTable('host')->gen(0);

/**

title=测试 testtaskModel->getResults();
cid=1
pid=1

*/

$runIdList  = array(0, 1, 2, 3, 4 ,5);
$caseIdList = array(0, 1, 2, 3, 4 ,5);
$statusList = array('all', 'done');
$typeList   = array('all', 'pass', 'fail');

$testtask = new testtaskTest();

r($testtask->getResultsTest($runIdList[0], $caseIdList[0])) && p() && e('0'); // 获取执行 0 用例 0 执行结果。

r($testtask->getResultsTest($runIdList[0], $caseIdList[1])) && p('1:id,case,run,caseResult,stepResults,ZTFResult,node') && e('1,1,1,pass,1,~~,0'); // 获取执行 0 用例 1 执行结果。
r($testtask->getResultsTest($runIdList[0], $caseIdList[2])) && p('2:id,case,run,caseResult,stepResults,ZTFResult,node') && e('2,2,2,fail,2,~~,0'); // 获取执行 0 用例 2 执行结果。
r($testtask->getResultsTest($runIdList[0], $caseIdList[3])) && p('3:id,case,run,caseResult,stepResults,ZTFResult,node') && e('3,3,3,pass,3,~~,0'); // 获取执行 0 用例 3 执行结果。
r($testtask->getResultsTest($runIdList[0], $caseIdList[4])) && p('4:id,case,run,caseResult,stepResults,ZTFResult,node') && e('4,4,4,fail,4,~~,0'); // 获取执行 0 用例 4 执行结果。
r($testtask->getResultsTest($runIdList[0], $caseIdList[5])) && p('5:id,case,run,caseResult,stepResults,ZTFResult,node') && e('5,5,5,pass,5,~~,0'); // 获取执行 0 用例 5 执行结果。

r($testtask->getResultsTest($runIdList[0], $caseIdList[1], $statusList[1], $typeList[0])) && p('1:id,case,run,caseResult,stepResults,ZTFResult,node') && e('1,1,1,pass,1,~~,0'); // 获取执行 0 用例 1 状态 done 结果 all 执行结果。
r($testtask->getResultsTest($runIdList[0], $caseIdList[1], $statusList[1], $typeList[1])) && p('1:id,case,run,caseResult,stepResults,ZTFResult,node') && e('1,1,1,pass,1,~~,0'); // 获取执行 0 用例 1 状态 done 结果 pass 执行结果。
r($testtask->getResultsTest($runIdList[0], $caseIdList[1], $statusList[1], $typeList[2])) && p() && e('0'); // 获取执行 0 用例 1 状态 done 结果 fail 执行结果。
r($testtask->getResultsTest($runIdList[0], $caseIdList[2], $statusList[1], $typeList[0])) && p('2:id,case,run,caseResult,stepResults,ZTFResult,node') && e('2,2,2,fail,2,~~,0'); // 获取执行 0 用例 2 状态 done 结果 all 执行结果。
r($testtask->getResultsTest($runIdList[0], $caseIdList[2], $statusList[1], $typeList[1])) && p() && e('0'); // 获取执行 0 用例 2 状态 done 结果 pass 执行结果。
r($testtask->getResultsTest($runIdList[0], $caseIdList[2], $statusList[1], $typeList[2])) && p('2:id,case,run,caseResult,stepResults,ZTFResult,node') && e('2,2,2,fail,2,~~,0'); // 获取执行 0 用例 2 状态 done 结果 fail 执行结果。

r($testtask->getResultsTest($runIdList[1], $caseIdList[1])) && p('1:id,case,run,caseResult,stepResults,ZTFResult,node') && e('1,1,1,pass,1,~~,0'); // 获取执行 1 用例 1 执行结果。
r($testtask->getResultsTest($runIdList[1], $caseIdList[2])) && p('1:id,case,run,caseResult,stepResults,ZTFResult,node') && e('1,1,1,pass,1,~~,0'); // 获取执行 1 用例 2 执行结果。
r($testtask->getResultsTest($runIdList[1], $caseIdList[3])) && p('1:id,case,run,caseResult,stepResults,ZTFResult,node') && e('1,1,1,pass,1,~~,0'); // 获取执行 1 用例 3 执行结果。
r($testtask->getResultsTest($runIdList[1], $caseIdList[4])) && p('1:id,case,run,caseResult,stepResults,ZTFResult,node') && e('1,1,1,pass,1,~~,0'); // 获取执行 1 用例 4 执行结果。
r($testtask->getResultsTest($runIdList[1], $caseIdList[5])) && p('1:id,case,run,caseResult,stepResults,ZTFResult,node') && e('1,1,1,pass,1,~~,0'); // 获取执行 1 用例 5 执行结果。

r($testtask->getResultsTest($runIdList[2], $caseIdList[1])) && p('2:id,case,run,caseResult,stepResults,ZTFResult,node') && e('2,2,2,fail,2,~~,0'); // 获取执行 2 用例 1 执行结果。
r($testtask->getResultsTest($runIdList[2], $caseIdList[2])) && p('2:id,case,run,caseResult,stepResults,ZTFResult,node') && e('2,2,2,fail,2,~~,0'); // 获取执行 2 用例 2 执行结果。
r($testtask->getResultsTest($runIdList[2], $caseIdList[3])) && p('2:id,case,run,caseResult,stepResults,ZTFResult,node') && e('2,2,2,fail,2,~~,0'); // 获取执行 2 用例 3 执行结果。
r($testtask->getResultsTest($runIdList[2], $caseIdList[4])) && p('2:id,case,run,caseResult,stepResults,ZTFResult,node') && e('2,2,2,fail,2,~~,0'); // 获取执行 2 用例 4 执行结果。
r($testtask->getResultsTest($runIdList[2], $caseIdList[5])) && p('2:id,case,run,caseResult,stepResults,ZTFResult,node') && e('2,2,2,fail,2,~~,0'); // 获取执行 2 用例 5 执行结果。

r($testtask->getResultsTest($runIdList[3], $caseIdList[1])) && p('3:id,case,run,caseResult,stepResults,ZTFResult,node') && e('3,3,3,pass,3,~~,0'); // 获取执行 3 用例 1 执行结果。
r($testtask->getResultsTest($runIdList[3], $caseIdList[2])) && p('3:id,case,run,caseResult,stepResults,ZTFResult,node') && e('3,3,3,pass,3,~~,0'); // 获取执行 3 用例 2 执行结果。
r($testtask->getResultsTest($runIdList[3], $caseIdList[3])) && p('3:id,case,run,caseResult,stepResults,ZTFResult,node') && e('3,3,3,pass,3,~~,0'); // 获取执行 3 用例 3 执行结果。
r($testtask->getResultsTest($runIdList[3], $caseIdList[4])) && p('3:id,case,run,caseResult,stepResults,ZTFResult,node') && e('3,3,3,pass,3,~~,0'); // 获取执行 3 用例 4 执行结果。
r($testtask->getResultsTest($runIdList[3], $caseIdList[5])) && p('3:id,case,run,caseResult,stepResults,ZTFResult,node') && e('3,3,3,pass,3,~~,0'); // 获取执行 3 用例 5 执行结果。

r($testtask->getResultsTest($runIdList[4], $caseIdList[1])) && p('4:id,case,run,caseResult,stepResults,ZTFResult,node') && e('4,4,4,fail,4,~~,0'); // 获取执行 4 用例 1 执行结果。
r($testtask->getResultsTest($runIdList[4], $caseIdList[2])) && p('4:id,case,run,caseResult,stepResults,ZTFResult,node') && e('4,4,4,fail,4,~~,0'); // 获取执行 4 用例 2 执行结果。
r($testtask->getResultsTest($runIdList[4], $caseIdList[3])) && p('4:id,case,run,caseResult,stepResults,ZTFResult,node') && e('4,4,4,fail,4,~~,0'); // 获取执行 4 用例 3 执行结果。
r($testtask->getResultsTest($runIdList[4], $caseIdList[4])) && p('4:id,case,run,caseResult,stepResults,ZTFResult,node') && e('4,4,4,fail,4,~~,0'); // 获取执行 4 用例 4 执行结果。
r($testtask->getResultsTest($runIdList[4], $caseIdList[5])) && p('4:id,case,run,caseResult,stepResults,ZTFResult,node') && e('4,4,4,fail,4,~~,0'); // 获取执行 4 用例 5 执行结果。

r($testtask->getResultsTest($runIdList[5], $caseIdList[1])) && p('5:id,case,run,caseResult,stepResults,ZTFResult,node') && e('5,5,5,pass,5,~~,0'); // 获取执行 5 用例 1 执行结果。
r($testtask->getResultsTest($runIdList[5], $caseIdList[2])) && p('5:id,case,run,caseResult,stepResults,ZTFResult,node') && e('5,5,5,pass,5,~~,0'); // 获取执行 5 用例 2 执行结果。
r($testtask->getResultsTest($runIdList[5], $caseIdList[3])) && p('5:id,case,run,caseResult,stepResults,ZTFResult,node') && e('5,5,5,pass,5,~~,0'); // 获取执行 5 用例 3 执行结果。
r($testtask->getResultsTest($runIdList[5], $caseIdList[4])) && p('5:id,case,run,caseResult,stepResults,ZTFResult,node') && e('5,5,5,pass,5,~~,0'); // 获取执行 5 用例 4 执行结果。
r($testtask->getResultsTest($runIdList[5], $caseIdList[5])) && p('5:id,case,run,caseResult,stepResults,ZTFResult,node') && e('5,5,5,pass,5,~~,0'); // 获取执行 5 用例 5 执行结果。
