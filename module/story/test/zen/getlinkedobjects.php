#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getLinkedObjects();
timeout=0
cid=0

- 执行storyTest模块的getLinkedObjectsTest方法，参数是$story1 第fromBug条的title属性 @Bug1
- 执行storyTest模块的getLinkedObjectsTest方法，参数是$story2 属性fromBug @~~
- 执行storyTest模块的getLinkedObjectsTest方法，参数是$story3 属性fromBug @~~
- 执行storyTest模块的getLinkedObjectsTest方法，参数是$story4 属性fromBug @~~
- 执行storyTest模块的getLinkedObjectsTest方法，参数是$story5 属性fromBug @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

$story = zenData('story');
$story->id->range('1-10');
$story->product->range('1-5');
$story->module->range('0,1-5');
$story->title->range('Story1,Story2,Story3,Story4,Story5,Story6,Story7,Story8,Story9,Story10');
$story->type->range('story,requirement,epic');
$story->fromBug->range('0,1,2');
$story->twins->range('``,`2,3`,`4,5`');
$story->gen(10);

$bug = zenData('bug');
$bug->id->range('1-10');
$bug->product->range('1-5');
$bug->story->range('0,1,1,2,3,0,0,0,0,0');
$bug->title->range('Bug1,Bug2,Bug3,Bug4,Bug5,Bug6,Bug7,Bug8,Bug9,Bug10');
$bug->status->range('active,resolved,closed');
$bug->pri->range('1-4');
$bug->severity->range('1-4');
$bug->deleted->range('0{9},1');
$bug->gen(10);

$case = zenData('case');
$case->id->range('1-10');
$case->product->range('1-5');
$case->story->range('0,1,1,1,2,0,0,0,0,0');
$case->title->range('Case1,Case2,Case3,Case4,Case5,Case6,Case7,Case8,Case9,Case10');
$case->status->range('1,2,3');
$case->pri->range('1-4');
$case->deleted->range('0{9},1');
$case->gen(10);

$module = zenData('module');
$module->id->range('1-10');
$module->root->range('1-5');
$module->parent->range('0,0,1,1,2,2,3,3,4,4');
$module->name->range('Module1,Module2,Module3,Module4,Module5,Module6,Module7,Module8,Module9,Module10');
$module->type->range('story');
$module->gen(10);

zenData('user')->gen(5);

su('admin');

$storyTest = new storyZenTest();

// 准备测试需求对象
$story1 = new stdclass();
$story1->id = 1;
$story1->product = 1;
$story1->module = 1;
$story1->title = 'Story1';
$story1->type = 'story';
$story1->version = 1;
$story1->fromBug = 1;
$story1->twins = '';
$story1->linkStoryTitles = array();

$story2 = new stdclass();
$story2->id = 2;
$story2->product = 2;
$story2->module = 2;
$story2->title = 'Story2';
$story2->type = 'story';
$story2->version = 1;
$story2->fromBug = 0;
$story2->twins = '3';
$story2->linkStoryTitles = array(3 => 'Story3');

$story3 = new stdclass();
$story3->id = 3;
$story3->product = 3;
$story3->module = 0;
$story3->title = 'Story3';
$story3->type = 'requirement';
$story3->version = 1;
$story3->fromBug = 0;
$story3->twins = '';
$story3->linkStoryTitles = array();

$story4 = new stdclass();
$story4->id = 4;
$story4->product = 4;
$story4->module = 0;
$story4->title = 'Story4';
$story4->type = 'story';
$story4->version = 1;
$story4->fromBug = 0;
$story4->twins = '';
$story4->linkStoryTitles = array();

$story5 = new stdclass();
$story5->id = 2;
$story5->product = 2;
$story5->module = 3;
$story5->title = 'Story5';
$story5->type = 'epic';
$story5->version = 1;
$story5->fromBug = 0;
$story5->twins = '3';
$story5->linkStoryTitles = array();

r($storyTest->getLinkedObjectsTest($story1)) && p('fromBug:title') && e('Bug1');
r($storyTest->getLinkedObjectsTest($story2)) && p('fromBug') && e('~~');
r($storyTest->getLinkedObjectsTest($story3)) && p('fromBug') && e('~~');
r($storyTest->getLinkedObjectsTest($story4)) && p('fromBug') && e('~~');
r($storyTest->getLinkedObjectsTest($story5)) && p('fromBug') && e('~~');