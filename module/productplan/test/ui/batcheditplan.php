#!/usr/bin/env php
<?php
/**
title=批量编辑计划
timeout=0
cid=0
*/
chdir(__DIR__);
include '../lib/batcheditplan.ui.class.php';

zendata('product')->loadYaml('product', false, 2)->gen(10);
zendata('productplan')->loadYaml('productplan', false, 2)->gen(10);
$tester = new batchEditPlanTester();
$tester->login();

$planurl['productID'] = 2;
$planurl['branch']    = 0;

$productplan = new stdClass();
$productplan->title = '';
r($tester->batchEditPlan($productplan, $planurl)) && p('message,status') && e('计划名称必填提示信息正确,SUCCESS'); // 计划名称必填校验

$productplan->title = '计划_编辑后';
r($tester->batchEditPlan($productplan, $planurl)) && p('message,status') && e('编辑计划成功,SUCCESS'); //编辑计划

$productplan->begin = '2024-06-24';
$productplan->end   = '2024-05-30';
r($tester->batchEditPlan($productplan, $planurl)) && p('message,status') && e('日期校验提示信息正确,SUCCESS'); //校验结束日期不小于开始日期

$tester->closeBrowser();
