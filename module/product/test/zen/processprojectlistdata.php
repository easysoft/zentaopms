#!/usr/bin/env php
<?php

/**

title=测试 productZen::processProjectListData();
timeout=0
cid=17600

- 测试空项目列表 @0
- 测试单个项目
 - 第0条的id属性 @1
 - 第0条的name属性 @项目1
 - 第0条的storyCount属性 @5
- 测试多个项目 @3
- 测试需求点数
 - 第0条的id属性 @2
 - 第0条的storyCount属性 @5
- 测试返回字段
 - 第0条的id属性 @3
 - 第0条的from属性 @project
 - 第0条的storyCount属性 @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('user')->gen(10);

$project = zenData('project');
$project->id->range('1-5');
$project->project->range('0');
$project->parent->range('0');
$project->name->range('项目1,项目2,项目3,项目4,项目5');
$project->code->range('project1,project2,project3,project4,project5');
$project->status->range('doing{3},wait{1},suspended{1}');
$project->type->range('project');
$project->model->range('scrum,waterfall,kanban,scrum,scrum');
$project->auth->range('extend');
$project->grade->range('1');
$project->PM->range('admin,user1,user2,admin,user3');
$project->budget->range('100000,200000,300000,150000,250000');
$project->budgetUnit->range('CNY');
$project->begin->range('`2024-01-01`');
$project->end->range('`2024-12-31`');
$project->openedBy->range('admin');
$project->openedDate->range('`2024-01-01`');
$project->gen(5);

$story = zenData('story');
$story->id->range('1-15');
$story->product->range('1-3');
$story->title->range('需求1,需求2,需求3,需求4,需求5,需求6,需求7,需求8,需求9,需求10,需求11,需求12,需求13,需求14,需求15');
$story->type->range('story');
$story->status->range('active,draft,closed,active,active,draft,active,closed,active,active,draft,closed,active,active,draft');
$story->estimate->range('1-5:R');
$story->version->range('1');
$story->openedBy->range('admin');
$story->gen(15);

$projectStory = zenData('projectstory');
$projectStory->project->range('1{5},2{5},3{5}');
$projectStory->product->range('1{5},2{5},3{5}');
$projectStory->story->range('1-15');
$projectStory->version->range('1');
$projectStory->gen(15);

$execution = zenData('project');
$execution->id->range('6-15');
$execution->project->range('1{4},2{3},3{3}');
$execution->parent->range('1{4},2{3},3{3}');
$execution->name->range('迭代1,迭代2,迭代3,迭代4,迭代5,迭代6,迭代7,迭代8,迭代9,迭代10');
$execution->type->range('sprint{7},stage{3}');
$execution->status->range('doing{6},wait{2},suspended{2}');
$execution->grade->range('2');
$execution->auth->range('extend');
$execution->openedBy->range('admin');
$execution->openedDate->range('`2024-01-01`');
$execution->gen(10);

su('admin');

$productTest = new productZenTest();

$projectObj1 = (object)array('id' => 1, 'name' => '项目1', 'status' => 'doing', 'PM' => 'admin', 'type' => 'project', 'model' => 'scrum', 'budget' => '100000', 'budgetUnit' => 'CNY', 'begin' => '2024-01-01', 'end' => '2024-12-31', 'estimate' => 0, 'consumed' => 0, 'left' => 0, 'progress' => 0);
$projectObj2 = (object)array('id' => 2, 'name' => '项目2', 'status' => 'doing', 'PM' => 'user1', 'type' => 'project', 'model' => 'waterfall', 'budget' => '200000', 'budgetUnit' => 'CNY', 'begin' => '2024-01-01', 'end' => '2024-12-31', 'estimate' => 0, 'consumed' => 0, 'left' => 0, 'progress' => 0);
$projectObj3 = (object)array('id' => 3, 'name' => '项目3', 'status' => 'doing', 'PM' => 'user2', 'type' => 'project', 'model' => 'kanban', 'budget' => '300000', 'budgetUnit' => 'CNY', 'begin' => '2024-01-01', 'end' => '2024-12-31', 'estimate' => 0, 'consumed' => 0, 'left' => 0, 'progress' => 0);

r($productTest->processProjectListDataTest(array())) && p() && e('0'); // 测试空项目列表
r($productTest->processProjectListDataTest(array($projectObj1))) && p('0:id,name,storyCount') && e('1,项目1,5'); // 测试单个项目
r(count($productTest->processProjectListDataTest(array($projectObj1, $projectObj2, $projectObj3)))) && p() && e('3'); // 测试多个项目
r($productTest->processProjectListDataTest(array($projectObj2))) && p('0:id,storyCount') && e('2,5'); // 测试需求点数
r($productTest->processProjectListDataTest(array($projectObj3))) && p('0:id,from,storyCount') && e('3,project,5'); // 测试返回字段