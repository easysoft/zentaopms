#!/usr/bin/env php
<?php

/**

title=测试 repoZen::buildBranchRuleData();
timeout=0
cid=0

- 步骤1：完整权限配置属性deleteUser @admin
- 步骤2：Delete权限限制属性deleteUser @~~
- 步骤3：Update权限限制属性updateUser @~~
- 步骤4：强制推送权限限制属性forcePushUser @~~
- 步骤5：合并From设为all属性sourceBranch @~~
- 步骤6：合并To设为all属性targetBranch @~~
- 步骤7：混合模式属性deleteUser @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

// 2. zendata数据准备
zenData('user')->gen(5);
zenData('repo')->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$repoTest = new repoZenTest();

// 5. 准备测试数据对象

// 完整权限配置
$fullData = new stdclass();
$fullData->radioForAllowDelete = 'specified';
$fullData->radioForAllowUpdate = 'specified';
$fullData->radioForAllowForcePush = 'specified';
$fullData->radioForAllowMergeFrom = 'specified';
$fullData->radioForAllowMergeTo = 'specified';
$fullData->userAllowDeleteGroup = array('admin');
$fullData->userAllowUpdateGroup = array('admin', 'user2');
$fullData->userAllowForcePushGroup = array('admin', 'user3');
$fullData->branchTypeAllowMergeFromGroup = array('feature', 'hotfix');
$fullData->branchTypeAllowMergeToGroup = array('master', 'develop');

// Delete权限限制
$deletePrivData = new stdclass();
$deletePrivData->radioForAllowDelete = 'hasPriv';
$deletePrivData->radioForAllowUpdate = 'specified';
$deletePrivData->radioForAllowForcePush = 'specified';
$deletePrivData->radioForAllowMergeFrom = 'specified';
$deletePrivData->radioForAllowMergeTo = 'specified';
$deletePrivData->userAllowDeleteGroup = array('admin', 'user1');
$deletePrivData->userAllowUpdateGroup = array('admin', 'user2');
$deletePrivData->userAllowForcePushGroup = array('admin', 'user3');
$deletePrivData->branchTypeAllowMergeFromGroup = array('feature', 'hotfix');
$deletePrivData->branchTypeAllowMergeToGroup = array('master', 'develop');

// Update权限限制
$updatePrivData = new stdclass();
$updatePrivData->radioForAllowDelete = 'specified';
$updatePrivData->radioForAllowUpdate = 'hasPriv';
$updatePrivData->radioForAllowForcePush = 'specified';
$updatePrivData->radioForAllowMergeFrom = 'specified';
$updatePrivData->radioForAllowMergeTo = 'specified';
$updatePrivData->userAllowDeleteGroup = array('admin', 'user1');
$updatePrivData->userAllowUpdateGroup = array('admin', 'user2');
$updatePrivData->userAllowForcePushGroup = array('admin', 'user3');
$updatePrivData->branchTypeAllowMergeFromGroup = array('feature', 'hotfix');
$updatePrivData->branchTypeAllowMergeToGroup = array('master', 'develop');

// 强制推送权限限制
$forcePushPrivData = new stdclass();
$forcePushPrivData->radioForAllowDelete = 'specified';
$forcePushPrivData->radioForAllowUpdate = 'specified';
$forcePushPrivData->radioForAllowForcePush = 'hasPriv';
$forcePushPrivData->radioForAllowMergeFrom = 'specified';
$forcePushPrivData->radioForAllowMergeTo = 'specified';
$forcePushPrivData->userAllowDeleteGroup = array('admin', 'user1');
$forcePushPrivData->userAllowUpdateGroup = array('admin', 'user2');
$forcePushPrivData->userAllowForcePushGroup = array('admin', 'user3');
$forcePushPrivData->branchTypeAllowMergeFromGroup = array('feature', 'hotfix');
$forcePushPrivData->branchTypeAllowMergeToGroup = array('master', 'develop');

// 合并From设为all
$mergeFromAllData = new stdclass();
$mergeFromAllData->radioForAllowDelete = 'specified';
$mergeFromAllData->radioForAllowUpdate = 'specified';
$mergeFromAllData->radioForAllowForcePush = 'specified';
$mergeFromAllData->radioForAllowMergeFrom = 'all';
$mergeFromAllData->radioForAllowMergeTo = 'specified';
$mergeFromAllData->userAllowDeleteGroup = array('admin', 'user1');
$mergeFromAllData->userAllowUpdateGroup = array('admin', 'user2');
$mergeFromAllData->userAllowForcePushGroup = array('admin', 'user3');
$mergeFromAllData->branchTypeAllowMergeFromGroup = array('feature', 'hotfix');
$mergeFromAllData->branchTypeAllowMergeToGroup = array('master', 'develop');

// 合并To设为all
$mergeToAllData = new stdclass();
$mergeToAllData->radioForAllowDelete = 'specified';
$mergeToAllData->radioForAllowUpdate = 'specified';
$mergeToAllData->radioForAllowForcePush = 'specified';
$mergeToAllData->radioForAllowMergeFrom = 'specified';
$mergeToAllData->radioForAllowMergeTo = 'all';
$mergeToAllData->userAllowDeleteGroup = array('admin', 'user1');
$mergeToAllData->userAllowUpdateGroup = array('admin', 'user2');
$mergeToAllData->userAllowForcePushGroup = array('admin', 'user3');
$mergeToAllData->branchTypeAllowMergeFromGroup = array('feature', 'hotfix');
$mergeToAllData->branchTypeAllowMergeToGroup = array('master', 'develop');

// 混合模式：多个权限同时限制
$mixedData = new stdclass();
$mixedData->radioForAllowDelete = 'hasPriv';
$mixedData->radioForAllowUpdate = 'hasPriv';
$mixedData->radioForAllowForcePush = 'hasPriv';
$mixedData->radioForAllowMergeFrom = 'all';
$mixedData->radioForAllowMergeTo = 'all';
$mixedData->userAllowDeleteGroup = array('admin', 'user1');
$mixedData->userAllowUpdateGroup = array('admin', 'user2');
$mixedData->userAllowForcePushGroup = array('admin', 'user3');
$mixedData->branchTypeAllowMergeFromGroup = array('feature', 'hotfix');
$mixedData->branchTypeAllowMergeToGroup = array('master', 'develop');

// 6. 执行测试步骤（至少5个）
r($repoTest->buildBranchRuleDataTest(1, 1, 'master', $fullData)) && p('deleteUser') && e('admin'); // 步骤1：完整权限配置
r($repoTest->buildBranchRuleDataTest(1, 1, 'master', $deletePrivData)) && p('deleteUser') && e('~~'); // 步骤2：Delete权限限制
r($repoTest->buildBranchRuleDataTest(1, 1, 'master', $updatePrivData)) && p('updateUser') && e('~~'); // 步骤3：Update权限限制
r($repoTest->buildBranchRuleDataTest(1, 1, 'master', $forcePushPrivData)) && p('forcePushUser') && e('~~'); // 步骤4：强制推送权限限制
r($repoTest->buildBranchRuleDataTest(1, 1, 'master', $mergeFromAllData)) && p('sourceBranch') && e('~~'); // 步骤5：合并From设为all
r($repoTest->buildBranchRuleDataTest(1, 1, 'master', $mergeToAllData)) && p('targetBranch') && e('~~'); // 步骤6：合并To设为all
r($repoTest->buildBranchRuleDataTest(1, 1, 'master', $mixedData)) && p('deleteUser') && e('~~'); // 步骤7：混合模式
