#!/usr/bin/env php
<?php

/**

title=测试 epicModel::getToAndCcList();
timeout=0
cid=0

- 执行epicTest模块的getToAndCcListTest方法  @user1
- 执行epicTest模块的getToAndCcListTest方法  @user4
- 执行epicTest模块的getToAndCcListTest方法 
 - 属性1 @user4
- 执行epicTest模块的getToAndCcListTest方法  @user3
- 执行epicTest模块的getToAndCcListTest方法 
 - 属性1 @user6
- 执行epicTest模块的getToAndCcListTest方法  @
- 执行epicTest模块的getToAndCcListTest方法 属性1 @user4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/epic.unittest.class.php';

$table = zenData('story');
$table->id->range('1-10');
$table->title->range('测试需求{1-10}');
$table->assignedTo->range('user1{3},user2{3},[]{2},user3{2}');
$table->mailto->range('[]{3},user4,user5{2},[]{4}');
$table->status->range('active{5},closed{3},draft{2}');
$table->type->range('story{8},epic{2}');
$table->openedBy->range('admin{5},user1{3},user2{2}');
$table->version->range('1{5},2{3},3{2}');
$table->gen(10);

$storyreview = zenData('storyreview');
$storyreview->story->range('1-5');
$storyreview->version->range('1{3},2{2}');
$storyreview->reviewer->range('reviewer1,reviewer2,reviewer3,reviewer1,reviewer2');
$storyreview->result->range('pass{2},reject{1},pass{1},pending{1}');
$storyreview->gen(5);

$team = zenData('team');
$team->root->range('1-3');
$team->type->range('project{2},execution{1}');
$team->account->range('teammember1,teammember2,teammember3');
$team->gen(3);

$task = zenData('task');
$task->id->range('1-5');
$task->story->range('1{2},2{2},3{1}');
$task->execution->range('1{3},2{2}');
$task->status->range('doing{3},done{2}');
$task->gen(5);

$projectstory = zenData('projectstory');
$projectstory->story->range('1-3');
$projectstory->project->range('1{2},2{1}');
$projectstory->gen(3);

$project = zenData('project');
$project->id->range('1-2');
$project->status->range('doing{2}');
$project->deleted->range('0{2}');
$project->gen(2);

su('admin');

$epicTest = new epicTest();

r($epicTest->getToAndCcListTest((object)array('id' => 1, 'assignedTo' => 'user1', 'mailto' => '', 'status' => 'active', 'type' => 'story', 'version' => 1, 'openedBy' => 'admin'), 'opened')) && p('0') && e('user1');
r($epicTest->getToAndCcListTest((object)array('id' => 2, 'assignedTo' => '', 'mailto' => 'user4,user5', 'status' => 'active', 'type' => 'story', 'version' => 1, 'openedBy' => 'admin'), 'opened')) && p('0') && e('user4');
r($epicTest->getToAndCcListTest((object)array('id' => 3, 'assignedTo' => 'user2', 'mailto' => 'user4,user5', 'status' => 'active', 'type' => 'story', 'version' => 1, 'openedBy' => 'admin'), 'opened')) && p('1') && e('user4,user5,');
r($epicTest->getToAndCcListTest((object)array('id' => 4, 'assignedTo' => 'user3', 'mailto' => '', 'status' => 'active', 'type' => 'story', 'version' => 2, 'openedBy' => 'user1'), 'changed')) && p('0') && e('user3');
r($epicTest->getToAndCcListTest((object)array('id' => 5, 'assignedTo' => 'user1', 'mailto' => 'user6', 'status' => 'closed', 'type' => 'story', 'version' => 1, 'openedBy' => 'admin'), 'opened')) && p('1') && e('user6,,admin');
r($epicTest->getToAndCcListTest((object)array('id' => 6, 'assignedTo' => '', 'mailto' => '', 'status' => 'active', 'type' => 'story', 'version' => 1, 'openedBy' => 'admin'), 'opened')) && p('0') && e('');
r($epicTest->getToAndCcListTest((object)array('id' => 7, 'assignedTo' => 'user1', 'mailto' => 'user4', 'status' => 'active', 'type' => 'epic', 'version' => 1, 'openedBy' => 'admin'), 'reviewed')) && p('1') && e('user4');