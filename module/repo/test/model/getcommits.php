#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getCommits();
timeout=0
cid=18052

- 步骤1：获取指定代码库的所有提交记录 @1
- 步骤2：获取指定路径的提交记录 @1
- 步骤3：测试不存在的代码库ID @1
- 步骤4：测试时间范围筛选提交记录 @1
- 步骤5：测试文件类型筛选提交记录 @1
- 步骤6：测试另一个代码库的提交记录 @1
- 步骤7：测试指定版本的提交记录 @1
- 步骤8：SVN 类型代码库获取提交记录 @1
- 步骤9：SVN 类型代码库按路径过滤提交 @1
- 步骤10：SVN 类型代码库单 revision 精准查询 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
// 2. 用户登录
su('admin');

global $tester;
$historyTable = trim((string)TABLE_REPOHISTORY, '`');
$historyExists = (bool)$tester->dao->query("SHOW TABLES LIKE '{$historyTable}'")->fetch();
if(!$historyExists)
{
    $tester->dao->exec(<<<SQL
CREATE TABLE `{$historyTable}` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `repo` int unsigned NOT NULL DEFAULT 0,
  `revision` varchar(255) NOT NULL DEFAULT '',
  `commit` int unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);
}
else
{
    $commitColumn = $tester->dao->query("SHOW COLUMNS FROM `{$historyTable}` LIKE 'commit'")->fetch();
    if(!$commitColumn) $tester->dao->exec("ALTER TABLE `{$historyTable}` ADD `commit` int unsigned NOT NULL DEFAULT 0");
}

// 3. 创建测试实例
$repoTest = new repoModelTest();

// 4. 执行测试步骤
$result1 = $repoTest->getCommitsTest((object)array('id' => 1, 'SCM' => 'Git'), '');
r($repoTest->getCommitsIsArrayTest((object)array('id' => 1, 'SCM' => 'Git'), '')) && p() && e('1'); // 步骤1：获取指定代码库的所有提交记录

$result2 = $repoTest->getCommitsTest((object)array('id' => 1, 'SCM' => 'Git'), '/src');
r($repoTest->getCommitsIsArrayTest((object)array('id' => 1, 'SCM' => 'Git'), '/src')) && p() && e('1'); // 步骤2：获取指定路径的提交记录

$result3 = $repoTest->getCommitsTest((object)array('id' => 999, 'SCM' => 'Git'), '');
r($repoTest->getCommitsIsArrayTest((object)array('id' => 999, 'SCM' => 'Git'), '')) && p() && e('1'); // 步骤3：测试不存在的代码库ID

$result4 = $repoTest->getCommitsTest((object)array('id' => 1, 'SCM' => 'Git'), '', 'HEAD', 'dir', null, '2024-01-01', '2024-12-31');
r($repoTest->getCommitsIsArrayTest((object)array('id' => 1, 'SCM' => 'Git'), '', 'HEAD', 'dir', null, '2024-01-01', '2024-12-31')) && p() && e('1'); // 步骤4：测试时间范围筛选提交记录

$result5 = $repoTest->getCommitsTest((object)array('id' => 1, 'SCM' => 'Git'), '/src/main.php', 'HEAD', 'file');
r($repoTest->getCommitsIsArrayTest((object)array('id' => 1, 'SCM' => 'Git'), '/src/main.php', 'HEAD', 'file')) && p() && e('1'); // 步骤5：测试文件类型筛选提交记录

$result6 = $repoTest->getCommitsTest((object)array('id' => 2, 'SCM' => 'Git'), '');
r($repoTest->getCommitsIsArrayTest((object)array('id' => 2, 'SCM' => 'Git'), '')) && p() && e('1'); // 步骤6：测试另一个代码库的提交记录

$result7 = $repoTest->getCommitsTest((object)array('id' => 1, 'SCM' => 'Git'), '', 'commit1');
r($repoTest->getCommitsIsArrayTest((object)array('id' => 1, 'SCM' => 'Git'), '', 'commit1')) && p() && e('1'); // 步骤7：测试指定版本的提交记录

$svnRepo = (object)array('id' => 1, 'SCM' => 'Subversion', 'scmType' => 'svn');
$result8 = $repoTest->getCommitsTest($svnRepo, '');
r($repoTest->getCommitsIsArrayTest($svnRepo, '')) && p() && e('1'); // 步骤8：SVN 类型代码库获取提交记录

$result9 = $repoTest->getCommitsTest($svnRepo, '/trunk');
r($repoTest->getCommitsIsArrayTest($svnRepo, '/trunk')) && p() && e('1'); // 步骤9：SVN 类型代码库按路径过滤提交

$singleQuery = new stdclass();
$singleQuery->commit = '1';
$result10 = $repoTest->getCommitsTest($svnRepo, '', '1', 'dir', null, '', '', $singleQuery);
r($repoTest->getCommitsIsArrayTest($svnRepo, '', '1', 'dir', null, '', '', $singleQuery)) && p() && e('1'); // 步骤10：SVN 类型代码库单 revision 精准查询
