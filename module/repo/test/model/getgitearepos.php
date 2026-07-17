#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->getGiteaRepos();
timeout=0
cid=0

- 空apiRoot @rray()
- 有效apiRoot @rray()
- 空字符串apiRoot @rray()
- 缺失apiRoot @rray()
- 无效URL格式 @rray()

*/

su('admin');

$repoTest = new repoModelTest();

r($repoTest->getGiteaReposTest('')) && p() && e(array());                                  // 空apiRoot
r($repoTest->getGiteaReposTest('https://gitea.example.com/api/v1')) && p() && e(array());  // 有效apiRoot
r($repoTest->getGiteaReposTest('')) && p() && e(array());                                  // 空字符串apiRoot
r($repoTest->getGiteaReposTest('')) && p() && e(array());                                  // 缺失apiRoot
r($repoTest->getGiteaReposTest('bad-url')) && p() && e(array());                           // 无效URL格式