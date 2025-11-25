#!/usr/bin/env php
<?php

/**

title=测试 indexZen::checkShowFeatures();
timeout=0
cid=16765

- 执行indexTest模块的checkShowFeaturesTest方法  @0
- 执行indexTest模块的checkShowFeaturesTest方法  @0
- 执行indexTest模块的checkShowFeaturesTest方法  @1
- 执行indexTest模块的checkShowFeaturesTest方法  @1
- 执行indexTest模块的checkShowFeaturesTest方法  @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/index.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$indexTest = new indexZenTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
// 步骤1：测试版本为ipd时返回false
global $config;
$originalEdition = $config->edition;
$config->edition = 'ipd';
r($indexTest->checkShowFeaturesTest()) && p() && e('0');

// 步骤2：测试当用户在所有功能的skip配置中时返回false
$config->edition = 'max';
$originalGlobal = isset($config->global) ? $config->global : new stdClass();
$config->global->skipIntroduction = 'admin,user1';
$config->global->skipTutorial = 'admin,user2';
$config->global->skipYoungBlueTheme = 'admin,user3';
$config->global->skipVisions = 'admin,user4';
$config->global->skipAiPrompts = 'admin,user5';
$config->global->skipPromptDesign = 'admin,user6';
$config->global->skipPromptExec = 'admin,user7';
r($indexTest->checkShowFeaturesTest()) && p() && e('0');

// 步骤3：测试当用户不在skip配置中时返回true  
$config->global->skipIntroduction = 'user1,user2';
$config->global->skipTutorial = 'user3,user4';
$config->global->skipYoungBlueTheme = 'user5,user6';
$config->global->skipVisions = 'user7,user8';
$config->global->skipAiPrompts = 'user9,user10';
$config->global->skipPromptDesign = 'user11,user12';
$config->global->skipPromptExec = 'user13,user14';
r($indexTest->checkShowFeaturesTest()) && p() && e('1');

// 步骤4：测试当没有skip配置时返回true
$config->global = new stdClass();
r($indexTest->checkShowFeaturesTest()) && p() && e('1');

// 步骤5：测试当newFeatures为空时返回false
$originalNewFeatures = $config->newFeatures;
$config->newFeatures = array();
r($indexTest->checkShowFeaturesTest()) && p() && e('0');

// 恢复原始配置
$config->edition = $originalEdition;
$config->global = $originalGlobal;
$config->newFeatures = $originalNewFeatures;