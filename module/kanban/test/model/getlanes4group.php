#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

zenData('user')->gen(10);
zenData('taskteam')->gen(10);
zenData('module')->gen(10);
zenData('story')->gen(20);
zenData('projectstory')->gen(20);
zenData('product')->gen(20);
zenData('bug')->gen(20);
zenData('project')->loadYaml('kanbanexecution')->gen(5);
zenData('kanbanregion')->loadYaml('rdkanbanregion')->gen(5);
zenData('kanbangroup')->loadYaml('rdkanbangroup')->gen(20);
zenData('kanbancolumn')->gen(20);
zenData('kanbanlane')->loadYaml('rdkanbanlane')->gen(10);
zenData('kanbancell')->loadYaml('rdkanbancell')->gen(20);
zenData('task')->loadYaml('rdkanbantask')->gen(20);

su('admin');

/**

title=测试 kanbanModel->getLanes4Group();
timeout=0
cid=16933

- 获取执行1 story pri的泳道 @,优先级: 无
- 获取执行1 story category的泳道 @,功能
- 获取执行1 story module的泳道 @,所属模块: 无
- 获取执行1 story source的泳道 @,来源: 无
- 获取执行2 task pri的泳道 @,1,2,3,4,优先级: 无
- 获取执行2 task module的泳道 @,所属模块: 无
- 获取执行2 task assignedTo的泳道 @,指派给: 无
- 获取执行1 bug pri的泳道 @,优先级: 无
- 获取执行1 bug module的泳道 @,所属模块: 无
- 获取执行1 bug assignedTo的泳道 @,指派给: 无
- 获取执行1 bug severity的泳道 @,严重程度: 无
- 获取执行1 story pri的泳道 @,优先级: 无
- 获取执行1 story category的泳道 @,功能
- 获取执行1 story module的泳道 @,所属模块: 无
- 获取执行1 story source的泳道 @,来源: 无
- 获取执行2 task pri的泳道 @,1,2,3,4,优先级: 无
- 获取执行2 task module的泳道 @,所属模块: 无
- 获取执行2 task assignedTo的泳道 @,指派给: 无

*/
$executionIDList = array('1', '2', '3', '4', '5');
$browseTypeList  = array('story', 'task', 'bug');
$groupByList     = array('pri', 'category', 'module', 'source', 'assignedTo', 'story', 'severity');

$kanban = new kanbanTest();

r($kanban->getLanes4GroupTest($executionIDList[0], $browseTypeList[0], $groupByList[0])) && p('', '|') && e(',优先级: 无');         // 获取执行1 story pri的泳道
r($kanban->getLanes4GroupTest($executionIDList[0], $browseTypeList[0], $groupByList[1])) && p('', '|') && e(',功能');               // 获取执行1 story category的泳道
r($kanban->getLanes4GroupTest($executionIDList[0], $browseTypeList[0], $groupByList[2])) && p('', '|') && e(',所属模块: 无');       // 获取执行1 story module的泳道
r($kanban->getLanes4GroupTest($executionIDList[0], $browseTypeList[0], $groupByList[3])) && p('', '|') && e(',来源: 无');           // 获取执行1 story source的泳道
r($kanban->getLanes4GroupTest($executionIDList[1], $browseTypeList[1], $groupByList[0])) && p('', '|') && e(',1,2,3,4,优先级: 无'); // 获取执行2 task pri的泳道
r($kanban->getLanes4GroupTest($executionIDList[1], $browseTypeList[1], $groupByList[2])) && p('', '|') && e(',所属模块: 无');       // 获取执行2 task module的泳道
r($kanban->getLanes4GroupTest($executionIDList[1], $browseTypeList[1], $groupByList[4])) && p('', '|') && e(',指派给: 无');         // 获取执行2 task assignedTo的泳道
r($kanban->getLanes4GroupTest($executionIDList[2], $browseTypeList[2], $groupByList[0])) && p('', '|') && e(',优先级: 无');         // 获取执行1 bug pri的泳道
r($kanban->getLanes4GroupTest($executionIDList[2], $browseTypeList[2], $groupByList[2])) && p('', '|') && e(',所属模块: 无');       // 获取执行1 bug module的泳道
r($kanban->getLanes4GroupTest($executionIDList[2], $browseTypeList[2], $groupByList[4])) && p('', '|') && e(',指派给: 无');         // 获取执行1 bug assignedTo的泳道
r($kanban->getLanes4GroupTest($executionIDList[2], $browseTypeList[2], $groupByList[6])) && p('', '|') && e(',严重程度: 无');       // 获取执行1 bug severity的泳道
r($kanban->getLanes4GroupTest($executionIDList[3], $browseTypeList[0], $groupByList[0])) && p('', '|') && e(',优先级: 无');         // 获取执行1 story pri的泳道
r($kanban->getLanes4GroupTest($executionIDList[3], $browseTypeList[0], $groupByList[1])) && p('', '|') && e(',功能');               // 获取执行1 story category的泳道
r($kanban->getLanes4GroupTest($executionIDList[3], $browseTypeList[0], $groupByList[2])) && p('', '|') && e(',所属模块: 无');       // 获取执行1 story module的泳道
r($kanban->getLanes4GroupTest($executionIDList[3], $browseTypeList[0], $groupByList[3])) && p('', '|') && e(',来源: 无');           // 获取执行1 story source的泳道
r($kanban->getLanes4GroupTest($executionIDList[4], $browseTypeList[1], $groupByList[0])) && p('', '|') && e(',1,2,3,4,优先级: 无'); // 获取执行2 task pri的泳道
r($kanban->getLanes4GroupTest($executionIDList[4], $browseTypeList[1], $groupByList[2])) && p('', '|') && e(',所属模块: 无');       // 获取执行2 task module的泳道
r($kanban->getLanes4GroupTest($executionIDList[4], $browseTypeList[1], $groupByList[4])) && p('', '|') && e(',指派给: 无');         // 获取执行2 task assignedTo的泳道