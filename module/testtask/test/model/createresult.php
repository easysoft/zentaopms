#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtask.unittest.class.php';
su('admin');

zenData('case')->gen(2);
zenData('testrun')->gen(2);
zenData('testresult')->gen(0);

/**

title=测试 testtaskModel->createResult();
timeout=0
cid=19161

- 在测试单外执行测试用例 1，测试结果为忽略。
 - 属性caseResult @pass
 - 第case条的lastRunner属性 @admin
 - 第case条的lastRunResult属性 @pass
 - 第result条的lastRunner属性 @admin
 - 第result条的run属性 @0
 - 第result条的case属性 @1
 - 第result条的version属性 @1
 - 第result条的caseResult属性 @pass
- 在测试单外执行测试用例 1，测试结果为通过。
 - 属性caseResult @pass
 - 第case条的lastRunner属性 @admin
 - 第case条的lastRunResult属性 @pass
 - 第result条的lastRunner属性 @admin
 - 第result条的run属性 @0
 - 第result条的case属性 @1
 - 第result条的version属性 @1
 - 第result条的caseResult属性 @pass
- 在测试单外执行测试用例 1，测试结果为失败。
 - 属性caseResult @fail
 - 第case条的lastRunner属性 @admin
 - 第case条的lastRunResult属性 @fail
 - 第result条的lastRunner属性 @admin
 - 第result条的run属性 @0
 - 第result条的case属性 @1
 - 第result条的version属性 @1
 - 第result条的caseResult属性 @fail
- 在测试单外执行测试用例 1，测试结果为阻塞。
 - 属性caseResult @blocked
 - 第case条的lastRunner属性 @admin
 - 第case条的lastRunResult属性 @blocked
 - 第result条的lastRunner属性 @admin
 - 第result条的run属性 @0
 - 第result条的case属性 @1
 - 第result条的version属性 @1
 - 第result条的caseResult属性 @blocked
- 在测试单外执行测试用例 2，测试结果为忽略。
 - 属性caseResult @pass
 - 第case条的lastRunner属性 @admin
 - 第case条的lastRunResult属性 @pass
 - 第result条的lastRunner属性 @admin
 - 第result条的run属性 @0
 - 第result条的case属性 @2
 - 第result条的version属性 @1
 - 第result条的caseResult属性 @pass
- 在测试单外执行测试用例 2，测试结果为通过。
 - 属性caseResult @pass
 - 第case条的lastRunner属性 @admin
 - 第case条的lastRunResult属性 @pass
 - 第result条的lastRunner属性 @admin
 - 第result条的run属性 @0
 - 第result条的case属性 @2
 - 第result条的version属性 @1
 - 第result条的caseResult属性 @pass
- 在测试单外执行测试用例 2，测试结果为失败。
 - 属性caseResult @fail
 - 第case条的lastRunner属性 @admin
 - 第case条的lastRunResult属性 @fail
 - 第result条的lastRunner属性 @admin
 - 第result条的run属性 @0
 - 第result条的case属性 @2
 - 第result条的version属性 @1
 - 第result条的caseResult属性 @fail
- 在测试单外执行测试用例 2，测试结果为阻塞。
 - 属性caseResult @blocked
 - 第case条的lastRunner属性 @admin
 - 第case条的lastRunResult属性 @blocked
 - 第result条的lastRunner属性 @admin
 - 第result条的run属性 @0
 - 第result条的case属性 @2
 - 第result条的version属性 @1
 - 第result条的caseResult属性 @blocked
- 在测试单 1 中执行测试用例 1，测试结果为忽略。
 - 属性caseResult @pass
 - 第case条的lastRunner属性 @admin
 - 第case条的lastRunResult属性 @pass
 - 第result条的lastRunner属性 @admin
 - 第result条的run属性 @1
 - 第result条的case属性 @1
 - 第result条的version属性 @1
 - 第result条的caseResult属性 @pass
 - 第run条的lastRunner属性 @admin
 - 第run条的lastRunResult属性 @pass
 - 第run条的status属性 @normal
- 在测试单 1 中执行测试用例 1，测试结果为通过。
 - 属性caseResult @pass
 - 第case条的lastRunner属性 @admin
 - 第case条的lastRunResult属性 @pass
 - 第result条的lastRunner属性 @admin
 - 第result条的run属性 @1
 - 第result条的case属性 @1
 - 第result条的version属性 @1
 - 第result条的caseResult属性 @pass
 - 第run条的lastRunner属性 @admin
 - 第run条的lastRunResult属性 @pass
 - 第run条的status属性 @normal
- 在测试单 1 中执行测试用例 1，测试结果为失败。
 - 属性caseResult @fail
 - 第case条的lastRunner属性 @admin
 - 第case条的lastRunResult属性 @fail
 - 第result条的lastRunner属性 @admin
 - 第result条的run属性 @1
 - 第result条的case属性 @1
 - 第result条的version属性 @1
 - 第result条的caseResult属性 @fail
 - 第run条的lastRunner属性 @admin
 - 第run条的lastRunResult属性 @fail
 - 第run条的status属性 @normal
- 在测试单 1 中执行测试用例 1，测试结果为阻塞。
 - 属性caseResult @blocked
 - 第case条的lastRunner属性 @admin
 - 第case条的lastRunResult属性 @blocked
 - 第result条的lastRunner属性 @admin
 - 第result条的run属性 @1
 - 第result条的case属性 @1
 - 第result条的version属性 @1
 - 第result条的caseResult属性 @blocked
 - 第run条的lastRunner属性 @admin
 - 第run条的lastRunResult属性 @blocked
 - 第run条的status属性 @blocked
- 在测试单 2 中执行测试用例 2，测试结果为忽略。
 - 属性caseResult @pass
 - 第case条的lastRunner属性 @admin
 - 第case条的lastRunResult属性 @pass
 - 第result条的lastRunner属性 @admin
 - 第result条的run属性 @2
 - 第result条的case属性 @2
 - 第result条的version属性 @1
 - 第result条的caseResult属性 @pass
 - 第run条的lastRunner属性 @admin
 - 第run条的lastRunResult属性 @pass
 - 第run条的status属性 @normal
- 在测试单 2 中执行测试用例 2，测试结果为通过。
 - 属性caseResult @pass
 - 第case条的lastRunner属性 @admin
 - 第case条的lastRunResult属性 @pass
 - 第result条的lastRunner属性 @admin
 - 第result条的run属性 @2
 - 第result条的case属性 @2
 - 第result条的version属性 @1
 - 第result条的caseResult属性 @pass
 - 第run条的lastRunner属性 @admin
 - 第run条的lastRunResult属性 @pass
 - 第run条的status属性 @normal
- 在测试单 2 中执行测试用例 2，测试结果为失败。
 - 属性caseResult @fail
 - 第case条的lastRunner属性 @admin
 - 第case条的lastRunResult属性 @fail
 - 第result条的lastRunner属性 @admin
 - 第result条的run属性 @2
 - 第result条的case属性 @2
 - 第result条的version属性 @1
 - 第result条的caseResult属性 @fail
 - 第run条的lastRunner属性 @admin
 - 第run条的lastRunResult属性 @fail
 - 第run条的status属性 @normal
- 在测试单 2 中执行测试用例 2，测试结果为阻塞。
 - 属性caseResult @blocked
 - 第case条的lastRunner属性 @admin
 - 第case条的lastRunResult属性 @blocked
 - 第result条的lastRunner属性 @admin
 - 第result条的run属性 @2
 - 第result条的case属性 @2
 - 第result条的version属性 @1
 - 第result条的caseResult属性 @blocked
 - 第run条的lastRunner属性 @admin
 - 第run条的lastRunResult属性 @blocked
 - 第run条的status属性 @blocked

*/

$testtask = new testtaskTest();

$stepResults1 = array(1 => (object)array('result' => 'pass', 'real' => ''), 2 => (object)array('result' => 'n/a',     'real' => '')); // 测试用例 1 有测试步骤，测试结果为忽略。
$stepResults2 = array(1 => (object)array('result' => 'pass', 'real' => ''), 2 => (object)array('result' => 'pass',    'real' => '')); // 测试用例 1 有测试步骤，测试结果为通过。
$stepResults3 = array(1 => (object)array('result' => 'pass', 'real' => ''), 2 => (object)array('result' => 'fail',    'real' => '')); // 测试用例 1 有测试步骤，测试结果为失败。
$stepResults4 = array(1 => (object)array('result' => 'pass', 'real' => ''), 2 => (object)array('result' => 'blocked', 'real' => '')); // 测试用例 1 有测试步骤，测试结果为阻塞。
$stepResults5 = array(0 => (object)array('result' => 'n/a',     'real' => '')); // 测试用例 2 无测试步骤，测试结果为忽略。
$stepResults6 = array(0 => (object)array('result' => 'pass',    'real' => '')); // 测试用例 2 无测试步骤，测试结果为通过。
$stepResults7 = array(0 => (object)array('result' => 'fail',    'real' => '')); // 测试用例 2 无测试步骤，测试结果为失败。
$stepResults8 = array(0 => (object)array('result' => 'blocked', 'real' => '')); // 测试用例 2 无测试步骤，测试结果为阻塞。

r($testtask->createResultTest(0, 1, 1, $stepResults1)) && p('caseResult;case:lastRunner,lastRunResult;result:lastRunner,run,case,version,caseResult') && e('pass;admin,pass;admin,0,1,1,pass');          // 在测试单外执行测试用例 1，测试结果为忽略。
r($testtask->createResultTest(0, 1, 1, $stepResults2)) && p('caseResult;case:lastRunner,lastRunResult;result:lastRunner,run,case,version,caseResult') && e('pass;admin,pass;admin,0,1,1,pass');          // 在测试单外执行测试用例 1，测试结果为通过。
r($testtask->createResultTest(0, 1, 1, $stepResults3)) && p('caseResult;case:lastRunner,lastRunResult;result:lastRunner,run,case,version,caseResult') && e('fail;admin,fail;admin,0,1,1,fail');          // 在测试单外执行测试用例 1，测试结果为失败。
r($testtask->createResultTest(0, 1, 1, $stepResults4)) && p('caseResult;case:lastRunner,lastRunResult;result:lastRunner,run,case,version,caseResult') && e('blocked;admin,blocked;admin,0,1,1,blocked'); // 在测试单外执行测试用例 1，测试结果为阻塞。
r($testtask->createResultTest(0, 2, 1, $stepResults5)) && p('caseResult;case:lastRunner,lastRunResult;result:lastRunner,run,case,version,caseResult') && e('pass;admin,pass;admin,0,2,1,pass');          // 在测试单外执行测试用例 2，测试结果为忽略。
r($testtask->createResultTest(0, 2, 1, $stepResults6)) && p('caseResult;case:lastRunner,lastRunResult;result:lastRunner,run,case,version,caseResult') && e('pass;admin,pass;admin,0,2,1,pass');          // 在测试单外执行测试用例 2，测试结果为通过。
r($testtask->createResultTest(0, 2, 1, $stepResults7)) && p('caseResult;case:lastRunner,lastRunResult;result:lastRunner,run,case,version,caseResult') && e('fail;admin,fail;admin,0,2,1,fail');          // 在测试单外执行测试用例 2，测试结果为失败。
r($testtask->createResultTest(0, 2, 1, $stepResults8)) && p('caseResult;case:lastRunner,lastRunResult;result:lastRunner,run,case,version,caseResult') && e('blocked;admin,blocked;admin,0,2,1,blocked'); // 在测试单外执行测试用例 2，测试结果为阻塞。

r($testtask->createResultTest(1, 1, 1, $stepResults1)) && p('caseResult;case:lastRunner,lastRunResult;result:lastRunner,run,case,version,caseResult;run:lastRunner,lastRunResult,status') && e('pass;admin,pass;admin,1,1,1,pass;admin,pass,normal');              // 在测试单 1 中执行测试用例 1，测试结果为忽略。
r($testtask->createResultTest(1, 1, 1, $stepResults2)) && p('caseResult;case:lastRunner,lastRunResult;result:lastRunner,run,case,version,caseResult;run:lastRunner,lastRunResult,status') && e('pass;admin,pass;admin,1,1,1,pass;admin,pass,normal');              // 在测试单 1 中执行测试用例 1，测试结果为通过。
r($testtask->createResultTest(1, 1, 1, $stepResults3)) && p('caseResult;case:lastRunner,lastRunResult;result:lastRunner,run,case,version,caseResult;run:lastRunner,lastRunResult,status') && e('fail;admin,fail;admin,1,1,1,fail;admin,fail,normal');              // 在测试单 1 中执行测试用例 1，测试结果为失败。
r($testtask->createResultTest(1, 1, 1, $stepResults4)) && p('caseResult;case:lastRunner,lastRunResult;result:lastRunner,run,case,version,caseResult;run:lastRunner,lastRunResult,status') && e('blocked;admin,blocked;admin,1,1,1,blocked;admin,blocked,blocked'); // 在测试单 1 中执行测试用例 1，测试结果为阻塞。
r($testtask->createResultTest(2, 2, 1, $stepResults5)) && p('caseResult;case:lastRunner,lastRunResult;result:lastRunner,run,case,version,caseResult;run:lastRunner,lastRunResult,status') && e('pass;admin,pass;admin,2,2,1,pass;admin,pass,normal');              // 在测试单 2 中执行测试用例 2，测试结果为忽略。
r($testtask->createResultTest(2, 2, 1, $stepResults6)) && p('caseResult;case:lastRunner,lastRunResult;result:lastRunner,run,case,version,caseResult;run:lastRunner,lastRunResult,status') && e('pass;admin,pass;admin,2,2,1,pass;admin,pass,normal');              // 在测试单 2 中执行测试用例 2，测试结果为通过。
r($testtask->createResultTest(2, 2, 1, $stepResults7)) && p('caseResult;case:lastRunner,lastRunResult;result:lastRunner,run,case,version,caseResult;run:lastRunner,lastRunResult,status') && e('fail;admin,fail;admin,2,2,1,fail;admin,fail,normal');              // 在测试单 2 中执行测试用例 2，测试结果为失败。
r($testtask->createResultTest(2, 2, 1, $stepResults8)) && p('caseResult;case:lastRunner,lastRunResult;result:lastRunner,run,case,version,caseResult;run:lastRunner,lastRunResult,status') && e('blocked;admin,blocked;admin,2,2,1,blocked;admin,blocked,blocked'); // 在测试单 2 中执行测试用例 2，测试结果为阻塞。
