#!/usr/bin/env php
<?php

/**

title=测试 executionModel->generateRow();
cid=0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/execution.class.php';

$execution = zdTable('project');
$execution->id->range('1-6');
$execution->project->range('0,1{5}');
$execution->name->range('1-6')->prefix('执行');
$execution->type->range('project,sprint,stage,kanban,sprint,stage');
$execution->model->range('scrum,``{5}');
$execution->status->range('wait{3},suspended,closed,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(6);

su('admin');

$executionTester = new executionTest();
$executionTester->executionModel->app->user->admin = true;

$executions = $executionTester->generateRowTest();
r(count($executions)) && p()                 && e('4');                     // 判断执行数量
r($executions)        && p('pid2:name')      && e('执行2');                 // 判断第一个执行的名称
r($executions)        && p('pid4:begin,end') && e('2022-01-12,2022-02-12'); // 查看获取到的第三个执行的开始日期和结束日期

$executionTester->executionModel->app->user->admin = false;
$executionTester->executionModel->app->user->view->sprints = '2,3,5';

$executions = $executionTester->generateRowTest();
r(implode('|', array_keys($executions))) && p() && e('pid2|pid3'); //不是超级超级管理员账号，判断执行数据。
