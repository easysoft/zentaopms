#!/usr/bin/env php
<?php

/**

title=测试 dataviewModel::checkUniColumn();
timeout=0
cid=15951

- 执行dataviewTest模块的checkUniColumnTest方法，参数是'', 'mysql', false, $uniqueColumns  @1
- 执行dataviewTest模块的checkUniColumnTest方法，参数是'', 'mysql', false, $duplicateColumns  @0
- 执行dataviewTest模块的checkUniColumnTest方法，参数是'', 'mysql', false, $emptyColumns  @1
- 执行dataviewTest模块的checkUniColumnTest方法，参数是'', 'mysql', true, $duplicateColumns  @~~
- 执行dataviewTest模块的checkUniColumnTest方法，参数是'', 'mysql', true, $duplicateColumns 第1条的id属性 @id
- 执行dataviewTest模块的checkUniColumnTest方法，参数是'', 'mysql', false, $singleColumn  @1
- 执行dataviewTest模块的checkUniColumnTest方法，参数是'', 'duckdb', false, $uniqueColumns  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$dataviewTest = new dataviewModelTest();

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

// 测试数据4：单列数组
$singleColumn = array(
    array('name' => 'id')
);

r($dataviewTest->checkUniColumnTest('', 'mysql', false, $uniqueColumns)) && p() && e('1');
r($dataviewTest->checkUniColumnTest('', 'mysql', false, $duplicateColumns)) && p() && e('0');
r($dataviewTest->checkUniColumnTest('', 'mysql', false, $emptyColumns)) && p() && e('1');
r($dataviewTest->checkUniColumnTest('', 'mysql', true, $duplicateColumns)) && p('0') && e('~~');
r($dataviewTest->checkUniColumnTest('', 'mysql', true, $duplicateColumns)) && p('1:id') && e('id');
r($dataviewTest->checkUniColumnTest('', 'mysql', false, $singleColumn)) && p() && e('1');
r($dataviewTest->checkUniColumnTest('', 'duckdb', false, $uniqueColumns)) && p() && e('1');