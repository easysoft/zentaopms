#!/usr/bin/env php
<?php

/**

title=测试 mrModel::getDiffs();
timeout=0
cid=17245

- 执行mrTest模块的getDiffsTest方法，参数是new stdclass  @0
- 执行mrTest模块的getDiffsTest方法，参数是$emptyMR  @0
- 执行mrTest模块的getDiffsTest方法，参数是$invalidRepoMR  @0
- 执行mrTest模块的getDiffsTest方法，参数是$noRepoMR  @0
- 执行mrTest模块的getDiffsTest方法，参数是$validMR  @0
- 执行mrTest模块的getDiffsTest方法，参数是$unsyncedMR 第0条的fileName属性 @test.txt
- 执行mrTest模块的getDiffsTest方法，参数是$stringDiffMR 第0条的fileName属性 @file.php

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mr.unittest.class.php';

su('admin');

// 创建测试实例
$mrTest = new mrTest();

// 测试步骤1：使用空的MR对象
r($mrTest->getDiffsTest(new stdclass())) && p() && e('0');

// 测试步骤2：使用缺少repoID的MR对象
$emptyMR = new stdclass();
$emptyMR->hostID = 1;
r($mrTest->getDiffsTest($emptyMR)) && p() && e('0');

// 测试步骤3：使用无效repoID的MR对象
$invalidRepoMR = new stdclass();
$invalidRepoMR->repoID = 999;
$invalidRepoMR->hostID = 1;
r($mrTest->getDiffsTest($invalidRepoMR)) && p() && e('0');

// 测试步骤4：使用有效MR但repo不存在的情况
$noRepoMR = new stdclass();
$noRepoMR->repoID = 100;
$noRepoMR->hostID = 1;
r($mrTest->getDiffsTest($noRepoMR)) && p() && e('0');

// 测试步骤5：使用有效MR和repo但可能无法获取diff
$validMR = new stdclass();
$validMR->repoID = 1;
$validMR->hostID = 1;
$validMR->synced = 1;
$validMR->targetProject = 'project1';
$validMR->mriid = 100;
r($mrTest->getDiffsTest($validMR)) && p() && e('0');

// 测试步骤6：使用未同步的MR对象且包含diffs数据
$unsyncedMR = new stdclass();
$unsyncedMR->repoID = 1;
$unsyncedMR->hostID = 1;
$unsyncedMR->synced = 0;
$unsyncedMR->diffs = "diff --git a/test.txt b/test.txt\nindex 123..456 100644\n--- a/test.txt\n+++ b/test.txt\n@@ -1 +1,2 @@\n test\n+new line";
r($mrTest->getDiffsTest($unsyncedMR)) && p('0:fileName') && e('test.txt');

// 测试步骤7：使用字符串形式的diffs数据
$stringDiffMR = new stdclass();
$stringDiffMR->repoID = 1;
$stringDiffMR->hostID = 1;
$stringDiffMR->synced = 0;
$stringDiffMR->diffs = "diff --git a/file.php b/file.php\n+++ b/file.php\n@@ -1,1 +1,2 @@\n <?php\n+echo 'test';";
r($mrTest->getDiffsTest($stringDiffMR)) && p('0:fileName') && e('file.php');