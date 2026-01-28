#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::getValidDBSettings();
timeout=0
cid=16804

- 步骤1：正常情况，验证service字段属性service @mysql
- 步骤2：正常情况，验证name字段属性name @testdb
- 步骤3：service为空时应该返回原设置属性name @testdb
- 步骤4：name为空时应该返回原设置属性service @mysql
- 步骤5：递归超过10次的边界情况 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$instanceTest = new instanceModelTest();

// 4. 准备测试数据
$validSettings = new stdclass();
$validSettings->service = 'mysql';
$validSettings->name = 'testdb';
$validSettings->user = 'testuser';
$validSettings->namespace = 'default';

$emptyServiceSettings = new stdclass();
$emptyServiceSettings->service = '';
$emptyServiceSettings->name = 'testdb';
$emptyServiceSettings->user = 'testuser';

$emptyNameSettings = new stdclass();
$emptyNameSettings->service = 'mysql';
$emptyNameSettings->name = '';
$emptyNameSettings->user = 'testuser';

$invalidSettings = new stdclass();
$invalidSettings->service = 'mysql';
$invalidSettings->name = 'invaliddb';
$invalidSettings->user = 'invaliduser';
$invalidSettings->namespace = 'default';

// 5. 强制要求：必须包含至少5个测试步骤
r($instanceTest->getValidDBSettingsTest($validSettings, 'defaultuser', 'defaultdb', 1)) && p('service') && e('mysql'); // 步骤1：正常情况，验证service字段
r($instanceTest->getValidDBSettingsTest($validSettings, 'defaultuser', 'defaultdb', 1)) && p('name') && e('testdb'); // 步骤2：正常情况，验证name字段  
r($instanceTest->getValidDBSettingsTest($emptyServiceSettings, 'defaultuser', 'defaultdb', 1)) && p('name') && e('testdb'); // 步骤3：service为空时应该返回原设置
r($instanceTest->getValidDBSettingsTest($emptyNameSettings, 'defaultuser', 'defaultdb', 1)) && p('service') && e('mysql'); // 步骤4：name为空时应该返回原设置
r($instanceTest->getValidDBSettingsTest($validSettings, 'defaultuser', 'defaultdb', 11)) && p() && e('0'); // 步骤5：递归超过10次的边界情况