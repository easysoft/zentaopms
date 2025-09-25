#!/usr/bin/env php
<?php

/**

title=测试 dataviewModel::checkUniColumn();
timeout=0
cid=0

- 执行dataviewTest模块的checkUniColumnTest方法，参数是'', 'mysql', false, $uniqueColumns  @1
- 执行dataviewTest模块的checkUniColumnTest方法，参数是'', 'mysql', false, $duplicateColumns  @0
- 执行dataviewTest模块的checkUniColumnTest方法，参数是'', 'mysql', true, $duplicateColumns  @0
- 执行dataviewTest模块的checkUniColumnTest方法，参数是'', 'mysql', false, $emptyColumns  @1
- 执行dataviewTest模块的checkUniColumnTest方法，参数是'', 'mysql', false, $mixedColumns  @0
- 执行dataviewTest模块的checkUniColumnTest方法，参数是'', 'mysql', true, $multiDuplicateColumns 属性1 @0~~id~~status
属性id @0~~id~~status
属性status @0~~id~~status
- 执行dataviewTest模块的checkUniColumnTest方法，参数是'', 'mysql', false, $singleColumn  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dataview.unittest.class.php';

su('admin');

$dataviewTest = new dataviewTest();

// 测试数据1：唯一列数组
$uniqueColumns = array(
    array('name' => 'id'),
    array('name' => 'name'),
    array('name' => 'code')
);

// 测试数据2：包含重复列的数组
$duplicateColumns = array(
    array('name' => 'id'),
    array('name' => 'name'),
    array('name' => 'id')
);

// 测试数据3：空数组
$emptyColumns = array();

// 测试数据4：混合重复列
$mixedColumns = array(
    array('name' => 'id'),
    array('name' => 'name'),
    array('name' => 'status'),
    array('name' => 'name'),
    array('name' => 'type')
);

// 测试数据5：多个重复列
$multiDuplicateColumns = array(
    array('name' => 'id'),
    array('name' => 'name'),
    array('name' => 'id'),
    array('name' => 'status'),
    array('name' => 'status')
);

// 测试数据6：单列数组
$singleColumn = array(
    array('name' => 'id')
);

r($dataviewTest->checkUniColumnTest('', 'mysql', false, $uniqueColumns)) && p() && e('1');
r($dataviewTest->checkUniColumnTest('', 'mysql', false, $duplicateColumns)) && p() && e('0');
r($dataviewTest->checkUniColumnTest('', 'mysql', true, $duplicateColumns)) && p('0') && e('0');
r($dataviewTest->checkUniColumnTest('', 'mysql', false, $emptyColumns)) && p() && e('1');
r($dataviewTest->checkUniColumnTest('', 'mysql', false, $mixedColumns)) && p() && e('0');
r($dataviewTest->checkUniColumnTest('', 'mysql', true, $multiDuplicateColumns)) && p('1,id,status') && e('0~~id~~status');
r($dataviewTest->checkUniColumnTest('', 'mysql', false, $singleColumn)) && p() && e('1');