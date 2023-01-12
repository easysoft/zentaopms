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
$task->execution->range('4');
$task->name->range('1-10')->prefix('任务');
$task->type->range('design,devel,test,study,discuss,ui,affair,misc');
$task->status->range('wait,doing,done,closed');
$task->gen(10);

/**

title=测试executionModel->getPrevKanban();
cid=1
pid=1

查询执行4数据 >> empty
保存数据后查询执行4数据 >> task1

*/

$executionTester = new executionTest();

$emptyExecutionID = 5;
$executionID      = 4;

r($executionTester->getPrevKanbanTest($emptyExecutionID)) && p()         && e('empty'); // 查询执行id=4数据
$executionTester->saveKanbanDataTest($executionID);
r($executionTester->getPrevKanbanTest($executionID))      && p('wait:0') && e('task1'); // 保存数据后查询执行id=4数据
