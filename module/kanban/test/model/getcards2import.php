#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbancard')->gen(100);

/**

title=测试 kanbanModel->getCards2Import();
timeout=0
cid=1

- 测试获取可以导入的卡片数量 @100
- 测试获取看板1可以导入的卡片数量 @8
- 测试获取看板1可以导入的卡片数量 排除看板1 @0
- 测试获取看板2可以导入的卡片数量 @8
- 测试获取看板2可以导入的卡片数量 排除看板1 @8
- 测试获取看板3可以导入的卡片数量 @8
- 测试获取看板3可以导入的卡片数量 排除看板1 @8
- 测试获取看板4可以导入的卡片数量 @8
- 测试获取看板4可以导入的卡片数量 排除看板1 @8
- 测试获取看板5可以导入的卡片数量 @8
- 测试获取看板5可以导入的卡片数量 @8
- 测试获取不存在看板可以导入的卡片数量 排除看板1 @0
- 测试获取不存在看板可以导入的卡片数量 排除看板1 @0

*/
$kanbanIDList = array('1', '2', '3', '4', '5', '1000001');
$excludedID   = 1;

$kanban = new kanbanTest();

r($kanban->getCards2ImportTest())                              && p() && e('100'); // 测试获取可以导入的卡片数量
r($kanban->getCards2ImportTest($kanbanIDList[0]))              && p() && e('8');   // 测试获取看板1可以导入的卡片数量
r($kanban->getCards2ImportTest($kanbanIDList[0], $excludedID)) && p() && e('0');   // 测试获取看板1可以导入的卡片数量 排除看板1
r($kanban->getCards2ImportTest($kanbanIDList[1]))              && p() && e('8');   // 测试获取看板2可以导入的卡片数量
r($kanban->getCards2ImportTest($kanbanIDList[1], $excludedID)) && p() && e('8');   // 测试获取看板2可以导入的卡片数量 排除看板1
r($kanban->getCards2ImportTest($kanbanIDList[2]))              && p() && e('8');   // 测试获取看板3可以导入的卡片数量
r($kanban->getCards2ImportTest($kanbanIDList[2], $excludedID)) && p() && e('8');   // 测试获取看板3可以导入的卡片数量 排除看板1
r($kanban->getCards2ImportTest($kanbanIDList[3]))              && p() && e('8');   // 测试获取看板4可以导入的卡片数量
r($kanban->getCards2ImportTest($kanbanIDList[3], $excludedID)) && p() && e('8');   // 测试获取看板4可以导入的卡片数量 排除看板1
r($kanban->getCards2ImportTest($kanbanIDList[4]))              && p() && e('8');   // 测试获取看板5可以导入的卡片数量
r($kanban->getCards2ImportTest($kanbanIDList[4]))              && p() && e('8');   // 测试获取看板5可以导入的卡片数量
r($kanban->getCards2ImportTest($kanbanIDList[5], $excludedID)) && p() && e('0');   // 测试获取不存在看板可以导入的卡片数量 排除看板1
r($kanban->getCards2ImportTest($kanbanIDList[5], $excludedID)) && p() && e('0');   // 测试获取不存在看板可以导入的卡片数量 排除看板1