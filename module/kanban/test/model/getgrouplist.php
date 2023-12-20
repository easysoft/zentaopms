#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbangroup')->gen(10);

/**

title=测试 kanbanModel->getGroupList();
timeout=0
cid=1

- 获取区域1的看板 @,1

- 获取区域1的看板 @,2

- 获取不存在的看板 @0

*/

$regionIDList = array('1', '2', '10001');

$kanban = new kanbanTest();

r($kanban->getGroupListTest($regionIDList[0])) && p() && e(',1'); // 获取区域1的看板
r($kanban->getGroupListTest($regionIDList[1])) && p() && e(',2'); // 获取区域1的看板
r($kanban->getGroupListTest($regionIDList[2])) && p() && e('0');  // 获取不存在的看板