#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
zdTable('user')->gen(5);
su('admin');

$execution = zdTable('project');
$execution->id->range('1-5');
$execution->name->range('项目集1,项目1,迭代1,阶段1,看板1');
$execution->type->range('program,project,sprint,stage,kanban');
$execution->parent->range('0,1,2{3}');
$execution->status->range('wait{3},suspended,closed,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$task = zdTable('task');
$task->id->range('1-10');
$task->execution->range('3');
$task->name->range('1-10')->prefix('任务');
$task->type->range('design,devel,test,study,discuss,ui,affair,misc');
$task->status->range('wait,doing,done,closed');
$task->gen(10);

$product = zdTable('product');
$product->id->range('1-3');
$product->name->range('1-3')->prefix('产品');
$product->code->range('1-3')->prefix('product');
$product->type->range('normal');
$product->status->range('normal');
$product->PO->range('admin');
$product->QD->range('user1');
$product->RD->range('user2');
$product->gen(3);

$story = zdTable('story');
$story->id->range('1-10');
$story->name->range('1-10')->prefix('需求');
$story->type->range('story');
$story->product->range('1');
$story->status->range('active');
$story->staged->range('projected');
$story->version->range('1');
$story->gen(10);

$projectStory = zdTable('projectstory');
$projectStory->project->range('3');
$projectStory->product->range('1');
$projectStory->story->range('1-10');
$projectStory->version->range('1');
$projectStory->gen(10);

$bug = zdTable('bug');
$bug->id->range('1-10');
$bug->execution->range('3');
$bug->name->range('1-10')->prefix('Bug');
$bug->status->range('active');
$bug->gen(10);

/**

title=测试executionModel->getKanbanGroupData();
cid=1
pid=1

空数据查询           >> empty
获取id=3执行的任务数 >> 4

*/

$executionTester = new executionTest();

$executionID = 3;
$stories     = $tester->loadModel('story')->getExecutionStories($executionID);
$tasks       = $executionTester->getKanbanTasksTest($executionID, false);
$bugs        = $tester->loadModel('bug')->getExecutionBugs($executionID);

r($executionTester->getKanbanGroupDataTest(array(), array(), array(), 'story')) && p('') && e('empty'); // 空数据查询
r($executionTester->getKanbanGroupDataTest($stories, $tasks, $bugs, 'story'))   && p('') && e('1');     // 获取id=3执行的任务数
