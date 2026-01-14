#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('project')->loadYaml('kanbanexecution')->gen(5);
zenData('kanbanregion')->loadYaml('rdkanbanregion')->gen(5);
zenData('kanbangroup')->loadYaml('rdkanbangroup')->gen(20);
zenData('kanbancolumn')->gen(20);
zenData('kanbanlane')->loadYaml('rdkanbanlane')->gen(10);
zenData('kanbancell')->loadYaml('rdkanbancell')->gen(20);
zenData('story')->gen(0);
zenData('task')->loadYaml('rdkanbantask')->gen(20);
zenData('bug')->gen(0);

/**

title=测试 kanbanModel->getExecutionKanban();
timeout=0
cid=16916

- 测试获取execution2的执行看板信息 @columns:0, lanes:0, cards:0

- 测试获取execution2 story的执行看板信息 @columns:0, lanes:0, cards:0

- 测试获取execution2 task的执行看板信息 @columns:0, lanes:0, cards:0

- 测试获取execution2 bug的执行看板信息 @columns:10, lanes:1, cards:0

- 测试获取execution3的执行看板信息 @columns:0, lanes:0, cards:0

- 测试获取execution3 story pri的执行看板信息 @columns:0, lanes:0, cards:0

- 测试获取execution3 story category的执行看板信息 @columns:0, lanes:0, cards:0

- 测试获取execution4的执行看板信息 @columns:0, lanes:0, cards:0

- 测试获取execution4 task module的执行看板信息 @columns:0, lanes:0, cards:0

- 测试获取execution4 story source的执行看板信息 @columns:0, lanes:0, cards:0

*/

$executionIDList = array(2, 3, 4);
$browseTypeList  = array('story', 'task', 'bug');
$groupByList     = array('pri', 'category', 'module', 'source', 'assignedTo', 'story', 'severity');

$kanban = new kanbanModelTest();

r($kanban->getExecutionKanbanTest($executionIDList[0]))                                      && p() && e('columns:0, lanes:0, cards:0');   // 测试获取execution2的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[0], $browseTypeList[0]))                  && p() && e('columns:0, lanes:0, cards:0');   // 测试获取execution2 story的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[0], $browseTypeList[1]))                  && p() && e('columns:0, lanes:0, cards:0');   // 测试获取execution2 task的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[0], $browseTypeList[2]))                  && p() && e('columns:10, lanes:1, cards:0');   // 测试获取execution2 bug的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[1]))                                      && p() && e('columns:0, lanes:0, cards:0');   // 测试获取execution3的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[1], $browseTypeList[0], $groupByList[0])) && p() && e('columns:0, lanes:0, cards:0');   // 测试获取execution3 story pri的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[1], $browseTypeList[0], $groupByList[1])) && p() && e('columns:0, lanes:0, cards:0');   // 测试获取execution3 story category的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[2]))                                      && p() && e('columns:0, lanes:0, cards:0');   // 测试获取execution4的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[2], $browseTypeList[1], $groupByList[2])) && p() && e('columns:0, lanes:0, cards:0'); // 测试获取execution4 task module的执行看板信息
r($kanban->getExecutionKanbanTest($executionIDList[2], $browseTypeList[0], $groupByList[3])) && p() && e('columns:0, lanes:0, cards:0');   // 测试获取execution4 story source的执行看板信息