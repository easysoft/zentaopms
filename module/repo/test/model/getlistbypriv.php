#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->getlistbypriv();
timeout=0
cid=0

- 执行repoTest模块的getListByPrivTest方法，参数是'all'  @0
- 执行repoTest模块的getListByPrivTest方法，参数是'browse'  @0
- 执行repoTest模块的getListByPrivTest方法，参数是'active'  @0
- 执行repoTest模块的getListByPrivTest方法，参数是'closed'  @0
- 执行repoTest模块的getListByPrivTest方法，参数是'private'  @0

*/

su('admin');
global $tester;
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repo`');
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `acl` varchar(30) NOT NULL DEFAULT 'open',
  `synced` tinyint unsigned NOT NULL DEFAULT 0,
  `deleted` tinyint unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL
);
$tester->dao->insert('ops_repo')->data((object)array('status' => 'active', 'acl' => 'open', 'synced' => 0, 'deleted' => 0))->exec();

$repoTest = new repoModelTest();
r($repoTest->getListByPrivTest('all'))     && p() && e('0');
r($repoTest->getListByPrivTest('browse'))  && p() && e('0');
r($repoTest->getListByPrivTest('active'))  && p() && e('0');
r($repoTest->getListByPrivTest('closed'))  && p() && e('0');
r($repoTest->getListByPrivTest('private')) && p() && e('0');