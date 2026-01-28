#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('user')->gen(5);
su('admin');

$execution = zenData('project');
$execution->id->range('1-10');
$execution->name->range('项目集1,项目1,项目2,项目3,项目4,项目5,项目6,迭代1,阶段1,看板1');
$execution->type->range('program,project{6},sprint,stage,kanban');
$execution->model->range('[],scrum{2},waterfall{2},kanban{2},[]{3}');
$execution->parent->range('0,1{6},2,3,4');
$execution->path->range('`,1,`,`,1,2,`,`,1,3,`,`,1,4,`,`,1,5,`,`,1,6,`,`1,7,`,`,1,2,8,`,`,1,3,9,`,`,1,4,10`');
$execution->status->range('doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(10);

/**

title=测试executionModel->changeProjectTest();
timeout=0
cid=16277

- 测试不修改敏捷执行关联项目属性parent @2
- 测试不修改瀑布执行关联项目属性path @,1,3,9,
- 测试不修改看板执行关联项目属性parent @4
- 测试修改敏捷执行关联项目属性parent @5
- 测试修改瀑布执行关联项目属性path @,6,9,
- 测试修改看板执行关联项目属性parent @7

*/

$executionIDList  = array(8, 9, 10);
$oldProjectIDlist = array(2, 3, 4);
$newProjectIDlist = array(5, 6, 7);

$executionTester = new executionModelTest();
r($executionTester->changeProjectTest($oldProjectIDlist[0], $oldProjectIDlist[0], $executionIDList[0])) && p('parent')    && e('2');       // 测试不修改敏捷执行关联项目
r($executionTester->changeProjectTest($oldProjectIDlist[1], $oldProjectIDlist[1], $executionIDList[1])) && p('path', ';') && e(',1,3,9,'); // 测试不修改瀑布执行关联项目
r($executionTester->changeProjectTest($oldProjectIDlist[2], $oldProjectIDlist[2], $executionIDList[2])) && p('parent')    && e('4');       // 测试不修改看板执行关联项目
r($executionTester->changeProjectTest($newProjectIDlist[0], $oldProjectIDlist[0], $executionIDList[0])) && p('parent')    && e('5');       // 测试修改敏捷执行关联项目
r($executionTester->changeProjectTest($newProjectIDlist[1], $oldProjectIDlist[1], $executionIDList[1])) && p('path', ';') && e(',6,9,');   // 测试修改瀑布执行关联项目
r($executionTester->changeProjectTest($newProjectIDlist[2], $oldProjectIDlist[2], $executionIDList[2])) && p('parent')    && e('7');       // 测试修改看板执行关联项目