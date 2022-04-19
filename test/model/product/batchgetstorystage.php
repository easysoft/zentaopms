#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

/**

title=productModel->batchGetStoryStage();
cid=1
pid=1

测试获取需求1 2 3的阶段 >> wait;planned;projected
测试获取需求4 5 6的阶段 >> developing;developed;testing
测试获取需求7 8 9的阶段 >> tested;verified;released

*/

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

r($product->batchGetStoryStageTest($stories1))  && p('1;2;3') && e('wait;planned;projected');       // 测试获取需求1 2 3的阶段
r($product->batchGetStoryStageTest($stories2))  && p('4;5;6') && e('developing;developed;testing'); // 测试获取需求4 5 6的阶段
r($product->batchGetStoryStageTest($stories3))  && p('7;8;9') && e('tested;verified;released');     // 测试获取需求7 8 9的阶段