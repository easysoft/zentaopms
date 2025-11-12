#!/usr/bin/env php
<?php

/**

title=测试 repoZen::checkSyncResult();
timeout=0
cid=0

- 测试步骤1:Git类型仓库,有提交数量,batch类型 @10
- 测试步骤2:Git类型仓库,有提交数量,sync类型 @finish
- 测试步骤3:Git类型仓库,无提交,未同步,有剩余分支 @finish
- 测试步骤4:Git类型仓库,无提交,未同步,无剩余分支 @finish
- 测试步骤5:Gitlab类型仓库,无提交,未同步 @finish
- 测试步骤6:Subversion类型仓库,有提交数量,batch类型 @20
- 测试步骤7:Git类型仓库,已同步,有提交数量 @15
- 测试步骤8:无效的repo对象参数 @0
- 测试步骤9:无效的type参数 @0
- 测试步骤10:Git类型仓库,无提交,未同步,当前分支为空 @finish

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

su('admin');

$repoTest = new repoZenTest();

// 创建测试用的repo对象
$repo1 = new stdClass();
$repo1->id = 101;
$repo1->SCM = 'Git';
$repo1->synced = 0;
$repo1->commits = 0;

$repo2 = new stdClass();
$repo2->id = 102;
$repo2->SCM = 'Gitlab';
$repo2->synced = 0;
$repo2->commits = 0;

$repo3 = new stdClass();
$repo3->id = 103;
$repo3->SCM = 'Subversion';
$repo3->synced = 0;
$repo3->commits = 0;

$repo4 = new stdClass();
$repo4->id = 104;
$repo4->SCM = 'Git';
$repo4->synced = 1;
$repo4->commits = 100;

$branches1 = array('develop', 'feature/test');
$branches2 = array();

r($repoTest->checkSyncResultTest($repo1, $branches1, 'master', 10, 'batch')) && p() && e('10'); // 测试步骤1:Git类型仓库,有提交数量,batch类型
r($repoTest->checkSyncResultTest($repo1, $branches1, 'master', 5, 'sync')) && p() && e('finish'); // 测试步骤2:Git类型仓库,有提交数量,sync类型
r($repoTest->checkSyncResultTest($repo1, $branches1, 'master', 0, 'sync')) && p() && e('finish'); // 测试步骤3:Git类型仓库,无提交,未同步,有剩余分支
r($repoTest->checkSyncResultTest($repo1, $branches2, '', 0, 'sync')) && p() && e('finish'); // 测试步骤4:Git类型仓库,无提交,未同步,无剩余分支
r($repoTest->checkSyncResultTest($repo2, $branches2, '', 0, 'sync')) && p() && e('finish'); // 测试步骤5:Gitlab类型仓库,无提交,未同步
r($repoTest->checkSyncResultTest($repo3, array(), '', 20, 'batch')) && p() && e('20'); // 测试步骤6:Subversion类型仓库,有提交数量,batch类型
r($repoTest->checkSyncResultTest($repo4, array(), '', 15, 'batch')) && p() && e('15'); // 测试步骤7:Git类型仓库,已同步,有提交数量
r($repoTest->checkSyncResultTest(null, array(), '', 0, 'batch')) && p() && e('0'); // 测试步骤8:无效的repo对象参数
r($repoTest->checkSyncResultTest($repo1, array(), '', 10, 'invalid')) && p() && e('0'); // 测试步骤9:无效的type参数
r($repoTest->checkSyncResultTest($repo1, array(), '', 0, 'sync')) && p() && e('finish'); // 测试步骤10:Git类型仓库,无提交,未同步,当前分支为空