#!/usr/bin/env php
<?php

/**

title=计划看板中排序测试
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

$orderBy = 'begin_desc';
r($tester->sortInKanban($planurl, $orderBy)) && p('message,status') && e('按计划开始时间倒序排序成功,SUCCESS');//按计划开始时间倒序进行排序

$orderBy = 'begin_asc';
r($tester->sortInKanban($planurl, $orderBy)) && p('message,status') && e('按计划开始时间正序排序成功,SUCCESS');//按计划开始时间正序进行排序
$tester->closeBrowser();
