#!/usr/bin/env php
<?php

/**

title=测试 customModel::getURSRPairs();
timeout=0
cid=15906

- 步骤1：正常情况获取需求概念集合
 - 属性1 @软件需求
 - 属性2 @研发需求
 - 属性3 @软需
 - 属性4 @故事
 - 属性5 @需求
- 步骤2：URAndSR配置开启时
 - 属性1 @用户需求/软件需求
 - 属性2 @用户需求/研发需求
 - 属性3 @用户需求/软需
- 步骤3：enableER配置开启时
 - 属性1 @业务需求/用户需求/软件需求
 - 属性2 @业务需求/用户需求/研发需求
 - 属性3 @业务需求/用户需求/软需
- 步骤4：无数据情况 @0
- 步骤5：边界情况空数据 @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$customTest = new customModelTest();

// 4. 测试步骤 - 至少5个测试步骤

// 步骤1：正常情况获取需求概念集合
global $config;
$config->URAndSR = 0;
$config->enableER = 0;
r($customTest->getURSRPairsTestWithData()) && p('1,2,3,4,5') && e('软件需求,研发需求,软需,故事,需求'); // 步骤1：正常情况获取需求概念集合

// 步骤2：URAndSR配置开启时
$config->URAndSR = 1;
$config->enableER = 0;
r($customTest->getURSRPairsTestWithData()) && p('1,2,3') && e('用户需求/软件需求,用户需求/研发需求,用户需求/软需'); // 步骤2：URAndSR配置开启时

// 步骤3：enableER配置开启时
$config->URAndSR = 1;
$config->enableER = 1;
r($customTest->getURSRPairsTestWithData()) && p('1,2,3') && e('业务需求/用户需求/软件需求,业务需求/用户需求/研发需求,业务需求/用户需求/软需'); // 步骤3：enableER配置开启时

// 步骤4：测试无数据情况
$config->URAndSR = 0;
$config->enableER = 0;
r(count($customTest->getURSRPairsTestWithCleanData())) && p() && e(0); // 步骤4：无数据情况

// 步骤5：测试边界情况
$config->URAndSR = 0;
$config->enableER = 0;
r(count($customTest->getURSRPairsTestWithCleanData())) && p() && e(0); // 步骤5：边界情况空数据