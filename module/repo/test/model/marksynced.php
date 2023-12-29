#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->markSynced();
timeout=0
cid=1

- 更新代码库1属性synced @1
- 更新不存在代码库属性synced @0

*/

$repo = zdTable('repo')->config('repo');
$repo->synced->range('0');
$repo->gen(4);
zdTable('repohistory')->config('repohistory')->gen(4);

$repo = new repoTest();

$repoID = 1;

r($repo->markSyncedTest($repoID)) && p('synced') && e('1'); //更新代码库1
r($repo->markSyncedTest(10001))   && p('synced') && e('0'); //更新不存在代码库