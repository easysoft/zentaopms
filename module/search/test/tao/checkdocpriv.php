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
- 执行searchTest模块的checkDocPrivTest方法，参数是$results6, $objectIdList6, $table1  @1
- 执行searchTest模块的checkDocPrivTest方法，参数是$results7, $objectIdList7, $table1  @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

// 初始化测试数据（如果可能的话）
if(function_exists('zenData')) {
    try {
        zenData('doc')->gen(10);
        zenData('doclib')->gen(5);
    } catch(Exception $e) {
        // 忽略数据初始化错误，测试仍可继续
    }
}

su('admin');

$searchTest = new searchTest();

// 测试用例1：有权限访问的文档（文档ID 1,2）
$results1 = array(
    1 => (object)array('id' => 1, 'objectType' => 'doc', 'objectID' => 1),
    2 => (object)array('id' => 2, 'objectType' => 'doc', 'objectID' => 2)
);
$objectIdList1 = array(1 => 1, 2 => 2);
$table1 = TABLE_DOC;

// 测试用例2：有权限访问的文档（文档ID 1,2,3）
$results2 = array(
    1 => (object)array('id' => 1, 'objectType' => 'doc', 'objectID' => 1),
    2 => (object)array('id' => 2, 'objectType' => 'doc', 'objectID' => 2),
    3 => (object)array('id' => 3, 'objectType' => 'doc', 'objectID' => 3)
);
$objectIdList2 = array(1 => 1, 2 => 2, 3 => 3);

// 测试用例3：空的结果数组
$results3 = array();
$objectIdList3 = array();

// 测试用例4：不存在的文档ID
$results4 = array(
    1 => (object)array('id' => 1, 'objectType' => 'doc', 'objectID' => 999),
    2 => (object)array('id' => 2, 'objectType' => 'doc', 'objectID' => 888)
);
$objectIdList4 = array(999 => 1, 888 => 2);

// 测试用例5：部分有权限的文档（文档4有权限，文档999无权限）
$results5 = array(
    1 => (object)array('id' => 1, 'objectType' => 'doc', 'objectID' => 4),
    2 => (object)array('id' => 2, 'objectType' => 'doc', 'objectID' => 999)
);
$objectIdList5 = array(4 => 1, 999 => 2);

// 测试用例6：边界值测试（文档ID 10有权限，11可能无权限）
$results6 = array(
    1 => (object)array('id' => 1, 'objectType' => 'doc', 'objectID' => 10),
    2 => (object)array('id' => 2, 'objectType' => 'doc', 'objectID' => 11)
);
$objectIdList6 = array(10 => 1, 11 => 2);

// 测试用例7：所有都有权限的文档
$results7 = array(
    1 => (object)array('id' => 1, 'objectType' => 'doc', 'objectID' => 1),
    2 => (object)array('id' => 2, 'objectType' => 'doc', 'objectID' => 2),
    3 => (object)array('id' => 3, 'objectType' => 'doc', 'objectID' => 3),
    4 => (object)array('id' => 4, 'objectType' => 'doc', 'objectID' => 4),
    5 => (object)array('id' => 5, 'objectType' => 'doc', 'objectID' => 5)
);
$objectIdList7 = array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5);

r(count($searchTest->checkDocPrivTest($results1, $objectIdList1, $table1))) && p() && e('2');
r(count($searchTest->checkDocPrivTest($results2, $objectIdList2, $table1))) && p() && e('3');
r(count($searchTest->checkDocPrivTest($results3, $objectIdList3, $table1))) && p() && e('0');
r(count($searchTest->checkDocPrivTest($results4, $objectIdList4, $table1))) && p() && e('0');
r(count($searchTest->checkDocPrivTest($results5, $objectIdList5, $table1))) && p() && e('1');
r(count($searchTest->checkDocPrivTest($results6, $objectIdList6, $table1))) && p() && e('1');
r(count($searchTest->checkDocPrivTest($results7, $objectIdList7, $table1))) && p() && e('5');