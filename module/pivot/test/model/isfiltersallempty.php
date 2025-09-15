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

// 步骤1：正常情况 - 所有默认值都为空
r($pivotTest->isFiltersAllEmptyTest(array(
    array('name' => 'filter1', 'default' => ''),
    array('name' => 'filter2', 'default' => null),
    array('name' => 'filter3', 'default' => 0)
))) && p() && e('1');

// 步骤2：边界值 - 包含非空值的过滤器
r($pivotTest->isFiltersAllEmptyTest(array(
    array('name' => 'filter1', 'default' => ''),
    array('name' => 'filter2', 'default' => 'value'),
    array('name' => 'filter3', 'default' => null)
))) && p() && e('0');

// 步骤3：异常输入 - 空数组
r($pivotTest->isFiltersAllEmptyTest(array())) && p() && e('0');

// 步骤4：权限验证 - 不同类型的空值
r($pivotTest->isFiltersAllEmptyTest(array(
    array('name' => 'filter1', 'default' => ''),
    array('name' => 'filter2', 'default' => null),
    array('name' => 'filter3', 'default' => false),
    array('name' => 'filter4', 'default' => 0),
    array('name' => 'filter5', 'default' => array())
))) && p() && e('1');

// 步骤5：业务规则 - 混合空值和非空值
r($pivotTest->isFiltersAllEmptyTest(array(
    array('name' => 'filter1', 'default' => ''),
    array('name' => 'filter2', 'default' => null),
    array('name' => 'filter3', 'default' => 'test'),
    array('name' => 'filter4', 'default' => 0),
    array('name' => 'filter5', 'default' => false)
))) && p() && e('0');