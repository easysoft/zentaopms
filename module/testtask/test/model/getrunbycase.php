#!/usr/bin/env php
<?php

/**

title=测试 testtaskModel::getRunByCase();
timeout=0
cid=19189

- 执行testtaskTest模块的getRunByCaseTest方法，参数是1, 1 
 - 属性task @1
 - 属性case @1
- 执行testtaskTest模块的getRunByCaseTest方法，参数是1, 2 
 - 属性task @1
 - 属性case @2
- 执行testtaskTest模块的getRunByCaseTest方法，参数是2, 2 
 - 属性task @2
 - 属性case @2
- 执行testtaskTest模块的getRunByCaseTest方法，参数是2, 3 
 - 属性task @2
 - 属性case @3
- 执行testtaskTest模块的getRunByCaseTest方法，参数是3, 4 
 - 属性task @3
 - 属性case @4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtask.unittest.class.php';

$table = zenData('testrun');
$table->id->range('1-5');
$table->task->range('1{2},2{2},3{1}');
$table->case->range('1,2,2,3,4');
$table->version->range('1');
$table->assignedTo->range('admin');
$table->lastRunner->range('admin');
$table->lastRunDate->range('`2023-01-01 10:00:00`');
$table->lastRunResult->range('pass');
$table->status->range('normal');
$table->gen(5);

su('admin');

$testtaskTest = new testtaskTest();

r($testtaskTest->getRunByCaseTest(1, 1)) && p('task,case') && e('1,1');
r($testtaskTest->getRunByCaseTest(1, 2)) && p('task,case') && e('1,2');
r($testtaskTest->getRunByCaseTest(2, 2)) && p('task,case') && e('2,2');
r($testtaskTest->getRunByCaseTest(2, 3)) && p('task,case') && e('2,3');
r($testtaskTest->getRunByCaseTest(3, 4)) && p('task,case') && e('3,4');