#!/usr/bin/env php
<?php
/**

title=批量变更状态
timeout=0
cid=0

*/
chdir(__DIR__);
include '../lib/batchchangestatus.ui.class.php';
zendata('product')->loadYaml('product', false, 2)->gen(10);
zendata('productplan')->loadYaml('productplan', false, 2)->gen(10);
$tester = new batchChangeStatusTester();
$tester->login();

$planurl['product'] = 2;
$planStatus = 'waiting';
r($tester->batchChangeStatus($planStatus, $planurl)) && p('message,status') && e("批量变更为waiting状态成功,SUCCESS");//批量变更为未开始状态

$planStatus = 'doing';
r($tester->batchChangeStatus($planStatus, $planurl)) && p('message,status') && e("批量变更为doing状态成功,SUCCESS");//批量变更为进行中状态

$planStatus = 'done';
r($tester->batchChangeStatus($planStatus, $planurl)) && p('message,status') && e("批量变更为done状态成功,SUCCESS");//批量变更为已完成状态

$tester->closeBrowser();
