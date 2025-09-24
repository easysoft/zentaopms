#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::isFiltersAllEmpty();
timeout=0
cid=0

- 步骤1：空数组输入测试，应返回false @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($pivotTest->isFiltersAllEmptyTest(array())) && p() && e('0'); // 步骤1：空数组输入测试，应返回false
r($pivotTest->isFiltersAllEmptyTest(array(
    array('name' => 'status', 'default' => ''),
    array('name' => 'type', 'default' => null),
    array('name' => 'priority', 'default' => false),
    array('name' => 'assignee', 'default' => 0),
    array('name' => 'keywords', 'default' => array())
))) && p() && e('1'); // 步骤2：所有default值为empty的过滤器数组，应返回true
r($pivotTest->isFiltersAllEmptyTest(array(
    array('name' => 'status', 'default' => ''),
    array('name' => 'type', 'default' => 'bug'),
    array('name' => 'priority', 'default' => null)
))) && p() && e('0'); // 步骤3：包含非空default值的混合过滤器，应返回false
r($pivotTest->isFiltersAllEmptyTest(array(
    array('name' => 'single_filter', 'default' => '')
))) && p() && e('1'); // 步骤4：单个空default值的过滤器，应返回true
r($pivotTest->isFiltersAllEmptyTest(array(
    array('name' => 'missing_default1'),
    array('name' => 'missing_default2'),
    array('name' => 'missing_default3')
))) && p() && e('1'); // 步骤5：缺少default键的过滤器结构，应返回true
r($pivotTest->isFiltersAllEmptyTest(array(
    array('name' => 'product', 'default' => 'zentao'),
    array('name' => 'module', 'default' => 'bug'),
    array('name' => 'version', 'default' => '1.0')
))) && p() && e('0'); // 步骤6：所有default值都非空的过滤器，应返回false
r($pivotTest->isFiltersAllEmptyTest(array(
    array('name' => 'string_filter', 'default' => ''),
    array('name' => 'numeric_filter', 'default' => 0),
    array('name' => 'string_zero', 'default' => '0')
))) && p() && e('0'); // 步骤7：空字符串与数值0和'0'混合测试，'0'不为empty所以应返回false
r($pivotTest->isFiltersAllEmptyTest(array(
    array('name' => 'array_filter', 'default' => array('key' => 'value')),
    array('name' => 'empty_filter', 'default' => '')
))) && p() && e('0'); // 步骤8：非空数组与空值混合测试，非空数组存在时应返回false