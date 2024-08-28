#!/usr/bin/env php
<?php

chdir(__DIR__);
include '../lib/changeplanstatus.ui.class.php';

zendata('product')->loadYaml('product', false, 2)->gen(10);
zendata('productplan')->loadYaml('productplan', false, 2)->gen(10);
$tester = new changePlanStatus();
$tester->login();

$planID['planID'] = 5;

r($tester->startPlan($planID))  && p('message,status') && e('开始计划成功,SUCCESS');
r($tester->finishPlan($planID)) && p('message,status') && e('完成计划成功,SUCCESS');
r($tester->closePlan($planID))  && p('message,status') && e('关闭计划成功,SUCCESS');
r($tester->activePlan($planID)) && p('message,status') && e('激活计划成功,SUCCESS');
r($tester->deletePlan($planID)) && p('message,status') && e('删除计划成功,SUCCESS');
$tester->closeBrowser();
