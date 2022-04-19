#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->getKanbanCardMenu();
cid=1
pid=1

测试获取执行101 story的操作数量 >> 12
测试获取执行101 task的操作数量 >> 60
测试获取执行101 bug的操作数量 >> 23
测试获取执行102 story的操作数量 >> 12
测试获取执行102 task的操作数量 >> 26
测试获取执行102 bug的操作数量 >> 22
测试获取执行103 story的操作数量 >> 12
测试获取执行103 task的操作数量 >> 25
测试获取执行103 bug的操作数量 >> 23
测试获取执行104 story的操作数量 >> 12
测试获取执行104 task的操作数量 >> 26
测试获取执行104 bug的操作数量 >> 22
测试获取执行105 story的操作数量 >> 12
测试获取执行105 task的操作数量 >> 25
测试获取执行105 bug的操作数量 >> 23

*/

$executionIDList = array('101', '102', '103', '104', '105');
$browseTypeList  = array('story', 'task', 'bug');

$kanban = new kanbanTest();

r($kanban->getKanbanCardMenuTest($executionIDList[0], $browseTypeList[0])) && p() && e('12'); // 测试获取执行101 story的操作数量
r($kanban->getKanbanCardMenuTest($executionIDList[0], $browseTypeList[1])) && p() && e('60'); // 测试获取执行101 task的操作数量
r($kanban->getKanbanCardMenuTest($executionIDList[0], $browseTypeList[2])) && p() && e('23'); // 测试获取执行101 bug的操作数量
r($kanban->getKanbanCardMenuTest($executionIDList[1], $browseTypeList[0])) && p() && e('12'); // 测试获取执行102 story的操作数量
r($kanban->getKanbanCardMenuTest($executionIDList[1], $browseTypeList[1])) && p() && e('26'); // 测试获取执行102 task的操作数量
r($kanban->getKanbanCardMenuTest($executionIDList[1], $browseTypeList[2])) && p() && e('22'); // 测试获取执行102 bug的操作数量
r($kanban->getKanbanCardMenuTest($executionIDList[2], $browseTypeList[0])) && p() && e('12'); // 测试获取执行103 story的操作数量
r($kanban->getKanbanCardMenuTest($executionIDList[2], $browseTypeList[1])) && p() && e('25'); // 测试获取执行103 task的操作数量
r($kanban->getKanbanCardMenuTest($executionIDList[2], $browseTypeList[2])) && p() && e('23'); // 测试获取执行103 bug的操作数量
r($kanban->getKanbanCardMenuTest($executionIDList[3], $browseTypeList[0])) && p() && e('12'); // 测试获取执行104 story的操作数量
r($kanban->getKanbanCardMenuTest($executionIDList[3], $browseTypeList[1])) && p() && e('26'); // 测试获取执行104 task的操作数量
r($kanban->getKanbanCardMenuTest($executionIDList[3], $browseTypeList[2])) && p() && e('22'); // 测试获取执行104 bug的操作数量
r($kanban->getKanbanCardMenuTest($executionIDList[4], $browseTypeList[0])) && p() && e('12'); // 测试获取执行105 story的操作数量
r($kanban->getKanbanCardMenuTest($executionIDList[4], $browseTypeList[1])) && p() && e('25'); // 测试获取执行105 task的操作数量
r($kanban->getKanbanCardMenuTest($executionIDList[4], $browseTypeList[2])) && p() && e('23'); // 测试获取执行105 bug的操作数量