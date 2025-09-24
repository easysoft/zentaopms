#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::isFiltersAllEmpty();
timeout=0
cid=0

- 步骤1：空数组返回false @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

su('admin');

$pivotTest = new pivotTest();

r($pivotTest->isFiltersAllEmptyTest(array())) && p() && e('0'); // 步骤1：空数组返回false
r($pivotTest->isFiltersAllEmptyTest(array(
    array('name' => 'filter1', 'default' => ''),
    array('name' => 'filter2', 'default' => null),
    array('name' => 'filter3', 'default' => false),
    array('name' => 'filter4', 'default' => 0),
    array('name' => 'filter5', 'default' => array())
))) && p() && e('1'); // 步骤2：所有default值为空返回true
r($pivotTest->isFiltersAllEmptyTest(array(
    array('name' => 'filter1', 'default' => ''),
    array('name' => 'filter2', 'default' => 'non-empty-value'),
    array('name' => 'filter3', 'default' => null)
))) && p() && e('0'); // 步骤3：包含非空default值返回false
r($pivotTest->isFiltersAllEmptyTest(array(
    array('name' => 'filter1', 'default' => '')
))) && p() && e('1'); // 步骤4：单个空default值返回true
r($pivotTest->isFiltersAllEmptyTest(array(
    array('name' => 'filter1'),
    array('name' => 'filter2'),
    array('name' => 'filter3')
))) && p() && e('1'); // 步骤5：缺少default键返回true
r($pivotTest->isFiltersAllEmptyTest(array(
    array('name' => 'filter1', 'default' => 'value1'),
    array('name' => 'filter2', 'default' => 'value2'),
    array('name' => 'filter3', 'default' => 'value3')
))) && p() && e('0'); // 步骤6：所有default值非空返回false
r($pivotTest->isFiltersAllEmptyTest(array(
    array('name' => 'filter1', 'default' => array('key' => 'value')),
    array('name' => 'filter2', 'default' => '')
))) && p() && e('0'); // 步骤7：混合数据类型返回false
r($pivotTest->isFiltersAllEmptyTest(array(
    array('name' => 'filter1', 'default' => 0),
    array('name' => 'filter2', 'default' => '0'),
    array('name' => 'filter3', 'default' => false)
))) && p() && e('1'); // 步骤8：特殊空值类型返回true