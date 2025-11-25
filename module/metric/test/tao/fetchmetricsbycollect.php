#!/usr/bin/env php
<?php

/**

title=测试 metricTao::fetchMetricsByCollect();
timeout=0
cid=17170

- 步骤1：获取管理员收藏的全部度量项 @3
- 步骤2：获取管理员收藏的已发布阶段度量项 @1
- 步骤3：获取管理员收藏的等待阶段度量项 @2
- 步骤4：获取不存在阶段的度量项 @0
- 步骤5：阶段参数为空值测试 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';

// 用户登录
su('admin');

// 创建测试实例
$metricTest = new metricTest();

// 设置收藏和stage
$metricTest->setCollector('1', 'admin');
$metricTest->setCollector('2', 'admin');
$metricTest->setCollector('10', 'admin');

// 设置不同的stage值以测试过滤功能
// 将ID 1,2设为wait，ID 10设为released
$metric1 = new stdclass();
$metric1->stage = 'wait';
$metricTest->objectModel->updateMetricFields('1', $metric1);
$metricTest->objectModel->updateMetricFields('2', $metric1);

r(count($metricTest->fetchMetricsByCollect('all'))) && p() && e('3'); // 步骤1：获取管理员收藏的全部度量项
r(count($metricTest->fetchMetricsByCollect('released'))) && p() && e('1'); // 步骤2：获取管理员收藏的已发布阶段度量项
r(count($metricTest->fetchMetricsByCollect('wait'))) && p() && e('2'); // 步骤3：获取管理员收藏的等待阶段度量项
r(count($metricTest->fetchMetricsByCollect('nonexistent'))) && p() && e('0'); // 步骤4：获取不存在阶段的度量项
r(count($metricTest->fetchMetricsByCollect(''))) && p() && e('0'); // 步骤5：阶段参数为空值测试