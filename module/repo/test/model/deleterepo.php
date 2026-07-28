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

$repoTable = zenData('ops_repo');
$repoTable->id->range('1,3,5');
$repoTable->spaceID->range('1');
$repoTable->product->range('1');
$repoTable->name->range('repo1,repo3,repo5');
$repoTable->scmType->range('git');
$repoTable->gitUID->range('uid1,uid3,uid5');
$repoTable->acl->range('open');
$repoTable->status->range('active');
$repoTable->deleted->range('0');
$repoTable->gen(3);

$tester->dao->insert(TABLE_REPOHISTORY)->data((object)array('repo' => 1, 'revision' => 'r1', 'commit' => 1, 'comment' => 'commit', 'committer' => 'admin', 'time' => '2024-01-01 10:00:00'))->exec();
$tester->dao->insert(TABLE_REPOBRANCH)->data((object)array('repo' => 1, 'revision' => 1, 'branch' => 'master'))->exec();
$tester->dao->insert(TABLE_REPOFILES)->data((object)array('repo' => 1, 'revision' => 1, 'parent' => '/', 'path' => '/file1'))->exec();
$tester->dao->insert(TABLE_REPOHISTORY)->data((object)array('repo' => 3, 'revision' => 'r3', 'commit' => 1, 'comment' => 'commit', 'committer' => 'admin', 'time' => '2024-01-01 10:00:00'))->exec();
$tester->dao->insert(TABLE_REPOBRANCH)->data((object)array('repo' => 3, 'revision' => 2, 'branch' => 'master'))->exec();
$tester->dao->insert(TABLE_REPOFILES)->data((object)array('repo' => 3, 'revision' => 2, 'parent' => '/', 'path' => '/file3'))->exec();
$tester->dao->insert(TABLE_REPOHISTORY)->data((object)array('repo' => 5, 'revision' => 'r5', 'commit' => 1, 'comment' => 'commit', 'committer' => 'admin', 'time' => '2024-01-01 10:00:00'))->exec();
$tester->dao->insert(TABLE_REPOBRANCH)->data((object)array('repo' => 5, 'revision' => 3, 'branch' => 'master'))->exec();
$tester->dao->insert(TABLE_REPOFILES)->data((object)array('repo' => 5, 'revision' => 3, 'parent' => '/', 'path' => '/file5'))->exec();

$repoTest = new repoModelTest();
r($repoTest->deleteRepoTest(1)) && p('repoCount,repoHistoryCount,repoBranchCount,repoFilesCount') && e('0,0,0,0');
r($repoTest->deleteRepoTest(3)) && p('repoCount,repoHistoryCount,repoBranchCount,repoFilesCount') && e('0,0,0,0');
r($repoTest->deleteRepoTest(999)) && p('repoCount,repoHistoryCount,repoBranchCount,repoFilesCount') && e('0,0,0,0');
r($repoTest->deleteRepoTest(1)) && p('repoCount,repoHistoryCount,repoBranchCount,repoFilesCount') && e('0,0,0,0');
r($repoTest->deleteRepoTest(5)) && p('repoCount,repoHistoryCount,repoBranchCount,repoFilesCount') && e('0,0,0,0');
