#!/usr/bin/env php
<?php
/**

title=测试 artifactrepoModel->create();
cid=1

- 正常创建制品库
 - 属性name @新建制品库1
 - 属性products @~~
 - 属性serverID @1
 - 属性repoName @代码库
 - 属性format @~~
 - 属性type @~~
 - 属性status @~~
- 创建制品库名称重复第name条的0属性 @『名称』已经有『新建制品库1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。
- 创建制品库代码库重复第repoName条的0属性 @『制品库』已经有『代码库』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/artifactrepo.class.php';

zdTable('user')->gen(5);
zdTable('artifactrepo')->gen(0);

$names     = array('新建制品库1', '新建制品库2');
$repoName  = '代码库';
$serverIds = array(1, 2);

$normalData = array('name' => $names[0], 'repoName' => $repoName, 'serverID' => $serverIds[0]);
$repeatName = array('name' => $names[0], 'repoName' => $repoName, 'serverID' => $serverIds[1]);
$repeatRepo = array('name' => $names[1], 'repoName' => $repoName, 'serverID' => $serverIds[0]);

$artifactrepoTester = new artifactrepoTest();
r($artifactrepoTester->createTest($normalData)) && p('name,products,serverID,repoName,format,type,status') && e('新建制品库1,~~,1,代码库,~~,~~,~~');                                                                // 正常创建制品库
r($artifactrepoTester->createTest($repeatName)) && p('name:0')                                             && e('『名称』已经有『新建制品库1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 创建制品库名称重复
r($artifactrepoTester->createTest($repeatRepo)) && p('repoName:0')                                         && e('『制品库』已经有『代码库』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。');    // 创建制品库代码库重复
