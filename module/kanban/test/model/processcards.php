#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbancolumn')->gen(4);
zdTable('kanbancell')->gen(4);

/**

title=测试 kanbanModel->processCards();
timeout=0
cid=1

- 处理列1的卡片 @2:,3,4,803,1,2,801,3:,5,6,805,4:,7,8,807,
- 处理列2的卡片 @1:,1,2,801,3,4,803,1,2,801,3:,5,6,805,4:,7,8,807,
- 处理列3的卡片 @1:,1,2,801,3,4,803,1,2,801,5,6,805,2:,3,4,803,1,2,801,4:,7,8,807,
- 处理列4的卡片 @1:,1,2,801,3,4,803,1,2,801,5,6,805,7,8,807,2:,3,4,803,1,2,801,3:,5,6,805,

*/

$columnIDList = array('1', '2', '3', '4');

$kanban = new kanbanTest();

r($kanban->processCardsTest($columnIDList[0])) && p('', '|') && e('2:,3,4,803,1,2,801,3:,5,6,805,4:,7,8,807,');         // 处理列1的卡片
r($kanban->processCardsTest($columnIDList[1])) && p('', '|') && e('1:,1,2,801,3,4,803,1,2,801,3:,5,6,805,4:,7,8,807,'); // 处理列2的卡片
r($kanban->processCardsTest($columnIDList[2])) && p('', '|') && e('1:,1,2,801,3,4,803,1,2,801,5,6,805,2:,3,4,803,1,2,801,4:,7,8,807,');         // 处理列3的卡片
r($kanban->processCardsTest($columnIDList[3])) && p('', '|') && e('1:,1,2,801,3,4,803,1,2,801,5,6,805,7,8,807,2:,3,4,803,1,2,801,3:,5,6,805,'); // 处理列4的卡片