#!/usr/bin/env php
<?php

/**

title=测试 storyModel->updateParentStatus();
timeout=0
cid=18595

- 只有一个子需求，并且需求状态为closed，检查父需求的状态。 @closed
- 没有子需求的父需求，检查父需求的parent字段。 @0
- 已经关闭的父需求，有激活状态的子需求，检查父需求的状态。 @active
- 没有父任务的子需求，检查子需求的parent字段。 @0
- 变更中的需求。 @changing

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$story = zenData('story');
$story->product->range(1);
$story->parent->range('0,`-1`,2,`-1`,0{10},`-1`,100,14,15,15,15');
$story->type->range('story');
$story->gen(20);

global $tester;
$storyModel = $tester->loadModel('story');
$storyModel->updateParentStatus(3);
$storyModel->updateParentStatus(16);
$storyModel->updateParentStatus(17, 4);
$storyModel->updateParentStatus(18);
$storyModel->updateParentStatus(20);

$story2  = $storyModel->fetchByID(2);
$story4  = $storyModel->fetchByID(4);
$story15 = $storyModel->fetchByID(15);
$story16 = $storyModel->fetchByID(16);
$story20 = $storyModel->fetchByID(20);

r($story2->status)   && p() && e('closed');   // 只有一个子需求，并且需求状态为closed，检查父需求的状态。
r($story4->isParent) && p() && e('0');        // 没有子需求的父需求，检查父需求的parent字段。
r($story15->status)  && p() && e('active');   // 已经关闭的父需求，有激活状态的子需求，检查父需求的状态。
r($story16->parent)  && p() && e('0');        // 没有父任务的子需求，检查子需求的parent字段。
r($story20->status)  && p() && e('changing'); // 变更中的需求。
