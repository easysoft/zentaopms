#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 repoModel->migrateRepoData();
cid=18121
timeout=0

- 测试 migrateRepoData 第一次执行 @success
- 测试 migrateRepoData 重复执行 @fail
- 测试 migrateRepoData 第三次执行 @fail
- 测试 migrateRepoData 第四次执行 @fail
- 测试 migrateRepoData 第五次执行 @fail
*/

$repo = new repoModelTest();
$testRepoID = 99999;

$result = $repo->migrateRepoDataTest(true, false, $testRepoID);
r($result) && p('result,error') && e('success,none');

for($i = 0; $i < 3; $i++)
{
	$result = $repo->migrateRepoDataTest(false, false, $testRepoID);
	r($result) && p('result') && e('fail');
	r(strlen(trim((string)$result['error'])) > 0 ? '1' : '0') && p() && e('1');
}

$result = $repo->migrateRepoDataTest(false, true, $testRepoID);
r($result) && p('result') && e('fail');
r(strlen(trim((string)$result['error'])) > 0 ? '1' : '0') && p() && e('1');
