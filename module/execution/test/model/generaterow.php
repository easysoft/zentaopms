#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

$execution = zenData('project');
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

/**

title=测试 executionModel->generateRow();
cid=16297

- 判断执行数量 @4
- 判断第一个执行的名称第pid2条的name属性 @执行2
- 查看获取到的第三个执行的开始日期和结束日期
 - 第pid4条的begin属性 @2022-01-12
 - 第pid4条的end属性 @2022-02-12
- 查看获取到的最后一个执行的project和rawID字段
 - 第pid6条的project属性 @执行1
 - 第pid6条的rawID属性 @6
- 查看获取到的最后一个执行的操作列的第一个操作
 - 第0条的name属性 @start
 - 第0条的disabled属性 @1
- 不是超级超级管理员账号，判断执行数据。 @pid2|pid3

*/

$executionTester = new executionTest();
$executionTester->executionModel->app->user->admin = true;

$executions = $executionTester->generateRowTest();
r(count($executions))           && p()                     && e('4');                       // 判断执行数量
r($executions)                  && p('pid2:name')          && e('执行2');                   // 判断第一个执行的名称
r($executions)                  && p('pid4:begin,end')     && e('2022-01-12,2022-02-12');   // 查看获取到的第三个执行的开始日期和结束日期
r($executions)                  && p('pid6:project,rawID') && e('执行1,6');                 // 查看获取到的最后一个执行的project和rawID字段
r($executions['pid6']->actions) && p('0:name,disabled')    && e('start,1');                 // 查看获取到的最后一个执行的操作列的第一个操作

$executionTester->executionModel->app->user->admin = false;
$executionTester->executionModel->app->user->view->sprints = '2,3,5';

$executions = $executionTester->generateRowTest();
r(implode('|', array_keys($executions))) && p() && e('pid2|pid3'); //不是超级超级管理员账号，判断执行数据。
