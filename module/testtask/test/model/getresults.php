#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('case')->gen(5);
zenData('casestep')->gen(5);
zenData('testresult')->loadYaml('testresult')->gen(5);
zenData('testrun')->gen(5);
zenData('host')->gen(0);

/**

title=测试 testtaskModel->getResults();
timeout=0
cid=19187

- 获取执行 0 用例 0 执行结果。 @0
- 获取执行 0 用例 1 执行结果。
 - 第1条的id属性 @1
 - 第1条的case属性 @1
 - 第1条的run属性 @1
 - 第1条的caseResult属性 @pass
 - 第1条的stepResults属性 @1
 - 第1条的ZTFResult属性 @~~
 - 第1条的node属性 @0
- 获取执行 0 用例 2 执行结果。
 - 第2条的id属性 @2
 - 第2条的case属性 @2
 - 第2条的run属性 @2
 - 第2条的caseResult属性 @fail
 - 第2条的stepResults属性 @2
 - 第2条的ZTFResult属性 @~~
 - 第2条的node属性 @0
- 获取执行 0 用例 3 执行结果。
 - 第3条的id属性 @3
 - 第3条的case属性 @3
 - 第3条的run属性 @3
 - 第3条的caseResult属性 @pass
 - 第3条的stepResults属性 @3
 - 第3条的ZTFResult属性 @~~
 - 第3条的node属性 @0
- 获取执行 0 用例 4 执行结果。
 - 第4条的id属性 @4
 - 第4条的case属性 @4
 - 第4条的run属性 @4
 - 第4条的caseResult属性 @fail
 - 第4条的stepResults属性 @4
 - 第4条的ZTFResult属性 @~~
 - 第4条的node属性 @0
- 获取执行 0 用例 5 执行结果。
 - 第5条的id属性 @5
 - 第5条的case属性 @5
 - 第5条的run属性 @5
 - 第5条的caseResult属性 @pass
 - 第5条的stepResults属性 @5
 - 第5条的ZTFResult属性 @~~
 - 第5条的node属性 @0
- 获取执行 0 用例 1 状态 done 结果 all 执行结果。
 - 第1条的id属性 @1
 - 第1条的case属性 @1
 - 第1条的run属性 @1
 - 第1条的caseResult属性 @pass
 - 第1条的stepResults属性 @1
 - 第1条的ZTFResult属性 @~~
 - 第1条的node属性 @0
- 获取执行 0 用例 1 状态 done 结果 pass 执行结果。
 - 第1条的id属性 @1
 - 第1条的case属性 @1
 - 第1条的run属性 @1
 - 第1条的caseResult属性 @pass
 - 第1条的stepResults属性 @1
 - 第1条的ZTFResult属性 @~~
 - 第1条的node属性 @0
- 获取执行 0 用例 1 状态 done 结果 fail 执行结果。 @0
- 获取执行 0 用例 2 状态 done 结果 all 执行结果。
 - 第2条的id属性 @2
 - 第2条的case属性 @2
 - 第2条的run属性 @2
 - 第2条的caseResult属性 @fail
 - 第2条的stepResults属性 @2
 - 第2条的ZTFResult属性 @~~
 - 第2条的node属性 @0
- 获取执行 0 用例 2 状态 done 结果 pass 执行结果。 @0
- 获取执行 0 用例 2 状态 done 结果 fail 执行结果。
 - 第2条的id属性 @2
 - 第2条的case属性 @2
 - 第2条的run属性 @2
 - 第2条的caseResult属性 @fail
 - 第2条的stepResults属性 @2
 - 第2条的ZTFResult属性 @~~
 - 第2条的node属性 @0
- 获取执行 1 用例 1 执行结果。
 - 第1条的id属性 @1
 - 第1条的case属性 @1
 - 第1条的run属性 @1
 - 第1条的caseResult属性 @pass
 - 第1条的stepResults属性 @1
 - 第1条的ZTFResult属性 @~~
 - 第1条的node属性 @0
- 获取执行 1 用例 2 执行结果。
 - 第1条的id属性 @1
 - 第1条的case属性 @1
 - 第1条的run属性 @1
 - 第1条的caseResult属性 @pass
 - 第1条的stepResults属性 @1
 - 第1条的ZTFResult属性 @~~
 - 第1条的node属性 @0
- 获取执行 1 用例 3 执行结果。
 - 第1条的id属性 @1
 - 第1条的case属性 @1
 - 第1条的run属性 @1
 - 第1条的caseResult属性 @pass
 - 第1条的stepResults属性 @1
 - 第1条的ZTFResult属性 @~~
 - 第1条的node属性 @0
- 获取执行 1 用例 4 执行结果。
 - 第1条的id属性 @1
 - 第1条的case属性 @1
 - 第1条的run属性 @1
 - 第1条的caseResult属性 @pass
 - 第1条的stepResults属性 @1
 - 第1条的ZTFResult属性 @~~
 - 第1条的node属性 @0
- 获取执行 1 用例 5 执行结果。
 - 第1条的id属性 @1
 - 第1条的case属性 @1
 - 第1条的run属性 @1
 - 第1条的caseResult属性 @pass
 - 第1条的stepResults属性 @1
 - 第1条的ZTFResult属性 @~~
 - 第1条的node属性 @0
- 获取执行 2 用例 1 执行结果。
 - 第2条的id属性 @2
 - 第2条的case属性 @2
 - 第2条的run属性 @2
 - 第2条的caseResult属性 @fail
 - 第2条的stepResults属性 @2
 - 第2条的ZTFResult属性 @~~
 - 第2条的node属性 @0
- 获取执行 2 用例 2 执行结果。
 - 第2条的id属性 @2
 - 第2条的case属性 @2
 - 第2条的run属性 @2
 - 第2条的caseResult属性 @fail
 - 第2条的stepResults属性 @2
 - 第2条的ZTFResult属性 @~~
 - 第2条的node属性 @0
- 获取执行 2 用例 3 执行结果。
 - 第2条的id属性 @2
 - 第2条的case属性 @2
 - 第2条的run属性 @2
 - 第2条的caseResult属性 @fail
 - 第2条的stepResults属性 @2
 - 第2条的ZTFResult属性 @~~
 - 第2条的node属性 @0
- 获取执行 2 用例 4 执行结果。
 - 第2条的id属性 @2
 - 第2条的case属性 @2
 - 第2条的run属性 @2
 - 第2条的caseResult属性 @fail
 - 第2条的stepResults属性 @2
 - 第2条的ZTFResult属性 @~~
 - 第2条的node属性 @0
- 获取执行 2 用例 5 执行结果。
 - 第2条的id属性 @2
 - 第2条的case属性 @2
 - 第2条的run属性 @2
 - 第2条的caseResult属性 @fail
 - 第2条的stepResults属性 @2
 - 第2条的ZTFResult属性 @~~
 - 第2条的node属性 @0
- 获取执行 3 用例 1 执行结果。
 - 第3条的id属性 @3
 - 第3条的case属性 @3
 - 第3条的run属性 @3
 - 第3条的caseResult属性 @pass
 - 第3条的stepResults属性 @3
 - 第3条的ZTFResult属性 @~~
 - 第3条的node属性 @0
- 获取执行 3 用例 2 执行结果。
 - 第3条的id属性 @3
 - 第3条的case属性 @3
 - 第3条的run属性 @3
 - 第3条的caseResult属性 @pass
 - 第3条的stepResults属性 @3
 - 第3条的ZTFResult属性 @~~
 - 第3条的node属性 @0
- 获取执行 3 用例 3 执行结果。
 - 第3条的id属性 @3
 - 第3条的case属性 @3
 - 第3条的run属性 @3
 - 第3条的caseResult属性 @pass
 - 第3条的stepResults属性 @3
 - 第3条的ZTFResult属性 @~~
 - 第3条的node属性 @0
- 获取执行 3 用例 4 执行结果。
 - 第3条的id属性 @3
 - 第3条的case属性 @3
 - 第3条的run属性 @3
 - 第3条的caseResult属性 @pass
 - 第3条的stepResults属性 @3
 - 第3条的ZTFResult属性 @~~
 - 第3条的node属性 @0
- 获取执行 3 用例 5 执行结果。
 - 第3条的id属性 @3
 - 第3条的case属性 @3
 - 第3条的run属性 @3
 - 第3条的caseResult属性 @pass
 - 第3条的stepResults属性 @3
 - 第3条的ZTFResult属性 @~~
 - 第3条的node属性 @0
- 获取执行 4 用例 1 执行结果。
 - 第4条的id属性 @4
 - 第4条的case属性 @4
 - 第4条的run属性 @4
 - 第4条的caseResult属性 @fail
 - 第4条的stepResults属性 @4
 - 第4条的ZTFResult属性 @~~
 - 第4条的node属性 @0
- 获取执行 4 用例 2 执行结果。
 - 第4条的id属性 @4
 - 第4条的case属性 @4
 - 第4条的run属性 @4
 - 第4条的caseResult属性 @fail
 - 第4条的stepResults属性 @4
 - 第4条的ZTFResult属性 @~~
 - 第4条的node属性 @0
- 获取执行 4 用例 3 执行结果。
 - 第4条的id属性 @4
 - 第4条的case属性 @4
 - 第4条的run属性 @4
 - 第4条的caseResult属性 @fail
 - 第4条的stepResults属性 @4
 - 第4条的ZTFResult属性 @~~
 - 第4条的node属性 @0
- 获取执行 4 用例 4 执行结果。
 - 第4条的id属性 @4
 - 第4条的case属性 @4
 - 第4条的run属性 @4
 - 第4条的caseResult属性 @fail
 - 第4条的stepResults属性 @4
 - 第4条的ZTFResult属性 @~~
 - 第4条的node属性 @0
- 获取执行 4 用例 5 执行结果。
 - 第4条的id属性 @4
 - 第4条的case属性 @4
 - 第4条的run属性 @4
 - 第4条的caseResult属性 @fail
 - 第4条的stepResults属性 @4
 - 第4条的ZTFResult属性 @~~
 - 第4条的node属性 @0
- 获取执行 5 用例 1 执行结果。
 - 第5条的id属性 @5
 - 第5条的case属性 @5
 - 第5条的run属性 @5
 - 第5条的caseResult属性 @pass
 - 第5条的stepResults属性 @5
 - 第5条的ZTFResult属性 @~~
 - 第5条的node属性 @0
- 获取执行 5 用例 2 执行结果。
 - 第5条的id属性 @5
 - 第5条的case属性 @5
 - 第5条的run属性 @5
 - 第5条的caseResult属性 @pass
 - 第5条的stepResults属性 @5
 - 第5条的ZTFResult属性 @~~
 - 第5条的node属性 @0
- 获取执行 5 用例 3 执行结果。
 - 第5条的id属性 @5
 - 第5条的case属性 @5
 - 第5条的run属性 @5
 - 第5条的caseResult属性 @pass
 - 第5条的stepResults属性 @5
 - 第5条的ZTFResult属性 @~~
 - 第5条的node属性 @0
- 获取执行 5 用例 4 执行结果。
 - 第5条的id属性 @5
 - 第5条的case属性 @5
 - 第5条的run属性 @5
 - 第5条的caseResult属性 @pass
 - 第5条的stepResults属性 @5
 - 第5条的ZTFResult属性 @~~
 - 第5条的node属性 @0
- 获取执行 5 用例 5 执行结果。
 - 第5条的id属性 @5
 - 第5条的case属性 @5
 - 第5条的run属性 @5
 - 第5条的caseResult属性 @pass
 - 第5条的stepResults属性 @5
 - 第5条的ZTFResult属性 @~~
 - 第5条的node属性 @0

*/

$runIdList  = array(0, 1, 2, 3, 4 ,5);
$caseIdList = array(0, 1, 2, 3, 4 ,5);
$statusList = array('all', 'done');
$typeList   = array('all', 'pass', 'fail');

$testtask = new testtaskModelTest();

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
