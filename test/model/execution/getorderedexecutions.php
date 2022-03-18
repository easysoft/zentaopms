#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=executionModel->getOrderedExecutions();
cid=1
pid=1

敏捷项目wait状态执行查看 >> 11,wait,sprint
敏捷项目doing状态执行查看 >> 12,doing,sprint
瀑布项目wait状态执行查看 >> 41,wait,stage
瀑布项目doing状态执行查看 >> 42,doing,stage
看板项目wait状态执行查看 >> 71,wait,kanban
看板项目doing状态执行查看 >> 72,doing,kanban
敏捷项目wait状态执行统计 >> 7
敏捷项目doing状态执行统计 >> 7
瀑布项目wait状态执行统计 >> 8
瀑布项目doing状态执行统计 >> 8
看板项目wait状态执行统计 >> 6
看板项目doing状态执行统计 >> 6
敏捷项目done状态执行统计 >> 0
敏捷项目closed状态执行统计 >> 0

*/

$projectIDList = array('11', '12', '41', '42', '71', '72');
$status        = array('wait', 'doing', 'done', 'closed');
$count         = array('0', '1');

$execution = new executionTest();
//var_dump($execution->getOrderedExecutionsTest($projectIDList[5],$status[1],$count[0]));die;
r($execution->getOrderedExecutionsTest($projectIDList[0],$status[0],$count[0])) && p('101:project,status,type') && e('11,wait,sprint');  //敏捷项目wait状态执行查看
r($execution->getOrderedExecutionsTest($projectIDList[1],$status[1],$count[0])) && p('102:project,status,type') && e('12,doing,sprint'); //敏捷项目doing状态执行查看
r($execution->getOrderedExecutionsTest($projectIDList[2],$status[0],$count[0])) && p('701:project,status,type') && e('41,wait,stage');   //瀑布项目wait状态执行查看
r($execution->getOrderedExecutionsTest($projectIDList[3],$status[1],$count[0])) && p('702:project,status,type') && e('42,doing,stage');  //瀑布项目doing状态执行查看
r($execution->getOrderedExecutionsTest($projectIDList[4],$status[0],$count[0])) && p('161:project,status,type') && e('71,wait,kanban');  //看板项目wait状态执行查看
r($execution->getOrderedExecutionsTest($projectIDList[5],$status[1],$count[0])) && p('162:project,status,type') && e('72,doing,kanban'); //看板项目doing状态执行查看
r($execution->getOrderedExecutionsTest($projectIDList[0],$status[0],$count[1])) && p()                          && e('7');               //敏捷项目wait状态执行统计
r($execution->getOrderedExecutionsTest($projectIDList[1],$status[1],$count[1])) && p()                          && e('7');               //敏捷项目doing状态执行统计
r($execution->getOrderedExecutionsTest($projectIDList[2],$status[0],$count[1])) && p()                          && e('8');               //瀑布项目wait状态执行统计
r($execution->getOrderedExecutionsTest($projectIDList[3],$status[1],$count[1])) && p()                          && e('8');               //瀑布项目doing状态执行统计
r($execution->getOrderedExecutionsTest($projectIDList[4],$status[0],$count[1])) && p()                          && e('6');               //看板项目wait状态执行统计
r($execution->getOrderedExecutionsTest($projectIDList[5],$status[1],$count[1])) && p()                          && e('6');               //看板项目doing状态执行统计
r($execution->getOrderedExecutionsTest($projectIDList[0],$status[2],$count[1])) && p()                          && e('0');               //敏捷项目done状态执行统计
r($execution->getOrderedExecutionsTest($projectIDList[0],$status[3],$count[1])) && p()                          && e('0');               //敏捷项目closed状态执行统计