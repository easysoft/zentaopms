#!/usr/bin/env php
<?php

/**

title=测试 storyModel->update();
timeout=0
cid=0

- 编辑用户需求，判断返回的信息，stage为空
 - 属性pri @4
 - 属性estimate @1
 - 属性sourceNote @测试来源备注1
 - 属性product @2
- 编辑软件需求，判断返回的信息，stage为wait，parent为2
 - 属性pri @4
 - 属性estimate @1
 - 属性sourceNote @测试来源备注1
 - 属性product @2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';
su('admin');

zenData('story')->gen(10);
zenData('storyspec')->gen(30);
zenData('product')->gen(30);

$story  = new storyTest();
$story1 = $tester->loadModel('story')->fetchByID(2);
$story1->parent        = 1;
$story1->pri           = 4;
$story1->plan          = '0';
$story1->estimate      = 1;
$story1->sourceNote    = '测试来源备注1';
$story1->product       = 2;
$story1->linkStories   = '';
$story1->spec          = '';
$story1->verify        = '';
$story1->retractedDate = NULL;
$story1->deleteFiles   = array();
unset($story1->approvedDate);
unset($story1->lastEditedDate);
unset($story1->changedDate);
unset($story1->reviewedDate);
unset($story1->releasedDate);
unset($story1->closedDate);
unset($story1->activatedDate);
unset($story1->files);

$result1 = $story->updateTest(2, clone($story1));
$result2 = $story->updateTest(4, clone($story1));

r($result1) && p('pri,estimate,sourceNote,product') && e('4,1,测试来源备注1,2'); // 编辑用户需求，判断返回的信息，stage为空
r($result2) && p('pri,estimate,sourceNote,product') && e('4,1,测试来源备注1,2'); // 编辑软件需求，判断返回的信息，stage为wait，parent为2