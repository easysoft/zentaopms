#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->fixCommit();
timeout=0
cid=1

- 更新版本库3的提交记录排序第2条的commit属性 @2
- 重复更新版本库3的提交记录排序第3条的commit属性 @1

*/

zdTable('repo')->config('repo')->gen(4);
$repoHistory = zdTable('repohistory')->config('repohistory');
$repoHistory->commit->range('1');
$repoHistory->gen(3);

$repo = new repoTest();

$repoID = 3;

r($repo->fixCommitTest($repoID)) && p('2:commit') && e('2'); // 更新版本库3的提交记录排序
r($repo->fixCommitTest($repoID)) && p('3:commit') && e('1'); // 重复更新版本库3的提交记录排序