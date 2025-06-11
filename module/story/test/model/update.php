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
 - 属性estimate @3
 - 属性sourceNote @测试来源备注2
 - 属性product @2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';
su('admin');

zenData('story')->gen(10);
zenData('storyspec')->gen(30);
zenData('product')->gen(30);

$story  = new storyTest();
$story1 = new stdClass();
$story1->parent        = 1;
$story1->pri           = 4;
$story1->plan          = '0';
$story1->estimate      = 1;
$story1->sourceNote    = '测试来源备注1';
$story1->title 	       = '测试需求1';
$story1->product       = 2;
$story1->linkStories   = '';
$story1->spec          = '';
$story1->stage 	 	   = 'wait';
$story1->notifyEmail   = 'qaz@123.com';
$story1->verify        = '';
$story1->branch 	   = 0;
$story1->grade         = 1;
$story1->parent        = 0;
$story1->retractedDate = NULL;
$story1->deleteFiles   = array();
$story1->docs	       = array();
$story1->oldDocs 	   = array();
$story1->docVersions   = array();

$story2 = new stdClass();
$story2->pri           = 4;
$story2->plan          = '0';
$story2->estimate      = 3;
$story2->sourceNote    = '测试来源备注2';
$story2->title 	       = '测试需求2';
$story2->product       = 2;
$story2->notifyEmail   = 'qaz@123.com';
$story2->linkStories   = '';
$story2->spec          = '';
$story2->grade         = 1;
$story2->parent        = 0;
$story2->stage 	 	   = 'wait';
$story2->verify        = '';
$story2->branch 	   = 0;
$story2->retractedDate = NULL;
$story2->deleteFiles   = array();
$story2->docs	       = array();
$story2->oldDocs 	   = array();
$story2->docVersions   = array();

$result1 = $story->updateTest(2, $story1);
$result2 = $story->updateTest(4, $story2);

r($result1) && p('pri,estimate,sourceNote,product') && e('4,1,测试来源备注1,2'); // 编辑用户需求，判断返回的信息，stage为空
r($result2) && p('pri,estimate,sourceNote,product') && e('4,3,测试来源备注2,2'); // 编辑软件需求，判断返回的信息，stage为wait，parent为2