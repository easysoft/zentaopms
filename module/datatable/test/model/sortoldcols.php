#!/usr/bin/env php
<?php

/**

title=测试 datatableModel::sortOldCols();
timeout=0
cid=15949

- 执行datatableTest模块的sortOldColsTest方法，参数是$objA, $objB  @-1
- 执行datatableTest模块的sortOldColsTest方法，参数是$objA, $objB  @0
- 执行datatableTest模块的sortOldColsTest方法，参数是$objA, $objB  @7
- 执行datatableTest模块的sortOldColsTest方法，参数是$objNoOrder, $objWithOrder  @0
- 执行datatableTest模块的sortOldColsTest方法，参数是$objC, $objD  @50

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/datatable.unittest.class.php';

su('admin');

$datatableTest = new datatableTest();

$objA = new stdclass();
$objB = new stdclass();

$objA->order = 1;
$objB->order = 2;
r($datatableTest->sortOldColsTest($objA, $objB)) && p() && e('-1');

$objA->order = 5;
$objB->order = 5;
r($datatableTest->sortOldColsTest($objA, $objB)) && p() && e('0');

$objA->order = 10;
$objB->order = 3;
r($datatableTest->sortOldColsTest($objA, $objB)) && p() && e('7');

$objNoOrder = new stdclass();
$objWithOrder = new stdclass();
$objWithOrder->order = 5;
r($datatableTest->sortOldColsTest($objNoOrder, $objWithOrder)) && p() && e('0');

$objC = new stdclass();
$objD = new stdclass();
$objC->order = 100;
$objD->order = 50;
r($datatableTest->sortOldColsTest($objC, $objD)) && p() && e('50');