#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtask.unittest.class.php';
su('admin');

zenData('case')->loadYaml('case')->gen(10);
zenData('casestep')->loadYaml('casestep')->gen(24);
zenData('testrun')->loadYaml('testrun')->gen(5);
zenData('testresult')->gen(0);
zenData('action')->gen(0);

/**

title=测试 testtaskModel->batchRun();
cid=19156

- 测试用例参数为空数组返回 false。 @0
- 测试用例参数内元素为空字符串返回 false。 @0
- 测试用例参数内元素为 0 返回 false。 @0
- 更新 zt_case 表中用例 1 的 lastRunner 字段和 lastRunResult 字段。
 - 第cases[1]条的lastRunner属性 @admin
 - 第cases[1]条的lastRunResult属性 @n/a
- 更新 zt_case 表中用例 2 的 lastRunner 字段和 lastRunResult 字段。
 - 第cases[2]条的lastRunner属性 @admin
 - 第cases[2]条的lastRunResult属性 @pass
- 更新 zt_case 表中用例 3 的 lastRunner 字段和 lastRunResult 字段。
 - 第cases[3]条的lastRunner属性 @admin
 - 第cases[3]条的lastRunResult属性 @fail
- 更新 zt_case 表中用例 4 的 lastRunner 字段和 lastRunResult 字段。
 - 第cases[4]条的lastRunner属性 @admin
 - 第cases[4]条的lastRunResult属性 @blocked
- 更新 zt_case 表中用例 5 的 lastRunner 字段和 lastRunResult 字段。
 - 第cases[5]条的lastRunner属性 @admin
 - 第cases[5]条的lastRunResult属性 @pass
- 更新 zt_case 表中用例 6 的 lastRunner 字段和 lastRunResult 字段。
 - 第cases[6]条的lastRunner属性 @admin
 - 第cases[6]条的lastRunResult属性 @n/a
- 更新 zt_case 表中用例 7 的 lastRunner 字段和 lastRunResult 字段。
 - 第cases[7]条的lastRunner属性 @admin
 - 第cases[7]条的lastRunResult属性 @pass
- 更新 zt_case 表中用例 8 的 lastRunner 字段和 lastRunResult 字段。
 - 第cases[8]条的lastRunner属性 @admin
 - 第cases[8]条的lastRunResult属性 @fail
- 更新 zt_case 表中用例 9 的 lastRunner 字段和 lastRunResult 字段。
 - 第cases[9]条的lastRunner属性 @admin
 - 第cases[9]条的lastRunResult属性 @blocked
- 更新 zt_case 表中用例 10 的 lastRunner 字段和 lastRunResult 字段。
 - 第cases[10]条的lastRunner属性 @admin
 - 第cases[10]条的lastRunResult属性 @pass
- 记录用例 1 的执行结果到 zt_testresult 表中，run 字段为 0。
 - 第results[1]条的run属性 @0
 - 第results[1]条的case属性 @1
 - 第results[1]条的version属性 @1
 - 第results[1]条的lastRunner属性 @admin
 - 第results[1]条的caseResult属性 @n/a
- 记录用例 2 的执行结果到 zt_testresult 表中，run 字段为 0。
 - 第results[2]条的run属性 @0
 - 第results[2]条的case属性 @2
 - 第results[2]条的version属性 @1
 - 第results[2]条的lastRunner属性 @admin
 - 第results[2]条的caseResult属性 @pass
- 记录用例 3 的执行结果到 zt_testresult 表中，run 字段为 0。
 - 第results[3]条的run属性 @0
 - 第results[3]条的case属性 @3
 - 第results[3]条的version属性 @1
 - 第results[3]条的lastRunner属性 @admin
 - 第results[3]条的caseResult属性 @fail
- 记录用例 4 的执行结果到 zt_testresult 表中，run 字段为 0。
 - 第results[4]条的run属性 @0
 - 第results[4]条的case属性 @4
 - 第results[4]条的version属性 @1
 - 第results[4]条的lastRunner属性 @admin
 - 第results[4]条的caseResult属性 @blocked
- 记录用例 5 的执行结果到 zt_testresult 表中，run 字段为 0。
 - 第results[5]条的run属性 @0
 - 第results[5]条的case属性 @5
 - 第results[5]条的version属性 @1
 - 第results[5]条的lastRunner属性 @admin
 - 第results[5]条的caseResult属性 @pass
- 记录用例 6 的执行结果到 zt_testresult 表中，run 字段为 0。
 - 第results[6]条的run属性 @0
 - 第results[6]条的case属性 @6
 - 第results[6]条的version属性 @1
 - 第results[6]条的lastRunner属性 @admin
 - 第results[6]条的caseResult属性 @n/a
- 记录用例 7 的执行结果到 zt_testresult 表中，run 字段为 0。
 - 第results[7]条的run属性 @0
 - 第results[7]条的case属性 @7
 - 第results[7]条的version属性 @1
 - 第results[7]条的lastRunner属性 @admin
 - 第results[7]条的caseResult属性 @pass
- 记录用例 8 的执行结果到 zt_testresult 表中，run 字段为 0。
 - 第results[8]条的run属性 @0
 - 第results[8]条的case属性 @8
 - 第results[8]条的version属性 @1
 - 第results[8]条的lastRunner属性 @admin
 - 第results[8]条的caseResult属性 @fail
- 记录用例 9 的执行结果到 zt_testresult 表中，run 字段为 0。
 - 第results[9]条的run属性 @0
 - 第results[9]条的case属性 @9
 - 第results[9]条的version属性 @1
 - 第results[9]条的lastRunner属性 @admin
 - 第results[9]条的caseResult属性 @blocked
- 记录用例 10 的执行结果到 zt_testresult 表中，run 字段为 0。
 - 第results[10]条的run属性 @0
 - 第results[10]条的case属性 @10
 - 第results[10]条的version属性 @1
 - 第results[10]条的lastRunner属性 @admin
 - 第results[10]条的caseResult属性 @pass
- 记录用例 1 的执行日志到 zt_action 表中，extra 字段为 0。
 - 第actions[1]条的objectType属性 @case
 - 第actions[1]条的action属性 @run
 - 第actions[1]条的extra属性 @0,n/a
- 记录用例 2 的执行日志到 zt_action 表中，extra 字段为 0。
 - 第actions[2]条的objectType属性 @case
 - 第actions[2]条的action属性 @run
 - 第actions[2]条的extra属性 @0,pass
- 记录用例 3 的执行日志到 zt_action 表中，extra 字段为 0。
 - 第actions[3]条的objectType属性 @case
 - 第actions[3]条的action属性 @run
 - 第actions[3]条的extra属性 @0,fail
- 记录用例 4 的执行日志到 zt_action 表中，extra 字段为 0。
 - 第actions[4]条的objectType属性 @case
 - 第actions[4]条的action属性 @run
 - 第actions[4]条的extra属性 @0,blocked
- 记录用例 5 的执行日志到 zt_action 表中，extra 字段为 0。
 - 第actions[5]条的objectType属性 @case
 - 第actions[5]条的action属性 @run
 - 第actions[5]条的extra属性 @0,pass
- 记录用例 6 的执行日志到 zt_action 表中，extra 字段为 0。
 - 第actions[6]条的objectType属性 @case
 - 第actions[6]条的action属性 @run
 - 第actions[6]条的extra属性 @0,n/a
- 记录用例 7 的执行日志到 zt_action 表中，extra 字段为 0。
 - 第actions[7]条的objectType属性 @case
 - 第actions[7]条的action属性 @run
 - 第actions[7]条的extra属性 @0,pass
- 记录用例 8 的执行日志到 zt_action 表中，extra 字段为 0。
 - 第actions[8]条的objectType属性 @case
 - 第actions[8]条的action属性 @run
 - 第actions[8]条的extra属性 @0,fail
- 记录用例 9 的执行日志到 zt_action 表中，extra 字段为 0。
 - 第actions[9]条的objectType属性 @case
 - 第actions[9]条的action属性 @run
 - 第actions[9]条的extra属性 @0,blocked
- 记录用例 10 的执行日志到 zt_action 表中，extra 字段为 0。
 - 第actions[10]条的objectType属性 @case
 - 第actions[10]条的action属性 @run
 - 第actions[10]条的extra属性 @0,pass
- 更新 zt_case 表中用例 1 的 lastRunner 字段和 lastRunResult 字段。
 - 第cases[1]条的lastRunner属性 @admin
 - 第cases[1]条的lastRunResult属性 @n/a
- 更新 zt_case 表中用例 2 的 lastRunner 字段和 lastRunResult 字段。
 - 第cases[2]条的lastRunner属性 @admin
 - 第cases[2]条的lastRunResult属性 @pass
- 更新 zt_case 表中用例 3 的 lastRunner 字段和 lastRunResult 字段。
 - 第cases[3]条的lastRunner属性 @admin
 - 第cases[3]条的lastRunResult属性 @fail
- 更新 zt_case 表中用例 4 的 lastRunner 字段和 lastRunResult 字段。
 - 第cases[4]条的lastRunner属性 @admin
 - 第cases[4]条的lastRunResult属性 @blocked
- 更新 zt_case 表中用例 5 的 lastRunner 字段和 lastRunResult 字段。
 - 第cases[5]条的lastRunner属性 @admin
 - 第cases[5]条的lastRunResult属性 @pass
- 记录用例 1 的执行结果到 zt_testresult 表中，run 字段为 1。
 - 第results[1]条的run属性 @1
 - 第results[1]条的case属性 @1
 - 第results[1]条的version属性 @1
 - 第results[1]条的lastRunner属性 @admin
 - 第results[1]条的caseResult属性 @n/a
- 记录用例 2 的执行结果到 zt_testresult 表中，run 字段为 2。
 - 第results[2]条的run属性 @2
 - 第results[2]条的case属性 @2
 - 第results[2]条的version属性 @1
 - 第results[2]条的lastRunner属性 @admin
 - 第results[2]条的caseResult属性 @pass
- 记录用例 3 的执行结果到 zt_testresult 表中，run 字段为 3。
 - 第results[3]条的run属性 @3
 - 第results[3]条的case属性 @3
 - 第results[3]条的version属性 @1
 - 第results[3]条的lastRunner属性 @admin
 - 第results[3]条的caseResult属性 @fail
- 记录用例 4 的执行结果到 zt_testresult 表中，run 字段为 4。
 - 第results[4]条的run属性 @4
 - 第results[4]条的case属性 @4
 - 第results[4]条的version属性 @1
 - 第results[4]条的lastRunner属性 @admin
 - 第results[4]条的caseResult属性 @blocked
- 记录用例 5 的执行结果到 zt_testresult 表中，run 字段为 5。
 - 第results[5]条的run属性 @5
 - 第results[5]条的case属性 @5
 - 第results[5]条的version属性 @1
 - 第results[5]条的lastRunner属性 @admin
 - 第results[5]条的caseResult属性 @pass
- 更新 zt_testrun 表中用例 1 的 lastRunner 字段、lastRunResult 字段和 status 字段。
 - 第runs[1]条的lastRunner属性 @admin
 - 第runs[1]条的lastRunResult属性 @n/a
 - 第runs[1]条的status属性 @normal
- 更新 zt_testrun 表中用例 2 的 lastRunner 字段、lastRunResult 字段和 status 字段。
 - 第runs[2]条的lastRunner属性 @admin
 - 第runs[2]条的lastRunResult属性 @pass
 - 第runs[2]条的status属性 @normal
- 更新 zt_testrun 表中用例 3 的 lastRunner 字段、lastRunResult 字段和 status 字段。
 - 第runs[3]条的lastRunner属性 @admin
 - 第runs[3]条的lastRunResult属性 @fail
 - 第runs[3]条的status属性 @normal
- 更新 zt_testrun 表中用例 4 的 lastRunner 字段、lastRunResult 字段和 status 字段。
 - 第runs[4]条的lastRunner属性 @admin
 - 第runs[4]条的lastRunResult属性 @blocked
 - 第runs[4]条的status属性 @blocked
- 更新 zt_testrun 表中用例 5 的 lastRunner 字段、lastRunResult 字段和 status 字段。
 - 第runs[5]条的lastRunner属性 @admin
 - 第runs[5]条的lastRunResult属性 @pass
 - 第runs[5]条的status属性 @normal
- 记录用例 1 的执行日志到 zt_action 表中，extra 字段为 1。
 - 第actions[1]条的objectType属性 @case
 - 第actions[1]条的action属性 @run
 - 第actions[1]条的extra属性 @1,n/a
- 记录用例 2 的执行日志到 zt_action 表中，extra 字段为 1。
 - 第actions[2]条的objectType属性 @case
 - 第actions[2]条的action属性 @run
 - 第actions[2]条的extra属性 @1,pass
- 记录用例 3 的执行日志到 zt_action 表中，extra 字段为 1。
 - 第actions[3]条的objectType属性 @case
 - 第actions[3]条的action属性 @run
 - 第actions[3]条的extra属性 @1,fail
- 记录用例 4 的执行日志到 zt_action 表中，extra 字段为 1。
 - 第actions[4]条的objectType属性 @case
 - 第actions[4]条的action属性 @run
 - 第actions[4]条的extra属性 @1,blocked
- 记录用例 5 的执行日志到 zt_action 表中，extra 字段为 1。
 - 第actions[5]条的objectType属性 @case
 - 第actions[5]条的action属性 @run
 - 第actions[5]条的extra属性 @1,pass

*/

$testtask = new testtaskTest();

r($testtask->batchRunTest(array()))   && p() && e(0); // 测试用例参数为空数组返回 false。
r($testtask->batchRunTest(array(''))) && p() && e(0); // 测试用例参数内元素为空字符串返回 false。
r($testtask->batchRunTest(array(0)))  && p() && e(0); // 测试用例参数内元素为 0 返回 false。

$case1  = (object)array('version' => 1, 'results' => 'n/a',     'steps' => array(1  => 'pass', 2  => 'pass', 3  => 'n/a'),     'reals' => array(1  => '', 2  => '', 3  => '')); // 用例 1 有测试单，测试结果为忽略。
$case2  = (object)array('version' => 1, 'results' => 'pass',    'steps' => array(4  => 'pass', 5  => 'pass', 6  => 'pass'),    'reals' => array(4  => '', 5  => '', 6  => '')); // 用例 2 有测试单，测试结果为通过。
$case3  = (object)array('version' => 1, 'results' => 'fail',    'steps' => array(7  => 'pass', 8  => 'pass', 9  => 'fail'),    'reals' => array(7  => '', 8  => '', 9  => '')); // 用例 3 有测试单，测试结果为失败。
$case4  = (object)array('version' => 1, 'results' => 'blocked', 'steps' => array(10 => 'pass', 11 => 'pass', 12 => 'blocked'), 'reals' => array(10 => '', 11 => '', 12 => '')); // 用例 4 有测试单，测试结果为阻塞。
$case5  = (object)array('version' => 1, 'results' => 'pass',    'reals' => array(''));                                                                                          // 用例 5 有测试单，没有测试步骤，测试结果为通过。
$case6  = (object)array('version' => 1, 'results' => 'n/a',     'steps' => array(13 => 'pass', 14 => 'pass', 15 => 'n/a'),     'reals' => array(13 => '', 14 => '', 15 => '')); // 用例 6 没有测试单，测试结果为忽略。
$case7  = (object)array('version' => 1, 'results' => 'pass',    'steps' => array(16 => 'pass', 17 => 'pass', 18 => 'pass'),    'reals' => array(16 => '', 17 => '', 18 => '')); // 用例 7 没有测试单，测试结果为通过。
$case8  = (object)array('version' => 1, 'results' => 'fail',    'steps' => array(19 => 'pass', 20 => 'pass', 21 => 'fail'),    'reals' => array(19 => '', 20 => '', 21 => '')); // 用例 8 没有测试单，测试结果为失败。
$case9  = (object)array('version' => 1, 'results' => 'blocked', 'steps' => array(22 => 'pass', 23 => 'pass', 24 => 'blocked'), 'reals' => array(22 => '', 23 => '', 24 => '')); // 用例 9 没有测试单，测试结果为阻塞。
$case10 = (object)array('version' => 1, 'results' => 'pass',    'reals' => array(''));                                                                                          // 用例 10 没有测试单，没有测试步骤，测试结果为通过。

$cases  = array(1 => $case1, 2 => $case2, 3 => $case3, 4 => $case4, 5 => $case5, 6 => $case6, 7 => $case7, 8 => $case8, 9 => $case9, 10 => $case10);
$result = $testtask->batchRunTest($cases, 'testcase'); // 从用例列表批量执行用例。

r($result) && p('cases[1]:lastRunner,lastRunResult')  && e('admin,n/a');     // 更新 zt_case 表中用例 1 的 lastRunner 字段和 lastRunResult 字段。
r($result) && p('cases[2]:lastRunner,lastRunResult')  && e('admin,pass');    // 更新 zt_case 表中用例 2 的 lastRunner 字段和 lastRunResult 字段。
r($result) && p('cases[3]:lastRunner,lastRunResult')  && e('admin,fail');    // 更新 zt_case 表中用例 3 的 lastRunner 字段和 lastRunResult 字段。
r($result) && p('cases[4]:lastRunner,lastRunResult')  && e('admin,blocked'); // 更新 zt_case 表中用例 4 的 lastRunner 字段和 lastRunResult 字段。
r($result) && p('cases[5]:lastRunner,lastRunResult')  && e('admin,pass');    // 更新 zt_case 表中用例 5 的 lastRunner 字段和 lastRunResult 字段。
r($result) && p('cases[6]:lastRunner,lastRunResult')  && e('admin,n/a');     // 更新 zt_case 表中用例 6 的 lastRunner 字段和 lastRunResult 字段。
r($result) && p('cases[7]:lastRunner,lastRunResult')  && e('admin,pass');    // 更新 zt_case 表中用例 7 的 lastRunner 字段和 lastRunResult 字段。
r($result) && p('cases[8]:lastRunner,lastRunResult')  && e('admin,fail');    // 更新 zt_case 表中用例 8 的 lastRunner 字段和 lastRunResult 字段。
r($result) && p('cases[9]:lastRunner,lastRunResult')  && e('admin,blocked'); // 更新 zt_case 表中用例 9 的 lastRunner 字段和 lastRunResult 字段。
r($result) && p('cases[10]:lastRunner,lastRunResult') && e('admin,pass');    // 更新 zt_case 表中用例 10 的 lastRunner 字段和 lastRunResult 字段。

r($result) && p('results[1]:run,case,version,lastRunner,caseResult') && e('0,1,1,admin,n/a');     // 记录用例 1 的执行结果到 zt_testresult 表中，run 字段为 0。
r($result) && p('results[2]:run,case,version,lastRunner,caseResult') && e('0,2,1,admin,pass');    // 记录用例 2 的执行结果到 zt_testresult 表中，run 字段为 0。
r($result) && p('results[3]:run,case,version,lastRunner,caseResult') && e('0,3,1,admin,fail');    // 记录用例 3 的执行结果到 zt_testresult 表中，run 字段为 0。
r($result) && p('results[4]:run,case,version,lastRunner,caseResult') && e('0,4,1,admin,blocked'); // 记录用例 4 的执行结果到 zt_testresult 表中，run 字段为 0。
r($result) && p('results[5]:run,case,version,lastRunner,caseResult') && e('0,5,1,admin,pass');    // 记录用例 5 的执行结果到 zt_testresult 表中，run 字段为 0。
r($result) && p('results[6]:run,case,version,lastRunner,caseResult') && e('0,6,1,admin,n/a');     // 记录用例 6 的执行结果到 zt_testresult 表中，run 字段为 0。
r($result) && p('results[7]:run,case,version,lastRunner,caseResult') && e('0,7,1,admin,pass');    // 记录用例 7 的执行结果到 zt_testresult 表中，run 字段为 0。
r($result) && p('results[8]:run,case,version,lastRunner,caseResult') && e('0,8,1,admin,fail');    // 记录用例 8 的执行结果到 zt_testresult 表中，run 字段为 0。
r($result) && p('results[9]:run,case,version,lastRunner,caseResult') && e('0,9,1,admin,blocked'); // 记录用例 9 的执行结果到 zt_testresult 表中，run 字段为 0。
r($result) && p('results[10]:run,case,version,lastRunner,caseResult') && e('0,10,1,admin,pass');  // 记录用例 10 的执行结果到 zt_testresult 表中，run 字段为 0。

r($result) && p('actions[1]:objectType|action|extra', '|')  && e('case|run|0,n/a');     // 记录用例 1 的执行日志到 zt_action 表中，extra 字段为 0。
r($result) && p('actions[2]:objectType|action|extra', '|')  && e('case|run|0,pass');    // 记录用例 2 的执行日志到 zt_action 表中，extra 字段为 0。
r($result) && p('actions[3]:objectType|action|extra', '|')  && e('case|run|0,fail');    // 记录用例 3 的执行日志到 zt_action 表中，extra 字段为 0。
r($result) && p('actions[4]:objectType|action|extra', '|')  && e('case|run|0,blocked'); // 记录用例 4 的执行日志到 zt_action 表中，extra 字段为 0。
r($result) && p('actions[5]:objectType|action|extra', '|')  && e('case|run|0,pass');    // 记录用例 5 的执行日志到 zt_action 表中，extra 字段为 0。
r($result) && p('actions[6]:objectType|action|extra', '|')  && e('case|run|0,n/a');     // 记录用例 6 的执行日志到 zt_action 表中，extra 字段为 0。
r($result) && p('actions[7]:objectType|action|extra', '|')  && e('case|run|0,pass');    // 记录用例 7 的执行日志到 zt_action 表中，extra 字段为 0。
r($result) && p('actions[8]:objectType|action|extra', '|')  && e('case|run|0,fail');    // 记录用例 8 的执行日志到 zt_action 表中，extra 字段为 0。
r($result) && p('actions[9]:objectType|action|extra', '|')  && e('case|run|0,blocked'); // 记录用例 9 的执行日志到 zt_action 表中，extra 字段为 0。
r($result) && p('actions[10]:objectType|action|extra', '|') && e('case|run|0,pass');    // 记录用例 10 的执行日志到 zt_action 表中，extra 字段为 0。

$cases  = array(1 => $case1, 2 => $case2, 3 => $case3, 4 => $case4, 5 => $case5);
$result = $testtask->batchRunTest($cases, 'testtask', 1); // 从测试单中批量执行用例。

r($result) && p('cases[1]:lastRunner,lastRunResult') && e('admin,n/a');     // 更新 zt_case 表中用例 1 的 lastRunner 字段和 lastRunResult 字段。
r($result) && p('cases[2]:lastRunner,lastRunResult') && e('admin,pass');    // 更新 zt_case 表中用例 2 的 lastRunner 字段和 lastRunResult 字段。
r($result) && p('cases[3]:lastRunner,lastRunResult') && e('admin,fail');    // 更新 zt_case 表中用例 3 的 lastRunner 字段和 lastRunResult 字段。
r($result) && p('cases[4]:lastRunner,lastRunResult') && e('admin,blocked'); // 更新 zt_case 表中用例 4 的 lastRunner 字段和 lastRunResult 字段。
r($result) && p('cases[5]:lastRunner,lastRunResult') && e('admin,pass');    // 更新 zt_case 表中用例 5 的 lastRunner 字段和 lastRunResult 字段。

r($result) && p('results[1]:run,case,version,lastRunner,caseResult') && e('1,1,1,admin,n/a');     // 记录用例 1 的执行结果到 zt_testresult 表中，run 字段为 1。
r($result) && p('results[2]:run,case,version,lastRunner,caseResult') && e('2,2,1,admin,pass');    // 记录用例 2 的执行结果到 zt_testresult 表中，run 字段为 2。
r($result) && p('results[3]:run,case,version,lastRunner,caseResult') && e('3,3,1,admin,fail');    // 记录用例 3 的执行结果到 zt_testresult 表中，run 字段为 3。
r($result) && p('results[4]:run,case,version,lastRunner,caseResult') && e('4,4,1,admin,blocked'); // 记录用例 4 的执行结果到 zt_testresult 表中，run 字段为 4。
r($result) && p('results[5]:run,case,version,lastRunner,caseResult') && e('5,5,1,admin,pass');    // 记录用例 5 的执行结果到 zt_testresult 表中，run 字段为 5。

r($result) && p('runs[1]:lastRunner,lastRunResult,status') && e('admin,n/a,normal');      // 更新 zt_testrun 表中用例 1 的 lastRunner 字段、lastRunResult 字段和 status 字段。
r($result) && p('runs[2]:lastRunner,lastRunResult,status') && e('admin,pass,normal');     // 更新 zt_testrun 表中用例 2 的 lastRunner 字段、lastRunResult 字段和 status 字段。
r($result) && p('runs[3]:lastRunner,lastRunResult,status') && e('admin,fail,normal');     // 更新 zt_testrun 表中用例 3 的 lastRunner 字段、lastRunResult 字段和 status 字段。
r($result) && p('runs[4]:lastRunner,lastRunResult,status') && e('admin,blocked,blocked'); // 更新 zt_testrun 表中用例 4 的 lastRunner 字段、lastRunResult 字段和 status 字段。
r($result) && p('runs[5]:lastRunner,lastRunResult,status') && e('admin,pass,normal');     // 更新 zt_testrun 表中用例 5 的 lastRunner 字段、lastRunResult 字段和 status 字段。

r($result) && p('actions[1]:objectType|action|extra', '|') && e('case|run|1,n/a');     // 记录用例 1 的执行日志到 zt_action 表中，extra 字段为 1。
r($result) && p('actions[2]:objectType|action|extra', '|') && e('case|run|1,pass');    // 记录用例 2 的执行日志到 zt_action 表中，extra 字段为 1。
r($result) && p('actions[3]:objectType|action|extra', '|') && e('case|run|1,fail');    // 记录用例 3 的执行日志到 zt_action 表中，extra 字段为 1。
r($result) && p('actions[4]:objectType|action|extra', '|') && e('case|run|1,blocked'); // 记录用例 4 的执行日志到 zt_action 表中，extra 字段为 1。
r($result) && p('actions[5]:objectType|action|extra', '|') && e('case|run|1,pass');    // 记录用例 5 的执行日志到 zt_action 表中，extra 字段为 1。
