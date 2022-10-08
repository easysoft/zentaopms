#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->restoreColumn();
cid=1
pid=1

还原卡片1 >> 未开始,0
还原卡片1 >> 进行中,0
还原卡片2 >> 已完成,0
还原卡片3 >> 已关闭,0
还原卡片4 >> 未开始,0

*/

$columnIDList = array('1', '2', '3', '4', '5');

$kanban = new kanbanTest();

$kanban->archiveColumnTest($columnIDList[0]);
$kanban->archiveColumnTest($columnIDList[1]);
$kanban->archiveColumnTest($columnIDList[2]);
$kanban->archiveColumnTest($columnIDList[3]);
$kanban->archiveColumnTest($columnIDList[4]);

r($kanban->restoreColumnTest($columnIDList[0])) && p('name,archived') && e('未开始,0'); // 还原卡片1
r($kanban->restoreColumnTest($columnIDList[1])) && p('name,archived') && e('进行中,0'); // 还原卡片1
r($kanban->restoreColumnTest($columnIDList[2])) && p('name,archived') && e('已完成,0'); // 还原卡片2
r($kanban->restoreColumnTest($columnIDList[3])) && p('name,archived') && e('已关闭,0'); // 还原卡片3
r($kanban->restoreColumnTest($columnIDList[4])) && p('name,archived') && e('未开始,0'); // 还原卡片4