#!/usr/bin/env php
<?php

/**

title=测试 storyModel->doUpdateSpec();
timeout=0
cid=18616

- 测试父需求不变的情况
 - 第2条的root属性 @1
 - 第3条的root属性 @1
- 测试将需求3的父需求修改为需求2
 - 第2条的isParent属性 @1
 - 第3条的parentVersion属性 @2
- 测试将用户需求6的父需求修改为用户需求5
 - 第5条的isParent属性 @1
 - 第6条的parentVersion属性 @1
- 测试将业务需求9的父需求修改为业务需求8
 - 第8条的isParent属性 @1
 - 第9条的parentVersion属性 @2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';
su('admin');

$story = zenData('story');
$story->id->range('1-100');
$story->title->range('teststory');
$story->product->range('1');
$story->parent->range('0,1,1,0,4,4,0,7,7');
$story->path->range('`,1,`,`,1,2,`,`,1,3,`,`,4,`,`,4,5,`,`,4,6,`,`,7,`,`,7,8,`,`,7,9,`');
$story->type->range('story{3},requirement{3},epic{3}');
$story->version->range('1,2');
$story->gen(9);

$noParent = new stdClass();
$noParent->parent = 0;

$oldStory3 = new stdClass();
$oldStory3->id     = 3;
$oldStory3->parent = 1;
$oldStory3->type   = 'story';

$newStory3 = new stdClass();
$newStory3->parent = 2;

$oldStory6 = new stdClass();
$oldStory6->id     = 6;
$oldStory6->parent = 4;
$oldStory6->type   = 'requirement';

$newStory6 = new stdClass();
$newStory6->parent = 5;

$oldStory9 = new stdClass();
$oldStory9->id     = 9;
$oldStory9->parent = 7;
$oldStory9->type   = 'epic';

$newStory9 = new stdClass();
$newStory9->parent = 8;

$story = new storyTest();
r($story->doChangeParentTest(1, $noParent, $noParent))   && p('2:root;3:root')              && e('1;1'); //测试父需求不变的情况
r($story->doChangeParentTest(3, $newStory3, $oldStory3)) && p('2:isParent;3:parentVersion') && e('1;2'); //测试将需求3的父需求修改为需求2
r($story->doChangeParentTest(6, $newStory6, $oldStory6)) && p('5:isParent;6:parentVersion') && e('1;1'); //测试将用户需求6的父需求修改为用户需求5
r($story->doChangeParentTest(9, $newStory9, $oldStory9)) && p('8:isParent;9:parentVersion') && e('1;2'); //测试将业务需求9的父需求修改为业务需求8
