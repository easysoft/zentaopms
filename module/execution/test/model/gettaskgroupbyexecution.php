#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->type->range('project{2},sprint,stage,kanban');
$execution->status->range('doing');
$execution->parent->range('0,0,1,1,2');
$execution->project->range('0,0,1,1,2');
$execution->grade->range('2{2},1{3}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$task = zenData('task');
$task->id->range('1-10');
$task->name->range('1-10')->prefix('任务');
$task->execution->range('3-5');
$task->type->range('test,devel');
$task->status->range('wait,doing');
$task->estimate->range('1-10');
$task->left->range('1-10');
$task->consumed->range('1-10');
$task->story->range('1-10');
$task->storyVersion->range('1{6},2{4}');
$task->gen(10);

$story = zenData('story');
$story->id->range('1-10');
$story->status->range('active,changed');
$story->version->range('1{6},2{2},3{2}');
$story->gen(10);

su('admin');

/**

title=测试 executionModel->getTaskGroupByExecution();
timeout=0
cid=1

*/


$executionIdList = array(0, 3, 4, 5);

$execution = new executionTest();
r($execution->getTaskGroupByExecutionTest())                 && p() && e('0');  // 测试空数据
r($execution->getTaskGroupByExecutionTest($executionIdList)) && p() && e('3');  // 测试获取执行的任务

$taskGroup = $execution->getTaskGroupByExecutionTest($executionIdList, false);
r($taskGroup[3]) && p('1:storyVersion,storyStatus,needConfirm') && e('1,active,~~'); // 测试获取执行ID=3的任务关联需求版本跟需求状态
r($taskGroup[4]) && p('2:storyVersion,storyStatus,needConfirm') && e('1,~~,~~');     // 测试获取执行ID=4的任务关联需求版本跟需求状态
r($taskGroup[5]) && p('9:storyVersion,storyStatus,needConfirm') && e('2,active,1');  // 测试获取执行ID=5的任务关联需求版本跟需求状态
