#!/usr/bin/env php
<?php

/**

title=计划列表切换Tab
timeout=0
cid=0

*/
chdir(__DIR__);
include '../lib/browse.ui.class.php';

zendata('product')->loadYaml('product', false, 2)->gen(10);
$productplan = zenData('productplan');
$productplan->id->range('1-7');
$productplan->product->range(1);
$productplan->title->range('计划1,计划2,计划3,计划4,计划5,计划6,计划7');
$productplan->parent->range(0);
$productplan->begin->range('`2024-09-19`,`2024-09-20`,`2024-09-21`,`2024-09-22`,`2024-09-23`,`2024-09-24`,`2024-09-25`');
$productplan->end->range('`2024-10-19`,`2024-10-20`,`2024-10-21`,`2024-10-22`,`2024-10-23`,`2024-10-24`,`2024-10-25`');
$productplan->status->range('wait{3},doing{2},done{1},closed{1}');
$productplan->gen(7);

$tester = new browseTester();
$tester->login();
$planurl['productID'] = 1;

$tabName = 'all';
$tabNum  = '7';
r($tester->switchTab($planurl, $tabName, $tabNum)) && p('message,status') && e("切换至allTab成功,SUCCESS");//切换至全部Tab

$tabName = 'undone';
$tabNum  = '5';
r($tester->switchTab($planurl, $tabName, $tabNum)) && p('message,status') && e("切换至undoneTab成功,SUCCESS");//切换至未完成Tab

$tabName = 'waiting';
$tabNum  = '3';
r($tester->switchTab($planurl, $tabName, $tabNum)) && p('message,status') && e("切换至waitingTab成功,SUCCESS");//切换至未开始Tab

$tabName = 'doing';
$tabNum  = '2';
r($tester->switchTab($planurl, $tabName, $tabNum)) && p('message,status') && e("切换至doingTab成功,SUCCESS");//切换至进行中Tab

$tabName = 'done';
$tabNum  = '1';
r($tester->switchTab($planurl, $tabName, $tabNum)) && p('message,status') && e("切换至doneTab成功,SUCCESS");//切换至已完成Tab

$tabName = 'closed';
$tabNum  = '1';
r($tester->switchTab($planurl, $tabName, $tabNum)) && p('message,status') && e("切换至closedTab成功,SUCCESS");//切换至已关闭Tab

$tester->closeBrowser();
