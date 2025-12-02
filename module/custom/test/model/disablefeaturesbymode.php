#!/usr/bin/env php
<?php

/**

title=测试 customModel::disableFeaturesByMode();
timeout=0
cid=15894

- 测试步骤1：全生命周期管理模式 @0
- 测试步骤2：轻量级管理模式 @productER,waterfall,waterfallplus,scrumMeasrecord,agileplusMeasrecord,productTrack,productRoadmap

- 测试步骤3：无效模式参数 @0
- 测试步骤4：空字符串模式参数 @0
- 测试步骤5：验证URAndSR和enableER配置 @productER,waterfall,waterfallplus,scrumMeasrecord,agileplusMeasrecord,productTrack,productRoadmap|1|0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

// 准备测试数据
ob_start();
zenData('project')->gen(10);
zenData('product')->gen(10);
zenData('story')->gen(5);
zenData('user')->gen(8);
zenData('assetlib')->gen(0);
zenData('issue')->gen(0);
zenData('risk')->gen(0);
zenData('opportunity')->gen(0);
zenData('meeting')->gen(0);
zenData('auditplan')->gen(0);
zenData('process')->gen(0);
zenData('measrecord')->gen(0);
ob_end_clean();

su('admin');

$customTester = new customTest();

r($customTester->disableFeaturesByModeTest('ALM')) && p() && e('0'); // 测试步骤1：全生命周期管理模式
r($customTester->disableFeaturesByModeTest('light')) && p() && e('productER,waterfall,waterfallplus,scrumMeasrecord,agileplusMeasrecord,productTrack,productRoadmap'); // 测试步骤2：轻量级管理模式
r($customTester->disableFeaturesByModeTest('invalid')) && p() && e('0'); // 测试步骤3：无效模式参数
r($customTester->disableFeaturesByModeTest('')) && p() && e('0'); // 测试步骤4：空字符串模式参数
r($customTester->disableFeaturesByModeTestWithURAndSR('light')) && p() && e('productER,waterfall,waterfallplus,scrumMeasrecord,agileplusMeasrecord,productTrack,productRoadmap|1|0'); // 测试步骤5：验证URAndSR和enableER配置