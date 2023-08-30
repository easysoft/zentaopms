#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('project')->config('project')->gen(5);
zdTable('story')->gen(10);
zdTable('bug')->gen(10);
zdTable('task')->config('task')->gen(10);
zdTable('kanbanlane')->gen(0);
zdTable('kanbancell')->gen(0);
zdTable('kanbancolumn')->gen(0);

/**

title=测试 kanbanModel->getExecutionKanban();
timeout=0
cid=1

*/

$executionIDList = array(2, 3, 4);
$browseTypeList  = array('story', 'task', 'bug');
$groupByList     = array('pri', 'category', 'module', 'source', 'assignedTo', 'story', 'severity');

$kanban = new kanbanTest();

r($kanban->getExecutionKanbanTest($executionIDList[0]))                                      && p() && e('columns:27, lanes:3, cards:0'); // 测试获取execution2的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[0], $browseTypeList[0]))                  && p() && e('columns:11, lanes:1, cards:0'); // 测试获取execution2 story的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[0], $browseTypeList[1]))                  && p() && e('columns:7, lanes:1, cards:0');  // 测试获取execution2 task的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[0], $browseTypeList[2]))                  && p() && e('columns:9, lanes:1, cards:0');  // 测试获取execution2 bug的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[1]))                                      && p() && e('columns:27, lanes:3, cards:8'); // 测试获取execution3的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[1], $browseTypeList[0], $groupByList[0])) && p() && e('columns:11, lanes:1, cards:0'); // 测试获取execution3 story pri的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[1], $browseTypeList[0], $groupByList[1])) && p() && e('columns:11, lanes:1, cards:0'); // 测试获取execution3 story category的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[2]))                                      && p() && e('columns:27, lanes:3, cards:0'); // 测试获取execution4的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[2], $browseTypeList[1], $groupByList[2])) && p() && e('columns:7, lanes:1, cards:0');  // 测试获取execution4 task module的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[2], $browseTypeList[0], $groupByList[3])) && p() && e('columns:11, lanes:1, cards:0'); // 测试获取execution4 story source的执行看板信息
