#!/usr/bin/env php
<?php

/**

title=测试 searchTao::checkDocPriv();
timeout=0
cid=0

- 执行searchTest模块的checkDocPrivTest方法，参数是$results1, $objectIdList1, $table1  @2
- 执行searchTest模块的checkDocPrivTest方法，参数是$results2, $objectIdList2, $table1  @3
- 执行searchTest模块的checkDocPrivTest方法，参数是$results3, $objectIdList3, $table1  @0
- 执行searchTest模块的checkDocPrivTest方法，参数是$results4, $objectIdList4, $table1  @0
- 执行searchTest模块的checkDocPrivTest方法，参数是$results5, $objectIdList5, $table1  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

zenData('doc')->loadYaml('doc_checkdocpriv', false, 2)->gen(10);
zenData('doclib')->loadYaml('doclib_checkdocpriv', false, 2)->gen(5);

su('admin');

$searchTest = new searchTest();

$results1 = array(
    1 => (object)array('id' => 1, 'objectType' => 'doc', 'objectID' => 1),
    2 => (object)array('id' => 2, 'objectType' => 'doc', 'objectID' => 2)
);
$objectIdList1 = array(1 => 1, 2 => 2);
$table1 = TABLE_DOC;

$results2 = array(
    1 => (object)array('id' => 1, 'objectType' => 'doc', 'objectID' => 1),
    2 => (object)array('id' => 2, 'objectType' => 'doc', 'objectID' => 2),
    3 => (object)array('id' => 3, 'objectType' => 'doc', 'objectID' => 3)
);
$objectIdList2 = array(1 => 1, 2 => 2, 3 => 3);

$results3 = array();
$objectIdList3 = array();

$results4 = array(
    1 => (object)array('id' => 1, 'objectType' => 'doc', 'objectID' => 999),
    2 => (object)array('id' => 2, 'objectType' => 'doc', 'objectID' => 888)
);
$objectIdList4 = array(999 => 1, 888 => 2);

$results5 = array(
    1 => (object)array('id' => 1, 'objectType' => 'doc', 'objectID' => 4),
    2 => (object)array('id' => 2, 'objectType' => 'doc', 'objectID' => 5)
);
$objectIdList5 = array(4 => 1, 5 => 2);

r(count($searchTest->checkDocPrivTest($results1, $objectIdList1, $table1))) && p() && e('2');
r(count($searchTest->checkDocPrivTest($results2, $objectIdList2, $table1))) && p() && e('3');
r(count($searchTest->checkDocPrivTest($results3, $objectIdList3, $table1))) && p() && e('0');
r(count($searchTest->checkDocPrivTest($results4, $objectIdList4, $table1))) && p() && e('0');
r(count($searchTest->checkDocPrivTest($results5, $objectIdList5, $table1))) && p() && e('1');