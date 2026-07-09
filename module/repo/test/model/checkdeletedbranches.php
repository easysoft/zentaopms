#!/usr/bin/env php
<?php

/**

title=测试 repoModel::checkDeletedBranches();
timeout=0
cid=18031

- 步骤1：正常删除已删除的分支数据
 - 属性repoHistoryCount @4
 - 属性repoBranchCount @4
 - 属性repoFilesCount @4
- 步骤2：测试空分支列表输入
 - 属性repoHistoryCount @6
 - 属性repoBranchCount @6
 - 属性repoFilesCount @6
- 步骤3：测试master分支不被删除（master存在但不在最新列表中）
 - 属性repoHistoryCount @4
 - 属性repoBranchCount @4
 - 属性repoFilesCount @4
- 步骤4：测试多个分支删除场景
 - 属性repoHistoryCount @2
 - 属性repoBranchCount @2
 - 属性repoFilesCount @2
- 步骤5：测试不存在代码库ID
 - 属性repoHistoryCount @2
 - 属性repoBranchCount @2
 - 属性repoFilesCount @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

function resetDeletedBranchTables(): void
{
    global $dbh;

    $dbh->exec('DROP TABLE IF EXISTS `ops_repofiles`');
    $dbh->exec('DROP TABLE IF EXISTS `ops_repobranch`');
    $dbh->exec('DROP TABLE IF EXISTS `ops_repohistory`');

    $dbh->exec(<<<'SQL'
CREATE TABLE `ops_repohistory` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `repo` int unsigned NOT NULL DEFAULT 0,
  `revision` varchar(40) NOT NULL DEFAULT '',
  `commit` int unsigned NOT NULL DEFAULT 0,
  `comment` text DEFAULT NULL,
  `committer` varchar(100) NOT NULL DEFAULT '',
  `time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `repo` (`repo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);
    $dbh->exec(<<<'SQL'
CREATE TABLE `ops_repobranch` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `repo` int unsigned NOT NULL DEFAULT 0,
  `revision` int unsigned NOT NULL DEFAULT 0,
  `branch` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);
    $dbh->exec(<<<'SQL'
CREATE TABLE `ops_repofiles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `repo` int unsigned NOT NULL DEFAULT 0,
  `revision` int unsigned NOT NULL DEFAULT 0,
  `parent` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);
}

function seedDeletedBranchData(array $rows): void
{
    global $tester;

    foreach($rows as $row)
    {
        $tester->dao->insert(TABLE_REPOHISTORY)->data((object)array(
            'id'        => $row['revision'],
            'repo'      => $row['repo'],
            'revision'  => 'r' . $row['revision'],
            'commit'    => $row['revision'],
            'comment'   => 'commit ' . $row['revision'],
            'committer' => 'admin',
            'time'      => sprintf('2024-01-01 10:%02d:00', $row['revision']),
        ))->exec();
        $tester->dao->insert(TABLE_REPOBRANCH)->data((object)array(
            'repo'     => $row['repo'],
            'revision' => $row['revision'],
            'branch'   => $row['branch'],
        ))->exec();
        $tester->dao->insert(TABLE_REPOFILES)->data((object)array(
            'id'       => $row['revision'],
            'repo'     => $row['repo'],
            'revision' => $row['revision'],
            'parent'   => '/',
            'path'     => '/file' . $row['revision'],
        ))->exec();
    }
}

// 用户登录
su('admin');

// 创建测试实例
$repoTest = new repoModelTest();

resetDeletedBranchTables();
seedDeletedBranchData(array(
    array('repo' => 1, 'revision' => 1, 'branch' => 'master'),
    array('repo' => 1, 'revision' => 2, 'branch' => 'main'),
    array('repo' => 1, 'revision' => 3, 'branch' => 'develop'),
    array('repo' => 1, 'revision' => 4, 'branch' => 'develop'),
    array('repo' => 1, 'revision' => 5, 'branch' => 'main'),
    array('repo' => 1, 'revision' => 6, 'branch' => 'master'),
));
r($repoTest->checkDeletedBranchesTest(1, array('main' => 'main'))) && p('repoHistoryCount,repoBranchCount,repoFilesCount') && e('4,4,4');

resetDeletedBranchTables();
seedDeletedBranchData(array(
    array('repo' => 1, 'revision' => 1, 'branch' => 'master'),
    array('repo' => 1, 'revision' => 2, 'branch' => 'main'),
    array('repo' => 1, 'revision' => 3, 'branch' => 'develop'),
    array('repo' => 1, 'revision' => 4, 'branch' => 'develop'),
    array('repo' => 1, 'revision' => 5, 'branch' => 'main'),
    array('repo' => 1, 'revision' => 6, 'branch' => 'master'),
));
r($repoTest->checkDeletedBranchesTest(1, array())) && p('repoHistoryCount,repoBranchCount,repoFilesCount') && e('6,6,6');

resetDeletedBranchTables();
seedDeletedBranchData(array(
    array('repo' => 1, 'revision' => 1, 'branch' => 'master'),
    array('repo' => 1, 'revision' => 2, 'branch' => 'main'),
    array('repo' => 1, 'revision' => 3, 'branch' => 'feature'),
    array('repo' => 1, 'revision' => 4, 'branch' => 'main'),
    array('repo' => 1, 'revision' => 5, 'branch' => 'master'),
));
r($repoTest->checkDeletedBranchesTest(1, array('main' => 'main'))) && p('repoHistoryCount,repoBranchCount,repoFilesCount') && e('4,4,4');

resetDeletedBranchTables();
seedDeletedBranchData(array(
    array('repo' => 2, 'revision' => 7,  'branch' => 'master'),
    array('repo' => 2, 'revision' => 8,  'branch' => 'main'),
    array('repo' => 2, 'revision' => 9,  'branch' => 'develop'),
    array('repo' => 2, 'revision' => 10, 'branch' => 'develop'),
));
r($repoTest->checkDeletedBranchesTest(2, array('main' => 'main'))) && p('repoHistoryCount,repoBranchCount,repoFilesCount') && e('2,2,2');
r($repoTest->checkDeletedBranchesTest(999, array('master' => 'master'))) && p('repoHistoryCount,repoBranchCount,repoFilesCount') && e('2,2,2');
