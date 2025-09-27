#!/usr/bin/env php
<?php

/**

title=测试 searchTao::checkDocPriv();
timeout=0
cid=0

- 测试有权限访问的文档：传入两个有效文档ID >> 返回2个结果
- 测试有权限访问的文档：传入三个有效文档ID >> 返回3个结果
- 测试空的结果数组和对象ID列表 >> 返回0个结果
- 测试不存在的文档ID >> 返回0个结果
- 测试部分有权限的文档：传入两个文档ID但只有一个有权限 >> 返回1个结果

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

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

r(count($searchTest->checkDocPrivTest($results1, $objectIdList1, $table1))) && p() && e('2');
r(count($searchTest->checkDocPrivTest($results2, $objectIdList2, $table1))) && p() && e('3');
r(count($searchTest->checkDocPrivTest($results3, $objectIdList3, $table1))) && p() && e('0');
r(count($searchTest->checkDocPrivTest($results4, $objectIdList4, $table1))) && p() && e('0');
r(count($searchTest->checkDocPrivTest($results5, $objectIdList5, $table1))) && p() && e('1');