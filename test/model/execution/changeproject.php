#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
zdTable('user')->gen(5);
su('admin');

$execution = zdTable('project');
$execution->id->range('1-10');
$execution->name->range('项目集1,项目1,项目2,项目3,项目4,项目5,项目6,迭代1,阶段1,看板1');
$execution->type->range('program,project{6},sprint,stage,kanban');
$execution->model->range('[],scrum{2},waterfall{2},kanban{2},[]{3}');
$execution->parent->range('0,1{6},2,3,4');
$execution->status->range('doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(10);

/**

title=测试executionModel->changeProjectTest();
cid=1
pid=1

测试修改敏捷执行关联项目 >> 5
测试修改瀑布执行关联项目 >> ,6,9,
测试修改看板执行关联项目 >> 7

*/

$executionIDList  = array(8, 9, 10);
$oldProjectIDlist = array(2, 3, 4);
$newProjectIDlist = array(5, 6, 7);

$executionTester = new executionTest();
r($executionTester->changeProjectTest($newProjectIDlist[0], $oldProjectIDlist[0], $executionIDList[0])) && p('0:parent') && e('5');       // 测试修改敏捷执行关联项目
r($executionTester->changeProjectTest($newProjectIDlist[1], $oldProjectIDlist[1], $executionIDList[1])) && p('0:path')   && e(',6,9,'); // 测试修改瀑布执行关联项目
r($executionTester->changeProjectTest($newProjectIDlist[2], $oldProjectIDlist[2], $executionIDList[2])) && p('0:parent') && e('7');       // 测试修改看板执行关联项目
