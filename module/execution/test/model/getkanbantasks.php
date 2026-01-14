#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('user')->gen(5);
su('admin');

$execution = zenData('project');
$execution->id->range('1-10');
$execution->name->range('项目集1,项目1,项目2,项目3,迭代1,迭代2,阶段1,阶段2,看板1,看板2');
$execution->type->range('program,project{3},sprint{2},stage{2},kanban{2}');
$execution->model->range('[],scrum,waterfall,kanban,[]{6}');
$execution->parent->range('0,1{3},2{2},3{2},4{2}');
$execution->project->range('0{4},2{2},3{2},4{2}');
$execution->status->range('doing');
$execution->vision->range('rnd');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(10);

$task = zenData('task');
$task->id->range('1-30');
$task->execution->range('5, 7, 9');
$task->name->range('1-30')->prefix('任务');
$task->type->range('design,devel,test,study,discuss,ui,affair,misc');
$task->status->range('wait,doing,done,closed');
$task->gen(30);

/**

title=测试executionModel->getKanbanTasksTest();
timeout=0
cid=16324

- 敏捷执行查询
 - 第25条的name属性 @任务25
 - 第25条的execution属性 @5
- 瀑布执行查询
 - 第29条的name属性 @任务29
 - 第29条的execution属性 @7
- 看板执行查询
 - 第21条的name属性 @任务21
 - 第21条的execution属性 @9
- 敏捷执行查询统计 @10
- 瀑布执行查询统计 @10
- 看板执行查询统计 @10
- 敏捷执行查询不包括任务25
 - 第1条的name属性 @任务1
 - 第1条的execution属性 @5
- 瀑布执行查询不包括任务29
 - 第2条的name属性 @任务2
 - 第2条的execution属性 @7
- 看板执行查询不包括任务21
 - 第3条的name属性 @任务3
 - 第3条的execution属性 @9
- 敏捷执行不包括任务25查询统计 @9
- 瀑布执行不包括任务29查询统计 @9
- 看板执行不包括任务21查询统计 @9

*/

$executionIDList   = array(5, 7, 9);
$count             = array('0', '1');
$excludeTaskIdList = array(21, 25, 29);

$executionTester = new executionModelTest();
r($executionTester->getKanbanTasksTest($executionIDList[0], $count[0]))                     && p('25:name,execution') && e('任务25,5'); // 敏捷执行查询
r($executionTester->getKanbanTasksTest($executionIDList[1], $count[0]))                     && p('29:name,execution') && e('任务29,7'); // 瀑布执行查询
r($executionTester->getKanbanTasksTest($executionIDList[2], $count[0]))                     && p('21:name,execution') && e('任务21,9'); // 看板执行查询
r($executionTester->getKanbanTasksTest($executionIDList[0], $count[1]))                     && p()                    && e('10');       // 敏捷执行查询统计
r($executionTester->getKanbanTasksTest($executionIDList[1], $count[1]))                     && p()                    && e('10');       // 瀑布执行查询统计
r($executionTester->getKanbanTasksTest($executionIDList[2], $count[1]))                     && p()                    && e('10');       // 看板执行查询统计
r($executionTester->getKanbanTasksTest($executionIDList[0], $count[0], $excludeTaskIdList)) && p('1:name,execution')  && e('任务1,5');  // 敏捷执行查询不包括任务25
r($executionTester->getKanbanTasksTest($executionIDList[1], $count[0], $excludeTaskIdList)) && p('2:name,execution')  && e('任务2,7');  // 瀑布执行查询不包括任务29
r($executionTester->getKanbanTasksTest($executionIDList[2], $count[0], $excludeTaskIdList)) && p('3:name,execution')  && e('任务3,9');  // 看板执行查询不包括任务21
r($executionTester->getKanbanTasksTest($executionIDList[0], $count[1], $excludeTaskIdList)) && p()                    && e('9');        // 敏捷执行不包括任务25查询统计
r($executionTester->getKanbanTasksTest($executionIDList[1], $count[1], $excludeTaskIdList)) && p()                    && e('9');        // 瀑布执行不包括任务29查询统计
r($executionTester->getKanbanTasksTest($executionIDList[2], $count[1], $excludeTaskIdList)) && p()                    && e('9');        // 看板执行不包括任务21查询统计