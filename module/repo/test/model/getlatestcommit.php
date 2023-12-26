#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getLatestCommit();
timeout=0
cid=1

- 执行repo模块的getLatestCommitTest方法  @

*/

$repo = new repoTest();

r($repo->getLatestCommitTest()) && p() && e();