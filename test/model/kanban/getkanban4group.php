#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->getKanban4Group();
cid=1
pid=1

获取执行101 类型story 分组pri的看板信息 >> lanes:3
获取执行101 类型story 分组category的看板信息 >> lanes:2
获取执行101 类型story 分组module的看板信息 >> lanes:3
获取执行101 类型story 分组source的看板信息 >> lanes:3
获取执行102 类型task 分组pri的看板信息 >> lanes:4
获取执行102 类型task 分组module的看板信息 >> lanes:5
获取执行102 类型task 分组assignedTo的看板信息 >> lanes:1
获取执行102 类型task 分组story的看板信息 >> lanes:2
获取执行103 类型bug 分组pri的看板信息 >> lanes:4
获取执行103 类型bug 分组module的看板信息 >> lanes:4
获取执行103 类型bug 分组assignedTo的看板信息 >> lanes:3
获取执行103 类型bug 分组severity的看板信息 >> lanes:4
获取执行104 类型story 分组pri的看板信息 >> lanes:3
获取执行105 类型story 分组pri的看板信息 >> lanes:3

*/

$executionIDList = array('101', '102', '103', '104', '105');
$browseTypeList  = array('story', 'task', 'bug');
$groupByList     = array('pri', 'category', 'module', 'source', 'assignedTo', 'story', 'severity');

$kanban = new kanbanTest();

r($kanban->getKanban4GroupTest($executionIDList[0], $browseTypeList[0], $groupByList[0])) && p() && e('lanes:3'); //获取执行101 类型story 分组pri的看板信息
r($kanban->getKanban4GroupTest($executionIDList[0], $browseTypeList[0], $groupByList[1])) && p() && e('lanes:2'); //获取执行101 类型story 分组category的看板信息
r($kanban->getKanban4GroupTest($executionIDList[0], $browseTypeList[0], $groupByList[2])) && p() && e('lanes:3'); //获取执行101 类型story 分组module的看板信息
r($kanban->getKanban4GroupTest($executionIDList[0], $browseTypeList[0], $groupByList[3])) && p() && e('lanes:3'); //获取执行101 类型story 分组source的看板信息
r($kanban->getKanban4GroupTest($executionIDList[1], $browseTypeList[1], $groupByList[0])) && p() && e('lanes:4'); //获取执行102 类型task 分组pri的看板信息
r($kanban->getKanban4GroupTest($executionIDList[1], $browseTypeList[1], $groupByList[2])) && p() && e('lanes:5'); //获取执行102 类型task 分组module的看板信息
r($kanban->getKanban4GroupTest($executionIDList[1], $browseTypeList[1], $groupByList[4])) && p() && e('lanes:1'); //获取执行102 类型task 分组assignedTo的看板信息
r($kanban->getKanban4GroupTest($executionIDList[1], $browseTypeList[1], $groupByList[5])) && p() && e('lanes:2'); //获取执行102 类型task 分组story的看板信息
r($kanban->getKanban4GroupTest($executionIDList[2], $browseTypeList[2], $groupByList[0])) && p() && e('lanes:4'); //获取执行103 类型bug 分组pri的看板信息
r($kanban->getKanban4GroupTest($executionIDList[2], $browseTypeList[2], $groupByList[2])) && p() && e('lanes:4'); //获取执行103 类型bug 分组module的看板信息
r($kanban->getKanban4GroupTest($executionIDList[2], $browseTypeList[2], $groupByList[4])) && p() && e('lanes:3'); //获取执行103 类型bug 分组assignedTo的看板信息
r($kanban->getKanban4GroupTest($executionIDList[2], $browseTypeList[2], $groupByList[6])) && p() && e('lanes:4'); //获取执行103 类型bug 分组severity的看板信息
r($kanban->getKanban4GroupTest($executionIDList[3], $browseTypeList[0], $groupByList[0])) && p() && e('lanes:3'); //获取执行104 类型story 分组pri的看板信息
r($kanban->getKanban4GroupTest($executionIDList[4], $browseTypeList[0], $groupByList[0])) && p() && e('lanes:3'); //获取执行105 类型story 分组pri的看板信息
