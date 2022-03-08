#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getByProjectTest();
cid=1
pid=1

敏捷项目执行列表查询 >> 11,迭代1
瀑布项目执行列表查询 >> 41,已关闭的正常产品31/阶段31/子阶段1,stage
看板项目执行列表查询 >> 71,看板61,kanban
wait执行列表查询 >> wait,阶段581
doing执行列表查询 >> doing,阶段582
执行列表2条查询 >> 2
执行列表10条查询 >> 10

*/

$projectIDList = array('0', '11', '41', '71');
$status        = array('all', 'wait', 'doing');
$limit         = array('0', '2', '10');
$count         = array('0', '1');

$execution = new executionTest();
r($execution->getByProjectTest($projectIDList[1],$status[0],$limit[0],$count[0])) && p('101:project,name')      && e('11,迭代1');                                   // 敏捷项目执行列表查询
r($execution->getByProjectTest($projectIDList[2],$status[0],$limit[0],$count[0])) && p('701:project,name,type') && e('41,已关闭的正常产品31/阶段31/子阶段1,stage'); // 瀑布项目执行列表查询
r($execution->getByProjectTest($projectIDList[3],$status[0],$limit[0],$count[0])) && p('161:project,name,type') && e('71,看板61,kanban');                           // 看板项目执行列表查询
r($execution->getByProjectTest($projectIDList[0],$status[1],$limit[0],$count[0])) && p('681:status,name')       && e('wait,阶段581');                               // wait执行列表查询
r($execution->getByProjectTest($projectIDList[0],$status[2],$limit[0],$count[0])) && p('682:status,name')       && e('doing,阶段582');                              // doing执行列表查询
r($execution->getByProjectTest($projectIDList[0],$status[1],$limit[1],$count[1])) && p()                        && e('2');                                          // 执行列表2条查询
r($execution->getByProjectTest($projectIDList[0],$status[2],$limit[2],$count[1])) && p()                        && e('10');                                         // 执行列表10条查询