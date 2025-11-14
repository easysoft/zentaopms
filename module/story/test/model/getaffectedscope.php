#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getAffectedScope();
cid=18497

- 获取需求2团队成员的数量 @22|30|37
- 获取需求2影响任务的数量 @26|21
- 获取需求2影响任务的指派给属性assignedTo @A:admin
- 获取需求2关联bug数 @3
- 获取需求28关联bug数，包含孪生需求 @4
- 获取需求2关联用例数 @3
- 获取需求28关联用例数，包含孪生需求 @4
- 检查需求2孪生需求 @1
- 检查需求28孪生需求 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';
su('admin');

$story = zenData('story');
$story->product->range(1);
$story->plan->range('0,1,0{100}');
$story->duplicateStory->range('0,4,0{100}');
$story->linkStories->range('0,6,0{100}');
$story->linkRequirements->range('3,0{100}');
$story->toBug->range('0{9},1,0{100}');
$story->parent->range('0{17},`-1`,0,18,0{100}');
$story->twins->range('``{27},30,``,28');
$story->gen(30);

$storySpec = zenData('storyspec');
$storySpec->story->range('1-30{3}');
$storySpec->version->range('1-3');
$storySpec->gen(90);

$project = zenData('project');
$project->project->range('0{10},1-10,11-20{6}');
$project->parent->range('0{10},1-10,11-20{6}');
$project->type->range('program{10},project{10},sprint{60}');
$project->gen(80)->fixPath();

$projectStory = zenData('projectstory');
$projectStory->project->range('11-17{6},21-40{2}');
$projectStory->product->range('1');
$projectStory->story->range('2-30:2');
$projectStory->gen(80);

$task = zenData('task');
$task->story->range('2-30:2{2}');
$task->project->range('11-17{6}');
$task->execution->range('21-30{2}');
$task->storyVersion->range('3');
$task->assignedTo->range('admin');
$task->gen(60);

$team = zenData('team');
$team->account->range('admin,user1,user2,user3,user4');
$team->root->range('11-40{3}');
$team->type->range('execution');
$team->gen(90);

$bug = zenData('bug');
$bug->story->range('2-30:2');
$bug->gen(40);

$case = zenData('case');
$case->story->range('2-30:2');
$case->gen(40);

zenData('storystage')->gen(30);
zenData('productplan')->gen(1);
zenData('branch')->gen(5);

$story = new storyTest();
$affectedStory2  = $story->getAffectedScopeTest(2);
$affectedStory28 = $story->getAffectedScopeTest(28);

r(implode('|', array_keys($affectedStory2->teams))) && p() && e('22|30|37');  //获取需求2团队成员的数量
r(implode('|', array_keys($affectedStory2->tasks))) && p() && e('26|21');     //获取需求2影响任务的数量
r($affectedStory2->tasks[21][0]) && p('assignedTo') && e('A:admin');          //获取需求2影响任务的指派给

r(count($affectedStory2->bugs))            && p() && e('3');  //获取需求2关联bug数
r(count($affectedStory28->bugs))           && p() && e('4');  //获取需求28关联bug数，包含孪生需求
r(count($affectedStory2->cases))           && p() && e('3');  //获取需求2关联用例数
r(count($affectedStory28->cases))          && p() && e('4');  //获取需求28关联用例数，包含孪生需求
r((int)empty($affectedStory2->twins))      && p() && e('1');  //检查需求2孪生需求
r((int)isset($affectedStory28->twins[30])) && p() && e('1');  //检查需求28孪生需求
