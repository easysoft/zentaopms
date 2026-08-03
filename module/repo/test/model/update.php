#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->update();
timeout=0
cid=18109

- 执行repo模块的updateTest方法，参数是1, $data1, true
 - 第0条的field属性 @name
 - 第0条的old属性 @testHtml
 - 第0条的new属性 @repo1
- 执行repo模块的updateTest方法，参数是1, $data2, true
 - 第0条的field属性 @product
 - 第0条的old属性 @1
 - 第0条的new属性 @2
- 执行repo模块的updateTest方法，参数是1, $data3, true
 - 第0条的field属性 @projects
 - 第0条的old属性 @~~
 - 第0条的new属性 @3
- 执行repo模块的updateTest方法，参数是1, $data4, true
 - 第0条的field属性 @serviceProject
 - 第0条的old属性 @2
 - 第0条的new属性 @1
- 执行repo模块的updateTest方法，参数是1, $data5, true
 - 第0条的field属性 @name
 - 第0条的old属性 @repo1
 - 第0条的new属性 @repo1_rename
- 执行repo模块的updateTest方法，参数是1, $data6, true
 - 第0条的field属性 @product
 - 第0条的old属性 @2
 - 第0条的new属性 @3

*/

global $tester;
$tester->dao->exec('DROP TABLE IF EXISTS `ops_repo`');
$tester->dao->exec(<<<'SQL'
CREATE TABLE `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `space` int NOT NULL DEFAULT 0,
  `spaceID` int NOT NULL DEFAULT 0,
  `product` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  `SCM` varchar(30) NOT NULL DEFAULT '',
  `serviceHost` varchar(255) NOT NULL DEFAULT '',
  `serviceProject` varchar(255) NOT NULL DEFAULT '',
  `projects` varchar(255) NOT NULL DEFAULT '',
  `account` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `encrypt` varchar(30) NOT NULL DEFAULT 'base64',
  `gitUID` char(42) NOT NULL DEFAULT '',
  `acl` varchar(30) NOT NULL DEFAULT 'private',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `deleted` tinyint NOT NULL DEFAULT 0,
  `providerID` int unsigned NOT NULL DEFAULT 0,
  `mirror` tinyint unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

zenData('ops_repouser')->gen(0);

$tester->dao->insert(TABLE_REPO)->data((object)array(
    'id'             => 1,
    'space'          => 1,
    'spaceID'        => 1,
    'product'        => '1',
    'name'           => 'testHtml',
    'path'           => 'http://repo.local/testhtml',
    'SCM'            => 'Gitlab',
    'serviceHost'    => '1',
    'serviceProject' => '2',
    'projects'       => '',
    'gitUID'         => 'uid1',
    'acl'            => 'private',
    'status'         => 'active',
    'deleted'        => 0,
    'providerID'     => 0,
    'mirror'         => 0,
))->exec();
$tester->dao->insert(TABLE_DEVOPSREPOUSER)->data((object)array('repo' => 1, 'account' => 'admin'))->exec();

su('admin');

$_SERVER['REQUEST_URI'] = 'http://unittest.com';

$data1 = (object)array('space' => 1, 'product' => '1', 'SCM' => 'Gitlab', 'name' => 'repo1', 'serviceHost' => '1', 'serviceProject' => '2', 'acl' => 'private', 'members' => 'admin');
$data2 = (object)array('space' => 1, 'product' => '2', 'SCM' => 'Gitlab', 'name' => 'repo1', 'serviceHost' => '1', 'serviceProject' => '2', 'acl' => 'private', 'members' => 'admin');
$data3 = (object)array('space' => 1, 'product' => '2', 'projects' => '3', 'SCM' => 'Gitlab', 'name' => 'repo1', 'serviceHost' => '1', 'serviceProject' => '2', 'acl' => 'private', 'members' => 'admin');
$data4 = (object)array('space' => 1, 'product' => '2', 'projects' => '3', 'SCM' => 'Gitlab', 'name' => 'repo1', 'serviceHost' => '1', 'serviceProject' => '1', 'acl' => 'private', 'members' => 'admin');
$data5 = (object)array('space' => 1, 'product' => '2', 'projects' => '3', 'SCM' => 'Gitlab', 'name' => 'repo1_rename', 'serviceHost' => '1', 'serviceProject' => '1', 'acl' => 'private', 'members' => 'admin');
$data6 = (object)array('space' => 1, 'product' => '3', 'projects' => '3', 'SCM' => 'Gitlab', 'name' => 'repo1_rename', 'serviceHost' => '1', 'serviceProject' => '1', 'acl' => 'private', 'members' => 'admin');

$repo = new repoModelTest();
$repo->seedGitFoxEntry();

r($repo->updateTest(1, $data1, true)) && p('0:field,old,new') && e('name,testHtml,repo1');
r($repo->updateTest(1, $data2, true)) && p('0:field,old,new') && e('product,1,2');
r($repo->updateTest(1, $data3, true)) && p('0:field,old,new') && e('projects,~~,3');
r($repo->updateTest(1, $data4, true)) && p('0:field,old,new') && e('serviceProject,2,1');
r($repo->updateTest(1, $data5, true)) && p('0:field,old,new') && e('name,repo1,repo1_rename');
r($repo->updateTest(1, $data6, true)) && p('0:field,old,new') && e('product,2,3');
