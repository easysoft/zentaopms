#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/batchchangestatus.ui.class.php';
zendata('product')->loadYaml('product', false, 2)->gen(10);
zendata('productplan')->loadYaml('productplan', false, 2)->gen(10);
$tester = new batchChangeStatusTester();
$tester->login();

$planurl['product'] = 2;
$planStatus = 'waiting';
r($tester->batchChangeStatus($planStatus, $planurl)) && p('message,status') && e("批量变更为{$planStatus}状态成功,SUCCESS");

$planStatus = 'doing';
r($tester->batchChangeStatus($planStatus, $planurl)) && p('message,status') && e("批量变更为{$planStatus}状态成功,SUCCESS");

$planStatus = 'done';
r($tester->batchChangeStatus($planStatus, $planurl)) && p('message,status') && e("批量变更为{$planStatus}状态成功,SUCCESS");

$tester->closeBrowser();
