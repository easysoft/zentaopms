#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

$case = zenData('case');
$case->id->range('1-10');
$case->product->range('1{10}');
$case->module->range('1{4},0{6}');
$case->story->range('1{5},2{5}');
$case->storyVersion->range('1{10}');
$case->title->range('测试用例1,测试用例2,测试用例3,测试用例4,测试用例5,测试用例6,测试用例7,测试用例8,测试用例9,测试用例10');
$case->status->range('wait,normal,blocked,investigate,wait,normal,blocked,investigate,wait,normal');
$case->version->range('1{10}');
$case->deleted->range('0{10}');
$case->gen(10);

$story = zenData('story');
$story->id->range('1,2');
$story->title->range('用户需求1,软件需求2');
$story->status->range('active{2}');
$story->gen(2);

$testrun = zenData('testrun');
$testrun->id->range('1-10');
$testrun->task->range('1');
$testrun->case->range('1-10');
$testrun->version->range('1{10}');
$testrun->assignedTo->range('admin');
$testrun->status->range('normal,blocked,done,normal,wait,normal,done,done,normal,done');
$testrun->gen(10);

$suitecase = zenData('suitecase');
$suitecase->id->range('1-5');
$suitecase->suite->range('1{5}');
$suitecase->case->range('1-5');
$suitecase->gen(5);

$testsuite = zenData('testsuite');
$testsuite->id->range('1');
$testsuite->gen(1);

$module = zenData('module');
$module->id->range('1');
$module->root->range('1');
$module->name->range('模块1');
$module->gen(1);

su('admin');

/**

title=测试 testtaskModel->getTaskCases();
timeout=0
cid=19195

- 执行testtask模块的getTaskCases方法，参数是1, '', 1, 1, 'id_asc', $pager, $task2  @0
- 执行testtask模块的getTaskCases方法，参数是1, 'other', 1, 1, 'id_asc', $pager, $task2  @0
- 执行testtask模块的getTaskCases方法，参数是1, 'bymodule', 0, 1, 'id_asc', $pager, $task1  @0
- 执行testtask模块的getTaskCases方法，参数是1, 'bymodule', 0, 1, 'id_asc', $pager, $task3  @0
- 执行$cases @4
- 执行$cases
 - 第0条的id属性 @1
 - 第0条的title属性 @测试用例1
 - 第0条的version属性 @1
 - 第0条的status属性 @normal
 - 第0条的caseStatus属性 @wait
 - 第0条的storyTitle属性 @用户需求1
- 执行$cases
 - 第1条的id属性 @2
 - 第1条的title属性 @测试用例2
 - 第1条的version属性 @1
 - 第1条的status属性 @blocked
 - 第1条的caseStatus属性 @normal
 - 第1条的storyTitle属性 @用户需求1
- 执行$cases @4
- 执行$cases
 - 第0条的id属性 @4
 - 第0条的title属性 @测试用例4
 - 第0条的version属性 @1
 - 第0条的status属性 @normal
 - 第0条的caseStatus属性 @investigate
 - 第0条的storyTitle属性 @用户需求1
- 执行$cases
 - 第1条的id属性 @3
 - 第1条的title属性 @测试用例3
 - 第1条的version属性 @1
 - 第1条的status属性 @done
 - 第1条的caseStatus属性 @blocked
 - 第1条的storyTitle属性 @用户需求1
- 执行testtask模块的getTaskCases方法，参数是1, 'all', 0, 1, 'id_asc', $pager, $task1  @0
- 执行testtask模块的getTaskCases方法，参数是1, 'all', 0, 1, 'id_asc', $pager, $task3  @0
- 执行$cases @4
- 执行$cases
 - 第0条的id属性 @1
 - 第0条的title属性 @测试用例1
 - 第0条的version属性 @1
 - 第0条的status属性 @normal
 - 第0条的caseStatus属性 @wait
 - 第0条的storyTitle属性 @用户需求1
- 执行$cases
 - 第1条的id属性 @2
 - 第1条的title属性 @测试用例2
 - 第1条的version属性 @1
 - 第1条的status属性 @blocked
 - 第1条的caseStatus属性 @normal
 - 第1条的storyTitle属性 @用户需求1
- 执行$cases @4
- 执行$cases
 - 第0条的id属性 @4
 - 第0条的title属性 @测试用例4
 - 第0条的version属性 @1
 - 第0条的status属性 @normal
 - 第0条的caseStatus属性 @investigate
 - 第0条的storyTitle属性 @用户需求1
- 执行$cases
 - 第1条的id属性 @3
 - 第1条的title属性 @测试用例3
 - 第1条的version属性 @1
 - 第1条的status属性 @done
 - 第1条的caseStatus属性 @blocked
 - 第1条的storyTitle属性 @用户需求1
- 执行testtask模块的getTaskCases方法，参数是1, 'bysuite', 0, 1, 'id_asc', $pager, $task1  @0
- 执行testtask模块的getTaskCases方法，参数是1, 'bysuite', 0, 1, 'id_asc', $pager, $task3  @0
- 执行$cases @5
- 执行$cases
 - 第0条的id属性 @1
 - 第0条的title属性 @测试用例1
 - 第0条的version属性 @1
 - 第0条的status属性 @normal
 - 第0条的caseStatus属性 @wait
 - 第0条的storyTitle属性 @用户需求1
- 执行$cases
 - 第1条的id属性 @2
 - 第1条的title属性 @测试用例2
 - 第1条的version属性 @1
 - 第1条的status属性 @blocked
 - 第1条的caseStatus属性 @normal
 - 第1条的storyTitle属性 @用户需求1
- 执行$cases @5
- 执行$cases
 - 第0条的id属性 @5
 - 第0条的title属性 @测试用例5
 - 第0条的version属性 @1
 - 第0条的status属性 @wait
 - 第0条的caseStatus属性 @wait
 - 第0条的storyTitle属性 @用户需求1
- 执行$cases
 - 第1条的id属性 @4
 - 第1条的title属性 @测试用例4
 - 第1条的version属性 @1
 - 第1条的status属性 @normal
 - 第1条的caseStatus属性 @investigate
 - 第1条的storyTitle属性 @用户需求1
- 执行testtask模块的getTaskCases方法，参数是1, 'assignedtome', 0, 1, 'id_asc', $pager, $task1  @0
- 执行testtask模块的getTaskCases方法，参数是1, 'assignedtome', 0, 1, 'id_asc', $pager, $task3  @0
- 执行$cases @4
- 执行$cases
 - 第0条的id属性 @1
 - 第0条的title属性 @测试用例1
 - 第0条的version属性 @1
 - 第0条的status属性 @normal
 - 第0条的caseStatus属性 @wait
 - 第0条的storyTitle属性 @用户需求1
- 执行$cases
 - 第1条的id属性 @2
 - 第1条的title属性 @测试用例2
 - 第1条的version属性 @1
 - 第1条的status属性 @blocked
 - 第1条的caseStatus属性 @normal
 - 第1条的storyTitle属性 @用户需求1
- 执行$cases @4
- 执行$cases
 - 第0条的id属性 @4
 - 第0条的title属性 @测试用例4
 - 第0条的version属性 @1
 - 第0条的status属性 @normal
 - 第0条的caseStatus属性 @investigate
 - 第0条的storyTitle属性 @用户需求1
- 执行$cases
 - 第1条的id属性 @3
 - 第1条的title属性 @测试用例3
 - 第1条的version属性 @1
 - 第1条的status属性 @done
 - 第1条的caseStatus属性 @blocked
 - 第1条的storyTitle属性 @用户需求1
- 执行testtask模块的getTaskCases方法，参数是1, 'bysearch', 0, 1, 'id_asc', $pager, $task1  @0
- 执行testtask模块的getTaskCases方法，参数是1, 'bysearch', 0, 1, 'id_asc', $pager, $task3  @0
- 执行$cases @5
- 执行$cases
 - 第0条的id属性 @1
 - 第0条的title属性 @测试用例1
 - 第0条的version属性 @1
 - 第0条的status属性 @normal
 - 第0条的caseStatus属性 @wait
 - 第0条的storyTitle属性 @用户需求1
- 执行$cases
 - 第1条的id属性 @2
 - 第1条的title属性 @测试用例2
 - 第1条的version属性 @1
 - 第1条的status属性 @blocked
 - 第1条的caseStatus属性 @normal
 - 第1条的storyTitle属性 @用户需求1
- 执行$cases @5
- 执行$cases
 - 第0条的id属性 @10
 - 第0条的title属性 @测试用例10
 - 第0条的version属性 @1
 - 第0条的status属性 @done
 - 第0条的caseStatus属性 @normal
 - 第0条的storyTitle属性 @软件需求2
- 执行$cases
 - 第1条的id属性 @9
 - 第1条的title属性 @测试用例9
 - 第1条的version属性 @1
 - 第1条的status属性 @normal
 - 第1条的caseStatus属性 @wait
 - 第1条的storyTitle属性 @软件需求2

*/

global $tester, $app;

$app->rawModule = 'testtask';
$app->rawMethod = 'cases';
$app->loadClass('pager', true);

$null     = new stdclass();
$pager    = new pager(0, 5, 1);
$testtask = $tester->loadModel('testtask');

$task1 = (object)array('id' => 0, 'branch' => 0);
$task2 = (object)array('id' => 1, 'branch' => 0);
$task3 = (object)array('id' => 2, 'branch' => 0);

/* Empty and other browseType. */
r($testtask->getTaskCases(1, '',      1, 1, 'id_asc', $pager, $task2)) && p() && e(0);
r($testtask->getTaskCases(1, 'other', 1, 1, 'id_asc', $pager, $task2)) && p() && e(0);

/* bymodule with invalid task. */
r($testtask->getTaskCases(1, 'bymodule', 0, 1, 'id_asc', $pager, $task1)) && p() && e(0);
r($testtask->getTaskCases(1, 'bymodule', 0, 1, 'id_asc', $pager, $task3)) && p() && e(0);

/* bymodule with module filter, id asc. */
$cases = $testtask->getTaskCases(1, 'bymodule', 0, 1, 'id_asc', $pager, $task2);
$cases = array_values($cases);
r(count($cases)) && p() && e(4);
r($cases) && p('0:id,title,version,status,caseStatus,storyTitle') && e('1,测试用例1,1,normal,wait,用户需求1');
r($cases) && p('1:id,title,version,status,caseStatus,storyTitle') && e('2,测试用例2,1,blocked,normal,用户需求1');

/* bymodule with module filter, id desc. */
$cases = $testtask->getTaskCases(1, 'bymodule', 0, 1, 'id_desc', $pager, $task2);
$cases = array_values($cases);
r(count($cases)) && p() && e(4);
r($cases) && p('0:id,title,version,status,caseStatus,storyTitle') && e('4,测试用例4,1,normal,investigate,用户需求1');
r($cases) && p('1:id,title,version,status,caseStatus,storyTitle') && e('3,测试用例3,1,done,blocked,用户需求1');

/* all with invalid task. */
r($testtask->getTaskCases(1, 'all', 0, 1, 'id_asc', $pager, $task1)) && p() && e(0);
r($testtask->getTaskCases(1, 'all', 0, 1, 'id_asc', $pager, $task3)) && p() && e(0);

/* all with module filter, id asc. */
$cases = $testtask->getTaskCases(1, 'all', 0, 1, 'id_asc', $pager, $task2);
$cases = array_values($cases);
r(count($cases)) && p() && e(4);
r($cases) && p('0:id,title,version,status,caseStatus,storyTitle') && e('1,测试用例1,1,normal,wait,用户需求1');
r($cases) && p('1:id,title,version,status,caseStatus,storyTitle') && e('2,测试用例2,1,blocked,normal,用户需求1');

/* all with module filter, id desc. */
$cases = $testtask->getTaskCases(1, 'all', 0, 1, 'id_desc', $pager, $task2);
$cases = array_values($cases);
r(count($cases)) && p() && e(4);
r($cases) && p('0:id,title,version,status,caseStatus,storyTitle') && e('4,测试用例4,1,normal,investigate,用户需求1');
r($cases) && p('1:id,title,version,status,caseStatus,storyTitle') && e('3,测试用例3,1,done,blocked,用户需求1');

/* bysuite with invalid task. */
r($testtask->getTaskCases(1, 'bysuite', 0, 1, 'id_asc', $pager, $task1)) && p() && e(0);
r($testtask->getTaskCases(1, 'bysuite', 0, 1, 'id_asc', $pager, $task3)) && p() && e(0);

/* bysuite with suite=1, id asc. */
$cases = $testtask->getTaskCases(1, 'bysuite', 1, 1, 'id_asc', $pager, $task2);
$cases = array_values($cases);
r(count($cases)) && p() && e(5);
r($cases) && p('0:id,title,version,status,caseStatus,storyTitle') && e('1,测试用例1,1,normal,wait,用户需求1');
r($cases) && p('1:id,title,version,status,caseStatus,storyTitle') && e('2,测试用例2,1,blocked,normal,用户需求1');

/* bysuite with suite=1, id desc. */
$cases = $testtask->getTaskCases(1, 'bysuite', 1, 1, 'id_desc', $pager, $task2);
$cases = array_values($cases);
r(count($cases)) && p() && e(5);
r($cases) && p('0:id,title,version,status,caseStatus,storyTitle') && e('5,测试用例5,1,wait,wait,用户需求1');
r($cases) && p('1:id,title,version,status,caseStatus,storyTitle') && e('4,测试用例4,1,normal,investigate,用户需求1');

/* assignedtome with invalid task. */
r($testtask->getTaskCases(1, 'assignedtome', 0, 1, 'id_asc', $pager, $task1)) && p() && e(0);
r($testtask->getTaskCases(1, 'assignedtome', 0, 1, 'id_asc', $pager, $task3)) && p() && e(0);

/* assignedtome, id asc. */
$cases = $testtask->getTaskCases(1, 'assignedtome', 0, 1, 'id_asc', $pager, $task2);
$cases = array_values($cases);
r(count($cases)) && p() && e(4);
r($cases) && p('0:id,title,version,status,caseStatus,storyTitle') && e('1,测试用例1,1,normal,wait,用户需求1');
r($cases) && p('1:id,title,version,status,caseStatus,storyTitle') && e('2,测试用例2,1,blocked,normal,用户需求1');

/* assignedtome, id desc. */
$cases = $testtask->getTaskCases(1, 'assignedtome', 0, 1, 'id_desc', $pager, $task2);
$cases = array_values($cases);
r(count($cases)) && p() && e(4);
r($cases) && p('0:id,title,version,status,caseStatus,storyTitle') && e('4,测试用例4,1,normal,investigate,用户需求1');
r($cases) && p('1:id,title,version,status,caseStatus,storyTitle') && e('3,测试用例3,1,done,blocked,用户需求1');

/* bysearch with invalid task. */
$_SESSION['testtaskQuery'] = ' 1 = 1';
r($testtask->getTaskCases(1, 'bysearch', 0, 1, 'id_asc', $pager, $task1)) && p() && e(0);
r($testtask->getTaskCases(1, 'bysearch', 0, 1, 'id_asc', $pager, $task3)) && p() && e(0);

/* bysearch, id asc. */
$cases = $testtask->getTaskCases(1, 'bysearch', 0, 1, 'id_asc', $pager, $task2);
$cases = array_values($cases);
r(count($cases)) && p() && e(5);
r($cases) && p('0:id,title,version,status,caseStatus,storyTitle') && e('1,测试用例1,1,normal,wait,用户需求1');
r($cases) && p('1:id,title,version,status,caseStatus,storyTitle') && e('2,测试用例2,1,blocked,normal,用户需求1');

/* bysearch, id desc. */
$cases = $testtask->getTaskCases(1, 'bysearch', 0, 1, 'id_desc', $pager, $task2);
$cases = array_values($cases);
r(count($cases)) && p() && e(5);
r($cases) && p('0:id,title,version,status,caseStatus,storyTitle') && e('10,测试用例10,1,done,normal,软件需求2');
r($cases) && p('1:id,title,version,status,caseStatus,storyTitle') && e('9,测试用例9,1,normal,wait,软件需求2');
