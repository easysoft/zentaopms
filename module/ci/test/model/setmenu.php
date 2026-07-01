#!/usr/bin/env php
<?php

/**

title=测试 ciModel::setMenu();
timeout=0
cid=15592

- 执行ci模块的setMenuTest方法 第code条的link属性 @代码|repo|browse|repoID=1
- 执行ci模块的setMenuTest方法，参数是2 第code条的link属性 @代码|repo|browse|repoID=2
- 执行ci模块的setMenuTest方法，参数是5 属性mr @~~
- 执行ci模块的setMenuTest方法，参数是2, 'gitlab' 第code条的link属性 @代码|repo|browse|repoID=%s
- 执行ci模块的setMenuTest方法，参数是0, 'ci' 第code条的link属性 @代码|repo|browse|repoID=1
- 执行ci模块的setMenuTest方法，参数是1 第code条的link属性 @代码|repo|browse|repoID=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$repo = zenData('repo');
$repo->id->range('1-10');
$repo->product->range('1{5},2{5}');
$repo->name->range('Git仓库{3},SVN仓库{2},Gitlab仓库{3},Github仓库{2}');
$repo->SCM->range('Git{3},SVN{2},Gitlab{3},Github{2}');
$repo->serviceHost->range('1-5');
$repo->deleted->range('0{8},1{2}');
$repo->gen(10);

zenData('pipeline')->gen(5);

/* 与 module/repo/test/model/setmenu.php 同 schema，避免并行冲突。 */
global $dbh, $app;
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
(1,0,'1','repo1','uid1',0,'main','private','active','admin',0),
(2,0,'1','repo2','uid2',0,'main','private','active','admin',0),
(3,0,'1','repo3','uid3',0,'main','private','active','admin',0),
(4,0,'1','repo4','uid4',0,'main','private','active','admin',0),
(5,0,'1','repo5','uid5',0,'main','private','active','admin',0)");

$dbh->exec("INSERT INTO `ops_repouser` (`repo`,`account`) VALUES (1,'admin'),(2,'admin'),(3,'admin'),(4,'admin'),(5,'admin')");

/* 让 gitfoxModel::getServer() 返回非空，避免 processGitService 里 sprintf(null,...) fatal。 */
zenData('entry')->loadYaml('entry')->gen(1);
$dbh->exec("REPLACE INTO `zt_entry` (`id`,`name`,`account`,`code`,`key`,`freePasswd`,`ip`,`createdBy`,`createdDate`,`calledTime`,`editedBy`,`editedDate`,`deleted`) VALUES (1,'GitFox入口','admin','gitfox','testkey1234567890testkey1234567',0,'*','admin','2026-01-01 00:00:00',0,'admin','2026-01-01 00:00:00','0')");

su('admin');

/* stub HTTP，让 apiGetSingleRepo 返回带 gitURL 的对象，避免读 array 属性告警。 */
if(!class_exists('ciSetMenuStubHttpClient'))
{
    class ciSetMenuStubHttpClient
    {
        public function request($url, $data = null, $options = array(), $headers = array(), $dataType = 'data', $method = 'POST', $timeout = 30, $httpCode = false, $log = true)
        {
            return json_encode(array('code' => 'success', 'data' => array('id' => 1, 'path' => 'space/repo', 'gitURL' => 'http://gitfox.test/space/repo.git', 'importing' => false)));
        }
    }
}
common::$httpClient = new ciSetMenuStubHttpClient();

$ci = new ciModelTest();

r($ci->setMenuTest(0)) && p('code:link') && e('代码|repo|browse|repoID=1');
r($ci->setMenuTest(2)) && p('code:link') && e('代码|repo|browse|repoID=2');
r($ci->setMenuTest(5)) && p('mr') && e('~~');
r($ci->setMenuTest(2, 'gitlab')) && p('code:link') && e('代码|repo|browse|repoID=%s');
r($ci->setMenuTest(0, 'ci')) && p('code:link') && e('代码|repo|browse|repoID=1');
r($ci->setMenuTest(1)) && p('code:link') && e('代码|repo|browse|repoID=1');

common::$httpClient = null;
