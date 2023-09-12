#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/execution.class.php';

$execution = zdTable('project');
$execution->id->range('1-5');
$execution->name->range('1-5')->prefix('执行');
$execution->type->range('sprint,stage,kanban');
$execution->status->range('wait{3},suspended,closed,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$task = zdTable('task');
$task->execution->range('1-3');
$task->project->range('0');
$task->gen(10);

su('admin');

/**

title=测试 executionModel->appendTasks();
timeout=0
cid=1

*/

$executionTester = new executionTest();
$executions      = $executionTester->appendTasksTest();

r(count($executions)) && p()                     && e('4');      // 判断执行数量
r($executions)        && p('0:execution,status') && e('1,wait'); // 判断第一个执行的名称
r($executions)        && p('3:rawID,progress')   && e('10,57');  // 查看获取到的第三个执行的开始日期和结束日期
