#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

$story = zdTable('story');
$story->product->range(1);
$story->parent->range('0,`-1`,2,`-1`,0{10},`-1`,100,14,15,15,15');
$story->type->range('story');
$story->gen(20);

/**

title=测试 storyModel->updateParentStatus();
cid=1
pid=1

判断需求变更所属产品之前的产品ID >> 1
判断需求变更所属产品之后的产品ID >> 2

*/

global $tester;
$storyModel = $tester->loadModel('story');
$storyModel->updateParentStatus(3);
$storyModel->updateParentStatus(16);
$storyModel->updateParentStatus(17, 4);
$storyModel->updateParentStatus(18);

$story2  = $storyModel->fetchByID(2);
$story4  = $storyModel->fetchByID(4);
$story15 = $storyModel->fetchByID(15);
$story16 = $storyModel->fetchByID(16);

r($story2->status) && p() && e('closed');  // 只有一个子需求，并且需求状态为closed，检查父需求的状态。
r($story4->parent) && p() && e('0');       // 没有子需求的父需求，检查父需求的parent字段。
r($story15->status) && p() && e('active'); // 已经关闭的父需求，有激活状态的子需求，检查父需求的状态。
r($story16->parent) && p() && e('0');      // 没有父任务的子需求，检查子需求的parent字段。
