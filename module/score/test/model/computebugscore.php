#!/usr/bin/env php
<?php
/**

title=测试 scoreModel->computeBugScore();
cid=1

- 计算caseID=0时，用例建bug的积分第0条的score属性 @1
- 计算caseID=1时，用例建bug的积分第0条的score属性 @1
- 计算caseID不存在时，用例建bug的积分第0条的score属性 @1
- 计算caseID=0时，保存bug步骤模版的积分第0条的score属性 @20
- 计算caseID=1时，保存bug步骤模版的积分第0条的score属性 @20
- 计算caseID不存在时，保存bug步骤模版的积分第0条的score属性 @20
- 计算caseID=0时，确认bug的积分第0条的score属性 @4
- 计算caseID=1时，确认bug的积分第0条的score属性 @4
- 计算caseID不存在时，确认bug的积分第0条的score属性 @4
- 计算caseID=0时，解决bug的积分第0条的score属性 @4
- 计算caseID=1时，解决bug的积分第0条的score属性 @4
- 计算caseID不存在时，解决bug的积分第0条的score属性 @4
- 计算caseID=0时，创建bug的积分第0条的score属性 @1
- 计算caseID=1时，创建bug的积分第0条的score属性 @1
- 计算caseID不存在时，创建bug的积分第0条的score属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/score.class.php';

zdTable('user')->gen(5);
zdTable('case')->gen(1);
zdTable('bug')->gen(1);

$caseIds = array(0, 1, 2);
$methods = array('createFormCase', 'saveTplModal', 'confirm', 'resolve', 'create');

$scoreTester = new scoreTest();
r($scoreTester->computeBugScoreTest($caseIds[0], $methods[0])) && p('0:score') && e('1');  // 计算caseID=0时，用例建bug的积分
r($scoreTester->computeBugScoreTest($caseIds[1], $methods[0])) && p('0:score') && e('1');  // 计算caseID=1时，用例建bug的积分
r($scoreTester->computeBugScoreTest($caseIds[2], $methods[0])) && p('0:score') && e('1');  // 计算caseID不存在时，用例建bug的积分
r($scoreTester->computeBugScoreTest($caseIds[0], $methods[1])) && p('0:score') && e('20'); // 计算caseID=0时，保存bug步骤模版的积分
r($scoreTester->computeBugScoreTest($caseIds[1], $methods[1])) && p('0:score') && e('20'); // 计算caseID=1时，保存bug步骤模版的积分
r($scoreTester->computeBugScoreTest($caseIds[2], $methods[1])) && p('0:score') && e('20'); // 计算caseID不存在时，保存bug步骤模版的积分
r($scoreTester->computeBugScoreTest($caseIds[0], $methods[2])) && p('0:score') && e('4');  // 计算caseID=0时，确认bug的积分
r($scoreTester->computeBugScoreTest($caseIds[1], $methods[2])) && p('0:score') && e('4');  // 计算caseID=1时，确认bug的积分
r($scoreTester->computeBugScoreTest($caseIds[2], $methods[2])) && p('0:score') && e('4');  // 计算caseID不存在时，确认bug的积分
r($scoreTester->computeBugScoreTest($caseIds[0], $methods[3])) && p('0:score') && e('4');  // 计算caseID=0时，解决bug的积分
r($scoreTester->computeBugScoreTest($caseIds[1], $methods[3])) && p('0:score') && e('4');  // 计算caseID=1时，解决bug的积分
r($scoreTester->computeBugScoreTest($caseIds[2], $methods[3])) && p('0:score') && e('4');  // 计算caseID不存在时，解决bug的积分
r($scoreTester->computeBugScoreTest($caseIds[0], $methods[4])) && p('0:score') && e('1');  // 计算caseID=0时，创建bug的积分
r($scoreTester->computeBugScoreTest($caseIds[1], $methods[4])) && p('0:score') && e('1');  // 计算caseID=1时，创建bug的积分
r($scoreTester->computeBugScoreTest($caseIds[2], $methods[4])) && p('0:score') && e('1');  // 计算caseID不存在时，创建bug的积分
