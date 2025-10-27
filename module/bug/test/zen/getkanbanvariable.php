#!/usr/bin/env php
<?php

/**

title=测试 bugZen::getKanbanVariable();
timeout=0
cid=0

- 执行$result
 -  @1
 - 属性1 @5
- 执行$result @2
- 执行$result
 -  @0
 - 属性1 @0
- 执行$result
 -  @3
 - 属性1 @5
- 执行$result
 -  @999
 - 属性1 @10

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

global $tester, $app;
$app->rawModule = 'bug';
$app->rawMethod = 'browse';

// zendata数据准备
$laneTable = zenData('kanbanlane');
$laneTable->loadYaml('kanbanlane_getkanbanvariable', false, 2)->gen(10);

$columnTable = zenData('kanbancolumn');
$columnTable->loadYaml('kanbancolumn_getkanbanvariable', false, 2)->gen(20);

$zen = initReference('bug');
$func = $zen->getMethod('getKanbanVariable');

// 测试步骤1：正常的output参数包含laneID和columnID
$output1 = array('laneID' => 1, 'columnID' => 5);
$result = $func->invokeArgs($zen->newInstance(), [$output1]);
r($result) && p('0,1') && e('1,5');

// 测试步骤2：output参数只包含laneID无columnID
$output2 = array('laneID' => 2);
$result = $func->invokeArgs($zen->newInstance(), [$output2]);
r($result) && p('0') && e('2');

// 测试步骤3：空的output参数
$output3 = array();
$result = $func->invokeArgs($zen->newInstance(), [$output3]);
r($result) && p('0,1') && e('0,0');

// 测试步骤4：POST参数lane覆盖output中的laneID
$_POST['lane'] = 3;
$output4 = array('laneID' => 1, 'columnID' => 5);
$result = $func->invokeArgs($zen->newInstance(), [$output4]);
r($result) && p('0,1') && e('3,5');
unset($_POST['lane']);

// 测试步骤5：无效的laneID但有columnID
$output5 = array('laneID' => 999, 'columnID' => 10);
$result = $func->invokeArgs($zen->newInstance(), [$output5]);
r($result) && p('0,1') && e('999,10');