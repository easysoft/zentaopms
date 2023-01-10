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

title=测试executionModel->getByProjectTest();
cid=1
pid=1

敏捷项目执行列表查询 >> 2,迭代1,sprint
瀑布项目执行列表查询 >> 3,阶段1,stage
看板项目执行列表查询 >> 4,看板1,kanban
wait执行列表查询 >> 0
doing执行列表查询 >> doing,看板1
执行列表2条查询 >> 0
执行列表10条查询 >> 3

*/

$projectIDList = array(0, 2, 3, 4);
$status        = array('all', 'wait', 'doing');
$limit         = array('0', '2', '10');
$count         = array('0', '1');

$executionTester = new executionTest();
r($executionTester->getByProjectTest($projectIDList[1],$status[0],$limit[0],$count[0])) && p('5:project,name,type') && e('2,迭代1,sprint'); // 敏捷项目执行列表查询
r($executionTester->getByProjectTest($projectIDList[2],$status[0],$limit[0],$count[0])) && p('6:project,name,type') && e('3,阶段1,stage');  // 瀑布项目执行列表查询
r($executionTester->getByProjectTest($projectIDList[3],$status[0],$limit[0],$count[0])) && p('7:project,name,type') && e('4,看板1,kanban'); // 看板项目执行列表查询
r($executionTester->getByProjectTest($projectIDList[0],$status[1],$limit[0],$count[0])) && p()                      && e('0');              // wait执行列表查询
r($executionTester->getByProjectTest($projectIDList[0],$status[2],$limit[0],$count[0])) && p('7:status,name')       && e('doing,看板1');    // doing执行列表查询
r($executionTester->getByProjectTest($projectIDList[0],$status[1],$limit[1],$count[1])) && p()                      && e('0');              // 执行列表2条查询
r($executionTester->getByProjectTest($projectIDList[0],$status[2],$limit[2],$count[1])) && p()                      && e('3');              // 执行列表10条查询
