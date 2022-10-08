#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->getGroupList();
cid=1
pid=1

获取区域1的看板 >> ,1
获取区域1的看板 >> ,2
获取区域1的看板 >> ,179,180,181
获取区域1的看板 >> ,182,183,184
获取区域1的看板 >> ,185,186,187
获取区域1的看板 >> 0

*/

$regionIDList     = array('1', '2', '107', '108', '109', '10001');

$kanban = new kanbanTest();

r($kanban->getGroupListTest($regionIDList[0])) && p() && e(',1');           // 获取区域1的看板
r($kanban->getGroupListTest($regionIDList[1])) && p() && e(',2');           // 获取区域1的看板
r($kanban->getGroupListTest($regionIDList[2])) && p() && e(',179,180,181'); // 获取区域1的看板
r($kanban->getGroupListTest($regionIDList[3])) && p() && e(',182,183,184'); // 获取区域1的看板
r($kanban->getGroupListTest($regionIDList[4])) && p() && e(',185,186,187'); // 获取区域1的看板
r($kanban->getGroupListTest($regionIDList[5])) && p() && e('0');            // 获取区域1的看板