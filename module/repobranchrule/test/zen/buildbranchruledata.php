#!/usr/bin/env php
<?php

/**

title=测试 repobranchruleZen::buildBranchRuleData();
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
include dirname(__FILE__, 2) . '/lib/repobranchrulezen.unittest.class.php';

// 2. zendata数据准备
zenData('user')->gen(5);
zenData('repo')->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$repoTest = new repobranchruleZenTest();

// 5. 准备测试数据对象（使用新的数据格式：allowXxx['option'] 和 allowXxx['value']）

// 完整权限配置
$fullData = new stdclass();
$fullData->allowCreate    = array('option' => 'specify', 'value' => array('admin', 'user1'));
$fullData->allowDelete    = array('option' => 'specify', 'value' => array('admin'));
$fullData->allowUpdate    = array('option' => 'specify', 'value' => array('admin', 'user2'));
$fullData->allowForcePush = array('option' => 'specify', 'value' => array('admin', 'user3'));
$fullData->allowMergeFrom = array('option' => 'specify', 'value' => array('feature', 'hotfix'));
$fullData->allowMergeTo   = array('option' => 'specify', 'value' => array('master', 'develop'));

// Delete权限限制（option为hasPriv时value会被清空）
$deletePrivData = new stdclass();
$deletePrivData->allowCreate    = array('option' => 'specify', 'value' => array('admin', 'user1'));
$deletePrivData->allowDelete    = array('option' => 'hasPriv', 'value' => array('admin', 'user1'));
$deletePrivData->allowUpdate    = array('option' => 'specify', 'value' => array('admin', 'user2'));
$deletePrivData->allowForcePush = array('option' => 'specify', 'value' => array('admin', 'user3'));
$deletePrivData->allowMergeFrom = array('option' => 'specify', 'value' => array('feature', 'hotfix'));
$deletePrivData->allowMergeTo   = array('option' => 'specify', 'value' => array('master', 'develop'));

// Update权限限制（option为hasPriv时value会被清空）
$updatePrivData = new stdclass();
$updatePrivData->allowCreate    = array('option' => 'specify', 'value' => array('admin', 'user1'));
$updatePrivData->allowDelete    = array('option' => 'specify', 'value' => array('admin', 'user1'));
$updatePrivData->allowUpdate    = array('option' => 'hasPriv', 'value' => array('admin', 'user2'));
$updatePrivData->allowForcePush = array('option' => 'specify', 'value' => array('admin', 'user3'));
$updatePrivData->allowMergeFrom = array('option' => 'specify', 'value' => array('feature', 'hotfix'));
$updatePrivData->allowMergeTo   = array('option' => 'specify', 'value' => array('master', 'develop'));

// 强制推送权限限制（option为hasPriv时value会被清空）
$forcePushPrivData = new stdclass();
$forcePushPrivData->allowCreate    = array('option' => 'specify', 'value' => array('admin', 'user1'));
$forcePushPrivData->allowDelete    = array('option' => 'specify', 'value' => array('admin', 'user1'));
$forcePushPrivData->allowUpdate    = array('option' => 'specify', 'value' => array('admin', 'user2'));
$forcePushPrivData->allowForcePush = array('option' => 'hasPriv', 'value' => array('admin', 'user3'));
$forcePushPrivData->allowMergeFrom = array('option' => 'specify', 'value' => array('feature', 'hotfix'));
$forcePushPrivData->allowMergeTo   = array('option' => 'specify', 'value' => array('master', 'develop'));

// 合并From设为all（option为all时value会被清空）
$mergeFromAllData = new stdclass();
$mergeFromAllData->allowCreate    = array('option' => 'specify', 'value' => array('admin', 'user1'));
$mergeFromAllData->allowDelete    = array('option' => 'specify', 'value' => array('admin', 'user1'));
$mergeFromAllData->allowUpdate    = array('option' => 'specify', 'value' => array('admin', 'user2'));
$mergeFromAllData->allowForcePush = array('option' => 'specify', 'value' => array('admin', 'user3'));
$mergeFromAllData->allowMergeFrom = array('option' => 'all', 'value' => array('feature', 'hotfix'));
$mergeFromAllData->allowMergeTo   = array('option' => 'specify', 'value' => array('master', 'develop'));

// 合并To设为all（option为all时value会被清空）
$mergeToAllData = new stdclass();
$mergeToAllData->allowCreate    = array('option' => 'specify', 'value' => array('admin', 'user1'));
$mergeToAllData->allowDelete    = array('option' => 'specify', 'value' => array('admin', 'user1'));
$mergeToAllData->allowUpdate    = array('option' => 'specify', 'value' => array('admin', 'user2'));
$mergeToAllData->allowForcePush = array('option' => 'specify', 'value' => array('admin', 'user3'));
$mergeToAllData->allowMergeFrom = array('option' => 'specify', 'value' => array('feature', 'hotfix'));
$mergeToAllData->allowMergeTo   = array('option' => 'all', 'value' => array('master', 'develop'));

// 混合模式：多个权限同时限制
$mixedData = new stdclass();
$mixedData->allowCreate    = array('option' => 'hasPriv', 'value' => array('admin', 'user1'));
$mixedData->allowDelete    = array('option' => 'hasPriv', 'value' => array('admin', 'user1'));
$mixedData->allowUpdate    = array('option' => 'hasPriv', 'value' => array('admin', 'user2'));
$mixedData->allowForcePush = array('option' => 'hasPriv', 'value' => array('admin', 'user3'));
$mixedData->allowMergeFrom = array('option' => 'all', 'value' => array('feature', 'hotfix'));
$mixedData->allowMergeTo   = array('option' => 'all', 'value' => array('master', 'develop'));

// 6. 执行测试步骤（至少5个）
r($repoTest->buildBranchRuleDataTest(1, 1, 'master', $fullData)) && p('deleteUser') && e('admin'); // 步骤1：完整权限配置
r($repoTest->buildBranchRuleDataTest(1, 1, 'master', $deletePrivData)) && p('deleteUser') && e('~~'); // 步骤2：Delete权限限制
r($repoTest->buildBranchRuleDataTest(1, 1, 'master', $updatePrivData)) && p('updateUser') && e('~~'); // 步骤3：Update权限限制
r($repoTest->buildBranchRuleDataTest(1, 1, 'master', $forcePushPrivData)) && p('forcePushUser') && e('~~'); // 步骤4：强制推送权限限制
r($repoTest->buildBranchRuleDataTest(1, 1, 'master', $mergeFromAllData)) && p('sourceBranch') && e('~~'); // 步骤5：合并From设为all
r($repoTest->buildBranchRuleDataTest(1, 1, 'master', $mergeToAllData)) && p('targetBranch') && e('~~'); // 步骤6：合并To设为all
r($repoTest->buildBranchRuleDataTest(1, 1, 'master', $mixedData)) && p('deleteUser') && e('~~'); // 步骤7：混合模式
