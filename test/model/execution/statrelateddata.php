#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';

$execution = zdTable('project');
$execution->id->range('1-5');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->type->range('project{2},sprint,waterfall,kanban');
$execution->status->range('doing{3},closed,doing');
$execution->parent->range('0,0,1,1,2');
$execution->grade->range('2{2},1{3}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$stroy = zdTable('story');
$stroy->id->range('4,324,364');
$stroy->title->range('1-3')->prefix('需求');
$stroy->type->range('story');
$stroy->status->range('active');
$stroy->gen(3);

$projectstory = zdTable('projectstory');
$projectstory->project->range('3-5');
$projectstory->product->range('1-3');
$projectstory->story->range('4,324,364');
$projectstory->gen(3);

$task = zdTable('task');
$task->id->range('1-10');
$task->execution->range('3,4,5');
$task->status->range('wait,doing');
$task->estimate->range('6');
$task->left->range('3');
$task->consumed->range('3');
$task->gen(10);

$bug = zdTable('bug');
$bug->id->range('1-10');
$bug->project->range('1,2,1');
$bug->execution->range('3,4,5');
$bug->status->range('wait,doing');
$bug->gen(10);

su('admin');

/**

title=测试executionModel->statRelatedDataTest();
cid=1
pid=1

敏捷执行数据统计 >> 1
瀑布执行数据统计 >> 3
看板执行数据统计 >> 3

*/

$executionIDList = array('3', '4', '5');

$execution = new executionTest();
r($execution->statRelatedDataTest($executionIDList[0])) && p('storyCount') && e('1'); // 敏捷执行数据统计
r($execution->statRelatedDataTest($executionIDList[1])) && p('taskCount')  && e('3'); // 瀑布执行数据统计
r($execution->statRelatedDataTest($executionIDList[2])) && p('bugCount')   && e('3'); // 看板执行数据统计
