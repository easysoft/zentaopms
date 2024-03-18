#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getAffectedProjects();
cid=0

- 获取需求2团队成员的数量 @22|30|37
- 获取需求2影响任务的数量 @26|21
- 获取需求2影响任务的指派给属性assignedTo @管理员

*/
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/story.class.php';

$story = zdTable('story');
$story->product->range(1);
$story->plan->range('0,1,0{100}');
$story->duplicateStory->range('0,4,0{100}');
$story->linkStories->range('0,6,0{100}');
$story->linkRequirements->range('3,0{100}');
$story->childStories->range('0,8,0{100}');
$story->toBug->range('0{9},1,0{100}');
$story->parent->range('0{17},`-1`,0,18,0{100}');
$story->twins->range('``{27},30,``,28');
$story->gen(30);

$storySpec = zdTable('storyspec');
$storySpec->story->range('1-30{3}');
$storySpec->version->range('1-3');
$storySpec->gen(90);

$project = zdTable('project');
$project->project->range('0{10},1-10,11-20{6}');
$project->parent->range('0{10},1-10,11-20{6}');
$project->type->range('program{10},project{10},sprint{60}');
$project->gen(80)->fixPath();

$projectStory = zdTable('projectstory');
$projectStory->project->range('11-17{6},21-40{2}');
$projectStory->product->range('1');
$projectStory->story->range('2-30:2');
$projectStory->gen(80);

$task = zdTable('task');
$task->story->range('2-30:2{2}');
$task->project->range('11-17{6}');
$task->execution->range('21-30{2}');
$task->storyVersion->range('3');
$task->assignedTo->range('admin');
$task->gen(60);

$team = zdTable('team');
$team->account->range('admin,user1,user2,user3,user4');
$team->root->range('11-40{3}');
$team->type->range('execution');
$team->gen(90);

zdTable('storystage')->gen(30);
zdTable('bug')->gen(1);
zdTable('productplan')->gen(1);

$story = new storyTest();
$affectedStory = $story->getAffectedProjectsTest(2);

r(implode('|', array_keys($affectedStory->teams))) && p() && e('22|30|37');  //获取需求2团队成员的数量
r(implode('|', array_keys($affectedStory->tasks))) && p() && e('26|21');     //获取需求2影响任务的数量
r($affectedStory->tasks[21][0])  && p('assignedTo') && e('管理员');          //获取需求2影响任务的指派给
