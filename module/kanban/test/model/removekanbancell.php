#!/usr/bin/env php
<?php

/**

title=测试 kanbanModel::removeKanbanCell();
timeout=0
cid=16953

- 正常移除单个卡片 @1:2,801,; 2:3,4,803,; 3:5,6,805,; 4:7,8,807,
- 正常移除多个卡片 @1:2,801,; 2:803,; 3:5,6,805,; 4:7,8,807,
- 移除不存在的卡片ID @1:2,801,; 2:803,; 3:5,6,805,; 4:7,8,807,
- 使用空卡片ID列表 @0
- 使用无效type参数 @0
- 移除卡片后清理空格子 @1; 2:803,; 3:5,6,805,; 4:7,8,807,
- 使用空kanbanList参数 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('kanbancell')->gen(50);

su('admin');

$kanban = new kanbanModelTest();

r($kanban->removeKanbanCellTest('common', 1, array('1' => '1'))) && p('', '|') && e('1:2,801,; 2:3,4,803,; 3:5,6,805,; 4:7,8,807,'); // 正常移除单个卡片
r($kanban->removeKanbanCellTest('common', array(3, 4), array('3' => '1', '4' => '1'))) && p('', '|') && e('1:2,801,; 2:803,; 3:5,6,805,; 4:7,8,807,'); // 正常移除多个卡片
r($kanban->removeKanbanCellTest('common', 999, array('999' => '1'))) && p('', '|') && e('1:2,801,; 2:803,; 3:5,6,805,; 4:7,8,807,'); // 移除不存在的卡片ID
r($kanban->removeKanbanCellTest('common', array(), array())) && p('', '|') && e('0'); // 使用空卡片ID列表
r($kanban->removeKanbanCellTest('invalid_type', 5, array('5' => '1'))) && p('', '|') && e('0'); // 使用无效type参数
r($kanban->removeKanbanCellTest('common', array(2, 801), array('2' => '1', '801' => '1'))) && p('', '|') && e('1; 2:803,; 3:5,6,805,; 4:7,8,807,'); // 移除卡片后清理空格子
r($kanban->removeKanbanCellTest('common', 0, array())) && p('', '|') && e('0'); // 使用空kanbanList参数