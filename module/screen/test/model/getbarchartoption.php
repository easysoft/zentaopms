#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getBarChartOption();
timeout=0
cid=0

- 测试基本方法调用属性result @success
- 测试空SQL情况属性result @success
- 测试空参数情况属性result @success
- 测试数据集生成属性result @success
- 测试维度处理属性result @success

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

// 2. 简化数据准备，避免 zendata 输出干扰

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$screenTest = new screenTest();

// 5. 测试步骤（必须≥5个）
r($screenTest->testGetBarChartOptionBasic()) && p('result') && e('success'); // 测试基本方法调用
r($screenTest->testGetBarChartOptionEmptySQL()) && p('result') && e('success'); // 测试空SQL情况
r($screenTest->testGetBarChartOptionNullParams()) && p('result') && e('success'); // 测试空参数情况
r($screenTest->testGetBarChartOptionDataset()) && p('result') && e('success'); // 测试数据集生成
r($screenTest->testGetBarChartOptionDimensions()) && p('result') && e('success'); // 测试维度处理