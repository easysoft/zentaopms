#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->getObjectGroup();
cid=1
pid=1

获取执行101 story pri的分组 >> 4,2
获取执行101 story category的分组 >> feature
获取执行101 story module的分组 >> 1824,1822
获取执行101 story source的分组 >> user,market
获取执行102 task pri的分组 >> 4,2,1
获取执行102 task module的分组 >> 36,33,30,24
获取执行102 task assignedTo的分组 >> 0
获取执行102 task story的分组 >> 8,6,0
获取执行101 bug pri的分组 >> 4,3,1
获取执行101 bug module的分组 >> 1833,1832,1831,0
获取执行101 bug assignedTo的分组 >> test1,admin
获取执行101 bug severity的分组 >> 4,3,1
获取执行101 story pri的分组 >> 4,2
获取执行101 story category的分组 >> feature
获取执行101 story module的分组 >> 1836,1834
获取执行101 story source的分组 >> user,other
获取执行102 task pri的分组 >> 3,2,1
获取执行102 task module的分组 >> 63,60,57,33
获取执行102 task assignedTo的分组 >> 0
获取执行102 task story的分组 >> 20,18,0

*/
$executionIDList = array('101', '102', '103', '104', '105');
$browseTypeList  = array('story', 'task', 'bug');
$groupByList     = array('pri', 'category', 'module', 'source', 'assignedTo', 'story', 'severity');

$kanban = new kanbanTest();

r($kanban->getObjectGroupTest($executionIDList[0], $browseTypeList[0], $groupByList[0])) && p() && e('4,2');              // 获取执行101 story pri的分组
r($kanban->getObjectGroupTest($executionIDList[0], $browseTypeList[0], $groupByList[1])) && p() && e('feature');          // 获取执行101 story category的分组
r($kanban->getObjectGroupTest($executionIDList[0], $browseTypeList[0], $groupByList[2])) && p() && e('1824,1822');        // 获取执行101 story module的分组
r($kanban->getObjectGroupTest($executionIDList[0], $browseTypeList[0], $groupByList[3])) && p() && e('user,market');      // 获取执行101 story source的分组
r($kanban->getObjectGroupTest($executionIDList[1], $browseTypeList[1], $groupByList[0])) && p() && e('4,2,1');            // 获取执行102 task pri的分组
r($kanban->getObjectGroupTest($executionIDList[1], $browseTypeList[1], $groupByList[2])) && p() && e('36,33,30,24');      // 获取执行102 task module的分组
r($kanban->getObjectGroupTest($executionIDList[1], $browseTypeList[1], $groupByList[4])) && p() && e('0');                // 获取执行102 task assignedTo的分组
r($kanban->getObjectGroupTest($executionIDList[1], $browseTypeList[1], $groupByList[5])) && p() && e('8,6,0');            // 获取执行102 task story的分组
r($kanban->getObjectGroupTest($executionIDList[2], $browseTypeList[2], $groupByList[0])) && p() && e('4,3,1');            // 获取执行101 bug pri的分组
r($kanban->getObjectGroupTest($executionIDList[2], $browseTypeList[2], $groupByList[2])) && p() && e('1833,1832,1831,0'); // 获取执行101 bug module的分组
r($kanban->getObjectGroupTest($executionIDList[2], $browseTypeList[2], $groupByList[4])) && p() && e('test1,admin');      // 获取执行101 bug assignedTo的分组
r($kanban->getObjectGroupTest($executionIDList[2], $browseTypeList[2], $groupByList[6])) && p() && e('4,3,1');            // 获取执行101 bug severity的分组
r($kanban->getObjectGroupTest($executionIDList[3], $browseTypeList[0], $groupByList[0])) && p() && e('4,2');              // 获取执行101 story pri的分组
r($kanban->getObjectGroupTest($executionIDList[3], $browseTypeList[0], $groupByList[1])) && p() && e('feature');          // 获取执行101 story category的分组
r($kanban->getObjectGroupTest($executionIDList[3], $browseTypeList[0], $groupByList[2])) && p() && e('1836,1834');        // 获取执行101 story module的分组
r($kanban->getObjectGroupTest($executionIDList[3], $browseTypeList[0], $groupByList[3])) && p() && e('user,other');       // 获取执行101 story source的分组
r($kanban->getObjectGroupTest($executionIDList[4], $browseTypeList[1], $groupByList[0])) && p() && e('3,2,1');            // 获取执行102 task pri的分组
r($kanban->getObjectGroupTest($executionIDList[4], $browseTypeList[1], $groupByList[2])) && p() && e('63,60,57,33');      // 获取执行102 task module的分组
r($kanban->getObjectGroupTest($executionIDList[4], $browseTypeList[1], $groupByList[4])) && p() && e('0');                // 获取执行102 task assignedTo的分组
r($kanban->getObjectGroupTest($executionIDList[4], $browseTypeList[1], $groupByList[5])) && p() && e('20,18,0');          // 获取执行102 task story的分组