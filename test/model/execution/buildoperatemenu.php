#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';

$execution = zdTable('project');
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

su('admin');
/**

title=测试 executionModel::buildOperateMenu();
cid=1
pid=1

执行不存在 >> 0
执行存在   >> div

*/

$executionTester = new executionTest();
$menus1          = $executionTester->buildOperateMenuTest(0);
$menus2          = $executionTester->buildOperateMenuTest(3);

r($menus1)               && p() && e('0');   // 项目集不存在
r(substr($menus2, 1, 3)) && p() && e('div'); // 项目集存在
