#!/usr/bin/env php
<?php

/**

title=测试 myZen::assignRelatedData();
timeout=0
cid=0

- 测试空反馈列表设置view属性属性hasBugs @1
- 测试单个tobug类型反馈设置bugs属性属性hasBugs @1
- 测试单个tostory类型反馈设置stories属性属性hasStories @1
- 测试单个totodo类型反馈设置todos属性属性hasTodos @1
- 测试单个totask类型反馈设置tasks属性属性hasTasks @1
- 测试多个相同类型反馈设置bugs属性属性hasBugs @1
- 测试混合多种类型反馈设置所有属性属性hasBugs @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('bug')->loadYaml('assignrelateddata/bug', false, 2)->gen(10);
zenData('story')->loadYaml('assignrelateddata/story', false, 2)->gen(10);
zenData('todo')->loadYaml('assignrelateddata/todo', false, 2)->gen(10);
zenData('task')->loadYaml('assignrelateddata/task', false, 2)->gen(10);

su('admin');

$myTest = new myZenTest();

$emptyFeedbacks = array();
$feedback1 = new stdClass();
$feedback1->solution = 'tobug';
$feedback1->result = 1;

$feedback2 = new stdClass();
$feedback2->solution = 'tostory';
$feedback2->result = 1;

$feedback3 = new stdClass();
$feedback3->solution = 'totodo';
$feedback3->result = 1;

$feedback4 = new stdClass();
$feedback4->solution = 'totask';
$feedback4->result = 1;

$feedback5 = new stdClass();
$feedback5->solution = 'tobug';
$feedback5->result = 2;

$feedback6 = new stdClass();
$feedback6->solution = 'tostory';
$feedback6->result = 2;

$multipleBugFeedbacks = array($feedback1, $feedback5);
$mixedFeedbacks = array($feedback1, $feedback2, $feedback3, $feedback4);

r($myTest->assignRelatedDataTest($emptyFeedbacks)) && p('hasBugs') && e('1'); // 测试空反馈列表设置view属性
r($myTest->assignRelatedDataTest(array($feedback1))) && p('hasBugs') && e('1'); // 测试单个tobug类型反馈设置bugs属性
r($myTest->assignRelatedDataTest(array($feedback2))) && p('hasStories') && e('1'); // 测试单个tostory类型反馈设置stories属性
r($myTest->assignRelatedDataTest(array($feedback3))) && p('hasTodos') && e('1'); // 测试单个totodo类型反馈设置todos属性
r($myTest->assignRelatedDataTest(array($feedback4))) && p('hasTasks') && e('1'); // 测试单个totask类型反馈设置tasks属性
r($myTest->assignRelatedDataTest($multipleBugFeedbacks)) && p('hasBugs') && e('1'); // 测试多个相同类型反馈设置bugs属性
r($myTest->assignRelatedDataTest($mixedFeedbacks)) && p('hasBugs') && e('1'); // 测试混合多种类型反馈设置所有属性