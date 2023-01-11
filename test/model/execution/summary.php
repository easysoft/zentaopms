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

$task = zdTable('task');
$task->id->range('1-10');
$task->execution->range('3,4,5');
$task->status->range('wait,doing');
$task->estimate->range('6');
$task->left->range('3');
$task->consumed->range('3');
$task->gen(10);

su('admin');

/**

title=测试executionModel->summaryTest();
cid=1
pid=1

敏捷执行任务统计 >> 本页共 <strong>4</strong> 个任务，未开始 <strong>2</strong>，进行中 <strong>2</strong>，总预计 <strong>24</strong> 工时，已消耗 <strong>12</strong> 工时，剩余 <strong>12</strong> 工时。
瀑布执行任务统计 >> 本页共 <strong>3</strong> 个任务，未开始 <strong>1</strong>，进行中 <strong>2</strong>，总预计 <strong>18</strong> 工时，已消耗 <strong>9</strong> 工时，剩余 <strong>9</strong> 工时。
看板执行任务统计 >> 本页共 <strong>3</strong> 个任务，未开始 <strong>2</strong>，进行中 <strong>1</strong>，总预计 <strong>18</strong> 工时，已消耗 <strong>9</strong> 工时，剩余 <strong>9</strong> 工时。

*/

$executionIDList = array('3', '4', '5');

$execution = new executionTest();
r($execution->summaryTest($executionIDList[0])) && p() && e('本页共 <strong>4</strong> 个任务，未开始 <strong>2</strong>，进行中 <strong>2</strong>，总预计 <strong>24</strong> 工时，已消耗 <strong>12</strong> 工时，剩余 <strong>12</strong> 工时。');  // 敏捷执行任务统计
r($execution->summaryTest($executionIDList[1])) && p() && e('本页共 <strong>3</strong> 个任务，未开始 <strong>1</strong>，进行中 <strong>2</strong>，总预计 <strong>18</strong> 工时，已消耗 <strong>9</strong> 工时，剩余 <strong>9</strong> 工时。');    // 瀑布执行任务统计
r($execution->summaryTest($executionIDList[2])) && p() && e('本页共 <strong>3</strong> 个任务，未开始 <strong>2</strong>，进行中 <strong>1</strong>，总预计 <strong>18</strong> 工时，已消耗 <strong>9</strong> 工时，剩余 <strong>9</strong> 工时。');    // 看板执行任务统计
