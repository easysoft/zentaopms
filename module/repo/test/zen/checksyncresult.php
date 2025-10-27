#!/usr/bin/env php
<?php

/**

title=测试 repoZen::checkSyncResult();
timeout=0
cid=0

- 执行repoTest模块的checkSyncResultTest方法，参数是$gitRepo, $branchesEmpty, 'master', 5, 'batch'  @5
- 执行repoTest模块的checkSyncResultTest方法，参数是$gitRepo, $branchesEmpty, 'master', 5, 'normal'  @finish
- 执行repoTest模块的checkSyncResultTest方法，参数是$syncedRepo, $branchesEmpty, 'master', 0, 'batch'  @0
- 执行repoTest模块的checkSyncResultTest方法，参数是$gitRepo, $branchesWithData, 'master', 0, 'normal'  @finish
- 执行repoTest模块的checkSyncResultTest方法，参数是$gitRepo, $branchesEmpty, 'master', 0, 'normal'  @finish
- 执行repoTest模块的checkSyncResultTest方法，参数是$gitlabRepo, $branchesEmpty, 'master', 0, 'normal'  @finish
- 执行repoTest模块的checkSyncResultTest方法，参数是null, $branchesEmpty, 'master', 0, 'normal'  @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('repo');
$table->id->range('1-10');
$table->name->range('repo1,repo2,repo3');
$table->SCM->range('Git{3},Gitlab{3},Subversion{3}');
$table->synced->range('0{8},1{2}');
$table->commits->range('10-100:10');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$repoTest = new repoZenTest();

// 5. 强制要求：必须包含至少5个测试步骤

// 准备测试数据
$gitRepo = new stdClass();
$gitRepo->id = 1;
$gitRepo->SCM = 'Git';
$gitRepo->synced = 0;

$gitlabRepo = new stdClass();
$gitlabRepo->id = 2;
$gitlabRepo->SCM = 'Gitlab';
$gitlabRepo->synced = 0;

$syncedRepo = new stdClass();
$syncedRepo->id = 3;
$syncedRepo->SCM = 'Git';
$syncedRepo->synced = 1;

$branchesWithData = array('develop', 'feature');
$branchesEmpty = array();

// 步骤1：正常情况，有提交计数，返回批处理模式下的提交计数
r($repoTest->checkSyncResultTest($gitRepo, $branchesEmpty, 'master', 5, 'batch')) && p() && e('5');

// 步骤2：正常情况，有提交计数，返回完成标志
r($repoTest->checkSyncResultTest($gitRepo, $branchesEmpty, 'master', 5, 'normal')) && p() && e('finish');

// 步骤3：同步情况，无提交计数且版本库已同步，返回批处理模式下的提交计数
r($repoTest->checkSyncResultTest($syncedRepo, $branchesEmpty, 'master', 0, 'batch')) && p() && e('0');

// 步骤4：Git类型版本库，无提交计数且未同步，有分支时处理下一个分支
r($repoTest->checkSyncResultTest($gitRepo, $branchesWithData, 'master', 0, 'normal')) && p() && e('finish');

// 步骤5：Git类型版本库，无提交计数且未同步，无分支时标记同步完成
r($repoTest->checkSyncResultTest($gitRepo, $branchesEmpty, 'master', 0, 'normal')) && p() && e('finish');

// 步骤6：Gitlab类型版本库，无提交计数且未同步，直接标记同步完成
r($repoTest->checkSyncResultTest($gitlabRepo, $branchesEmpty, 'master', 0, 'normal')) && p() && e('finish');

// 步骤7：无效参数测试，空的repo对象
r($repoTest->checkSyncResultTest(null, $branchesEmpty, 'master', 0, 'normal')) && p() && e('0');