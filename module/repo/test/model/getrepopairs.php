#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->getRepoPairs();
timeout=0
cid=18078

- 执行repoTest模块的getRepoPairsTest方法，参数是$typeList[1]
 - 属性1 @testHtml
 - 属性4 @testSvn
- 执行repoTest模块的getRepoPairsCountTest方法，参数是$typeList[1]  @4
- 执行repoTest模块的getRepoPairsTest方法，参数是$typeList[0], $projectID 属性1 @testHtml
- 执行repoTest模块的getRepoPairsCountTest方法，参数是$typeList[0], $projectID  @1
- 执行repoTest模块的getRepoPairsTest方法，参数是$typeList[1], 0, false 属性2 @project1

*/

global $tester;
$tester->dao->exec('DROP TABLE IF EXISTS `ops_spaceuser`');
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repo`');
$tester->dao->exec(<<<'SQL'
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
$tester->dao->exec(<<<'SQL'
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
    'name'        => 'GitFox',
    'account'     => 'admin',
    'code'        => 'gitfox',
    'key'         => 'gitfox',
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

$typeList  = array('project', 'repo');
$projectID = 11;

r($repoTest->getRepoPairsTest($typeList[1])) && p('1,4') && e('testHtml,testSvn');
r($repoTest->getRepoPairsCountTest($typeList[1])) && p() && e('4');
r($repoTest->getRepoPairsTest($typeList[0], $projectID)) && p('1') && e('testHtml');
r($repoTest->getRepoPairsCountTest($typeList[0], $projectID)) && p() && e('1');
r($repoTest->getRepoPairsTest($typeList[1], 0, false)) && p('2') && e('project1');