#!/usr/bin/env php
<?php

/**

title=测试 storyModel->updateStoryProduct();
timeout=0
cid=18598

- 没有跟随父需求变化的情况属性product @1
- 没有跟随父需求变化的情况属性product @1
- 判断需求变更所属产品之前的产品ID属性product @1
- 判断需求变更所属产品之后的产品ID属性product @2
- 不存在的需求属性product @0
- 不存在的需求属性product @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$story = zenData('story');
$story->parent->range('0,1');
$story->gen(2);
zenData('storyspec')->gen(5);

$story = new storyModelTest();
global $tester;

$parent = $tester->loadModel('story')->getByID(1);
$parent->product = 2;

$oldStory = $tester->story->getByID(1);
$tester->story->updateStoryProduct(1, $parent, 2);
$newStory = $tester->story->getByID(1);

r($oldStory) && p('product') && e('1'); // 没有跟随父需求变化的情况
r($newStory) && p('product') && e('1'); // 没有跟随父需求变化的情况

$oldStory = $tester->story->getByID(2);
$tester->story->updateStoryProduct(2, $parent, 1);
$newStory = $tester->story->getByID(2);

r($oldStory) && p('product') && e('1'); // 判断需求变更所属产品之前的产品ID
r($newStory) && p('product') && e('2'); // 判断需求变更所属产品之后的产品ID

$oldStory = $tester->story->getByID(3);
$tester->story->updateStoryProduct(3, $parent, 1);
$newStory = $tester->story->getByID(3);

r($oldStory) && p('product') && e('0'); // 不存在的需求
r($newStory) && p('product') && e('0'); // 不存在的需求