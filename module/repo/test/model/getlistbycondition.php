#!/usr/bin/env php
<?php
declare(strict_types=1);

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel::getListByCondition();
timeout=0
cid=18070

- 执行repoTest模块的getListByConditionIsArrayTest方法，参数是''  @1
- 执行repoTest模块的getListByConditionCountTest方法，参数是''  @5
- 执行repoTest模块的getListByConditionCountTest方法，参数是"name='testHtml'"  @1
- 执行repoTest模块的getListByConditionCountTest方法，参数是'', 2  @2
- 执行repoTest模块的getListByConditionTest方法，参数是'', 0, 'id_asc' 第0条的id属性 @1
- 执行repoTest模块的getListByConditionCountTest方法，参数是'', 0, 'id_desc', $pager  @2
- 执行repoTest模块的getListByConditionCountTest方法，参数是"name like '%test%'"  @3
- 执行repoTest模块的getListByConditionCountTest方法，参数是"name = 'nonexistent'"  @0

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
  `SCM` varchar(30) NOT NULL DEFAULT '',
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
$tester->dao->insert(TABLE_ENTRY)->data((object)array(
    'name'        => 'GitFox',
    'account'     => '',
    'code'        => 'gitfox',
    'key'         => 'gitfox',
    'freePasswd'  => 1,
    'ip'          => '*',
    'createdBy'   => 'admin',
    'createdDate' => '2026-01-01 00:00:00',
    'calledTime'  => 0,
    'editedBy'    => 'admin',
    'editedDate'  => '2026-01-01 00:00:00',
    'deleted'     => 0,
))->exec();

$repos = array(
    array('id' => 1, 'spaceID' => 1, 'product' => '1', 'name' => 'testHtml',    'SCM' => 'Gitlab', 'gitUID' => 'uid1', 'acl' => 'open', 'status' => 'active',    'deleted' => 0),
    array('id' => 2, 'spaceID' => 1, 'product' => '1', 'name' => 'testApi',     'SCM' => 'Gitlab', 'gitUID' => 'uid2', 'acl' => 'open', 'status' => 'active',    'deleted' => 0),
    array('id' => 3, 'spaceID' => 2, 'product' => '1', 'name' => 'projectRepo', 'SCM' => 'Git',    'gitUID' => 'uid3', 'acl' => 'open', 'status' => 'active',    'deleted' => 0),
    array('id' => 4, 'spaceID' => 2, 'product' => '1', 'name' => 'testDocs',    'SCM' => 'GitFox', 'gitUID' => 'uid4', 'acl' => 'open', 'status' => 'active',    'deleted' => 0),
    array('id' => 5, 'spaceID' => 1, 'product' => '1', 'name' => 'archiveRepo', 'SCM' => 'Gitlab', 'gitUID' => 'uid5', 'acl' => 'open', 'status' => 'active',    'deleted' => 0),
    array('id' => 6, 'spaceID' => 1, 'product' => '1', 'name' => 'hiddenRepo',  'SCM' => 'Gitlab', 'gitUID' => 'uid6', 'acl' => 'open', 'status' => 'importing', 'deleted' => 0),
    array('id' => 7, 'spaceID' => 1, 'product' => '1', 'name' => 'deletedRepo', 'SCM' => 'Gitlab', 'gitUID' => 'uid7', 'acl' => 'open', 'status' => 'active',    'deleted' => 1),
);
foreach($repos as $repoData) $tester->dao->insert(TABLE_REPO)->data((object)$repoData)->exec();

$tester->dao->insert(TABLE_DEVOPSSPACEUSER)->data((object)array('space' => 1, 'role' => 'manager', 'account' => 'admin'))->exec();
$tester->dao->insert(TABLE_DEVOPSSPACEUSER)->data((object)array('space' => 2, 'role' => 'manager', 'account' => 'admin'))->exec();

su('admin');

$repoTest = new repoModelTest();

$pager = new stdclass();
$pager->recPerPage = 2;
$pager->pageID     = 1;
$repoTest->instance->app->rawModule = 'repo';
$repoTest->instance->app->rawMethod = 'browse';
$repoTest->instance->app->loadClass('pager', true);
$pager = pager::init(0, $pager->recPerPage, $pager->pageID);

r($repoTest->getListByConditionIsArrayTest('')) && p() && e('1');
r($repoTest->getListByConditionCountTest('')) && p() && e('5');
r($repoTest->getListByConditionCountTest("name='testHtml'")) && p() && e('1');
r($repoTest->getListByConditionCountTest('', 2)) && p() && e('2');
r(array_values($repoTest->getListByConditionTest('', 0, 'id_asc'))) && p('0:id') && e('1');
r($repoTest->getListByConditionCountTest('', 0, 'id_desc', $pager)) && p() && e('2');
r($repoTest->getListByConditionCountTest("name like '%test%'")) && p() && e('3');
r($repoTest->getListByConditionCountTest("name = 'nonexistent'")) && p() && e('0');