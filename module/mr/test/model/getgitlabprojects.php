#!/usr/bin/env php
<?php

/**

title=测试 mrModel->getGitlabProjects();
timeout=0
cid=1

- 正确的服务器ID和项目ID列表
 - 第3条的id属性 @3
 - 第3条的name属性 @unittest1
- 服务器ID正确，筛选项目ID是1的项目属性2 @~~
- 服务器ID错误 @0
- 非管理员用户，正确的服务器ID和项目ID列表
 - 第1条的id属性 @1
 - 第1条的name属性 @Monitoring

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mr.class.php';

zdTable('user')->gen(3);
zdTable('mr')->gen(0);
zdTable('pipeline')->gen(1);
zdTable('oauth')->config('oauth')->gen(1);
su('admin');

$mrTester = new mrTest();

$hostID        = 1;
$projectIdList = array();
r($mrTester->getGitlabProjectsTester($hostID, $projectIdList)) && p('3:id,name') && e('3,unittest1'); // 正确的服务器ID和项目ID列表

$projectIdList = array('1');
r($mrTester->getGitlabProjectsTester($hostID, $projectIdList)) && p('2') && e('~~'); // 服务器ID正确，筛选项目ID是1的项目

$hostID        = 10;
$projectIdList = array();
r($mrTester->getGitlabProjectsTester($hostID, $projectIdList)) && p() && e('0'); // 服务器ID错误

su('user1', false);
$hostID        = 1;
$projectIdList = array();
r($mrTester->getGitlabProjectsTester($hostID, $projectIdList)) && p('1:id,name') && e('1,Monitoring'); // 非管理员用户，正确的服务器ID和项目ID列表