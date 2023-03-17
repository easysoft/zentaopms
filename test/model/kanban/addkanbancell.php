#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->addKanbanCell();
cid=1
pid=1

更新类型为common的kanbancell >> common,,0,1,2,
更新类型为common的kanbancell 有cardID >> common,,3,0,1,2,
插入类型为common的kanbancell >> common,
插入类型为common的kanbancell 有cardID >> common,,3,
更新类型为story的kanbancell >> story,,0,
更新类型为story的kanbancell 有cardID >> story,,3,0,
插入类型为story的kanbancell >> story,
插入类型为story的kanbancell 有cardID >> story,,3,
更新类型为bug的kanbancell >> bug,,0,
更新类型为bug的kanbancell 有cardID >> bug,,3,0,
插入类型为bug的kanbancell >> bug,
插入类型为bug的kanbancell 有cardID >> bug,,3,
更新类型为task的kanbancell >> task,,0,
更新类型为task的kanbancell 有cardID >> task,,3,0,
插入类型为task的kanbancell >> task,
插入类型为task的kanbancell 有cardID >> task,,3,

*/

$kanban = new kanbanTest();

$kanbanIDList = array('1', '161');
$laneIDList   = array('1', '101', '102', '103');
$columnIDList = array('1', '408', '412', '421');
$typeList     = array('common', 'story', 'bug', 'task');
$cardID       = '3';

r($kanban->addKanbanCellTest($kanbanIDList[0], $laneIDList[0], $columnIDList[0], $typeList[0]))          && p('type,cards') && e('common,,0,1,2,');   // 更新类型为common的kanbancell
r($kanban->addKanbanCellTest($kanbanIDList[0], $laneIDList[0], $columnIDList[0], $typeList[0], $cardID)) && p('type,cards') && e('common,,3,0,1,2,'); // 更新类型为common的kanbancell 有cardID
r($kanban->addKanbanCellTest($kanbanIDList[0], $laneIDList[1], $columnIDList[0], $typeList[0]))          && p('type,cards') && e('common,');          // 插入类型为common的kanbancell
r($kanban->addKanbanCellTest($kanbanIDList[0], $laneIDList[1], $columnIDList[0], $typeList[0], $cardID)) && p('type,cards') && e('common,,3,');       // 插入类型为common的kanbancell 有cardID
r($kanban->addKanbanCellTest($kanbanIDList[1], $laneIDList[1], $columnIDList[1], $typeList[1]))          && p('type,cards') && e('story,,0,');        // 更新类型为story的kanbancell
r($kanban->addKanbanCellTest($kanbanIDList[1], $laneIDList[1], $columnIDList[1], $typeList[1], $cardID)) && p('type,cards') && e('story,,3,0,');      // 更新类型为story的kanbancell 有cardID
r($kanban->addKanbanCellTest($kanbanIDList[1], $laneIDList[1], $columnIDList[2], $typeList[1]))          && p('type,cards') && e('story,');           // 插入类型为story的kanbancell
r($kanban->addKanbanCellTest($kanbanIDList[1], $laneIDList[1], $columnIDList[2], $typeList[1], $cardID)) && p('type,cards') && e('story,,3,');        // 插入类型为story的kanbancell 有cardID
r($kanban->addKanbanCellTest($kanbanIDList[1], $laneIDList[2], $columnIDList[2], $typeList[2]))          && p('type,cards') && e('bug,,0,');          // 更新类型为bug的kanbancell
r($kanban->addKanbanCellTest($kanbanIDList[1], $laneIDList[2], $columnIDList[2], $typeList[2], $cardID)) && p('type,cards') && e('bug,,3,0,');        // 更新类型为bug的kanbancell 有cardID
r($kanban->addKanbanCellTest($kanbanIDList[1], $laneIDList[2], $columnIDList[3], $typeList[2]))          && p('type,cards') && e('bug,');             // 插入类型为bug的kanbancell
r($kanban->addKanbanCellTest($kanbanIDList[1], $laneIDList[2], $columnIDList[3], $typeList[2], $cardID)) && p('type,cards') && e('bug,,3,');          // 插入类型为bug的kanbancell 有cardID
r($kanban->addKanbanCellTest($kanbanIDList[1], $laneIDList[3], $columnIDList[3], $typeList[3]))          && p('type,cards') && e('task,,0,');         // 更新类型为task的kanbancell
r($kanban->addKanbanCellTest($kanbanIDList[1], $laneIDList[3], $columnIDList[3], $typeList[3], $cardID)) && p('type,cards') && e('task,,3,0,');       // 更新类型为task的kanbancell 有cardID
r($kanban->addKanbanCellTest($kanbanIDList[1], $laneIDList[3], $columnIDList[0], $typeList[3]))          && p('type,cards') && e('task,');            // 插入类型为task的kanbancell
r($kanban->addKanbanCellTest($kanbanIDList[1], $laneIDList[3], $columnIDList[0], $typeList[3], $cardID)) && p('type,cards') && e('task,,3,');         // 插入类型为task的kanbancell 有cardID
