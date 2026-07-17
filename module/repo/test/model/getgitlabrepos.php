#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->getGitLabRepos();
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

r($repoTest->getGitLabReposTest('')) && p() && e(array());                              // 空apiRoot
r($repoTest->getGitLabReposTest('https://gitlab.example.com/api/v4')) && p() && e(array()); // 有效apiRoot
r($repoTest->getGitLabReposTest('')) && p() && e(array());                              // 空字符串apiRoot
r($repoTest->getGitLabReposTest('')) && p() && e(array());                              // 缺失apiRoot
r($repoTest->getGitLabReposTest('invalid-url')) && p() && e(array());                   // 无效URL格式