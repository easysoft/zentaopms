#!/usr/bin/env php
<?php
/**

title=测试productplanModel->buildPlanByStatus();
timeout=0
cid=17621

- 测试构造doing状态的计划
 - 属性status @doing
 - 属性closedReason @~~
- 测试构造done状态的计划
 - 属性status @done
 - 属性closedReason @~~
- 测试构造closed状态的计划
 - 属性status @closed
 - 属性closedReason @~~
- 测试构造closed状态的计划且关闭原因为已完成
 - 属性status @closed
 - 属性closedReason @done
- 测试构造closed状态的计划且关闭原因为已取消
 - 属性status @closed
 - 属性closedReason @cancel

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('user')->gen(5);
su('admin');

$statusList       = array('doing', 'done', 'closed');
$closedReasonList = array('', 'done', 'cancel');

global $tester, $app;
$app->rawModule  = 'productplan';
$app->moduleName = 'productplan';

$tester->loadModel('productplan');
r($tester->productplan->buildPlanByStatus($statusList[0], $closedReasonList[0])) && p('status,closedReason') && e('doing,~~');      // 测试构造doing状态的计划
r($tester->productplan->buildPlanByStatus($statusList[1], $closedReasonList[0])) && p('status,closedReason') && e('done,~~');       // 测试构造done状态的计划
r($tester->productplan->buildPlanByStatus($statusList[2], $closedReasonList[0])) && p('status,closedReason') && e('closed,~~');     // 测试构造closed状态的计划
r($tester->productplan->buildPlanByStatus($statusList[2], $closedReasonList[1])) && p('status,closedReason') && e('closed,done');   // 测试构造closed状态的计划且关闭原因为已完成
r($tester->productplan->buildPlanByStatus($statusList[2], $closedReasonList[2])) && p('status,closedReason') && e('closed,cancel'); // 测试构造closed状态的计划且关闭原因为已取消
