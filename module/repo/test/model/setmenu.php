#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

global $app;
$app->config->debug = 0;

/**

title=测试 repoModel->setMenu();
timeout=0
cid=18104

- 正常设置版本库id @2
- 正常设置版本库id @3
- 正常设置版本库id @4
- 设置不存在版本库id @1
- 无权限用户设置版本库id @0
- 非镜像库不屏蔽 repoCodeScan/review 菜单 @repoCodeScan:1|review:1
- 镜像库屏蔽 repoCodeScan/review 两个一级菜单 @repoCodeScan:0|review:0

*/

zenData('user')->gen(20);
zenData('pipeline')->gen(5);
zenData('project')->gen(5);
zenData('oauth')->loadYaml('oauth')->gen(20);

global $dbh;
$dbh->exec("CREATE TABLE IF NOT EXISTS `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `spaceID` int NOT NULL DEFAULT 0,
  `product` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `desc` varchar(500) NOT NULL DEFAULT '',
  `scmType` varchar(10) NOT NULL DEFAULT 'git',
  `projects` varchar(255) NOT NULL DEFAULT '',
  `gitUID` char(42) NOT NULL DEFAULT '',
  `forkID` int unsigned DEFAULT NULL,
  `mirror` tinyint(1) NOT NULL DEFAULT 0,
  `providerID` int unsigned NOT NULL DEFAULT 0,
  `connector` text DEFAULT NULL,
  `defaultBranch` varchar(255) NOT NULL DEFAULT '',
  `acl` varchar(30) NOT NULL DEFAULT 'open',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `createdBy` varchar(30) NOT NULL DEFAULT '',
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(30) NOT NULL DEFAULT '',
  `editedDate` datetime DEFAULT NULL,
  `deleted` tinyint NOT NULL DEFAULT 0,
  `synced` tinyint unsigned NOT NULL DEFAULT 0,
  `branchArchivable` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$dbh->exec("CREATE TABLE IF NOT EXISTS `ops_repouser` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `repo` int unsigned NOT NULL DEFAULT 0,
  `account` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$dbh->exec("TRUNCATE TABLE `ops_repo`");
$dbh->exec("TRUNCATE TABLE `ops_repouser`");

$dbh->exec("INSERT INTO `ops_repo` (`id`,`spaceID`,`product`,`name`,`gitUID`,`mirror`,`defaultBranch`,`acl`,`status`,`createdBy`,`deleted`) VALUES
(2,0,'1','repo2','uid2',0,'main','private','active','admin',0),
(3,0,'1','repo3','uid3',0,'main','private','active','admin',0),
(4,0,'1000','repo4','uid4',0,'main','private','active','admin',0),
(5,0,'1','repo5','uid5',1,'main','private','active','admin',0)");

/* admin 加入 repo2/3/4/5 的成员列表。 */
$dbh->exec("INSERT INTO `ops_repouser` (`repo`,`account`) VALUES (2,'admin'),(3,'admin'),(4,'admin'),(5,'admin')");

/* 让 gitfoxModel::getServer() 返回非空，避免 getApiRoot() 返空字符串导致 processGitService 走到 sprintf(null, ...) fatal。 */
zenData('entry')->loadYaml('entry')->gen(1);

$repo = new repoModelTest();

r($repo->setMenuTest(2))             && p() && e('2');                       // 步骤1：正常设置版本库id=2
r($repo->setMenuTest(3))             && p() && e('3');                       // 步骤2：正常设置版本库id=3
r($repo->setMenuTest(4))             && p() && e('4');                       // 步骤3：正常设置版本库id=4
r($repo->setMenuTest(10001))         && p() && e('2');                       // 步骤4：不存在的 id 时 setMenu 内 key($repos) 回退到首条 id=2

su('user19');
r($repo->setMenuTest(3))             && p() && e('0');                       // 步骤5：无权限用户访问 → repoID 归 0

su('admin');
r($repo->setMenuMirrorCheckTest(2))  && p() && e('repoCodeScan:1|review:1'); // 步骤6：非镜像库 mirror=0，两菜单仍在
r($repo->setMenuMirrorCheckTest(5))  && p() && e('repoCodeScan:0|review:0'); // 步骤7：镜像库 mirror=1，两菜单被 unset
