#!/usr/bin/env php
<?php

/**

title=测试 searchTao::checkExecutionPriv();
timeout=0
cid=18315

- 执行searchTest模块的checkExecutionPrivTest方法，参数是$results1, $objectIdList1, $executions1
 - 第1条的objectID属性 @1
 - 第2条的objectID属性 @2
 - 第3条的objectID属性 @3
- 执行searchTest模块的checkExecutionPrivTest方法，参数是$results2, $objectIdList2, $executions2
 - 第1条的objectID属性 @1
 - 第2条的objectID属性 @2
- 执行searchTest模块的checkExecutionPrivTest方法，参数是$results3, $objectIdList3, $executions3  @0
- 执行searchTest模块的checkExecutionPrivTest方法，参数是$results4, $objectIdList4, $executions4  @0
- 执行searchTest模块的checkExecutionPrivTest方法，参数是$results5, $objectIdList5, $executions5
 - 第1条的objectID属性 @1
 - 第2条的objectID属性 @2
- 执行searchTest模块的checkExecutionPrivTest方法，参数是$results6, $objectIdList6, $executions6 第1条的objectID属性 @1
- 执行searchTest模块的checkExecutionPrivTest方法，参数是$results7, $objectIdList7, $executions7
 - 第1条的objectID属性 @1
 - 第2条的objectID属性 @100

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

$searchTest = new searchTaoTest();

$results1 = array(1 => (object)array('id' => 1, 'objectID' => 1, 'objectType' => 'execution'), 2 => (object)array('id' => 2, 'objectID' => 2, 'objectType' => 'execution'), 3 => (object)array('id' => 3, 'objectID' => 3, 'objectType' => 'execution'));
$objectIdList1 = array(1 => 1, 2 => 2, 3 => 3);
$executions1 = '1,2,3';
r($searchTest->checkExecutionPrivTest($results1, $objectIdList1, $executions1)) && p('1:objectID;2:objectID;3:objectID') && e('1;2;3');

$results2 = array(1 => (object)array('id' => 1, 'objectID' => 1, 'objectType' => 'execution'), 2 => (object)array('id' => 2, 'objectID' => 2, 'objectType' => 'execution'), 3 => (object)array('id' => 3, 'objectID' => 99, 'objectType' => 'execution'));
$objectIdList2 = array(1 => 1, 2 => 2, 99 => 3);
$executions2 = '1,2,3';
r($searchTest->checkExecutionPrivTest($results2, $objectIdList2, $executions2)) && p('1:objectID;2:objectID') && e('1;2');

$results3 = array(1 => (object)array('id' => 1, 'objectID' => 10, 'objectType' => 'execution'), 2 => (object)array('id' => 2, 'objectID' => 20, 'objectType' => 'execution'));
$objectIdList3 = array(10 => 1, 20 => 2);
$executions3 = '1,2,3';
r(count($searchTest->checkExecutionPrivTest($results3, $objectIdList3, $executions3))) && p() && e('0');

$results4 = array();
$objectIdList4 = array();
$executions4 = '1,2,3';
r(count($searchTest->checkExecutionPrivTest($results4, $objectIdList4, $executions4))) && p() && e('0');

$results5 = array(1 => (object)array('id' => 1, 'objectID' => 1, 'objectType' => 'execution'), 2 => (object)array('id' => 2, 'objectID' => 2, 'objectType' => 'execution'), 3 => (object)array('id' => 3, 'objectID' => 999, 'objectType' => 'execution'));
$objectIdList5 = array(1 => 1, 2 => 2, 999 => 3);
$executions5 = '1,2,3,4,5';
r($searchTest->checkExecutionPrivTest($results5, $objectIdList5, $executions5)) && p('1:objectID;2:objectID') && e('1;2');

$results6 = array(1 => (object)array('id' => 1, 'objectID' => 1, 'objectType' => 'execution'));
$objectIdList6 = array(1 => 1);
$executions6 = '1';
r($searchTest->checkExecutionPrivTest($results6, $objectIdList6, $executions6)) && p('1:objectID') && e('1');

$results7 = array(1 => (object)array('id' => 1, 'objectID' => 1, 'objectType' => 'execution'), 2 => (object)array('id' => 2, 'objectID' => 100, 'objectType' => 'execution'), 3 => (object)array('id' => 3, 'objectID' => 50, 'objectType' => 'execution'));
$objectIdList7 = array(1 => 1, 100 => 2, 50 => 3);
$executions7 = '1,100';
r($searchTest->checkExecutionPrivTest($results7, $objectIdList7, $executions7)) && p('1:objectID;2:objectID') && e('1;100');