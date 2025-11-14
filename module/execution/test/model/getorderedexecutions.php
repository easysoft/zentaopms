#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';
zenData('user')->gen(5);
su('admin');

zenData('team')->gen(0);

$execution = zenData('project');
$execution->id->range('1-10');
$execution->name->range('项目集1,项目1,项目2,项目3,迭代1,迭代2,阶段1,阶段2,看板1,看板2');
$execution->type->range('program,project{3},sprint{2},stage{2},kanban{2}');
$execution->model->range('[],scrum,waterfall,kanban,[]{6}');
$execution->parent->range('0,1{3},2{2},3{2},4{2}');
$execution->project->range('0{4},2{2},3{2},4{2}');
$execution->status->range('wait,doing,done,closed');
$execution->vision->range('rnd');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(10);

/**

title=executionModel->getOrderedExecutions();
timeout=0
cid=16331

- 敏捷项目wait状态执行查看
 - 第5条的project属性 @2
 - 第5条的status属性 @wait
 - 第5条的type属性 @sprint
- 敏捷项目doing状态执行查看
 - 第6条的project属性 @2
 - 第6条的status属性 @doing
 - 第6条的type属性 @sprint
- 瀑布项目wait状态执行查看 @0
- 瀑布项目doing状态执行查看 @0
- 看板项目wait状态执行查看
 - 第9条的project属性 @4
 - 第9条的status属性 @wait
 - 第9条的type属性 @kanban
- 看板项目doing状态执行查看
 - 第10条的project属性 @4
 - 第10条的status属性 @doing
 - 第10条的type属性 @kanban
- 敏捷项目wait状态执行统计 @1
- 敏捷项目doing状态执行统计 @1
- 瀑布项目wait状态执行统计 @0
- 瀑布项目doing状态执行统计 @0
- 看板项目wait状态执行统计 @1
- 看板项目doing状态执行统计 @1
- 敏捷项目done状态执行统计 @0
- 敏捷项目closed状态执行统计 @0

*/

$projectIDList = array(2, 3, 4);
$status        = array('wait', 'doing', 'done', 'closed');
$count         = array(0, 1);

$executionTester = new executionTest();
r($executionTester->getOrderedExecutionsTest($projectIDList[0], $status[0], $count[0])) && p('5:project,status,type')  && e('2,wait,sprint');  // 敏捷项目wait状态执行查看
r($executionTester->getOrderedExecutionsTest($projectIDList[0], $status[1], $count[0])) && p('6:project,status,type')  && e('2,doing,sprint'); // 敏捷项目doing状态执行查看
r($executionTester->getOrderedExecutionsTest($projectIDList[1], $status[0], $count[0])) && p()                         && e('0');              // 瀑布项目wait状态执行查看
r($executionTester->getOrderedExecutionsTest($projectIDList[1], $status[1], $count[0])) && p()                         && e('0');              // 瀑布项目doing状态执行查看
r($executionTester->getOrderedExecutionsTest($projectIDList[2], $status[0], $count[0])) && p('9:project,status,type')  && e('4,wait,kanban');  // 看板项目wait状态执行查看
r($executionTester->getOrderedExecutionsTest($projectIDList[2], $status[1], $count[0])) && p('10:project,status,type') && e('4,doing,kanban'); // 看板项目doing状态执行查看
r($executionTester->getOrderedExecutionsTest($projectIDList[0], $status[0], $count[1])) && p()                         && e('1');              // 敏捷项目wait状态执行统计
r($executionTester->getOrderedExecutionsTest($projectIDList[0], $status[1], $count[1])) && p()                         && e('1');              // 敏捷项目doing状态执行统计
r($executionTester->getOrderedExecutionsTest($projectIDList[1], $status[0], $count[1])) && p()                         && e('0');              // 瀑布项目wait状态执行统计
r($executionTester->getOrderedExecutionsTest($projectIDList[1], $status[1], $count[1])) && p()                         && e('0');              // 瀑布项目doing状态执行统计
r($executionTester->getOrderedExecutionsTest($projectIDList[2], $status[0], $count[1])) && p()                         && e('1');              // 看板项目wait状态执行统计
r($executionTester->getOrderedExecutionsTest($projectIDList[2], $status[1], $count[1])) && p()                         && e('1');              // 看板项目doing状态执行统计
r($executionTester->getOrderedExecutionsTest($projectIDList[0], $status[2], $count[1])) && p()                         && e('0');              // 敏捷项目done状态执行统计
r($executionTester->getOrderedExecutionsTest($projectIDList[0], $status[3], $count[1])) && p()                         && e('0');              // 敏捷项目closed状态执行统计
