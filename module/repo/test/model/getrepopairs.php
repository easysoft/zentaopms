#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->getRepoPairs();
timeout=0
cid=18078

- 获取type为repo的结果集
 - 属性1 @testHtml
 - 属性4 @testSvn
- 获取type为repo的结果数量 @4
- 获取指定projectID的结果集属性1 @testHtml
- 获取指定projectID的结果数量 @1
- 获取type为repo的结果集，showScm参数为false属性2 @project1

*/

global $dbh, $tester;
$dbh->exec('DROP TABLE IF EXISTS `ops_spaceuser`');
$dbh->exec('DROP TABLE IF EXISTS `ops_repo`');
$dbh->exec(<<<'SQL'
CREATE TABLE `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `spaceID` int NOT NULL DEFAULT 0,
  `product` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `gitUID` char(42) NOT NULL DEFAULT '',
  `acl` varchar(30) NOT NULL DEFAULT 'open',
  `status` varchar(30) NOT NULL DEFAULT 'active',
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
$tester->dao->delete()->from(TABLE_PROJECTPRODUCT)->where('project')->eq(11)->exec();
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
    array('id' => 1, 'spaceID' => 1, 'product' => '1', 'name' => 'testHtml', 'gitUID' => 'uid1', 'acl' => 'open', 'status' => 'active', 'deleted' => 0),
    array('id' => 2, 'spaceID' => 1, 'product' => '2', 'name' => 'project1', 'gitUID' => 'uid2', 'acl' => 'open', 'status' => 'active', 'deleted' => 0),
    array('id' => 3, 'spaceID' => 1, 'product' => '3', 'name' => 'unittest', 'gitUID' => 'uid3', 'acl' => 'open', 'status' => 'active', 'deleted' => 0),
    array('id' => 4, 'spaceID' => 1, 'product' => '4', 'name' => 'testSvn', 'gitUID' => 'uid4', 'acl' => 'open', 'status' => 'active', 'deleted' => 0),
);
foreach($repos as $repoData) $tester->dao->insert(TABLE_REPO)->data((object)$repoData)->exec();
$tester->dao->insert('ops_spaceuser')->data((object)array('space' => 1, 'role' => 'manager', 'account' => 'admin'))->exec();
$tester->dao->insert(TABLE_PROJECTPRODUCT)->data((object)array('project' => 11, 'product' => 1, 'branch' => 0, 'plan' => '', 'roadmap' => ''))->exec();

su('admin');

$repo       = $tester->loadModel('repo');
$repoTest   = new repoModelTest();
$httpClient = $repoTest->resetHttpClient();
$httpClient->setResponse('/spaces', json_encode((object)array(
    'code'     => 'success',
    'data'     => array((object)array('id' => 1, 'name' => 'space1', 'createdDate' => '2026-01-01T00:00:00+08:00')),
    'listArgs' => (object)array('pageSize' => 1),
)));

$typeList  = array('project', 'repo');
$projectID = 11;

$result = $repo->getRepoPairs($typeList[1]);
r($result)        && p('1,4') && e('testHtml,testSvn');
r(count($result)) && p()      && e('4');

$result = $repo->getRepoPairs($typeList[0], $projectID);
r($result)        && p('1') && e('testHtml');
r(count($result)) && p()    && e('1');

r($repo->getRepoPairs($typeList[1], 0, false)) && p('2') && e('project1');

$repoTest->restoreHttpClient();
