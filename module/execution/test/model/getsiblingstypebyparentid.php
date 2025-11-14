#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';
/**

title=测试executionModel->getSiblingsTypeByParentID();
timeout=0
cid=16338

- 查找parent为1的类型属性project @project
- 查找parent为2的类型属性sprint @sprint
- 查找parent为2的类型属性stage @stage
- 查找parent为2的类型属性kanban @kanban
- 查找parent为3的类型没有类型 @0

*/

zenData('user')->gen(5);
su('admin');

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目集1,项目1,迭代1,阶段1,看板1');
$execution->type->range('program,project,sprint,stage,kanban');
$execution->parent->range('0,1,2{3}');
$execution->status->range('wait{3},suspended,closed,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$team = zenData('team');
$team->root->range('3-5');
$team->type->range('execution');
$team->account->range('admin,user1,user2,user3,user4');
$team->role->range('研发,测试{4}');
$team->days->range('5');
$team->hours->range('7');
$team->gen(10);

$executionIDList = array(3, 4, 5);
$count           = array('0','1', '2');

global $tester;
$executionTester = $tester->loadModel('execution');
r($executionTester->getSiblingsTypeByParentID(1)) && p('project') && e('project'); // 查找parent为1的类型
r($executionTester->getSiblingsTypeByParentID(2)) && p('sprint')  && e('sprint');  // 查找parent为2的类型
r($executionTester->getSiblingsTypeByParentID(2)) && p('stage')   && e('stage');   // 查找parent为2的类型
r($executionTester->getSiblingsTypeByParentID(2)) && p('kanban')  && e('kanban');  // 查找parent为2的类型
r($executionTester->getSiblingsTypeByParentID(3)) && p()          && e(0);         // 查找parent为3的类型没有类型
