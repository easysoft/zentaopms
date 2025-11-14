#!/usr/bin/env php
<?php

/**

title=测试 kanbanModel::importObject();
timeout=0
cid=16948

- 执行kanban模块的importObjectTest方法，参数是1, 1, 1, 1, 'productplan', $param1 属性cards @,2,3,4,
- 执行kanban模块的importObjectTest方法，参数是2, 2, 2, 5, 'release', $param2 属性cards @,5,6,7,
- 执行kanban模块的importObjectTest方法，参数是3, 3, 3, 9, 'execution', $param3 属性cards @,8,9,10,
- 执行kanban模块的importObjectTest方法，参数是4, 4, 4, 13, 'build', $param4 属性cards @,11,12,13,
- 执行kanban模块的importObjectTest方法，参数是5, 5, 5, 17, 'task', $emptyParam 属性cards @,,
- 执行kanban模块的importObjectTest方法，参数是1, 1, 1, 1, 'task', $param6 属性cards @,14,2,3,4,
- 执行kanban模块的importObjectTest方法，参数是2, 2, 2, 5, 'story', $param7 属性cards @,15,16,17,18,19,20,21,22,5,6,7,

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

$table = zenData('kanbancard');
$table->id->range('1-100');
$table->kanban->range('1-5');
$table->region->range('1-5');
$table->group->range('1-5');
$table->fromID->range('70-100');
$table->fromType->range('productplan,release,execution,build');
$table->name->range('');
$table->status->range('doing');
$table->createdBy->range('admin');
$table->createdDate->range('`2023-01-01 00:00:00`');
$table->gen(1);

$cellTable = zenData('kanbancell');
$cellTable->id->range('1-10');
$cellTable->kanban->range('1-5');
$cellTable->lane->range('1-5');
$cellTable->column->range('1,5,9,13,17');
$cellTable->type->range('common');
$cellTable->cards->range('');
$cellTable->gen(5);

su('admin');

$kanban = new kanbanTest();

// 测试用例1：正常导入产品计划对象
$productplans = array('72', '73', '74');
$param1 = array('productplans' => $productplans, 'targetLane' => '1');
r($kanban->importObjectTest(1, 1, 1, 1, 'productplan', $param1)) && p('cards', '|') && e(',2,3,4,');

// 测试用例2：正常导入发布对象
$releases = array('75', '76', '77');
$param2 = array('releases' => $releases, 'targetLane' => '2');
r($kanban->importObjectTest(2, 2, 2, 5, 'release', $param2)) && p('cards', '|') && e(',5,6,7,');

// 测试用例3：正常导入执行对象
$executions = array('82', '83', '84');
$param3 = array('executions' => $executions, 'targetLane' => '3');
r($kanban->importObjectTest(3, 3, 3, 9, 'execution', $param3)) && p('cards', '|') && e(',8,9,10,');

// 测试用例4：正常导入构建对象
$builds = array('85', '86', '87');
$param4 = array('builds' => $builds, 'targetLane' => '4');
r($kanban->importObjectTest(4, 4, 4, 13, 'build', $param4)) && p('cards', '|') && e(',11,12,13,');

// 测试用例5：空对象列表导入测试
$emptyParam = array('tasks' => array(), 'targetLane' => '5');
r($kanban->importObjectTest(5, 5, 5, 17, 'task', $emptyParam)) && p('cards', '|') && e(',,');

// 测试用例6：单个对象导入测试
$singleTask = array('100');
$param6 = array('tasks' => $singleTask, 'targetLane' => '1');
r($kanban->importObjectTest(1, 1, 1, 1, 'task', $param6)) && p('cards', '|') && e(',14,2,3,4,');

// 测试用例7：大量对象导入测试（边界测试）
$manyObjects = array('101', '102', '103', '104', '105', '106', '107', '108');
$param7 = array('storys' => $manyObjects, 'targetLane' => '2');
r($kanban->importObjectTest(2, 2, 2, 5, 'story', $param7)) && p('cards', '|') && e(',15,16,17,18,19,20,21,22,5,6,7,');