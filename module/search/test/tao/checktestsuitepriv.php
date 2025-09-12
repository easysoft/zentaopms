#!/usr/bin/env php
<?php

/**

title=测试 searchTao::checkTestsuitePriv();
timeout=0
cid=0

- 步骤1：正常情况（私有套件被过滤） @1
- 步骤2：空结果数组 @0
- 步骤3：空对象ID列表 @3
- 步骤4：所有公开套件 @3
- 步骤5：混合类型过滤 @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

// 2. zendata数据准备
$testsuite = zenData('testsuite');
$testsuite->id->range('1-10');
$testsuite->project->range('0');
$testsuite->product->range('1-3');
$testsuite->name->range('Test Suite {1-10}');
$testsuite->desc->range('This is test suite description {1-10}');
$testsuite->type->range('public{5},private{5}');
$testsuite->order->range('0');
$testsuite->addedBy->range('admin{5},user{5}');
$testsuite->addedDate->range('`2024-01-01`');
$testsuite->lastEditedBy->range('admin{5},user{5}');
$testsuite->lastEditedDate->range('`2024-01-01`');
$testsuite->deleted->range('0');
$testsuite->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$searchTest = new searchTest();

// 5. 测试步骤（必须包含至少5个测试步骤）

// 步骤1：测试正常情况下过滤私有类型测试套件
$results = array(
    1 => (object)array('id' => 1, 'objectType' => 'testsuite', 'objectID' => 1),
    2 => (object)array('id' => 2, 'objectType' => 'testsuite', 'objectID' => 6),
    3 => (object)array('id' => 3, 'objectType' => 'testsuite', 'objectID' => 7),
    4 => (object)array('id' => 4, 'objectType' => 'testsuite', 'objectID' => 8),
    5 => (object)array('id' => 5, 'objectType' => 'testsuite', 'objectID' => 9),
    6 => (object)array('id' => 6, 'objectType' => 'testsuite', 'objectID' => 10)
);
$objectIdList = array(1 => 1, 6 => 2, 7 => 3, 8 => 4, 9 => 5, 10 => 6);
$table = TABLE_TESTSUITE;
r($searchTest->checkTestsuitePrivTest($results, $objectIdList, $table)) && p() && e(1); // 步骤1：正常情况（私有套件被过滤）

// 步骤2：测试结果数组为空的情况
$emptyResults = array();
$emptyObjectIdList = array(1 => 1, 2 => 2);
r($searchTest->checkTestsuitePrivTest($emptyResults, $emptyObjectIdList, $table)) && p() && e(0); // 步骤2：空结果数组

// 步骤3：测试objectIdList为空的情况
$normalResults = array(
    1 => (object)array('id' => 1, 'objectType' => 'testsuite', 'objectID' => 1),
    2 => (object)array('id' => 2, 'objectType' => 'testsuite', 'objectID' => 2),
    3 => (object)array('id' => 3, 'objectType' => 'testsuite', 'objectID' => 3)
);
$emptyObjectIdList = array();
r($searchTest->checkTestsuitePrivTest($normalResults, $emptyObjectIdList, $table)) && p() && e(3); // 步骤3：空对象ID列表

// 步骤4：测试所有套件都是公开类型的情况
$publicResults = array(
    1 => (object)array('id' => 1, 'objectType' => 'testsuite', 'objectID' => 1),
    2 => (object)array('id' => 2, 'objectType' => 'testsuite', 'objectID' => 2),
    3 => (object)array('id' => 3, 'objectType' => 'testsuite', 'objectID' => 3)
);
$publicObjectIdList = array(1 => 1, 2 => 2, 3 => 3);
r($searchTest->checkTestsuitePrivTest($publicResults, $publicObjectIdList, $table)) && p() && e(3); // 步骤4：所有公开套件

// 步骤5：测试混合类型套件的权限过滤
$mixedResults = array(
    1 => (object)array('id' => 1, 'objectType' => 'testsuite', 'objectID' => 1),
    2 => (object)array('id' => 2, 'objectType' => 'testsuite', 'objectID' => 6),
    3 => (object)array('id' => 3, 'objectType' => 'testsuite', 'objectID' => 2)
);
$mixedObjectIdList = array(1 => 1, 6 => 2, 2 => 3);
r($searchTest->checkTestsuitePrivTest($mixedResults, $mixedObjectIdList, $table)) && p() && e(2); // 步骤5：混合类型过滤