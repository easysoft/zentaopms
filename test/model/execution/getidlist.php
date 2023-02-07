#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';

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

/**

title=测试executionModel->getIdListTest();
cid=1
pid=1

敏捷项目下执行id查询 >> 5
瀑布项目下执行id查询 >> 7
看板项目下执行id查询 >> 9
敏捷项目下执行id数量统计 >> 2
敏捷项目下执行id数量统计 >> 2
敏捷项目下执行id数量统计 >> 2

*/

$projectIDList = array(2, 3, 4);
$count         = array('0','1');

$executionTester = new executionTest();
r($executionTester->getIdListTest($projectIDList[0],$count[0])) && p('5') && e('5'); // 敏捷项目下执行id查询
r($executionTester->getIdListTest($projectIDList[1],$count[0])) && p('7') && e('7'); // 瀑布项目下执行id查询
r($executionTester->getIdListTest($projectIDList[2],$count[0])) && p('9') && e('9'); // 看板项目下执行id查询
r($executionTester->getIdListTest($projectIDList[0],$count[1])) && p()    && e('2'); // 敏捷项目下执行id数量统计
r($executionTester->getIdListTest($projectIDList[1],$count[1])) && p()    && e('2'); // 敏捷项目下执行id数量统计
r($executionTester->getIdListTest($projectIDList[2],$count[1])) && p()    && e('2'); // 敏捷项目下执行id数量统计
