#!/usr/bin/env php
<?php

/**

title=检查产品路线图
timeout=0
cid=0

*/
chdir(__DIR__);
include '../lib/roadmap.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->status->range('normal');
$product->type->range('normal');
$product->gen(1);

$productplan = zenData('productplan');
$productplan->id->range('1-7');
$productplan->product->range(1);
$productplan->title->range('计划1,计划2,计划3,计划4,计划5,计划6,计划7');
$productplan->parent->range(0);
$productplan->begin->range('`2024-09-19`,`2024-09-20`,`2024-09-21`,`2024-09-22`,`2024-09-23`,`2024-09-24`,`2024-09-25`');
$productplan->end->range('`2024-10-19`,`2024-10-20`,`2024-10-21`,`2024-10-22`,`2024-10-23`,`2024-10-24`,`2024-10-25`');
$productplan->status->range('wait{3},doing{2},done{1},closed{1}');
$productplan->gen(7);

zendata('release')->loadYaml('release', false, 2)->gen(10);

$tester = new roadmapTester();
$tester->login();
$productID['productID'] = 1;
$num = 8;
r($tester->checkIterationCount($productID, $num)) && p('message,status') && e('迭代次数正确,SUCCESS');

$tester->closeBrowser();
