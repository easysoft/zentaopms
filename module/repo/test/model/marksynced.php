#!/usr/bin/env php
<?php

/**

title=测试 repoModel::markSynced();
timeout=0
cid=18087

- 步骤1：正常代码库ID属性synced @1
- 步骤2：不存在的代码库ID属性synced @0
- 步骤3：边界值0属性synced @0
- 步骤4：负数代码库ID属性synced @0
- 步骤5：验证fixCommit功能的代码库属性synced @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
$repoTable = zenData('repo');
$repoTable->id->range('1-4');
$repoTable->name->range('测试代码库{1-4}');
$repoTable->path->range('/test/repo{1-4}');
$repoTable->SCM->range('Git');
$repoTable->synced->range('0');
$repoTable->deleted->range('0');
$repoTable->gen(4);

// 准备repohistory测试数据，验证fixCommit功能
zenData('repohistory')->loadYaml('repohistory')->gen(3);

// 用户登录
su('admin');

// 创建测试实例
$repoTest = new repoModelTest();

r($repoTest->markSyncedTest(1)) && p('synced') && e('1');    // 步骤1：正常代码库ID
r($repoTest->markSyncedTest(999)) && p('synced') && e('0');  // 步骤2：不存在的代码库ID
r($repoTest->markSyncedTest(0)) && p('synced') && e('0');    // 步骤3：边界值0
r($repoTest->markSyncedTest(-1)) && p('synced') && e('0');   // 步骤4：负数代码库ID
r($repoTest->markSyncedTest(2)) && p('synced') && e('1');    // 步骤5：验证fixCommit功能的代码库