#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$product = zenData('product')->gen(1);

$project = zenData('project');
$project->id->range('11-20');
$project->type->range('project,project,sprint,kanban');
$project->model->range('scrum,kanban');
$project->gen(10);

$projectStory = zenData('projectstory');
$projectStory->story->range('6-20');
$projectStory->product->range('1');
$projectStory->project->range('11-20');
$projectStory->gen(15);

$projectProduct = zenData('projectproduct');
$projectProduct->product->range('1');
$projectProduct->project->range('11-20');
$projectProduct->gen(10);

$story = zenData('story');
$story->product->range('1');
$story->plan->range('`1,2`,2,3,4,5');
$story->type->range('story');
$story->parent->range('0{5},1-5{3}');
$story->root->range('1-5,1-5{3}');
$story->isParent->range('1{5},0{15}');
$story->stage->range('wait,planned,developing,tested,released');
$story->gen(20)->fixPath();

$task = zenData('task');
$task->story->range('6-20');
$task->execution->range('11-20');
$task->type->range('devel,test,design');
$task->gen(100);

$release = zenData('release');
$release->stories->range('6-10');
$release->gen(5);

/**

title=测试 storyModel->setStage();
timeout=0
cid=18613

- 查看父需求初始阶段属性stage @wait
- 查看父需求初始阶段属性stage @planned
- 查看父需求初始阶段属性stage @developing
- 查看父需求初始阶段属性stage @tested
- 查看父需求初始阶段属性stage @released
- 通过子需求计算父需求的阶段属性stage @developing
- 通过子需求计算父需求的阶段属性stage @delivering
- 通过子需求计算父需求的阶段属性stage @developing
- 通过子需求计算父需求的阶段属性stage @delivering
- 通过子需求计算父需求的阶段属性stage @delivering

*/

global $tester;
$storyModel = $tester->loadModel('story');
$p1 = $tester->story->fetchById(1);
$p2 = $tester->story->fetchById(2);
$p3 = $tester->story->fetchById(3);
$p4 = $tester->story->fetchById(4);
$p5 = $tester->story->fetchById(5);

r($p1) && p('stage') && e('wait');       // 查看父需求初始阶段
r($p2) && p('stage') && e('planned');    // 查看父需求初始阶段
r($p3) && p('stage') && e('developing'); // 查看父需求初始阶段
r($p4) && p('stage') && e('tested');     // 查看父需求初始阶段
r($p5) && p('stage') && e('released');   // 查看父需求初始阶段

$c1 = $tester->story->fetchById(6);
$c2 = $tester->story->fetchById(9);
$c3 = $tester->story->fetchById(12);
$c4 = $tester->story->fetchById(15);
$c5 = $tester->story->fetchById(18);

$tester->story->computeParentStage($c1);
$tester->story->computeParentStage($c2);
$tester->story->computeParentStage($c3);
$tester->story->computeParentStage($c4);
$tester->story->computeParentStage($c5);

$p1 = $tester->story->fetchById(1);
$p2 = $tester->story->fetchById(2);
$p3 = $tester->story->fetchById(3);
$p4 = $tester->story->fetchById(4);
$p5 = $tester->story->fetchById(5);

r($p1) && p('stage') && e('developing'); // 通过子需求计算父需求的阶段
r($p2) && p('stage') && e('delivering'); // 通过子需求计算父需求的阶段
r($p3) && p('stage') && e('developing'); // 通过子需求计算父需求的阶段
r($p4) && p('stage') && e('delivering'); // 通过子需求计算父需求的阶段
r($p5) && p('stage') && e('delivering'); // 通过子需求计算父需求的阶段