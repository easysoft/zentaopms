#!/usr/bin/env php
<?php

/**

title=测试 bugZen::assignKanbanVars();
timeout=0
cid=0

- 执行bugTest模块的assignKanbanVarsTest方法，参数是$execution1, $output1  @1
- 执行bugTest模块的assignKanbanVarsTest方法，参数是$execution1, $output2  @1
- 执行bugTest模块的assignKanbanVarsTest方法，参数是$execution1, $output3  @1
- 执行bugTest模块的assignKanbanVarsTest方法，参数是$execution1, $output4  @1
- 执行bugTest模块的assignKanbanVarsTest方法，参数是$execution1, $output5  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

$table = zenData('kanbanregion');
$table->id->range('1-10');
$table->kanban->range('1-5');
$table->name->range('Region1,Region2,Region3,Region4,Region5');
$table->deleted->range('0');
$table->gen(5);

$table = zenData('kanbanlane');
$table->id->range('1-15');
$table->region->range('1-5');
$table->type->range('bug');
$table->name->range('Lane1,Lane2,Lane3,Lane4,Lane5');
$table->deleted->range('0');
$table->gen(10);

su('admin');

$bugTest = new bugTest();

$execution1 = new stdclass();
$execution1->id = 1;
$execution1->type = 'kanban';

$output1 = array('regionID' => 1, 'groupID' => 1, 'laneID' => 1);
$output2 = array('groupID' => 1);
$output3 = array('regionID' => 1);
$output4 = array();
$output5 = array('regionID' => 1, 'laneID' => 1);

r($bugTest->assignKanbanVarsTest($execution1, $output1)) && p() && e('1');
r($bugTest->assignKanbanVarsTest($execution1, $output2)) && p() && e('1');
r($bugTest->assignKanbanVarsTest($execution1, $output3)) && p() && e('1');
r($bugTest->assignKanbanVarsTest($execution1, $output4)) && p() && e('1');
r($bugTest->assignKanbanVarsTest($execution1, $output5)) && p() && e('1');