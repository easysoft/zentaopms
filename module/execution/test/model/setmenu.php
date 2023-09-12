#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/execution.class.php';

zdTable('user')->gen(5);
su('admin');

$execution = zdTable('project');
$execution->id->range('1-5');
$execution->name->range('项目集1,项目1,迭代1,阶段1,看板1');
$execution->type->range('execution,project,sprint,stage,kanban');
$execution->parent->range('0,1,2{3}');
$execution->status->range('wait{3},suspended,closed,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

/**

title=测试 executionModel::setMenu();
timeout=0
cid=1

*/

$executionTester = new executionTest();
r($executionTester->setMenuTest(3))  && p('more:link')   && e('更多|execution|more|3');               // 测试设置迭代导航
r($executionTester->setMenuTest(4))  && p('more:link')   && e('更多|execution|more|3');               // 测试设置阶段导航
r($executionTester->setMenuTest(5))  && p('kanban:link') && e('看板|execution|kanban|executionID=5'); // 测试设置看板导航
r($executionTester->setMenuTest(10)) && p()              && e('0');                                   // 执行不存在的情况
