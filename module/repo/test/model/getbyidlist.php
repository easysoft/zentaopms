#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getByIdList();
timeout=0
cid=18049

- 测试正常获取多个存在的ID >> 4
- 测试获取单个存在ID验证name >> repo1
- 测试不存在的ID >> 0
- 测试空ID列表 >> 0
- 测试混合存在不存在的ID >> 2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $dbh;
$dbh->exec('DROP TABLE IF EXISTS `ops_repo`');
$dbh->exec("CREATE TABLE `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `spaceID` int NOT NULL DEFAULT 0,
  `product` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `SCM` varchar(30) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `encrypt` varchar(30) NOT NULL DEFAULT 'base64',
  `acl` varchar(30) NOT NULL DEFAULT 'open',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$repos = array(1=>'repo1',2=>'repo2',3=>'repo3',4=>'repo4',5=>'repo5',6=>'repo6',7=>'repo7',8=>'repo8');
$pwds = array('dGVzdA==','cGFzc3dvcmQ=','plaintext1','plaintext2','dGVzdDE=','cGFzc3dvcmQx','plaintext3','plaintext4');
$scms = array('Git','Git','Gitlab','Gitlab','SVN','SVN','Subversion','Subversion');
$encs = array('base64','base64','base64','base64','plain','plain','plain','plain');
$dels = array(0,0,0,0,0,0,1,1);

for($i=1; $i<=8; $i++) {
    $p = $i<=4 ? '1' : '2';
    $dbh->exec("INSERT INTO `ops_repo` (`id`,`spaceID`,`product`,`name`,`SCM`,`password`,`encrypt`,`acl`,`status`,`deleted`) VALUES ($i,1,'$p','{$repos[$i]}','{$scms[$i-1]}','{$pwds[$i-1]}','{$encs[$i-1]}','open','active',{$dels[$i-1]})");
}

su('admin');
$repoTest = new repoModelTest();

r(count($repoTest->getByIdListTest(array(1,2,3,4)))) && p() && e('4');
r($repoTest->getByIdListTest(array(1))) && p('1:name') && e('repo1');
r(count($repoTest->getByIdListTest(array(999,1000)))) && p() && e('0');
r(count($repoTest->getByIdListTest(array()))) && p() && e('0');
r(count($repoTest->getByIdListTest(array(1,2,999,1000)))) && p() && e('2');
