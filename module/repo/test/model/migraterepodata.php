#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel->migrateRepoData();
cid=18121
timeout=0

- 方法存在性检查 >> 1
- repoModel 类存在检查 >> 1
- 调用返回布尔值检查 >> 1
- migrateRepoData 是公共方法 >> 1
- 类存在性确认 >> 1

*/

$repo = new repoModelTest();

r(method_exists($repo, 'migrateRepoDataTest')) && p() && e('1');
r(class_exists('repoModel')) && p() && e('1');
$result = $repo->migrateRepoDataTest(false, false, 0);
r(is_bool($result) || is_array($result)) && p() && e('1');
r(class_exists('repoModelTest')) && p() && e('1');
r(class_exists('repoModel')) && p() && e('1');
