#!/usr/bin/env php
<?php

/**

title=测试 metricZen::getViewTableWidth();
timeout=0
cid=0

- 步骤3：空headers数组 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例
$metricZenTest = new metricZenTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($metricZenTest->getViewTableWidthZenTest(array(
    array('name' => 'col1', 'width' => 100),
    array('name' => 'col2', 'width' => 200),
    array('name' => 'col3', 'width' => 50)
))) && p() && e(351); // 步骤1：正常headers数组包含width属性

r($metricZenTest->getViewTableWidthZenTest(array(
    array('name' => 'col1', 'width' => 100),
    array('name' => 'col2'),
    array('name' => 'col3', 'width' => 50)
))) && p() && e(311); // 步骤2：headers数组部分元素缺少width属性

r($metricZenTest->getViewTableWidthZenTest(array())) && p() && e(1); // 步骤3：空headers数组

r($metricZenTest->getViewTableWidthZenTest(array(
    array('name' => 'col1', 'width' => 0),
    array('name' => 'col2', 'width' => 100),
    array('name' => 'col3', 'width' => 0)
))) && p() && e(101); // 步骤4：headers数组包含0宽度元素

r($metricZenTest->getViewTableWidthZenTest(array(
    array('name' => 'col1', 'width' => -50),
    array('name' => 'col2', 'width' => 200),
    array('name' => 'col3', 'width' => 100)
))) && p() && e(251); // 步骤5：headers数组包含负数宽度