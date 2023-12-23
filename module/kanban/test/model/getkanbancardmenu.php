#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('story')->gen(20);
zdTable('storyreview')->gen(50);
zdTable('bug')->gen(20);
zdTable('project')->config('kanbanexecution')->gen(5);
zdTable('task')->config('rdkanbantask')->gen(20);

/**

title=测试 kanbanModel->getKanbanCardMenu();
timeout=0
cid=1

- 测试获取执行1 story的操作数量 @11
- 测试获取执行1 task的操作数量 @24
- 测试获取执行1 bug的操作数量 @0
- 测试获取执行2 story的操作数量 @0
- 测试获取执行2 task的操作数量 @28
- 测试获取执行2 bug的操作数量 @0
- 测试获取执行3 story的操作数量 @0
- 测试获取执行3 task的操作数量 @24
- 测试获取执行3 bug的操作数量 @0
- 测试获取执行4 story的操作数量 @0
- 测试获取执行4 task的操作数量 @24
- 测试获取执行4 bug的操作数量 @0
- 测试获取执行5 story的操作数量 @0
- 测试获取执行5 task的操作数量 @24
- 测试获取执行5 bug的操作数量 @0

*/

$executionIDList = array('1', '2', '3', '4', '5');
$browseTypeList  = array('story', 'task', 'bug');

$kanban = new kanbanTest();

r($kanban->getKanbanCardMenuTest($executionIDList[0], $browseTypeList[0])) && p() && e('11'); // 测试获取执行1 story的操作数量
r($kanban->getKanbanCardMenuTest($executionIDList[0], $browseTypeList[1])) && p() && e('24'); // 测试获取执行1 task的操作数量
r($kanban->getKanbanCardMenuTest($executionIDList[0], $browseTypeList[2])) && p() && e('0');  // 测试获取执行1 bug的操作数量
r($kanban->getKanbanCardMenuTest($executionIDList[1], $browseTypeList[0])) && p() && e('0');  // 测试获取执行2 story的操作数量
r($kanban->getKanbanCardMenuTest($executionIDList[1], $browseTypeList[1])) && p() && e('28'); // 测试获取执行2 task的操作数量
r($kanban->getKanbanCardMenuTest($executionIDList[1], $browseTypeList[2])) && p() && e('0');  // 测试获取执行2 bug的操作数量
r($kanban->getKanbanCardMenuTest($executionIDList[2], $browseTypeList[0])) && p() && e('0');  // 测试获取执行3 story的操作数量
r($kanban->getKanbanCardMenuTest($executionIDList[2], $browseTypeList[1])) && p() && e('24'); // 测试获取执行3 task的操作数量
r($kanban->getKanbanCardMenuTest($executionIDList[2], $browseTypeList[2])) && p() && e('0');  // 测试获取执行3 bug的操作数量
r($kanban->getKanbanCardMenuTest($executionIDList[3], $browseTypeList[0])) && p() && e('0');  // 测试获取执行4 story的操作数量
r($kanban->getKanbanCardMenuTest($executionIDList[3], $browseTypeList[1])) && p() && e('24'); // 测试获取执行4 task的操作数量
r($kanban->getKanbanCardMenuTest($executionIDList[3], $browseTypeList[2])) && p() && e('0');  // 测试获取执行4 bug的操作数量
r($kanban->getKanbanCardMenuTest($executionIDList[4], $browseTypeList[0])) && p() && e('0');  // 测试获取执行5 story的操作数量
r($kanban->getKanbanCardMenuTest($executionIDList[4], $browseTypeList[1])) && p() && e('24'); // 测试获取执行5 task的操作数量
r($kanban->getKanbanCardMenuTest($executionIDList[4], $browseTypeList[2])) && p() && e('0');  // 测试获取执行5 bug的操作数量
