#!/usr/bin/env php
<?php

/**
title=创建计划
timeout=0
cid=0

*/
chdir(__DIR__);
include '../lib/createplan.ui.class.php';
$product = zenData('product');
$product->id->range('1');
$product->name->range('产品01');
$product->status->range('normal');
$product->type->range('normal');
$product->gen(1);

zendata('productplan')->loadYaml('productplan', false, 2)->gen(10);

$tester = new createPlanTester();
$tester->login();

$planurl['productID'] = 1;
$planurl['branch']    = 0;

$productplan        = new stdClass();
$waitplan           = new stdClass();
$productplan->title = '';
r($tester->createDefault($productplan, $planurl)) && p('message,status') && e('创建计划表单页提示信息正确,SUCCESS'); // 计划名称必填校验

$productplan->title = '自动化计划';
$productplan->begin = '2024-06-24';
$productplan->end   = '2024-12-30';
r($tester->createDefault($productplan, $planurl)) && p('message,status') && e('创建计划成功,SUCCESS'); // 创建计划

$productplan->begin = '2024-06-24';
$productplan->end   = '2024-06-20';
r($tester->createDefault($productplan, $planurl)) && p('message,status') && e('日期校验正确,SUCCESS'); // 校验结束日期不小于开始日期

$productplan->parent = '计划1';
$productplan->title  = '自动化子计划';
$productplan->begin  = '2021-05-01';
$productplan->end    = '2021-06-01';
r($tester->createDefault($productplan, $planurl)) && p('message,status') && e('创建子计划成功,SUCCESS'); // 创建子计划

$waitplan->title  = '一个待定的计划';
$waitplan->begin  = '2024-06-24';
$waitplan->future = 'future';
r($tester->createDefault($waitplan, $planurl)) && p('message,status') && e('创建待定计划成功,SUCCESS'); // 创建待定的计划

$tester->closeBrowser();
