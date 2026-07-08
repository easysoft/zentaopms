#!/usr/bin/env php
<?php

/**

title=测试 customModel::disableFeaturesByMode();
timeout=0
cid=15894

- 测试步骤1：全生命周期管理模式 @0
- 测试步骤2：轻量级管理模式 @1
- 测试步骤3：无效模式参数 @0
- 测试步骤4：空字符串模式参数 @0
- 测试步骤5：验证URAndSR和enableER配置 @0
- 测试步骤6：无业务需求数据但有用户需求数据时禁用业务需求 @1
- 测试步骤7：有业务需求数据但没有用户需求数据时仍启用用户需求 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

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

$customTester = new customModelTest();

r($customTester->disableFeaturesByModeTest('ALM')) && p() && e('otherOA'); // 测试步骤1：全生命周期管理模式
$light = $customTester->disableFeaturesByModeTest('light');
r(strpos($light, 'productTrack') !== false) && p() && e('1'); // 测试步骤2：轻量级管理模式
r($customTester->disableFeaturesByModeTest('invalid')) && p() && e('otherOA'); // 测试步骤3：无效模式参数
r($customTester->disableFeaturesByModeTest('')) && p() && e('otherOA'); // 测试步骤4：空字符串模式参数
$light = $customTester->disableFeaturesByModeTestWithURAndSR('light');
r(strpos($light, 'agileplusMeasrecord') !== false) && p() && e('0'); // 测试步骤5：验证URAndSR和enableER配置

zenData('story')->gen(0);
$storyTable = zenData('story');
$storyTable->type->range('requirement');
$storyTable->deleted->range('0');
$storyTable->gen(3);
$light = $customTester->disableFeaturesByModeTestWithURAndSR('light');
list($disabledFeatures, $URAndSR, $enableER) = explode('|', $light);
r(strpos(",$disabledFeatures,", ',productER,') !== false && $URAndSR == '1' && $enableER == '0') && p() && e('1'); // 测试步骤6：无业务需求数据但有用户需求数据时禁用业务需求

zenData('story')->gen(0);
$storyTable = zenData('story');
$storyTable->type->range('epic');
$storyTable->deleted->range('0');
$storyTable->gen(3);
$light = $customTester->disableFeaturesByModeTestWithURAndSR('light');
list($disabledFeatures, $URAndSR, $enableER) = explode('|', $light);
r(strpos(",$disabledFeatures,", ',productUR,') === false && strpos(",$disabledFeatures,", ',productER,') === false && $URAndSR == '1' && $enableER == '1') && p() && e('1'); // 测试步骤7：有业务需求数据但没有用户需求数据时仍启用用户需求