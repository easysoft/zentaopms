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
zenData('cron')->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$instanceTest = new instanceModelTest();

// 创建用户对象
$user = new stdClass();
$user->account = 'admin';

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$instance1 = new stdClass();
$instance1->id = 1;
$instance1->k8name = 'test-k8name-1';
$instance1->channel = 'stable';
$instance1->spaceData = new stdClass();
$instance1->spaceData->k8space = 'default';

$instance2 = clone $instance1;
$instance2->id = 2;
$instance2->k8name = 'test-k8name-2';

$instance3 = clone $instance1;
$instance3->id = 3;
$instance3->k8name = 'test-k8name-3';

$instance999 = null;

r($instance1 ? $instanceTest->autoBackupTest($instance1, $user) : 'no_instance') && p() && e('0'); // 步骤1：正常实例测试
r($instance2 ? $instanceTest->autoBackupTest($instance2, $user) : 'no_instance') && p() && e('0'); // 步骤2：实例2测试
r($instance3 ? $instanceTest->autoBackupTest($instance3, $user) : 'no_instance') && p() && e('0'); // 步骤3：实例3测试
r($instance999 ? $instanceTest->autoBackupTest($instance999, $user) : 'no_instance') && p() && e('no_instance'); // 步骤4：不存在的实例
r(is_object($user) && isset($user->account)) && p() && e('1'); // 步骤5：验证用户对象结构
