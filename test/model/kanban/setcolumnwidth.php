#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/kanban.class.php';
su('admin');

/**

title=测试 kanbanModel->setColumnWidth();
cid=1
pid=1

测试修改看板1的fluidBoard值 >> 通用看板1,1
测试修改看板2的fluidBoard值 >> 通用看板2,1
测试修改看板3的fluidBoard值 >> 通用看板3,1
测试修改看板4的fluidBoard值 >> 迭代1,1
测试修改看板5的fluidBoard值 >> 迭代2,1
测试修改不存在的看板的fluidBoard值 >> 0

*/

$fromExecution = 'execution';

$kanbanIDList = array('1', '2', '3', '101', '102', '10001');
$fluidBoard   = 1;

$kanban = new kanbanTest();

r($kanban->setColumnWidthTest($kanbanIDList[0], $fluidBoard))                 && p('name,fluidBoard') && e('通用看板1,1'); // 测试修改看板1的fluidBoard值
r($kanban->setColumnWidthTest($kanbanIDList[1], $fluidBoard))                 && p('name,fluidBoard') && e('通用看板2,1'); // 测试修改看板2的fluidBoard值
r($kanban->setColumnWidthTest($kanbanIDList[2], $fluidBoard))                 && p('name,fluidBoard') && e('通用看板3,1'); // 测试修改看板3的fluidBoard值
r($kanban->setColumnWidthTest($kanbanIDList[3], $fluidBoard, $fromExecution)) && p('name,fluidBoard') && e('迭代1,1');     // 测试修改看板4的fluidBoard值
r($kanban->setColumnWidthTest($kanbanIDList[4], $fluidBoard, $fromExecution)) && p('name,fluidBoard') && e('迭代2,1');     // 测试修改看板5的fluidBoard值
r($kanban->setColumnWidthTest($kanbanIDList[5], $fluidBoard))                 && p('name,fluidBoard') && e('0');            // 测试修改不存在的看板的fluidBoard值
