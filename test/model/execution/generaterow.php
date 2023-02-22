#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
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

title=测试 executionModel->generateCol();
cid=1
pid=1

*/

$executionTester = new executionTest();
$executions = $executionTester->generateRowTest();

r(count($executions)) && p('')              && e('5');      // 判断执行数量 
r($executions)        && p("0:status")      && e('已关闭'); // 判断第一个执行的状态 
r($executions)        && p("2:begin;2:end") && e('2022-01-12;2022-02-12'); // 查看获取到的第三个执行的开始日期和结束日期 
