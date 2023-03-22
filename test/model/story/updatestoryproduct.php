#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->updateStoryProduct();
cid=1
pid=1

判断需求变更所属产品之前的产品ID >> 1
判断需求变更所属产品之后的产品ID >> 2

*/

$story = new storyTest();
global $tester;

$oldStory = $tester->loadModel('story')->getByID(1);
$tester->story->updateStoryProduct(1, 2);
$newStory = $tester->story->getByID(1);

r($oldStory) && p('product') && e('1'); // 判断需求变更所属产品之前的产品ID
r($newStory) && p('product') && e('2'); // 判断需求变更所属产品之后的产品ID
