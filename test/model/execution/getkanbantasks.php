#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
zdTable('user')->gen(5);
su('admin');

$execution = zdTable('project');
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

$task = zdTable('task');
$task->id->range('1-30');
$task->execution->range('5, 7, 9');
$task->name->range('1-30')->prefix('任务');
$task->type->range('design,devel,test,study,discuss,ui,affair,misc');
$task->status->range('wait,doing,done,closed');
$task->gen(30);

/**

title=测试executionModel->getKanbanTasksTest();
cid=1
pid=1

敏捷执行查询 >> 任务25,5
瀑布执行查询 >> 任务29,7
看板执行查询 >> 任务21,9
敏捷执行查询统计 >> 10
瀑布执行查询统计 >> 10
看板执行查询统计 >> 10

*/

$executionIDList = array(5, 7, 9);
$count           = array('0', '1');

$executionTester = new executionTest();
r($executionTester->getKanbanTasksTest($executionIDList[0], $count[0])) && p('25:name,execution') && e('任务25,5'); // 敏捷执行查询
r($executionTester->getKanbanTasksTest($executionIDList[1], $count[0])) && p('29:name,execution') && e('任务29,7'); // 瀑布执行查询
r($executionTester->getKanbanTasksTest($executionIDList[2], $count[0])) && p('21:name,execution') && e('任务21,9'); // 看板执行查询
r($executionTester->getKanbanTasksTest($executionIDList[0], $count[1])) && p()                    && e('10');       // 敏捷执行查询统计
r($executionTester->getKanbanTasksTest($executionIDList[1], $count[1])) && p()                    && e('10');       // 瀑布执行查询统计
r($executionTester->getKanbanTasksTest($executionIDList[2], $count[1])) && p()                    && e('10');       // 看板执行查询统计
