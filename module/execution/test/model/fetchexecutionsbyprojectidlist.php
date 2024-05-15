#!/usr/bin/env php
<?php

/**

title=测试 executionModel::fetchExecutionsByProjectIdList();
cid=0

- 不传入数据。 @1
- 传入不存在的项目ID。 @1
- 检查有关联执行项目的执行数。 @2
- 检查没有关联执行项目，是否在数据中存在。 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->type->range('project{2},sprint,stage,kanban');
$execution->status->range('doing');
$execution->parent->range('0,0,1,1,2');
$execution->project->range('0,0,1,1,2');
$execution->grade->range('2{2},1{3}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

global $tester;
$executionModel = $tester->loadModel('execution');
$executionModel->app->user->admin = true;

r(empty($executionModel->fetchExecutionsByProjectIdList(array()))) && p() && e('1');    //不传入数据。
r(empty($executionModel->fetchExecutionsByProjectIdList(array(100)))) && p() && e('1'); //传入不存在的项目ID。

$executionGroup = $executionModel->fetchExecutionsByProjectIdList(array(1, 100));
r(count($executionGroup[1])) && p() && e('2'); //检查有关联执行项目的执行数。
r(empty($executionGroup[100])) && p() && e('1');  //检查没有关联执行项目，是否在数据中存在。
