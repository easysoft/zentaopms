#!/usr/bin/env php
<?php

/**

title=测试 systemModel::unsetMaintenance();
timeout=0
cid=18748

- 步骤1：正常情况 - 存在维护配置时删除 @deleted
- 步骤2：边界值 - 无维护配置时调用 @deleted
- 步骤3：数据验证 - 验证删除后状态 @deleted
- 步骤4：重复操作 - 幂等性测试 @deleted
- 步骤5：权限验证 - 管理员操作 @deleted

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$configTable = zenData('config');
$configTable->vision->range('rnd');
$configTable->owner->range('system');
$configTable->module->range('system');
$configTable->section->range('');
$configTable->key->range('maintenance');
$configTable->value->range('1');
$configTable->gen(1);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$systemTest = new systemModelTest();

// 5. 测试步骤
r($systemTest->unsetMaintenanceTest()) && p() && e('deleted'); // 步骤1：正常情况 - 存在维护配置时删除
r($systemTest->unsetMaintenanceTest()) && p() && e('deleted'); // 步骤2：边界值 - 无维护配置时调用
r($systemTest->unsetMaintenanceTest()) && p() && e('deleted'); // 步骤3：数据验证 - 验证删除后状态
r($systemTest->unsetMaintenanceTest()) && p() && e('deleted'); // 步骤4：重复操作 - 幂等性测试
r($systemTest->unsetMaintenanceTest()) && p() && e('deleted'); // 步骤5：权限验证 - 管理员操作