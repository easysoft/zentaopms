#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel->migrateRepoData();
timeout=0
cid=18121

- 测试 migrateRepoData 第一次执行 @success
- 测试 migrateRepoData 重复执行 @success
*/

$repo = new repoModelTest();

r($repo->migrateRepoDataTest()) && p() && e('success');
r($repo->migrateRepoDataTest()) && p() && e('success');
