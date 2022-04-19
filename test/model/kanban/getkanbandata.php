#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->getKanbanData();
cid=1
pid=1

测试获取kanban1的视图 >> columns:4, lanes:1, cards:4
测试获取kanban2的视图 >> columns:4, lanes:1, cards:4
测试获取kanban3的视图 >> columns:4, lanes:1, cards:4
测试获取kanban4的视图 >> columns:4, lanes:1, cards:4
测试获取kanban5的视图 >> columns:4, lanes:1, cards:4
测试获取不存在的kanban的视图 >> columns:0, lanes:0, cards:0

*/

$kanbanIDList = array('1', '2', '3', '4', '5', '1000001');

$kanban = new kanbanTest();

r($kanban->getKanbanDataTest($kanbanIDList[0])) && p() && e('columns:4, lanes:1, cards:4'); // 测试获取kanban1的视图
r($kanban->getKanbanDataTest($kanbanIDList[1])) && p() && e('columns:4, lanes:1, cards:4'); // 测试获取kanban2的视图
r($kanban->getKanbanDataTest($kanbanIDList[2])) && p() && e('columns:4, lanes:1, cards:4'); // 测试获取kanban3的视图
r($kanban->getKanbanDataTest($kanbanIDList[3])) && p() && e('columns:4, lanes:1, cards:4'); // 测试获取kanban4的视图
r($kanban->getKanbanDataTest($kanbanIDList[4])) && p() && e('columns:4, lanes:1, cards:4'); // 测试获取kanban5的视图
r($kanban->getKanbanDataTest($kanbanIDList[5])) && p() && e('columns:0, lanes:0, cards:0'); // 测试获取不存在的kanban的视图