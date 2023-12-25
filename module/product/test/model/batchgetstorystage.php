#!/usr/bin/env php
<?php

/**

title=测试批量获取需求阶段 productModel->batchGetStoryStage();
cid=0

- 获取需求ID1,2,3的需求阶段
 - 属性1 @wait
 - 属性2 @planned
 - 属性3 @projected
- 获取需求ID4,5,6的需求阶段
 - 属性4 @developing
 - 属性5 @developed
 - 属性6 @testing
- 获取需求ID7,8,9的需求阶段
 - 属性7 @tested
 - 属性8 @verified
 - 属性9 @released

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('storystage')->gen(50);

$story1 = new stdclass();
$story1->id = 1;

$story2 = new stdclass();
$story2->id = 2;

$story3 = new stdclass();
$story3->id = 3;

$story4 = new stdclass();
$story4->id = 4;

$story5 = new stdclass();
$story5->id = 5;

$story6 = new stdclass();
$story6->id = 6;

$story7 = new stdclass();
$story7->id = 7;

$story8 = new stdclass();
$story8->id = 8;

$story9 = new stdclass();
$story9->id = 9;

$stories1 = array($story1, $story2, $story3);
$stories2 = array($story4, $story5, $story6);
$stories3 = array($story7, $story8, $story9);

$product = new productTest('admin');

r($product->batchGetStoryStageTest($stories1)) && p('1,2,3') && e('wait,planned,projected');       // 获取需求ID1,2,3的需求阶段
r($product->batchGetStoryStageTest($stories2)) && p('4,5,6') && e('developing,developed,testing'); // 获取需求ID4,5,6的需求阶段
r($product->batchGetStoryStageTest($stories3)) && p('7,8,9') && e('tested,verified,released');     // 获取需求ID7,8,9的需求阶段
