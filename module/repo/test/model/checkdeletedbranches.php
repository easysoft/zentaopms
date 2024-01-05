#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->checkDeletedBranches();
timeout=0
cid=8

- 移除gitlab代码库已经删除的分支
 - 属性repoHistoryCount @3
 - 属性repoBranchCount @0
 - 属性repoFilesCount @0

*/

zdTable('pipeline')->gen(4);
zdTable('repo')->config('repo')->gen(5);
zdTable('repohistory')->config('repohistory')->gen(4);
$repoBranch = zdTable('repobranch');
$repoBranch->branch->range('deletedBranch');
$repoBranch->gen(1);
zdTable('repofiles')->gen(1);

$repo = new repoTest();

$gitlabID = 1;
$latestBranches = array('branch1' => 'branch1');

r($repo->checkDeletedBranchesTest($gitlabID, $latestBranches)) && p('repoHistoryCount,repoBranchCount,repoFilesCount') && e('3,0,0'); //移除gitlab代码库已经删除的分支