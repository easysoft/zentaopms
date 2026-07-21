#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->getGitFoxRepos();
timeout=0
cid=0

- 方法存在性检查 >> 1
- repoModelTest 类存在 >> 1
- repoModel 类存在 >> 1
- getGitFoxRepos 方法存在 >> 1
- 类存在性确认 >> 1

*/

su('admin');
$repoTest = new repoModelTest();
r(method_exists($repoTest, 'getGitFoxReposTest')) && p() && e('1');
r(class_exists('repoModelTest')) && p() && e('1');
r(class_exists('repoModel')) && p() && e('1');
r(method_exists($repoTest, 'getGitFoxReposTest')) && p() && e('1');
r(class_exists('repoModelTest')) && p() && e('1');
