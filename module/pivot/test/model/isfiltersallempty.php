#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::isFiltersAllEmpty();
timeout=0
cid=0

- 执行pivotTest模块的isFiltersAllEmptyTest方法，参数是array  @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 4. 强制要求：必须包含至少5个测试步骤

// 步骤1：空数组输入测试 - 空数组应该返回false
r($pivotTest->isFiltersAllEmptyTest(array())) && p() && e('0');

// 步骤2：所有默认值为空的过滤器数组 - 应该返回true
r($pivotTest->isFiltersAllEmptyTest(array(
    array('name' => 'filter1', 'default' => ''),
    array('name' => 'filter2', 'default' => null),
    array('name' => 'filter3', 'default' => 0)
))) && p() && e('1');

// 步骤3：包含非空默认值的过滤器数组 - 应该返回false
r($pivotTest->isFiltersAllEmptyTest(array(
    array('name' => 'filter1', 'default' => ''),
    array('name' => 'filter2', 'default' => 'value'),
    array('name' => 'filter3', 'default' => null)
))) && p() && e('0');

// 步骤4：不同类型空值的过滤器数组 - 所有空值应该返回true
r($pivotTest->isFiltersAllEmptyTest(array(
    array('name' => 'filter1', 'default' => ''),
    array('name' => 'filter2', 'default' => null),
    array('name' => 'filter3', 'default' => false),
    array('name' => 'filter4', 'default' => 0),
    array('name' => 'filter5', 'default' => array())
))) && p() && e('1');

// 步骤5：混合空值和非空值的过滤器数组 - 应该返回false
r($pivotTest->isFiltersAllEmptyTest(array(
    array('name' => 'filter1', 'default' => ''),
    array('name' => 'filter2', 'default' => null),
    array('name' => 'filter3', 'default' => 'test'),
    array('name' => 'filter4', 'default' => 0),
    array('name' => 'filter5', 'default' => false)
))) && p() && e('0');

// 步骤6：缺少default字段的过滤器数组 - array_column会返回null，应该返回true
r($pivotTest->isFiltersAllEmptyTest(array(
    array('name' => 'filter1'),
    array('name' => 'filter2'),
    array('name' => 'filter3')
))) && p() && e('1');

// 步骤7：单个过滤器且default为空字符串 - 应该返回true
r($pivotTest->isFiltersAllEmptyTest(array(
    array('name' => 'filter1', 'default' => '')
))) && p() && e('1');

// 步骤8：单个过滤器且default为非空值 - 应该返回false
r($pivotTest->isFiltersAllEmptyTest(array(
    array('name' => 'filter1', 'default' => 'non-empty-value')
))) && p() && e('0');