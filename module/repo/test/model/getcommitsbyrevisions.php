#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getCommitsByRevisions();
timeout=0
cid=18054

- 测试步骤1：单个有效版本号 @1
- 测试步骤2：多个有效版本号 @3
- 测试步骤3：不存在的版本号 @0
- 测试步骤4：空版本号数组 @0
- 测试步骤5：混合版本号查询 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('repohistory');
$table->id->range('1-10');
$table->repo->range('1{3},2{3},3{4}');
$table->revision->range('commit001,commit002,commit003,commit004,commit005,commit006,commit007,commit008,commit009,commit010');
$table->commit->range('1-10');
$table->committer->range('admin,user1,dev1');
$table->comment->range('提交信息1,提交信息2,提交信息3,提交信息4,提交信息5,提交信息6,提交信息7,提交信息8,提交信息9,提交信息10');
$table->gen(10);

su('admin');

$repoTest = new repoModelTest();

$singleRevision = array('commit001');
$multipleRevisions = array('commit002', 'commit003', 'commit005');
$nonExistentRevision = array('commit999', 'commit888');
$emptyRevisions = array();
$mixedRevisions = array('commit004', 'commit999', 'commit006');

r($repoTest->getCommitsByRevisionsTest($singleRevision)) && p() && e('1'); // 测试步骤1：单个有效版本号
r($repoTest->getCommitsByRevisionsTest($multipleRevisions)) && p() && e('3'); // 测试步骤2：多个有效版本号
r($repoTest->getCommitsByRevisionsTest($nonExistentRevision)) && p() && e('0'); // 测试步骤3：不存在的版本号
r($repoTest->getCommitsByRevisionsTest($emptyRevisions)) && p() && e('0'); // 测试步骤4：空版本号数组
r($repoTest->getCommitsByRevisionsTest($mixedRevisions)) && p() && e('2'); // 测试步骤5：混合版本号查询