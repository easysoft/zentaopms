#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->getExecutionKanban();
cid=1
pid=1

测试获取execution101的执行看板信息 >> columns:27, lanes:3, cards:15
测试获取execution101 story的执行看板信息 >> columns:11, lanes:1, cards:0
测试获取execution101 task的执行看板信息 >> columns:7, lanes:1, cards:11
测试获取execution101 bug的执行看板信息 >> columns:9, lanes:1, cards:4
测试获取execution102的执行看板信息 >> columns:27, lanes:3, cards:10
测试获取execution102 story pri的执行看板信息 >> columns:11, lanes:3, cards:2
测试获取execution102 story category的执行看板信息 >> columns:11, lanes:2, cards:2
测试获取execution103的执行看板信息 >> columns:27, lanes:3, cards:10
测试获取execution103 task module的执行看板信息 >> columns:7, lanes:5, cards:4
测试获取execution103 story source的执行看板信息 >> columns:11, lanes:3, cards:2
测试获取execution103的执行看板信息 >> columns:27, lanes:3, cards:10
测试获取execution103 bug module的执行看板信息 >> columns:9, lanes:2, cards:4
测试获取execution103 bug source的执行看板信息 >> columns:9, lanes:5, cards:4
测试获取execution103的执行看板信息 >> columns:27, lanes:3, cards:10
测试获取execution103 bug module的执行看板信息 >> columns:9, lanes:5, cards:4
测试获取execution103 bug severity的执行看板信息 >> columns:9, lanes:4, cards:4

*/

$executionIDList = array('101', '102', '103', '104', '105');
$browseTypeList  = array('story', 'task', 'bug');
$groupByList     = array('pri', 'category', 'module', 'source', 'assignedTo', 'story', 'severity');

$kanban = new kanbanTest();

r($kanban->getExecutionKanbanTest($executionIDList[0]))                                      && p() && e('columns:27, lanes:3, cards:15'); // 测试获取execution101的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[0], $browseTypeList[0]))                  && p() && e('columns:11, lanes:1, cards:0');  // 测试获取execution101 story的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[0], $browseTypeList[1]))                  && p() && e('columns:7, lanes:1, cards:11');  // 测试获取execution101 task的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[0], $browseTypeList[2]))                  && p() && e('columns:9, lanes:1, cards:4');   // 测试获取execution101 bug的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[1]))                                      && p() && e('columns:27, lanes:3, cards:10'); // 测试获取execution102的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[1], $browseTypeList[0], $groupByList[0])) && p() && e('columns:11, lanes:3, cards:2');  // 测试获取execution102 story pri的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[1], $browseTypeList[0], $groupByList[1])) && p() && e('columns:11, lanes:2, cards:2');  // 测试获取execution102 story category的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[2]))                                      && p() && e('columns:27, lanes:3, cards:10'); // 测试获取execution103的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[2], $browseTypeList[1], $groupByList[2])) && p() && e('columns:7, lanes:5, cards:4');   // 测试获取execution103 task module的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[2], $browseTypeList[0], $groupByList[3])) && p() && e('columns:11, lanes:3, cards:2');  // 测试获取execution103 story source的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[3]))                                      && p() && e('columns:27, lanes:3, cards:10'); // 测试获取execution103的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[3], $browseTypeList[2], $groupByList[4])) && p() && e('columns:9, lanes:2, cards:4');   // 测试获取execution103 bug module的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[3], $browseTypeList[2], $groupByList[5])) && p() && e('columns:9, lanes:5, cards:4');   // 测试获取execution103 bug source的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[4]))                                      && p() && e('columns:27, lanes:3, cards:10'); // 测试获取execution103的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[4], $browseTypeList[2], $groupByList[5])) && p() && e('columns:9, lanes:5, cards:4');   // 测试获取execution103 bug module的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[4], $browseTypeList[2], $groupByList[6])) && p() && e('columns:9, lanes:4, cards:4');   // 测试获取execution103 bug severity的执行看板信息
