#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

zenData('user')->gen(5);
su('admin');

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目集1,项目1,迭代1,阶段1,看板1');
$execution->type->range('execution,project,sprint,stage,kanban');
$execution->parent->range('0,1,2{3}');
$execution->project->range('0{2},2{3}');
$execution->status->range('wait{3},suspended,closed,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

/**

title=测试 executionModel::setProjectSession();
timeout=0
cid=1

- 执行存在的情况 @2
- 执行不存在的情况 @0

*/

$executionTester = new executionTest();
r($executionTester->setProjectSessionTest(3))  && p() && e('2'); // 执行存在的情况
r($executionTester->setProjectSessionTest(10)) && p() && e('0'); // 执行不存在的情况
