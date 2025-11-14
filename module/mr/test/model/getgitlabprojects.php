#!/usr/bin/env php
<?php

/**

title=测试 mrModel::getGitlabProjects();
timeout=0
cid=17247

- 正确的服务器ID和项目ID列表
 - 第3条的id属性 @3
 - 第3条的name属性 @unittest1
- 服务器ID正确，筛选项目ID是2的项目属性2 @0
- 服务器ID错误 @0
- 非管理员用户，正确的服务器ID和项目ID列表 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mr.unittest.class.php';

zenData('user')->gen(3);
zenData('mr')->loadYaml('mr')->gen(1);
zenData('pipeline')->gen(1);
zenData('oauth')->loadYaml('oauth')->gen(1);
su('admin');

$mrTester = new mrTest();

$hostID        = 1;
$projectIdList = array();
r($mrTester->getGitlabProjectsTester($hostID, $projectIdList)) && p('3:id,name') && e('3,unittest1'); // 正确的服务器ID和项目ID列表

$projectIdList = array('1');
r($mrTester->getGitlabProjectsTester($hostID, $projectIdList)) && p('2') && e('0'); // 服务器ID正确，筛选项目ID是2的项目

$hostID        = 10;
$projectIdList = array();
r($mrTester->getGitlabProjectsTester($hostID, $projectIdList)) && p() && e('0'); // 服务器ID错误

su('user1');
$hostID        = 1;
$projectIdList = array();
r($mrTester->getGitlabProjectsTester($hostID, $projectIdList)) && p('') && e('0'); // 非管理员用户，正确的服务器ID和项目ID列表