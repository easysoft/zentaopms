#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->getRepoGroup();
timeout=0
cid=18076

- 按项目分组第4条的text属性 @正常产品4
- 按项目分组对应的repo第0条的text属性 @testHtml
- 指定projectID获取项目分组 @0
- 指定存在的projectID获取项目分组 @1
- 指定repoType获取项目分组个数 @4

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
$tester->dao->delete()->from(TABLE_PROJECTPRODUCT)->where('project')->in('1,11')->exec();
$tester->dao->delete()->from(TABLE_PRODUCT)->where('id')->in('1,2,3,4')->exec();
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

foreach(range(1, 4) as $productID)
{
    $tester->dao->insert(TABLE_PRODUCT)->data((object)array(
        'id'      => $productID,
        'name'    => '正常产品' . $productID,
        'code'    => 'product' . $productID,
        'shadow'  => 0,
        'deleted' => 0,
    ))->exec();
}

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

$type      = 'project';
$projectID = 1;

$result = $repo->getRepoGroup($type);
r($result)             && p('4:text') && e('正常产品4');
r($result[1]['items']) && p('0:text') && e('testHtml');

$result = $repo->getRepoGroup($type, $projectID);
r(count($result)) && p() && e(0);

$projectID = 11;
$result    = $repo->getRepoGroup($type, $projectID);
r(count($result)) && p() && e(1);

$result = $repo->getRepoGroup($type, 0, $repo->config->repo->gitServiceTypeList);
r(count($result)) && p() && e(4);

$repoTest->restoreHttpClient();
