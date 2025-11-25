#!/usr/bin/env php
<?php

/**

title=测试 storyModel->setStage();
timeout=0
cid=18584

- 不传入需求; @0
- 传入已经分解任务的需求，检查需求阶段。属性stage @testing
- 传入已关闭的需求，检查需求阶段。属性stage @closed
- 传入只关联的计划的需求，检查需求阶段。属性stage @planned
- 传入只关联的项目的需求，检查需求阶段。属性stage @projected
- 传入关联多分支，并分解成任务的需求，检查需求阶段。属性stage @testing
- 传入关联多分支，并分解成任务的需求，检查分支 0 的需求阶段。属性stage @testing
- 传入关联多分支，并分解成任务的需求，检查分支 1 的需求阶段。属性stage @tested
- 传入关联多分支，并分解成任务的需求，检查分支 2 的需求阶段。属性stage @released

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';
su('admin');

$product = zenData('product');
$product->type->range('normal,branch');
$product->gen(2);

$project = zenData('project');
$project->id->range('11-20');
$project->type->range('project,project,sprint,kanban');
$project->model->range('scrum,kanban,``{2}');
$project->gen(4);

$projectStory = zenData('projectstory');
$projectStory->story->range('1,2,2,3,5');
$projectStory->product->range('1,2,2,2');
$projectStory->project->range('11-14');
$projectStory->gen(5);

$projectProduct = zenData('projectproduct');
$projectProduct->product->range('1,2{3}');
$projectProduct->project->range('11-20');
$projectProduct->branch->range('0{2},1{2}');
$projectProduct->gen(4);

$story = zenData('story');
$story->product->range('1,2,2,1,1');
$story->plan->range('1,0,1,1,1');
$story->branch->range('0,0,1,0,0');
$story->assignedTo->range('admin,user1,user2,user3,user4');
$story->stage->range('developing,testing,closed,planned,delivering');
$story->gen(5);

$task = zenData('task');
$task->story->range('1{2},2{3}');
$task->execution->range('11{2},12{2},13{2}');
$task->type->range('devel{6},test{6}');
$task->deleted->range('0{24},1');
$task->gen(100);

$storyStage = zenData('storystage');
$storyStage->story->range('2');
$storyStage->branch->range('0,1');
$storyStage->gen(2);

$release = zenData('release');
$release->stories->range('2');
$release->branch->range('3');
$release->gen(3);

global $tester;
$storyModel = $tester->loadModel('story');
r($storyModel->setStage(0)) && p() && e('0'); //不传入需求;

$storyTest = new storyTest();
r($storyTest->setStageTest(1)) && p('stage') && e('testing');   //传入已经分解任务的需求，检查需求阶段。
r($storyTest->setStageTest(3)) && p('stage') && e('closed');    //传入已关闭的需求，检查需求阶段。
r($storyTest->setStageTest(4)) && p('stage') && e('planned');   //传入只关联的计划的需求，检查需求阶段。
r($storyTest->setStageTest(5)) && p('stage') && e('projected'); //传入只关联的项目的需求，检查需求阶段。

$story = $storyTest->setStageTest(2);
r($story) && p('stage') && e('testing');             //传入关联多分支，并分解成任务的需求，检查需求阶段。
r($story->stages[0]) && p('stage') && e('testing');  //传入关联多分支，并分解成任务的需求，检查分支 0 的需求阶段。
r($story->stages[1]) && p('stage') && e('tested');   //传入关联多分支，并分解成任务的需求，检查分支 1 的需求阶段。
r($story->stages[2]) && p('stage') && e('released'); //传入关联多分支，并分解成任务的需求，检查分支 2 的需求阶段。
