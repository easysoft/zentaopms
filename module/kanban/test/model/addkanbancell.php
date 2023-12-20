#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('kanbancell')->gen(0);

/**

title=测试 kanbanModel->addKanbanCell();
timeout=0
cid=1

- 更新类型为common的kanbancell
 - 属性type @common
 - 属性cards @,1,
- 更新类型为common的kanbancell 有cardID
 - 属性type @common
 - 属性cards @,3,1,
- 插入类型为common的kanbancell
 - 属性type @common
 - 属性cards @~~
- 插入类型为common的kanbancell 有cardID
 - 属性type @common
 - 属性cards @,3,
- 更新类型为story的kanbancell
 - 属性type @story
 - 属性cards @,2,
- 更新类型为story的kanbancell 有cardID
 - 属性type @story
 - 属性cards @,3,2,
- 插入类型为story的kanbancell
 - 属性type @story
 - 属性cards @~~
- 插入类型为story的kanbancell 有cardID
 - 属性type @story
 - 属性cards @,3,
- 插入类型为bug的kanbancell
 - 属性type @bug
 - 属性cards @~~
- 插入类型为bug的kanbancell 有cardID
 - 属性type @bug
 - 属性cards @,3,
- 插入类型为task的kanbancell
 - 属性type @task
 - 属性cards @~~
- 插入类型为task的kanbancell 有cardID
 - 属性type @task
 - 属性cards @,3,

*/

$kanban = new kanbanTest();

$kanbanIDList = array('1', '161');
$laneIDList   = array('1', '101', '102', '103');
$columnIDList = array('1', '408', '412', '421');
$typeList     = array('common', 'story', 'bug', 'task');
$cardID       = '3';

r($kanban->addKanbanCellTest($kanbanIDList[0], $laneIDList[0], $columnIDList[0], $typeList[0], 1))       && p('type|cards', '|') && e('common|,1,');   // 更新类型为common的kanbancell
r($kanban->addKanbanCellTest($kanbanIDList[0], $laneIDList[0], $columnIDList[0], $typeList[0], $cardID)) && p('type|cards', '|') && e('common|,3,1,'); // 更新类型为common的kanbancell 有cardID
r($kanban->addKanbanCellTest($kanbanIDList[0], $laneIDList[1], $columnIDList[0], $typeList[0]))          && p('type|cards', '|') && e('common|~~');    // 插入类型为common的kanbancell
r($kanban->addKanbanCellTest($kanbanIDList[0], $laneIDList[1], $columnIDList[0], $typeList[0], $cardID)) && p('type|cards', '|') && e('common|,3,');   // 插入类型为common的kanbancell 有cardID
r($kanban->addKanbanCellTest($kanbanIDList[1], $laneIDList[1], $columnIDList[1], $typeList[1], 2))       && p('type|cards', '|') && e('story|,2,');    // 更新类型为story的kanbancell
r($kanban->addKanbanCellTest($kanbanIDList[1], $laneIDList[1], $columnIDList[1], $typeList[1], $cardID)) && p('type|cards', '|') && e('story|,3,2,');  // 更新类型为story的kanbancell 有cardID
r($kanban->addKanbanCellTest($kanbanIDList[1], $laneIDList[1], $columnIDList[2], $typeList[1]))          && p('type|cards', '|') && e('story|~~');     // 插入类型为story的kanbancell
r($kanban->addKanbanCellTest($kanbanIDList[1], $laneIDList[1], $columnIDList[2], $typeList[1], $cardID)) && p('type|cards', '|') && e('story|,3,');    // 插入类型为story的kanbancell 有cardID
r($kanban->addKanbanCellTest($kanbanIDList[1], $laneIDList[2], $columnIDList[3], $typeList[2]))          && p('type|cards', '|') && e('bug|~~');       // 插入类型为bug的kanbancell
r($kanban->addKanbanCellTest($kanbanIDList[1], $laneIDList[2], $columnIDList[3], $typeList[2], $cardID)) && p('type|cards', '|') && e('bug|,3,');      // 插入类型为bug的kanbancell 有cardID
r($kanban->addKanbanCellTest($kanbanIDList[1], $laneIDList[3], $columnIDList[0], $typeList[3]))          && p('type|cards', '|') && e('task|~~');      // 插入类型为task的kanbancell
r($kanban->addKanbanCellTest($kanbanIDList[1], $laneIDList[3], $columnIDList[0], $typeList[3], $cardID)) && p('type|cards', '|') && e('task|,3,');     // 插入类型为task的kanbancell 有cardID