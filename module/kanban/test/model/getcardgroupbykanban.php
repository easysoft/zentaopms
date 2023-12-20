#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbancard')->gen(100);
zdTable('kanbancell')->gen(100);

/**

title=测试 kanbanModel->getCardGroupByKanban();
timeout=0
cid=1

- 测试查询看板1的卡片数量 @1
- 测试查询看板2的卡片数量 @1
- 测试查询看板3的卡片数量 @1
- 测试查询看板4的卡片数量 @1
- 测试查询看板5的卡片数量 @1
- 测试查询不存在看板的卡片数量 @0

*/

$kanbanIDList = array('1', '2', '3', '4', '5', '1000001');

$kanban = new kanbanTest();

r($kanban->getCardGroupByKanbanTest($kanbanIDList[0])) && p() && e('1'); // 测试查询看板1的卡片数量
r($kanban->getCardGroupByKanbanTest($kanbanIDList[1])) && p() && e('1'); // 测试查询看板2的卡片数量
r($kanban->getCardGroupByKanbanTest($kanbanIDList[2])) && p() && e('1'); // 测试查询看板3的卡片数量
r($kanban->getCardGroupByKanbanTest($kanbanIDList[3])) && p() && e('1'); // 测试查询看板4的卡片数量
r($kanban->getCardGroupByKanbanTest($kanbanIDList[4])) && p() && e('1'); // 测试查询看板5的卡片数量
r($kanban->getCardGroupByKanbanTest($kanbanIDList[5])) && p() && e('0'); // 测试查询不存在看板的卡片数量