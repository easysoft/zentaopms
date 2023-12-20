#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('project')->config('kanbanexecution')->gen(5);
zdTable('kanbanregion')->config('rdkanbanregion')->gen(5);
zdTable('kanbangroup')->config('rdkanbangroup')->gen(20);
zdTable('kanbancolumn')->gen(20);
zdTable('kanbanlane')->config('rdkanbanlane')->gen(10);
zdTable('kanbancell')->config('rdkanbancell')->gen(20);
zdTable('task')->config('rdkanbantask')->gen(20);

/**

title=测试 kanbanModel->getExecutionKanban();
timeout=0
cid=1

- 测试获取execution2的执行看板信息 @columns:2, lanes:2, cards:59

- 测试获取execution2 story的执行看板信息 @columns:1, lanes:1, cards:0

- 测试获取execution2 task的执行看板信息 @columns:1, lanes:1, cards:59

- 测试获取execution2 bug的执行看板信息 @columns:10, lanes:1, cards:0

- 测试获取execution3的执行看板信息 @columns:2, lanes:2, cards:53

- 测试获取execution3 story pri的执行看板信息 @columns:0, lanes:1, cards:0

- 测试获取execution3 story category的执行看板信息 @columns:0, lanes:1, cards:0

- 测试获取execution4的执行看板信息 @columns:2, lanes:2, cards:53

- 测试获取execution4 task module的执行看板信息 @columns:2, lanes:1, cards:107

- 测试获取execution4 story source的执行看板信息 @columns:0, lanes:1, cards:0

*/

$executionIDList = array(2, 3, 4);
$browseTypeList  = array('story', 'task', 'bug');
$groupByList     = array('pri', 'category', 'module', 'source', 'assignedTo', 'story', 'severity');

$kanban = new kanbanTest();

r($kanban->getExecutionKanbanTest($executionIDList[0]))                                      && p() && e('columns:2, lanes:2, cards:59');  // 测试获取execution2的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[0], $browseTypeList[0]))                  && p() && e('columns:1, lanes:1, cards:0');   // 测试获取execution2 story的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[0], $browseTypeList[1]))                  && p() && e('columns:1, lanes:1, cards:59');  // 测试获取execution2 task的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[0], $browseTypeList[2]))                  && p() && e('columns:10, lanes:1, cards:0');  // 测试获取execution2 bug的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[1]))                                      && p() && e('columns:2, lanes:2, cards:53');  // 测试获取execution3的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[1], $browseTypeList[0], $groupByList[0])) && p() && e('columns:0, lanes:1, cards:0');   // 测试获取execution3 story pri的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[1], $browseTypeList[0], $groupByList[1])) && p() && e('columns:0, lanes:1, cards:0');   // 测试获取execution3 story category的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[2]))                                      && p() && e('columns:2, lanes:2, cards:53');  // 测试获取execution4的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[2], $browseTypeList[1], $groupByList[2])) && p() && e('columns:2, lanes:1, cards:107'); // 测试获取execution4 task module的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[2], $browseTypeList[0], $groupByList[3])) && p() && e('columns:0, lanes:1, cards:0');   // 测试获取execution4 story source的执行看板信息