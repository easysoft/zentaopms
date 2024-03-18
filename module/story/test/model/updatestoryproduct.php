#!/usr/bin/env php
<?php

/**

title=测试 storyModel->updateStoryProduct();
cid=0

- 判断需求变更所属产品之前的产品ID属性product @1
- 判断需求变更所属产品之后的产品ID属性product @2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

zdTable('story')->gen(1);
zdTable('storyspec')->gen(5);

$story = new storyTest();
global $tester;

$oldStory = $tester->loadModel('story')->getByID(1);
$tester->story->updateStoryProduct(1, 2);
$newStory = $tester->story->getByID(1);

r($oldStory) && p('product') && e('1'); // 判断需求变更所属产品之前的产品ID
r($newStory) && p('product') && e('2'); // 判断需求变更所属产品之后的产品ID
