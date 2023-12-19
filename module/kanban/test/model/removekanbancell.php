#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbancell')->gen(50);

/**

title=测试 kanbanModel->removeKanbanCell();
timeout=0
cid=1

- 正常移除卡片 @1:2,801,; 2:3,4,803,; 3:5,6,805,; 4:7,8,807,; 17:33,34,833,; 18:35,36,835,; 19:37,38,837,; 20:39,40,839,
- 正常移除卡片 @1:2,801,; 2:803,; 3:5,6,805,; 4:7,8,807,; 17:33,34,833,; 18:35,36,835,; 19:37,38,837,; 20:39,40,839,
- 移除不存在的卡片 @0
- 移除不存在的卡片 @0

*/

$typeList   = array('common', 'story', 'bug', 'task');
$cardIDList = array(array('1'), array('3', '4'), '244', '300');
$kanbanList = array('1' => '1', '3' => '1', '4' => '1', '244' => '161', '300' => '5');

$kanban = new kanbanTest();

r($kanban->removeKanbanCellTest($typeList[0], $cardIDList[0], $kanbanList)) && p('', '|') && e('1:2,801,; 2:3,4,803,; 3:5,6,805,; 4:7,8,807,; 17:33,34,833,; 18:35,36,835,; 19:37,38,837,; 20:39,40,839,'); // 正常移除卡片
r($kanban->removeKanbanCellTest($typeList[0], $cardIDList[1], $kanbanList)) && p('', '|') && e('1:2,801,; 2:803,; 3:5,6,805,; 4:7,8,807,; 17:33,34,833,; 18:35,36,835,; 19:37,38,837,; 20:39,40,839,');     // 正常移除卡片
r($kanban->removeKanbanCellTest($typeList[1], $cardIDList[2], $kanbanList)) && p('', '|') && e('0'); // 移除不存在的卡片
r($kanban->removeKanbanCellTest($typeList[1], $cardIDList[3], $kanbanList)) && p('', '|') && e('0'); // 移除不存在的卡片