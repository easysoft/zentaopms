#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getFileTree();
timeout=0
cid=8

- 测试gitea服务器连接 @1
- 测试空得参数连接 @0
- 测试不存在gitea服务器项目连接属性serviceProject @该项目克隆地址未找到

*/

zdTable('pipeline')->gen(4);
zdTable('repo')->config('repo')->gen(5);

$repo = new repoTest();

$giteaID        = 3;
$serviceProject = 'unittest123';

r($repo->checkGiteaConnectionTest($giteaID))                  && p()                 && e('1'); //测试gitea服务器连接
r($repo->checkGiteaConnectionTest(0))                         && p()                 && e('0'); //测试空得参数连接
r($repo->checkGiteaConnectionTest($giteaID, $serviceProject)) && p('serviceProject') && e('该项目克隆地址未找到'); //测试不存在gitea服务器项目连接