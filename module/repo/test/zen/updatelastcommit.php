#!/usr/bin/env php
<?php

/**

title=测试 repoZen::updateLastCommit();
timeout=0
cid=18157

- 步骤1：正常情况-空lastCommit需要更新 @1
- 步骤2：没有committed_date字段直接返回 @1
- 步骤3：repo对象为null @0
- 步骤4：lastRevision对象为null @0
- 步骤5：新提交时间早于现有lastCommit不需要更新 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$repoTest = new repoZenTest();

// 4. 准备测试数据
$repo1 = new stdclass();
$repo1->id = 1;
$repo1->lastCommit = '';

$repo2 = new stdclass();
$repo2->id = 2;
$repo2->lastCommit = '2023-01-01 10:00:00';

$repo3 = new stdclass();
$repo3->id = 3;
$repo3->lastCommit = '2023-06-01 15:30:00';

$lastRevision1 = new stdclass();
$lastRevision1->committed_date = '2023-12-01 20:45:00';

$lastRevision2 = new stdclass();
// 没有committed_date字段

$lastRevision3 = new stdclass();
$lastRevision3->committed_date = '2023-01-01 08:00:00';

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($repoTest->updateLastCommitTest($repo1, $lastRevision1)) && p() && e('1'); // 步骤1：正常情况-空lastCommit需要更新
r($repoTest->updateLastCommitTest($repo2, $lastRevision2)) && p() && e('1'); // 步骤2：没有committed_date字段直接返回
r($repoTest->updateLastCommitTest(null, $lastRevision1)) && p() && e('0'); // 步骤3：repo对象为null
r($repoTest->updateLastCommitTest($repo1, null)) && p() && e('0'); // 步骤4：lastRevision对象为null
r($repoTest->updateLastCommitTest($repo3, $lastRevision3)) && p() && e('0'); // 步骤5：新提交时间早于现有lastCommit不需要更新