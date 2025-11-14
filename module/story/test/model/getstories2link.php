#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getStories2Link();
timeout=0
cid=18556

- 获取需求1可关联的需求数量 @9
- 获取需求2可关联的需求数量 @9
- 获取需求12可关联的需求id、product
 - 第12条的type属性 @story
 - 第12条的product属性 @1
- 获取需求3可关联的需求id、product
 - 第14条的type属性 @story
 - 第14条的product属性 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('userview')->gen(0);
zenData('storyspec')->gen(60);
zenData('product')->gen(20);
$story = zenData('story');
$story->product->range(1);
$story->parent->range('0{18},`-1`,19');
$story->type->range('requirement{10},story{10}');
$story->gen(20);

$relation = zenData('relation');
$relation->product->range(1);
$relation->AID->range('1,11,1,2,12,2,3,13,3,4,14,4,5,15,5,6,16,6,7,17,7,8,18,8');
$relation->BID->range('11,1,1,12,2,2,13,3,3,14,4,4,15,5,5,16,6,6,17,7,7,18,8,8');
$relation->AType->range('requirement,story,design');
$relation->BType->range('story,requirement,commit');
$relation->relation->range('linkedto,linkedfrom,completedin');
$relation->gen(24);

$_SESSION = array();
global $tester;

su('admin');
$stories1 = $tester->loadModel('story')->getStories2Link(1, 'bySearch', 0);
$stories2 = $tester->loadModel('story')->getStories2Link(2);

r(count($stories1)) && p()                  && e('9');       // 获取需求1可关联的需求数量
r(count($stories2)) && p()                  && e('9');       // 获取需求2可关联的需求数量
r($stories1)        && p('12:type,product') && e('story,1'); // 获取需求12可关联的需求id、product
r($stories2)        && p('14:type,product') && e('story,1'); // 获取需求3可关联的需求id、product
