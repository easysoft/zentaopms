#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
zdTable('user')->gen(5);
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

title=测试 executionModel->checkPriv();
cid=1
pid=1

测试传入空值 >> 0
测试传入0 >> 0
测试传入迭代ID的权限判断 >> 1
测试传入阶段ID的权限判断 >> 1
测试传入看板ID的权限判断 >> 1

*/

$executionIdList = array('', 0, 1, 2, 3);

$executionTester = new executionTest();

r($executionTester->checkPrivTest($executionIdList[0])) && p() && e('0'); // 测试传入空值
r($executionTester->checkPrivTest($executionIdList[1])) && p() && e('0'); // 测试传入0
r($executionTester->checkPrivTest($executionIdList[2])) && p() && e('1'); // 测试传入迭代ID的权限判断
r($executionTester->checkPrivTest($executionIdList[3])) && p() && e('1'); // 测试传入阶段ID的权限判断
r($executionTester->checkPrivTest($executionIdList[4])) && p() && e('1'); // 测试传入看板ID的权限判断
