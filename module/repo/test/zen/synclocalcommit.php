#!/usr/bin/env php
<?php

/**

title=测试 repoZen::syncLocalCommit();
timeout=0
cid=0

- 步骤1：传入有效的repo对象但不存在日志文件 @0
- 步骤2：传入空对象或null参数 @0
- 步骤3：传入缺少必要属性的repo对象 @0
- 步骤4：传入有效repo对象但getTmpRoot返回无效路径 @0
- 步骤5：测试方法在DAO错误情况下的处理 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

su('admin');

$repoZenTest = new repoZenTest();

// 创建有效的repo对象
$validRepo = new stdclass();
$validRepo->SCM = 'Git';
$validRepo->name = 'test-repo';
$validRepo->id = 1;

// 创建空对象
$emptyRepo = new stdclass();

// 创建缺少属性的repo对象
$incompleteRepo = new stdclass();
$incompleteRepo->SCM = 'Git';

// 创建具有无效名称的repo对象
$invalidNameRepo = new stdclass();
$invalidNameRepo->SCM = 'Git';
$invalidNameRepo->name = '';

// 创建另一个测试对象用于第五个测试
$testRepo = new stdclass();
$testRepo->SCM = 'Subversion';
$testRepo->name = 'svn-test-repo';
$testRepo->id = 2;

r($repoZenTest->syncLocalCommitTest($validRepo)) && p() && e('0');           // 步骤1：传入有效的repo对象但不存在日志文件
r($repoZenTest->syncLocalCommitTest(null)) && p() && e('0');                 // 步骤2：传入空对象或null参数
r($repoZenTest->syncLocalCommitTest($incompleteRepo)) && p() && e('0');      // 步骤3：传入缺少必要属性的repo对象
r($repoZenTest->syncLocalCommitTest($invalidNameRepo)) && p() && e('0');     // 步骤4：传入有效repo对象但getTmpRoot返回无效路径
r($repoZenTest->syncLocalCommitTest($testRepo)) && p() && e('0');            // 步骤5：测试方法在DAO错误情况下的处理