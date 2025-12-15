#!/usr/bin/env php
<?php

/**

title=测试 searchTao::checkDocPriv();
timeout=0
cid=18314

- 执行searchTest模块的checkDocPrivTest方法，参数是$results1, $objectIdList1, $table1
 - 第1条的objectID属性 @1
 - 第2条的objectID属性 @2
 - 第3条的objectID属性 @3
- 执行searchTest模块的checkDocPrivTest方法，参数是$results2, $objectIdList2, $table1 第1条的objectID属性 @1
- 执行searchTest模块的checkDocPrivTest方法，参数是$results3, $objectIdList3, $table1
 - 第1条的objectID属性 @1
 - 第2条的objectID属性 @2
- 执行searchTest模块的checkDocPrivTest方法，参数是$results4, $objectIdList4, $table1
 - 第1条的objectID属性 @4
 - 第2条的objectID属性 @5
- 执行searchTest模块的checkDocPrivTest方法，参数是$results5, $objectIdList5, $table1  @0
- 执行searchTest模块的checkDocPrivTest方法，参数是$results6, $objectIdList6, $table1  @0
- 执行searchTest模块的checkDocPrivTest方法，参数是$results7, $objectIdList7, $table1
 - 第1条的objectID属性 @1
 - 第2条的objectID属性 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('doc')->loadYaml('checkdocpriv/doc', false, 2)->gen(20);
zenData('doclib')->loadYaml('checkdocpriv/doclib', false, 2)->gen(10);
zenData('user')->gen(5);

su('admin');

global $tester;
$tester->loadModel('doc');

$searchTest = new searchTaoTest();

$results1 = array(1 => (object)array('id' => 1, 'objectID' => 1, 'objectType' => 'doc'), 2 => (object)array('id' => 2, 'objectID' => 2, 'objectType' => 'doc'), 3 => (object)array('id' => 3, 'objectID' => 3, 'objectType' => 'doc'));
$objectIdList1 = array(1 => 1, 2 => 2, 3 => 3);
$table1 = TABLE_DOC;
r($searchTest->checkDocPrivTest($results1, $objectIdList1, $table1)) && p('1:objectID;2:objectID;3:objectID') && e('1;2;3');

$results2 = array(1 => (object)array('id' => 1, 'objectID' => 1, 'objectType' => 'doc'), 2 => (object)array('id' => 2, 'objectID' => 19, 'objectType' => 'doc'), 3 => (object)array('id' => 3, 'objectID' => 20, 'objectType' => 'doc'));
$objectIdList2 = array(1 => 1, 19 => 2, 20 => 3);
r($searchTest->checkDocPrivTest($results2, $objectIdList2, $table1)) && p('1:objectID') && e('1');

$results3 = array(1 => (object)array('id' => 1, 'objectID' => 1, 'objectType' => 'doc'), 2 => (object)array('id' => 2, 'objectID' => 2, 'objectType' => 'doc'), 3 => (object)array('id' => 3, 'objectID' => 999, 'objectType' => 'doc'));
$objectIdList3 = array(1 => 1, 2 => 2, 999 => 3);
r($searchTest->checkDocPrivTest($results3, $objectIdList3, $table1)) && p('1:objectID;2:objectID') && e('1;2');

$results4 = array(1 => (object)array('id' => 1, 'objectID' => 4, 'objectType' => 'doc'), 2 => (object)array('id' => 2, 'objectID' => 5, 'objectType' => 'doc'));
$objectIdList4 = array(4 => 1, 5 => 2);
r($searchTest->checkDocPrivTest($results4, $objectIdList4, $table1)) && p('1:objectID;2:objectID') && e('4;5');

$results5 = array();
$objectIdList5 = array();
r(count($searchTest->checkDocPrivTest($results5, $objectIdList5, $table1))) && p() && e('0');

$results6 = array(1 => (object)array('id' => 1, 'objectID' => 888, 'objectType' => 'doc'), 2 => (object)array('id' => 2, 'objectID' => 999, 'objectType' => 'doc'));
$objectIdList6 = array(888 => 1, 999 => 2);
r(count($searchTest->checkDocPrivTest($results6, $objectIdList6, $table1))) && p() && e('0');

$results7 = array(1 => (object)array('id' => 1, 'objectID' => 1, 'objectType' => 'doc'), 2 => (object)array('id' => 2, 'objectID' => 2, 'objectType' => 'doc'), 3 => (object)array('id' => 3, 'objectID' => 19, 'objectType' => 'doc'), 4 => (object)array('id' => 4, 'objectID' => 999, 'objectType' => 'doc'));
$objectIdList7 = array(1 => 1, 2 => 2, 19 => 3, 999 => 4);
r($searchTest->checkDocPrivTest($results7, $objectIdList7, $table1)) && p('1:objectID;2:objectID') && e('1;2');
