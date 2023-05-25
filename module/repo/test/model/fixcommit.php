#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->fixCommit();
cid=1
pid=1

*/

$repo = new repoTest();

r($repo->fixCommitTest()) && p() && e();