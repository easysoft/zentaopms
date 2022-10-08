#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->getGroupBySpaceList();
cid=1
pid=1

获取空间1 2 3的看板 >> 6
获取空间1 2 3 看板1,2,3,4的看板 >> 2
获取空间4,5,6的看板 >> 6
获取空间4,5,6 看板7,8,11,12的看板 >> 2
获取空间7,8,9的看板 >> 6
获取空间7,8,9 看板15,16,17,18的看板 >> 2
获取空间10,11,12的看板 >> 6
获取空间10,11,12 看板19,20,21,22,23,24,25的看板 >> 3
获取空间13,14,15的看板 >> 6
获取空间13,14,15 看板26,28,30的看板 >> 3

*/

$spaceIDList  = array('1,2,3', '4,5,6', '7,8,9', '10,11,12', '13,14,15');
$kanbanIDList = array('1,2,3,4', '7,8,11,12', '15,16,17,18', '19,20,21,22,23,24,25', '26,28,30');

$kanban = new kanbanTest();

r($kanban->getGroupBySpaceListTest($spaceIDList[0]))                   && p() && e('6'); // 获取空间1 2 3的看板
r($kanban->getGroupBySpaceListTest($spaceIDList[0], $kanbanIDList[0])) && p() && e('2'); // 获取空间1 2 3 看板1,2,3,4的看板
r($kanban->getGroupBySpaceListTest($spaceIDList[1]))                   && p() && e('6'); // 获取空间4,5,6的看板
r($kanban->getGroupBySpaceListTest($spaceIDList[1], $kanbanIDList[1])) && p() && e('2'); // 获取空间4,5,6 看板7,8,11,12的看板
r($kanban->getGroupBySpaceListTest($spaceIDList[2]))                   && p() && e('6'); // 获取空间7,8,9的看板
r($kanban->getGroupBySpaceListTest($spaceIDList[2], $kanbanIDList[2])) && p() && e('2'); // 获取空间7,8,9 看板15,16,17,18的看板
r($kanban->getGroupBySpaceListTest($spaceIDList[3]))                   && p() && e('6'); // 获取空间10,11,12的看板
r($kanban->getGroupBySpaceListTest($spaceIDList[3], $kanbanIDList[3])) && p() && e('3'); // 获取空间10,11,12 看板19,20,21,22,23,24,25的看板
r($kanban->getGroupBySpaceListTest($spaceIDList[4]))                   && p() && e('6'); // 获取空间13,14,15的看板
r($kanban->getGroupBySpaceListTest($spaceIDList[4], $kanbanIDList[4])) && p() && e('3'); // 获取空间13,14,15 看板26,28,30的看板