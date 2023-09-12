#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/execution.class.php';

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

title=测试executionModel->getTotalEstimateTest();
timeout=0
cid=1

*/

$executionIDList = array(3, 4, 5);

global $tester;
$tester->loadModel('execution');
r($tester->execution->getTotalEstimate($executionIDList[0])) && p() && e('24'); // 敏捷执行预计工时统计
r($tester->execution->getTotalEstimate($executionIDList[1])) && p() && e('18'); // 瀑布执行预计工时统计
r($tester->execution->getTotalEstimate($executionIDList[2])) && p() && e('18'); // 看板执行预计工时统计
r($tester->execution->getTotalEstimate(0))                   && p() && e('0');  // 无执行预计工时统计
