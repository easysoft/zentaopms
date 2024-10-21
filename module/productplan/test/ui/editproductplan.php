#!/usr/bin/env php
<?php
/**
title=编辑计划
timeout=0
cid=0

*/
chdir(__DIR__);
include '../lib/editplan.ui.class.php';

zendata('product')->loadYaml('product', false, 2)->gen(10);
zendata('productplan')->loadYaml('productplan', false, 2)->gen(10);
$tester = new editPlanTester();
$tester->login();

$planurl['planID'] = 6;

$productplan        = new stdClass();
$waitplan           = new stdClass();
$productplan->title = '';
r($tester->editDefault($productplan, $planurl)) && p('message,status') && e('编辑计划提示信息正确,SUCCESS'); // 计划名称必填校验

$productplan->title = '计划_编辑后';
$productplan->begin = '2024-06-24';
$productplan->end   = '2024-12-30';
r($tester->editDefault($productplan, $planurl)) && p('message,status') && e('编辑计划成功,SUCCESS'); //编辑计划


$productplan->begin = '2024-06-24';
$productplan->end   = '2024-06-20';
r($tester->editDefault($productplan, $planurl)) && p('message,status') && e('日期校验正确,SUCCESS'); // 校验结束日期不小于开始日期


$waitplan->title  = '一个待定的计划';
$waitplan->begin  = '2024-06-24';
$waitplan->future = 'future';
r($tester->editDefault($waitplan, $planurl)) && p('message,status') && e('编辑为待定计划成功,SUCCESS'); // 将计划结束日期改为待定

$tester->closeBrowser();
