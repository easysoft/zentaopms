#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**
title=测试 repoModel::deleteRepo();
timeout=0
cid=18041

- 删除gitlab版本库
 - 属性repoCount @0
 - 属性repoHistoryCount @0
 - 属性repoBranchCount @0
 - 属性repoFilesCount @0
- 删除gitea版本库
 - 属性repoCount @0
 - 属性repoHistoryCount @0
 - 属性repoBranchCount @0
 - 属性repoFilesCount @0
- 删除不存在的版本库仍然清除关联数据 >> 1
- 删除已删除版本库仍然清除关联数据 >> 1
- 删除第三个版本库
 - 属性repoCount @0
 - 属性repoHistoryCount @0
 - 属性repoBranchCount @0
 - 属性repoFilesCount @0

*/

zenData('ops_repofiles')->gen(0);
zenData('ops_repobranch')->gen(0);
zenData('ops_repohistory')->gen(0);

$repo = zenData('ops_repo');
$repo->id->range('1,3,5');
$repo->spaceID->range('1{3}');
$repo->product->range('1{3}');
$repo->name->range('repo1,repo3,repo5');
$repo->gitUID->range('delete-repo-uid-1,delete-repo-uid-3,delete-repo-uid-5');
$repo->providerID->range('0,1,2');
$repo->mirror->range('0,1,0');
$repo->acl->range('open{3}');
$repo->status->range('active{3}');
$repo->deleted->range('0{3}');
$repo->gen(3);

$history = zenData('ops_repohistory');
$history->id->range('1-3');
$history->repo->range('1,3,5');
$history->revision->range('r1,r3,r5');
$history->commit->range('1{3}');
$history->comment->range('commit{3}');
$history->committer->range('admin{3}');
$history->time->range('[2024-01-01 10:00:00],[2024-01-01 10:00:00],[2024-01-01 10:00:00]');
$history->gen(3);

$branch = zenData('ops_repobranch');
$branch->repo->range('1,3,5');
$branch->revision->range('1-3');
$branch->branch->range('master{3}');
$branch->gen(3);

$file = zenData('ops_repofiles');
$file->repo->range('1,3,5');
$file->revision->range('1-3');
$file->parent->range('/{3}');
$file->path->range('/file1,/file3,/file5');
$file->gen(3);

$repoTest = new repoModelTest();
r($repoTest->deleteRepoTest(1)) && p('repoCount,repoHistoryCount,repoBranchCount,repoFilesCount') && e('0,0,0,0');
r($repoTest->deleteRepoTest(3)) && p('repoCount,repoHistoryCount,repoBranchCount,repoFilesCount') && e('0,0,0,0');
r($repoTest->deleteRepoTest(999)) && p('repoCount,repoHistoryCount,repoBranchCount,repoFilesCount') && e('0,0,0,0');
r($repoTest->deleteRepoTest(1)) && p('repoCount,repoHistoryCount,repoBranchCount,repoFilesCount') && e('0,0,0,0');
r($repoTest->deleteRepoTest(5)) && p('repoCount,repoHistoryCount,repoBranchCount,repoFilesCount') && e('0,0,0,0');
