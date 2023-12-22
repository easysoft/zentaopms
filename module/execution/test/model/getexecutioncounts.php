#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/execution.class.php';

zdTable('group')->gen(0);
zdTable('userview')->gen(0);

$execution = zdTable('project');
$execution->id->range('1-5');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->type->range('project{2},sprint,waterfall,kanban');
$execution->status->range('doing{3},closed,doing');
$execution->parent->range('0,0,1,1,2');
$execution->project->range('0,0,1,1,2');
$execution->grade->range('2{2},1{3}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

su('admin');

/**

title=executionModel->getExecutionCounts();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('execution');
r($tester->execution->getExecutionCounts(1)) && p() && e('1'); // 根据executionID查找任务详情
r($tester->execution->getExecutionCounts(2)) && p() && e('1'); // 根据executionID查找任务详情
r($tester->execution->getExecutionCounts(3)) && p() && e('0'); // 根据executionID查找任务详情
