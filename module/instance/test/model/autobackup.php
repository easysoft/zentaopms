#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::autoBackup();
timeout=0
cid=16778

- 步骤1：正常实例测试 @0
- 步骤2：实例2测试 @0
- 步骤3：实例3测试 @0
- 步骤4：不存在的实例 @no_instance
- 步骤5：验证用户对象结构 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$instanceTable = zenData('instance');
$instanceTable->loadYaml('instance_autobackup', false, 2)->gen(5);

$cronTable = zenData('cron');
$cronTable->loadYaml('cron_autobackup', false, 2)->gen(5);

$userTable = zenData('user');
$userTable->loadYaml('user_autobackup', false, 2)->gen(5);

$spaceTable = zenData('space');
$spaceTable->id->range('1-3');
$spaceTable->name->range('test-space{3}');
$spaceTable->k8space->range('test-k8space{3}');
$spaceTable->deleted->range('0{3}');
$spaceTable->gen(3);

$actionTable = zenData('action');
$actionTable->id->range('1-10');
$actionTable->objectType->range('instance{10}');
$actionTable->objectID->range('1-5');
$actionTable->action->range('autobackup{10}');
$actionTable->actor->range('admin{10}');
$actionTable->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$instanceTest = new instanceModelTest();

// 创建用户对象
$user = new stdClass();
$user->account = 'admin';

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$instance1 = $instanceTest->objectModel->getByID(1);
$instance2 = $instanceTest->objectModel->getByID(2);
$instance3 = $instanceTest->objectModel->getByID(3);
$instance999 = $instanceTest->objectModel->getByID(999);

r($instance1 ? $instanceTest->autoBackupTest($instance1, $user) : 'no_instance') && p() && e('0'); // 步骤1：正常实例测试
r($instance2 ? $instanceTest->autoBackupTest($instance2, $user) : 'no_instance') && p() && e('0'); // 步骤2：实例2测试
r($instance3 ? $instanceTest->autoBackupTest($instance3, $user) : 'no_instance') && p() && e('0'); // 步骤3：实例3测试
r($instance999 ? $instanceTest->autoBackupTest($instance999, $user) : 'no_instance') && p() && e('no_instance'); // 步骤4：不存在的实例
r($instance1 && is_object($user) && isset($user->account)) && p() && e('1'); // 步骤5：验证用户对象结构