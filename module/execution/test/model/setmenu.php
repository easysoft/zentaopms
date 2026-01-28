#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
/**

title=测试 executionModel::setMenu();
timeout=0
cid=16364

- 测试构建导航
 - 第build条的link属性 @构建|execution|build|executionID=3
 - 第build条的subModule属性 @build
- 测试设置迭代导航第more条的link属性 @更多|execution|more|3
- 测试看板导航
 - 第kanban条的link属性 @看板|execution|kanban|executionID=5
 - 第kanban条的subModule属性 @task
 - 第kanban条的alias属性 @importtask

*/

zenData('user')->gen(5);
su('admin');

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目集1,项目1,迭代1,阶段1,看板1');
$execution->type->range('execution,project,sprint,stage,kanban');
$execution->parent->range('0,1,2{3}');
$execution->status->range('wait{3},suspended,closed,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$executionTester = new executionModelTest();
r($executionTester->setMenuTest(3)) && p('build:link,subModule')        && e('构建|execution|build|executionID=3,build');            // 测试构建导航
r($executionTester->setMenuTest(4)) && p('more:link')                   && e('更多|execution|more|3');                               // 测试设置迭代导航
r($executionTester->setMenuTest(5)) && p('kanban:link,subModule,alias') && e('看板|execution|kanban|executionID=5,task,importtask'); // 测试看板导航
