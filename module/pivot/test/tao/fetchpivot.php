#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::fetchPivot();
timeout=0
cid=17437

- 执行pivotTest模块的fetchPivotTest方法，参数是1, null
 - 属性id @1
 - 属性deleted @0
- 执行pivotTest模块的fetchPivotTest方法，参数是2, '1'
 - 属性id @2
 - 属性version @1
- 执行pivotTest模块的fetchPivotTest方法，参数是999, null  @0
- 执行pivotTest模块的fetchPivotTest方法，参数是10, null  @0
- 执行pivotTest模块的fetchPivotTest方法，参数是3, '99'
 - 属性id @3
 - 属性deleted @0
- 执行pivotTest模块的fetchPivotTest方法，参数是4, '2'
 - 属性id @4
 - 属性version @2
- 执行pivotTest模块的fetchPivotTest方法，参数是5, null
 - 属性id @5
 - 属性deleted @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$pivotTable = zenData('pivot');
$pivotTable->id->range('1-10');
$pivotTable->name->range('测试透视表1,测试透视表2,测试透视表3,测试透视表4,测试透视表5,测试透视表6,测试透视表7,测试透视表8,测试透视表9,测试透视表10');
$pivotTable->deleted->range('0,0,0,0,0,0,0,0,0,1');
$pivotTable->version->range('1,1,1,1,1,2,2,2,3,3');
$pivotTable->gen(10);

$pivotspecTable = zenData('pivotspec');
$pivotspecTable->pivot->range('1-5');
$pivotspecTable->version->range('1,1,1,2,2,2,3,3');
$pivotspecTable->name->range('版本1名称,版本1名称,版本1名称,版本2名称,版本2名称,版本2名称,版本3名称,版本3名称');
$pivotspecTable->gen(8);

su('admin');

$pivotTest = new pivotTaoTest();

r($pivotTest->fetchPivotTest(1, null)) && p('id,deleted') && e('1,0');
r($pivotTest->fetchPivotTest(2, '1')) && p('id,version') && e('2,1');
r($pivotTest->fetchPivotTest(999, null)) && p() && e('0');
r($pivotTest->fetchPivotTest(10, null)) && p() && e('0');
r($pivotTest->fetchPivotTest(3, '99')) && p('id,deleted') && e('3,0');
r($pivotTest->fetchPivotTest(4, '2')) && p('id,version') && e('4,2');
r($pivotTest->fetchPivotTest(5, null)) && p('id,deleted') && e('5,0');