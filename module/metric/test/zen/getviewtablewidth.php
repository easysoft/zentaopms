#!/usr/bin/env php
<?php

/**

title=测试 metricZen::getViewTableWidth();
timeout=0
cid=0

- 步骤1：空数组边界测试 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$metricTest = new metricZenTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($metricTest->getViewTableWidthZenTest(array())) && p() && e(1); // 步骤1：空数组边界测试

r($metricTest->getViewTableWidthZenTest(array(
    array('name' => 'col1', 'width' => 100),
    array('name' => 'col2', 'width' => 200),
    array('name' => 'col3', 'width' => 50)
))) && p() && e(351); // 步骤2：正常headers包含width属性

r($metricTest->getViewTableWidthZenTest(array(
    array('name' => 'col1', 'width' => 100),
    array('name' => 'col2'),
    array('name' => 'col3', 'width' => 50)
))) && p() && e(311); // 步骤3：部分headers缺少width属性

r($metricTest->getViewTableWidthZenTest(array(
    array('name' => 'col1', 'width' => 0),
    array('name' => 'col2', 'width' => -50),
    array('name' => 'col3', 'width' => 100)
))) && p() && e(51); // 步骤4：特殊值测试width为0和负数

r($metricTest->getViewTableWidthZenTest(array(
    array('name' => 'col1'),
    array('name' => 'col2'),
    array('name' => 'col3')
))) && p() && e(481); // 步骤5：全部headers无width属性