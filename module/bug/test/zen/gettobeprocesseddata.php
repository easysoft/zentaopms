#!/usr/bin/env php
<?php

/**

title=测试 bugZen::getToBeProcessedData();
timeout=0
cid=15461

- 执行bugTest模块的getToBeProcessedDataTest方法，参数是$bug, $oldBug 
 -  @0
 - 属性1 @0
 - 属性2 @0
- 执行bugTest模块的getToBeProcessedDataTest方法，参数是$bug, $oldBug  @1
- 执行bugTest模块的getToBeProcessedDataTest方法，参数是$bug, $oldBug 属性2 @1
- 执行bugTest模块的getToBeProcessedDataTest方法，参数是$bug, $oldBug 属性1 @1
- 执行bugTest模块的getToBeProcessedDataTest方法，参数是$bug, $oldBug 
 - 属性1 @2
 - 属性2 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

zenData('bug')->gen(10);
zenData('task')->gen(5);
zenData('productplan')->gen(5);

su('admin');

$bugTest = new bugTest();

// 测试步骤1：正常情况，无状态变化，无计划变化
$bug = (object)array('id' => 1, 'status' => 'active', 'plan' => 1, 'toTask' => 0);
$oldBug = (object)array('status' => 'active', 'plan' => 1, 'toTask' => 0);
r($bugTest->getToBeProcessedDataTest($bug, $oldBug)) && p('0,1,2') && e('0,0,0');

// 测试步骤2：转任务且状态发生变化
$bug = (object)array('id' => 2, 'status' => 'resolved', 'plan' => 1, 'toTask' => 0);
$oldBug = (object)array('status' => 'active', 'plan' => 1, 'toTask' => 5);
r($bugTest->getToBeProcessedDataTest($bug, $oldBug)) && p('0') && e('1');

// 测试步骤3：计划从无到有的变化
$bug = (object)array('id' => 3, 'status' => 'active', 'plan' => 2, 'toTask' => 0);
$oldBug = (object)array('status' => 'active', 'plan' => 0, 'toTask' => 0);
r($bugTest->getToBeProcessedDataTest($bug, $oldBug)) && p('2') && e('1');

// 测试步骤4：计划从有到无的变化
$bug = (object)array('id' => 4, 'status' => 'active', 'plan' => 0, 'toTask' => 0);
$oldBug = (object)array('status' => 'active', 'plan' => 3, 'toTask' => 0);
r($bugTest->getToBeProcessedDataTest($bug, $oldBug)) && p('1') && e('1');

// 测试步骤5：计划从一个变到另一个
$bug = (object)array('id' => 5, 'status' => 'active', 'plan' => 4, 'toTask' => 0);
$oldBug = (object)array('status' => 'active', 'plan' => 6, 'toTask' => 0);
r($bugTest->getToBeProcessedDataTest($bug, $oldBug)) && p('1,2') && e('2,2');