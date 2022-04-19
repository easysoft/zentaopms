#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->getLaneById();
cid=1
pid=1

测试通过id1获取泳道信息 >> 默认泳道,common,1,1
测试通过id2获取泳道信息 >> 默认泳道,common,2,2
测试通过id3获取泳道信息 >> 默认泳道,common,3,3
测试通过id4获取泳道信息 >> 默认泳道,common,4,4
测试通过id5获取泳道信息 >> 默认泳道,common,5,5
测试通过不存在的id获取泳道信息 >> 0

*/

$laneIDList = array('1', '2', '3', '4', '5', '1000001');

$kanban = new kanbanTest();

r($kanban->getLaneByIdTest($laneIDList[0])) && p('name,type,region,group') && e('默认泳道,common,1,1'); // 测试通过id1获取泳道信息
r($kanban->getLaneByIdTest($laneIDList[1])) && p('name,type,region,group') && e('默认泳道,common,2,2'); // 测试通过id2获取泳道信息
r($kanban->getLaneByIdTest($laneIDList[2])) && p('name,type,region,group') && e('默认泳道,common,3,3'); // 测试通过id3获取泳道信息
r($kanban->getLaneByIdTest($laneIDList[3])) && p('name,type,region,group') && e('默认泳道,common,4,4'); // 测试通过id4获取泳道信息
r($kanban->getLaneByIdTest($laneIDList[4])) && p('name,type,region,group') && e('默认泳道,common,5,5'); // 测试通过id5获取泳道信息
r($kanban->getLaneByIdTest($laneIDList[5])) && p('name,type,region,group') && e('0');                   // 测试通过不存在的id获取泳道信息