#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
zdTable('user')->gen(5);
su('admin');

$execution = zdTable('project');
$execution->id->range('1-5');
$execution->name->range('项目集1,项目1,迭代1,阶段1,看板1');
$execution->type->range('program,project,sprint,stage,kanban');
$execution->parent->range('0,1,2{3}');
$execution->status->range('wait{3},suspended,closed,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

/**

title=测试executionModel->getLifetimeByIdList();
cid=1
pid=1

查询执行3和4 lifetime >> emptyLifetime
查询空执行 lifetime >> empty

*/

$executionIDList = array(3, 4);

$executionTester = new executionTest();
r($executionTester->getLifetimeByIdListTest($executionIDList)) && p('3') && e('emptyLifetime'); // 查询执行3和4 lifetime
r($executionTester->getLifetimeByIdListTest(array('0')))       && p('')  && e('empty');         // 查询空执行 lifetime
