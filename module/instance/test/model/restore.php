#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::restore();
timeout=0
cid=16813

- 步骤1：正常运行实例还原（CNE接口返回失败） @0
- 步骤2：已停止实例还原（CNE接口返回失败） @0
- 步骤3：使用无效备份名称还原 @0
- 步骤4：使用空备份名称还原 @0
- 步骤5：使用无效用户还原 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/instance.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('user')->loadYaml('user_restore', false, 2)->gen(5);
zendata('space')->loadYaml('space_restore', false, 2)->gen(1);
zendata('instance')->loadYaml('instance_restore', false, 2)->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$instanceTest = new instanceTest();

// 构造测试数据
$runningInstance = new stdclass();
$runningInstance->id = 1;
$runningInstance->name = 'Test-GitLab';
$runningInstance->status = 'running';
$runningInstance->chart = 'gitlab';
$runningInstance->k8name = 'test-gitlab-restore';
$runningInstance->spaceData = new stdclass();
$runningInstance->spaceData->k8space = 'quickon-user';

$stoppedInstance = new stdclass();
$stoppedInstance->id = 2;
$stoppedInstance->name = 'Test-Subversion';
$stoppedInstance->status = 'stopped';
$stoppedInstance->chart = 'subversion';
$stoppedInstance->k8name = 'test-subversion-restore';
$stoppedInstance->spaceData = new stdclass();
$stoppedInstance->spaceData->k8space = 'quickon-user';

$abnormalInstance = new stdclass();
$abnormalInstance->id = 3;
$abnormalInstance->name = 'Test-Jenkins';
$abnormalInstance->status = 'abnormal';
$abnormalInstance->chart = 'jenkins';
$abnormalInstance->k8name = 'test-jenkins-restore';
$abnormalInstance->spaceData = new stdclass();
$abnormalInstance->spaceData->k8space = 'quickon-user';

$validUser = new stdclass();
$validUser->account = 'admin';
$validUser->realname = '系统管理员';

$invalidUser = new stdclass();
$invalidUser->account = 'nonexist';
$invalidUser->realname = '不存在用户';

$validBackupName = 'backup_20240101_120000';
$invalidBackupName = 'invalid_backup_name';
$emptyBackupName = '';

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($instanceTest->restoreTest($runningInstance, $validUser, $validBackupName)) && p() && e('0'); // 步骤1：正常运行实例还原（CNE接口返回失败）
r($instanceTest->restoreTest($stoppedInstance, $validUser, $validBackupName)) && p() && e('0');  // 步骤2：已停止实例还原（CNE接口返回失败）
r($instanceTest->restoreTest($abnormalInstance, $validUser, $invalidBackupName)) && p() && e('0'); // 步骤3：使用无效备份名称还原
r($instanceTest->restoreTest($runningInstance, $validUser, $emptyBackupName)) && p() && e('0');   // 步骤4：使用空备份名称还原
r($instanceTest->restoreTest($stoppedInstance, $invalidUser, $validBackupName)) && p() && e('0'); // 步骤5：使用无效用户还原