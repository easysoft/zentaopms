#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getListTest();
cid=1
pid=1

敏捷项目执行列表查询 >> 11,迭代1
瀑布项目执行列表查询 >> 41,阶段31,stage
看板项目执行列表查询 >> 71,看板61,kanban
产品1执行列表查询 >> 迭代1
敏捷执行列表查询 >> sprint,迭代561
瀑布执行列表查询 >> stage,阶段591
看板执行列表查询 >> kanban,看板531
wait执行列表查询 >> wait,阶段581
doing执行列表查询 >> doing,阶段582
执行列表2条查询 >> 2
执行列表10条查询 >> 10

*/

$projectIDList = array('0', '11', '41', '71');
$productIDList = array('0', '1');
$status        = array('all', 'wait', 'doing');
$type          = array('all', 'sprint', 'stage', 'kanban');
$limit         = array('0', '2', '10');
$count         = array('0', '1');

$execution = new executionTest();
r($execution->getListTest($projectIDList[1],$type[0],$status[0],$limit[0],$productIDList[0],$count[0])) && p('101:project,name')      && e('11,迭代1');         // 敏捷项目执行列表查询
r($execution->getListTest($projectIDList[2],$type[0],$status[0],$limit[0],$productIDList[0],$count[0])) && p('131:project,name,type') && e('41,阶段31,stage');  // 瀑布项目执行列表查询
r($execution->getListTest($projectIDList[3],$type[0],$status[0],$limit[0],$productIDList[0],$count[0])) && p('161:project,name,type') && e('71,看板61,kanban'); // 看板项目执行列表查询
r($execution->getListTest($projectIDList[0],$type[0],$status[0],$limit[0],$productIDList[1],$count[0])) && p('101:name')              && e('迭代1');            // 产品1执行列表查询
r($execution->getListTest($projectIDList[0],$type[1],$status[0],$limit[2],$productIDList[0],$count[0])) && p('661:type,name')         && e('sprint,迭代561');   // 敏捷执行列表查询
r($execution->getListTest($projectIDList[0],$type[2],$status[0],$limit[2],$productIDList[0],$count[0])) && p('691:type,name')         && e('stage,阶段591');    // 瀑布执行列表查询
r($execution->getListTest($projectIDList[0],$type[3],$status[0],$limit[2],$productIDList[0],$count[0])) && p('631:type,name')         && e('kanban,看板531');   // 看板执行列表查询
r($execution->getListTest($projectIDList[0],$type[0],$status[1],$limit[0],$productIDList[0],$count[0])) && p('681:status,name')       && e('wait,阶段581');     // wait执行列表查询
r($execution->getListTest($projectIDList[0],$type[0],$status[2],$limit[0],$productIDList[0],$count[0])) && p('682:status,name')       && e('doing,阶段582');    // doing执行列表查询
r($execution->getListTest($projectIDList[0],$type[0],$status[1],$limit[1],$productIDList[0],$count[1])) && p()                        && e('2');                // 执行列表2条查询
r($execution->getListTest($projectIDList[0],$type[0],$status[2],$limit[2],$productIDList[0],$count[1])) && p()                        && e('10');               // 执行列表10条查询