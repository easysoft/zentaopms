#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->getRDKanban();
cid=1
pid=1

获取执行161的执行看板 >> columns:27, lanes:3, cards:5
获取执行161 all region 101的执行看板 >> columns:27, lanes:3, cards:8
获取执行161 all region 102的执行看板 >> columns:0, lanes:0, cards:0
获取执行162的执行看板 >> columns:27, lanes:3, cards:5
获取执行162 story region 102的执行看板 >> columns:11, lanes:1, cards:2
获取执行163的执行看板 >> columns:27, lanes:3, cards:4
获取执行163 task region 103的执行看板 >> columns:7, lanes:1, cards:4
获取执行164的执行看板 >> columns:27, lanes:3, cards:4
获取执行164 bug region 104的执行看板 >> columns:9, lanes:1, cards:3
获取执行165的执行看板 >> columns:27, lanes:3, cards:0
获取执行165 story region 105的执行看板 >> columns:11, lanes:1, cards:2

*/
$executionIDList = array('161', '162', '163', '164', '165');
$browseTypeList  = array('all', 'story', 'task', 'bug');
$regionIDList    = array('0', '101', '102', '103', '104', '105');
$groupBy         = 'pri';

$kanban = new kanbanTest();

r($kanban->getRDKanbanTest($executionIDList[0]))                                       && p() && e('columns:27, lanes:3, cards:5'); // 获取执行161的执行看板
r($kanban->getRDKanbanTest($executionIDList[0], $browseTypeList[0], $regionIDList[1])) && p() && e('columns:27, lanes:3, cards:8'); // 获取执行161 all region 101的执行看板
r($kanban->getRDKanbanTest($executionIDList[0], $browseTypeList[0], $regionIDList[2])) && p() && e('columns:0, lanes:0, cards:0');  // 获取执行161 all region 102的执行看板
r($kanban->getRDKanbanTest($executionIDList[1]))                                       && p() && e('columns:27, lanes:3, cards:5'); // 获取执行162的执行看板
r($kanban->getRDKanbanTest($executionIDList[1], $browseTypeList[1], $regionIDList[2])) && p() && e('columns:11, lanes:1, cards:2'); // 获取执行162 story region 102的执行看板
r($kanban->getRDKanbanTest($executionIDList[2]))                                       && p() && e('columns:27, lanes:3, cards:4'); // 获取执行163的执行看板
r($kanban->getRDKanbanTest($executionIDList[2], $browseTypeList[2], $regionIDList[3])) && p() && e('columns:7, lanes:1, cards:4');  // 获取执行163 task region 103的执行看板
r($kanban->getRDKanbanTest($executionIDList[3]))                                       && p() && e('columns:27, lanes:3, cards:4'); // 获取执行164的执行看板
r($kanban->getRDKanbanTest($executionIDList[3], $browseTypeList[3], $regionIDList[4])) && p() && e('columns:9, lanes:1, cards:3');  // 获取执行164 bug region 104的执行看板
r($kanban->getRDKanbanTest($executionIDList[4]))                                       && p() && e('columns:27, lanes:3, cards:0'); // 获取执行165的执行看板
r($kanban->getRDKanbanTest($executionIDList[4], $browseTypeList[1], $regionIDList[5])) && p() && e('columns:11, lanes:1, cards:2'); // 获取执行165 story region 105的执行看板