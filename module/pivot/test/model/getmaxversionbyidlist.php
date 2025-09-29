#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getMaxVersionByIDList();
timeout=0
cid=0

- 执行pivotTest模块的getMaxVersionByIDListTest方法，参数是array 
 - 属性1 @2.1
 - 属性2 @2.9
- 执行pivotTest模块的getMaxVersionByIDListTest方法，参数是'1' 属性1 @2.1
- 执行pivotTest模块的getMaxVersionByIDListTest方法，参数是array  @0
- 执行pivotTest模块的getMaxVersionByIDListTest方法，参数是array  @0
- 执行pivotTest模块的getMaxVersionByIDListTest方法，参数是array 属性1 @2.1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

$table = zenData('pivotspec');
$table->pivot->range('1{4},2{2}');
$table->version->range('1.0,1.5,2.0,2.1,2.5,2.9');
$table->driver->range('mysql');
$table->mode->range('builder');
$table->name->range('Test1{4},Test2{2}');
$table->desc->range('Test desc1{4},Test desc2{2}');
$table->gen(6);

su('admin');

$pivotTest = new pivotTest();

r($pivotTest->getMaxVersionByIDListTest(array(1, 2))) && p('1,2') && e('2.1,2.9');
r($pivotTest->getMaxVersionByIDListTest('1')) && p('1') && e('2.1');
r($pivotTest->getMaxVersionByIDListTest(array())) && p() && e('0');
r($pivotTest->getMaxVersionByIDListTest(array(999))) && p() && e('0');
r($pivotTest->getMaxVersionByIDListTest(array(1, 999))) && p('1') && e('2.1');