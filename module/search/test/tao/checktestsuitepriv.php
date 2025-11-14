#!/usr/bin/env php
<?php

/**

title=测试 searchTao::checkTestsuitePriv();
timeout=0
cid=18323

- 执行searchTest模块的checkTestsuitePrivTest方法，参数是$results1, $objectIdList1, TABLE_TESTSUITE  @1
- 执行searchTest模块的checkTestsuitePrivTest方法，参数是$results2, $objectIdList2, TABLE_TESTSUITE  @0
- 执行searchTest模块的checkTestsuitePrivTest方法，参数是$results3, $objectIdList3, TABLE_TESTSUITE  @1
- 执行searchTest模块的checkTestsuitePrivTest方法，参数是$results4, $objectIdList4, TABLE_TESTSUITE  @2
- 执行searchTest模块的checkTestsuitePrivTest方法，参数是$results5, $objectIdList5, TABLE_TESTSUITE  @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$testsuite = zenData('testsuite');
$testsuite->id->range('1-10');
$testsuite->name->range('Suite 1,Suite 2,Suite 3,Suite 4,Suite 5,Suite 6,Suite 7,Suite 8,Suite 9,Suite 10');
$testsuite->type->range('public{5},private{5}');
$testsuite->deleted->range('0{9},1');
$testsuite->gen(10);

su('admin');

$searchTest = new searchTaoTest();

$results1 = array(1 => (object)array('id' => 1, 'objectType' => 'testsuite', 'objectID' => 1));
$objectIdList1 = array(1 => 1);

$results2 = array(1 => (object)array('id' => 1, 'objectType' => 'testsuite', 'objectID' => 6));
$objectIdList2 = array(6 => 1);

$results3 = array(1 => (object)array('id' => 1, 'objectType' => 'testsuite', 'objectID' => 10));
$objectIdList3 = array(10 => 1);

$results4 = array(1 => (object)array('id' => 1, 'objectType' => 'testsuite', 'objectID' => 2), 2 => (object)array('id' => 2, 'objectType' => 'testsuite', 'objectID' => 3));
$objectIdList4 = array(2 => 1, 3 => 2);

$results5 = array(1 => (object)array('id' => 1, 'objectType' => 'testsuite', 'objectID' => 4), 2 => (object)array('id' => 2, 'objectType' => 'testsuite', 'objectID' => 7), 3 => (object)array('id' => 3, 'objectType' => 'testsuite', 'objectID' => 5));
$objectIdList5 = array(4 => 1, 7 => 2, 5 => 3);

r(count($searchTest->checkTestsuitePrivTest($results1, $objectIdList1, TABLE_TESTSUITE))) && p() && e('1');
r(count($searchTest->checkTestsuitePrivTest($results2, $objectIdList2, TABLE_TESTSUITE))) && p() && e('0');
r(count($searchTest->checkTestsuitePrivTest($results3, $objectIdList3, TABLE_TESTSUITE))) && p() && e('1');
r(count($searchTest->checkTestsuitePrivTest($results4, $objectIdList4, TABLE_TESTSUITE))) && p() && e('2');
r(count($searchTest->checkTestsuitePrivTest($results5, $objectIdList5, TABLE_TESTSUITE))) && p() && e('2');