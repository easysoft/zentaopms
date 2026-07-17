#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->getList();
timeout=0
cid=18069

- 获取代码库列表第1条的name属性 @testHtml
- 获取代码库列表数量 @4
- 获取代码库列表第4条的name属性 @testSvn
- 获取代码库列表第3条的name属性 @unittest
- 获取所有代码库列表数量 @4

*/

global $dbh, $tester;
$dbh->exec('DROP TABLE IF EXISTS `ops_provider`');
$dbh->exec('DROP TABLE IF EXISTS `ops_spaceuser`');
$dbh->exec('DROP TABLE IF EXISTS `ops_repo`');
$dbh->exec(<<<'SQL'
CREATE TABLE `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `spaceID` int NOT NULL DEFAULT 0,
  `product` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `SCM` varchar(30) NOT NULL DEFAULT '',
  `gitUID` char(42) NOT NULL DEFAULT '',
  `providerID` int unsigned NOT NULL DEFAULT 0,
  `mirror` tinyint(1) NOT NULL DEFAULT 0,
  `acl` varchar(30) NOT NULL DEFAULT 'open',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);
$dbh->exec(<<<'SQL'
CREATE TABLE `ops_provider` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(30) NOT NULL DEFAULT '',
  `deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);
$dbh->exec(<<<'SQL'
CREATE TABLE `ops_spaceuser` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `space` int unsigned NOT NULL DEFAULT 0,
  `role` varchar(10) NOT NULL DEFAULT '',
  `account` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

$tester->dao->delete()->from(TABLE_ENTRY)->where('code')->eq('gitfox')->exec();
$tester->dao->insert(TABLE_ENTRY)->data((object)array(
    'name'        => 'GitFox入口',
    'account'     => 'admin',
    'code'        => 'gitfox',
    'key'         => 'testkey1234567890testkey1234567',
    'freePasswd'  => 0,
    'ip'          => '*',
    'createdBy'   => 'admin',
    'createdDate' => '2026-01-01 00:00:00',
    'calledTime'  => 0,
    'editedBy'    => 'admin',
    'editedDate'  => '2026-01-01 00:00:00',
    'deleted'     => 0,
))->exec();

$repos = array(
    array('id' => 1, 'spaceID' => 1, 'product' => '1', 'name' => 'testHtml', 'SCM' => 'Gitlab', 'gitUID' => 'uid1', 'acl' => 'open', 'status' => 'active', 'deleted' => 0),
    array('id' => 2, 'spaceID' => 1, 'product' => '2', 'name' => 'project1', 'SCM' => 'Gitlab', 'gitUID' => 'uid2', 'acl' => 'open', 'status' => 'active', 'deleted' => 0),
    array('id' => 3, 'spaceID' => 1, 'product' => '3', 'name' => 'unittest', 'SCM' => 'GitFox', 'gitUID' => 'uid3', 'acl' => 'open', 'status' => 'active', 'deleted' => 0),
    array('id' => 4, 'spaceID' => 1, 'product' => '4', 'name' => 'testSvn', 'SCM' => 'Subversion', 'gitUID' => 'uid4', 'acl' => 'open', 'status' => 'active', 'deleted' => 0),
);
foreach($repos as $repoData) $tester->dao->insert(TABLE_REPO)->data((object)$repoData)->exec();
$tester->dao->insert('ops_spaceuser')->data((object)array('space' => 1, 'role' => 'manager', 'account' => 'admin'))->exec();

su('admin');

$repo       = new repoModelTest();
$httpClient = $repo->resetHttpClient();
$httpClient->setResponse('/spaces', json_encode((object)array(
    'code'     => 'success',
    'data'     => array((object)array('id' => 1, 'name' => 'space1', 'createdDate' => '2026-01-01T00:00:00+08:00')),
    'listArgs' => (object)array('pageSize' => 1),
)));

r($repo->getListTest(0, 0, 'id_asc')) && p('1:name') && e('testHtml');
r(count($repo->getListTest(0, 0, 'id_asc'))) && p() && e('4');
r($repo->getListTest(0, 0, 'id_asc')) && p('4:name') && e('testSvn');
r($repo->getListTest(0, 0, 'id_asc')) && p('3:name') && e('unittest');
r(count($repo->getListTest(0, 0, 'id_asc'))) && p() && e('4');

$repo->restoreHttpClient();
