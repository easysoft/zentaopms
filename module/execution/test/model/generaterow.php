#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/execution.class.php';
su('admin');

$execution = zdTable('project');
$execution->id->range('1-5');
$execution->name->range('1-5')->prefix('执行');
$execution->type->range('sprint,stage,kanban');
$execution->status->range('wait{3},suspended,closed,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

/**

title=测试 executionModel->generateRow();
timeout=0
cid=1

*/

$executionTester = new executionTest();
$executions      = $executionTester->generateRowTest();

r(count($executions)) && p()                      && e('4');                     // 判断执行数量
r($executions)        && p("pid1:name")           && e('执行1');                 // 判断第一个执行的名称
r($executions)        && p("pid2:begin;pid2:end") && e('2022-01-12;2022-02-12'); // 查看获取到的第三个执行的开始日期和结束日期

