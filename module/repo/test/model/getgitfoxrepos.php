#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->getGitFoxRepos();
timeout=0
cid=0

- 正常调用返回数组 @rray()
- 返回结果类型检查 @rray()
- 多次调用一致性 @rray()
- 重复调用验证 @rray()
- 最终验证 @rray()

*/

su('admin');

$repoTest = new repoModelTest();

r($repoTest->getGitFoxReposTest()) && p() && e(array());   // 正常调用返回数组
r($repoTest->getGitFoxReposTest()) && p() && e(array());   // 返回结果类型检查
r($repoTest->getGitFoxReposTest()) && p() && e(array());   // 多次调用一致性
r($repoTest->getGitFoxReposTest()) && p() && e(array());   // 重复调用验证
r($repoTest->getGitFoxReposTest()) && p() && e(array());   // 最终验证