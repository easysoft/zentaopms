#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->create();
timeout=0
cid=18035

- 正常创建 Gitlab 版本库属性id @1
- 重复 Gitlab 名称创建第name条的0属性 @『名称』已经有『zzxx』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。
- 客户端为空创建 Gitea 版本库第client条的0属性 @『客户端』不能为空。
- 正常创建 Gitea 版本库属性SCM @Gitea
- 客户端为空创建 Git 版本库第client条的0属性 @『客户端』不能为空。
- 客户端为空创建 SVN 版本库第client条的0属性 @『客户端』不能为空。

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
  `acl` varchar(30) NOT NULL DEFAULT 'open',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `deleted` tinyint NOT NULL DEFAULT 0,
  `encoding` varchar(32) NOT NULL DEFAULT 'utf-8',
  `client` varchar(255) NOT NULL DEFAULT '',
  `desc` varchar(500) NOT NULL DEFAULT '',
  `uid` varchar(32) NOT NULL DEFAULT '',
  `providerID` int unsigned NOT NULL DEFAULT 0,
  `mirror` tinyint unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL);

zenData('ops_repouser')->gen(0);
zenData('entry')->gen(0);

$entry = zenData('entry');
$entry->id->range('1');
$entry->name->range('GitFox');
$entry->account->range('');
$entry->code->range('gitfox');
$entry->key->range('cd65d97989fcb1fdb0d82471c3238a3a');
$entry->freePasswd->range('1');
$entry->ip->range('*');
$entry->createdBy->range('admin');
$entry->createdDate->range('2026-01-01 00:00:00');
$entry->calledTime->range('0');
$entry->editedBy->range('admin');
$entry->editedDate->range('2026-01-01 00:00:00');
$entry->deleted->range('0');
$entry->gen(1);

$_SERVER['REQUEST_URI'] = 'http://unittest.com';

su('admin');

$repo = new repoModelTest();
$repo->instance->config->devops->gitfoxURL  = 'http://localhost';
$repo->instance->config->devops->gitfoxPort = 3000;

$gitlab = array(
    'SCM'            => 'Gitlab',
    'serviceHost'    => 1,
    'serviceProject' => 100,
    'name'           => 'zzxx',
    'path'           => '/var/www/html/zentaopms/www/data/repo/zzxx',
    'encoding'       => 'utf-8',
    'client'         => '',
    'desc'           => '',
);

$gitea = array(
    'SCM'            => 'Gitea',
    'serviceHost'    => 4,
    'serviceProject' => 'gitea/unittest',
    'name'           => 'Demo',
    'path'           => '/var/www/html/zentaopms/www/data/repo/Demo',
    'encoding'       => 'utf-8',
    'client'         => '',
    'desc'           => '',
    'uid'            => '6322b184f3a72'
);

$git = array(
    'SCM'            => 'Git',
    'name'           => '本地git',
    'path'           => '/var/www/html/zentaopms/',
    'encoding'       => 'utf-8',
    'client'         => '',
    'desc'           => '',
);

$svn = array(
    'product'        => '1',
    'SCM'            => 'Subversion',
    'name'           => 'svn',
    'path'           => 'https://svn.zcorp.cc',
    'encoding'       => 'utf-8',
    'account'        => 'user1',
    'password'       => base64_encode('123456'),
    'encrypt'        => 'base64',
    'client'         => '',
    'desc'           => '',
);

r($repo->createTest($gitlab))            && p('id')     && e('1');
r($repo->createTest($gitlab))            && p('name:0') && e('『名称』已经有『zzxx』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。');
r($repo->createTest($gitea))        && p('client:0')    && e('『客户端』不能为空。');
$gitea['client'] = '/usr/bin/git';
r($repo->createTest($gitea))             && p('SCM')    && e('Gitea');
r($repo->createTest($git, false))        && p('client:0') && e('『客户端』不能为空。');
r($repo->createTest($svn, false))        && p('client:0') && e('『客户端』不能为空。');
