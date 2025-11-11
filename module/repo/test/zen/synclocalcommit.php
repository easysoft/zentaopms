#!/usr/bin/env php
<?php

/**

title=测试 repoZen::syncLocalCommit();
timeout=0
cid=0

- 步骤1：日志文件不存在时应返回空字符串 @0
- 步骤2：日志包含fatal错误时应返回错误行内容 @fatal: repository not found
- 步骤3：日志包含failed错误时应返回错误行内容 @failed to connect
- 步骤4：日志包含done标记且成功完成时应删除文件并返回空字符串 @0
- 步骤5：日志包含empty repository标记时应删除文件并返回空字符串 @0
- 步骤6：日志包含Total标记但未完成finishCount时应返回处理中状态 @1
- 步骤7：日志包含Total标记和finishCount及finishCompress时应删除文件并返回空字符串 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$repoZenTest = new repoZenTest();

// 4. 准备测试数据对象
$repo1 = new stdClass();
$repo1->id = 1;
$repo1->SCM = 'Git';
$repo1->name = 'testrepo1';

$repo2 = new stdClass();
$repo2->id = 2;
$repo2->SCM = 'Subversion';
$repo2->name = 'testrepo2';

$repo3 = new stdClass();
$repo3->id = 3;
$repo3->SCM = 'Git';
$repo3->name = 'testrepo3';

$repo4 = new stdClass();
$repo4->id = 4;
$repo4->SCM = 'Git';
$repo4->name = 'testrepo4';

$repo5 = new stdClass();
$repo5->id = 5;
$repo5->SCM = 'Git';
$repo5->name = 'testrepo5';

$repo6 = new stdClass();
$repo6->id = 6;
$repo6->SCM = 'Git';
$repo6->name = 'testrepo6';

$repo7 = new stdClass();
$repo7->id = 7;
$repo7->SCM = 'Git';
$repo7->name = 'testrepo7';

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($repoZenTest->syncLocalCommitTest($repo1, '', false)) && p() && e('0'); // 步骤1：日志文件不存在时应返回空字符串
r($repoZenTest->syncLocalCommitTest($repo2, "Line 1\nLine 2\nfatal: repository not found\n", true)) && p() && e('fatal: repository not found'); // 步骤2：日志包含fatal错误时应返回错误行内容
r($repoZenTest->syncLocalCommitTest($repo3, "Line 1\nLine 2\nfailed to connect\n", true)) && p() && e('failed to connect'); // 步骤3：日志包含failed错误时应返回错误行内容
r($repoZenTest->syncLocalCommitTest($repo4, "Line 1\nLine 2\ndone\n", true)) && p() && e('0'); // 步骤4：日志包含done标记且成功完成时应删除文件并返回空字符串
r($repoZenTest->syncLocalCommitTest($repo5, "Line 1\nempty repository\n", true)) && p() && e('0'); // 步骤5：日志包含empty repository标记时应删除文件并返回空字符串
r($repoZenTest->syncLocalCommitTest($repo6, "Line 1\nTotal 100 objects\n", true)) && p() && e('1'); // 步骤6：日志包含Total标记但未完成finishCount时应返回处理中状态
r($repoZenTest->syncLocalCommitTest($repo7, "Line 1\nCounting objects: 100%\nCompressing objects: 100%\nTotal 100 objects\n", true)) && p() && e('0'); // 步骤7：日志包含Total标记和finishCount及finishCompress时应删除文件并返回空字符串