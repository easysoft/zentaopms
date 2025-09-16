#!/usr/bin/env php
<?php

/**

title=测试 programZen::getProgramList4Kanban();
timeout=0
cid=0

- 执行programTest模块的getProgramList4KanbanTest方法，参数是'my'  @array
- 执行programTest模块的getProgramList4KanbanTest方法，参数是'other'  @array
- 执行programTest模块的getProgramList4KanbanTest方法，参数是''  @array
- 执行programTest模块的getProgramList4KanbanTest方法，参数是'invalid'  @array
- 执行programTest模块的getProgramList4KanbanTest方法，参数是'my'  @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';

$table = zenData('project');
$table->loadYaml('project_getprogramlist4kanban', false, 2);
$table->gen(10);

$product = zenData('product');
$product->id->range('1-3');
$product->program->range('1,2,3');
$product->name->range('产品1,产品2,产品3');
$product->deleted->range('0');
$product->gen(3);

$team = zenData('team');
$team->root->range('1,2,3');
$team->type->range('program');
$team->account->range('admin');
$team->gen(3);

su('admin');

$programTest = new programTest();

r($programTest->getProgramList4KanbanTest('my')) && p() && e('array');
r($programTest->getProgramList4KanbanTest('other')) && p() && e('array');
r($programTest->getProgramList4KanbanTest('')) && p() && e('array');
r($programTest->getProgramList4KanbanTest('invalid')) && p() && e('array');
r(count($programTest->getProgramList4KanbanTest('my'))) && p() && e('2');