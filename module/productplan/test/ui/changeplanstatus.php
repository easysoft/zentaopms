#!/usr/bin/env php
<?php
/**

title=修改计划状态
timeout=0
cid=0

*/
chdir(__DIR__);
include '../lib/changeplanstatus.ui.class.php';

zendata('product')->loadYaml('product', false, 2)->gen(10);
zendata('productplan')->loadYaml('productplan', false, 2)->gen(10);
$tester = new changePlanStatus();
$tester->login();

$planID['planID'] = 5;

r($tester->startPlan($planID))  && p('message,status') && e('开始计划成功,SUCCESS');//开始计划
r($tester->finishPlan($planID)) && p('message,status') && e('完成计划成功,SUCCESS');//完成计划
r($tester->closePlan($planID))  && p('message,status') && e('关闭计划成功,SUCCESS');//关闭计划
r($tester->activePlan($planID)) && p('message,status') && e('激活计划成功,SUCCESS');//激活计划
r($tester->deletePlan($planID)) && p('message,status') && e('删除计划成功,SUCCESS');//删除计划
$tester->closeBrowser();
