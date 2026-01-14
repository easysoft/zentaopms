#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getInstallDate();
timeout=0
cid=17101

- 步骤1：从config表获取有效安装日期 @2024
- 步骤2：config表中安装日期为无效年份时从action表获取 @2024-01-10 10:00:00
- 步骤3：config表中没有安装日期记录时从action表获取 @2024-01-10 10:00:00
- 步骤4：config表和action表都没有数据的边界情况 @0
- 步骤5：config表中安装日期为空值时从action表获取 @2024-01-14 10:00:00

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('config');
$table->owner->range('system');
$table->section->range('global');
$table->key->range('installedDate');
$table->value->range('2024-01-15');
$table->gen(0); // 先不生成数据，用于手动控制

$actionTable = zenData('action');
$actionTable->objectType->range('user');
$actionTable->objectID->range('1');  
$actionTable->actor->range('admin');
$actionTable->action->range('login');
$actionTable->date->range('`2024-01-10 10:00:00`');
$actionTable->gen(0); // 先不生成数据，用于手动控制

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricTest = new metricModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 步骤1：准备有效安装日期数据并测试
$table->gen(1);
$actionTable->gen(1);
r($metricTest->getInstallDateTest()) && p() && e('2024'); // 步骤1：从config表获取有效安装日期

// 步骤2：修改config表为无效年份
global $tester;
$tester->dao->update(TABLE_CONFIG)->set('value')->eq('0000-12-31')->where('section')->eq('global')->andWhere('key')->eq('installedDate')->exec();
r($metricTest->getInstallDateTest()) && p() && e('2024-01-10 10:00:00'); // 步骤2：config表中安装日期为无效年份时从action表获取

// 步骤3：删除config表数据
$tester->dao->delete()->from(TABLE_CONFIG)->where('section')->eq('global')->andWhere('key')->eq('installedDate')->exec();
r($metricTest->getInstallDateTest()) && p() && e('2024-01-10 10:00:00'); // 步骤3：config表中没有安装日期记录时从action表获取

// 步骤4：删除所有数据
$tester->dao->delete()->from(TABLE_ACTION)->exec();
r($metricTest->getInstallDateTest()) && p() && e('0'); // 步骤4：config表和action表都没有数据的边界情况

// 步骤5：重新插入数据测试空值config
$actionData = new stdClass();
$actionData->objectType = 'user';
$actionData->objectID = 1;
$actionData->actor = 'admin';
$actionData->action = 'login';
$actionData->date = '2024-01-14 10:00:00';
$tester->dao->insert(TABLE_ACTION)->data($actionData)->exec();

$configData = new stdClass();
$configData->vision = '';
$configData->owner = 'system';
$configData->module = '';
$configData->section = 'global';
$configData->key = 'installedDate';
$configData->value = '';
$tester->dao->insert(TABLE_CONFIG)->data($configData)->exec();
r($metricTest->getInstallDateTest()) && p() && e('2024-01-14 10:00:00'); // 步骤5：config表中安装日期为空值时从action表获取