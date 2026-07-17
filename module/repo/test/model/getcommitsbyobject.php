#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getCommitsByObject();
timeout=0
cid=18053

- 步骤1：获取任务关联提交信息第0条的id属性 @1
- 步骤2：获取bug关联提交信息第0条的revision属性 @c808480afe22d3a55d94e91c59a8f3170212ade0
- 步骤3：获取需求关联提交信息第0条的comment属性 @代码注释
- 步骤4：测试不存在对象ID @0
- 步骤5：测试无效对象类型 @0
- 步骤6：测试边界值ID为0 @0
- 步骤7：测试负数ID @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $dbh, $tester;
$dbh->exec('DROP TABLE IF EXISTS `ops_repohistory`');
$dbh->exec(<<<'SQL'
CREATE TABLE `ops_repohistory` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `repo` int unsigned NOT NULL DEFAULT 0,
  `revision` varchar(255) NOT NULL DEFAULT '',
  `commit` int NOT NULL DEFAULT 0,
  `comment` text DEFAULT NULL,
  `committer` varchar(255) NOT NULL DEFAULT '',
  `time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

$tester->dao->insert(TABLE_REPOHISTORY)->data((object)array(
    'id'        => 1,
    'repo'      => 1,
    'revision'  => 'c808480afe22d3a55d94e91c59a8f3170212ade0',
    'commit'    => 1,
    'comment'   => '代码注释',
    'committer' => 'admin',
    'time'      => '2026-01-01 00:00:00',
))->exec();

$relations = array(
    array('product' => 1, 'execution' => 1, 'AType' => 'revision', 'AID' => 1, 'AVersion' => 1, 'relation' => 'commit', 'BType' => 'task',  'BID' => 8001,  'BVersion' => 1, 'extra' => 1),
    array('product' => 1, 'execution' => 1, 'AType' => 'revision', 'AID' => 1, 'AVersion' => 1, 'relation' => 'commit', 'BType' => 'bug',   'BID' => 4001,  'BVersion' => 1, 'extra' => 1),
    array('product' => 1, 'execution' => 1, 'AType' => 'revision', 'AID' => 1, 'AVersion' => 1, 'relation' => 'commit', 'BType' => 'story', 'BID' => 10001, 'BVersion' => 1, 'extra' => 1),
);
foreach($relations as $relation) $tester->dao->insert(TABLE_RELATION)->data((object)$relation)->exec();

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$repoTest = new repoModelTest();

// 5. 执行测试步骤（至少5个）
r($repoTest->getCommitsByObjectTest(8001, 'task'))   && p('0:id')       && e('1');                  // 步骤1：获取任务关联提交信息
r($repoTest->getCommitsByObjectTest(4001, 'bug'))    && p('0:revision') && e('c808480afe22d3a55d94e91c59a8f3170212ade0'); // 步骤2：获取bug关联提交信息
r($repoTest->getCommitsByObjectTest(10001, 'story')) && p('0:comment')  && e('代码注释');             // 步骤3：获取需求关联提交信息
r($repoTest->getCommitsByObjectTest(999, 'task'))  && p()             && e('0');                   // 步骤4：测试不存在对象ID
r($repoTest->getCommitsByObjectTest(1, 'invalid')) && p()             && e('0');                   // 步骤5：测试无效对象类型
r($repoTest->getCommitsByObjectTest(0, 'task'))    && p()             && e('0');                   // 步骤6：测试边界值ID为0
r($repoTest->getCommitsByObjectTest(-1, 'bug'))    && p()             && e('0');                   // 步骤7：测试负数ID
