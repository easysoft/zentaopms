#!/usr/bin/env php
<?php
/**

title=测试 artifactrepoModel->getServerRepos();
cid=1

- 获取ID为0的服务器仓库列表 @0
- 获取ID为1的服务器仓库列表属性result @~~
- 获取ID为2的服务器仓库列表 @0
- 获取ID为3的服务器仓库列表 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/artifactrepo.class.php';

zdTable('user')->gen(5);
zdTable('pipeline')->config('pipeline')->gen(2);

$idList = array(0, 1, 2, 3);

$artifactrepoTester = new artifactrepoTest();
r($artifactrepoTester->getServerReposTest($idList[0])) && p()         && e('0');  // 获取ID为0的服务器仓库列表
r($artifactrepoTester->getServerReposTest($idList[1])) && p('result') && e('~~'); // 获取ID为1的服务器仓库列表
r($artifactrepoTester->getServerReposTest($idList[2])) && p()         && e('0');  // 获取ID为2的服务器仓库列表
r($artifactrepoTester->getServerReposTest($idList[3])) && p()         && e('0');  // 获取ID为3的服务器仓库列表
