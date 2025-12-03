#!/usr/bin/env php
<?php

/**

title=- 执行actionTest模块的processToStoryActionExtraTest方法,参数是1, '1' 属性extra @
timeout=0
cid=14968

- 执行actionTest模块的processToStoryActionExtraTest方法，参数是1, '1' 属性extra @#1 用户需求1
- 执行actionTest模块的processToStoryActionExtraTest方法，参数是2, '2' 属性extra @#2 软件需求2
- 执行actionTest模块的processToStoryActionExtraTest方法，参数是3, '2' 属性extra @#3 用户需求3
- 执行actionTest模块的processToStoryActionExtraTest方法，参数是4, '2' 属性extra @#4 软件需求4
- 执行actionTest模块的processToStoryActionExtraTest方法，参数是999, '2' 属性extra @#999

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$productTable = zenData('product');
$productTable->id->range('1-10');
$productTable->name->range('正常产品,影子产品{5},测试产品{4}');
$productTable->shadow->range('0,1,1,1,1,1,0,0,0,0');
$productTable->status->range('normal{10}');
$productTable->type->range('normal{10}');
$productTable->gen(10);

$storyTable = zenData('story');
$storyTable->id->range('1-10');
$storyTable->product->range('1,2,2,2,6,6,1,8,9,10');
$storyTable->type->range('story{10}');
$storyTable->status->range('active{10}');
$storyTable->stage->range('wait{5},planned{5}');
$storyTable->version->range('1{10}');
$storyTable->gen(10);

$projectStoryTable = zenData('projectstory');
$projectStoryTable->project->range('10,20,30,40,50');
$projectStoryTable->product->range('2,2,2,6,6');
$projectStoryTable->story->range('2,3,4,5,6');
$projectStoryTable->version->range('1{5}');
$projectStoryTable->gen(5);

su('admin');

$actionTest = new actionTaoTest();

r($actionTest->processToStoryActionExtraTest(1, '1')) && p('extra') && e('#1 用户需求1');
r($actionTest->processToStoryActionExtraTest(2, '2')) && p('extra') && e('#2 软件需求2');
r($actionTest->processToStoryActionExtraTest(3, '2')) && p('extra') && e('#3 用户需求3');
r($actionTest->processToStoryActionExtraTest(4, '2')) && p('extra') && e('#4 软件需求4');
r($actionTest->processToStoryActionExtraTest(999, '2')) && p('extra') && e('#999');