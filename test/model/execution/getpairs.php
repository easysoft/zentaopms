#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
zdTable('user')->gen(5);
su('admin');

$execution = zdTable('project');
$execution->id->range('1-7');
$execution->name->range('项目集1,项目1,项目2,项目3,迭代1,阶段1,看板1');
$execution->type->range('program,project{3},sprint,stage,kanban');
$execution->model->range('[],scrum,waterfall,kanban,[]{3}');
$execution->parent->range('0,1{3},2,3,4');
$execution->project->range('0{4},2,3,4');
$execution->status->range('doing');
$execution->vision->range('rnd');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(7);

/**

title=测试executionModel->getPairsTest();
cid=1
pid=1

敏捷项目执行查看 >> 迭代1
瀑布项目执行查看 >> 阶段1
看板项目执行查看 >> 看板1
敏捷项目执行统计 >> 1
敏捷项目执行统计 >> 1
敏捷项目执行统计 >> 1

*/

$projectIDList = array(2, 3, 4);
$count         = array('0','1');
$noRealEnd       = array('realEnd' => '');
$readjustTime    = array('readjustTime' => '1');

$executionTester = new executionTest();
r($executionTester->getPairsTest($projectIDList[0],$count[0])) && p('5') && e('迭代1'); // 敏捷项目执行查看
r($executionTester->getPairsTest($projectIDList[1],$count[0])) && p('6') && e('阶段1'); // 瀑布项目执行查看
r($executionTester->getPairsTest($projectIDList[2],$count[0])) && p('7') && e('看板1'); // 看板项目执行查看
r($executionTester->getPairsTest($projectIDList[0],$count[1])) && p()    && e('1');     // 敏捷项目执行统计
r($executionTester->getPairsTest($projectIDList[1],$count[1])) && p()    && e('1');     // 敏捷项目执行统计
r($executionTester->getPairsTest($projectIDList[2],$count[1])) && p()    && e('1');     // 敏捷项目执行统计
