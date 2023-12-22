#!/usr/bin/env php
<?php

/**

title=gitModel->run();
timeout=0
cid=1

- 更新git提交信息到禅道，Gtilab的webhook调用 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/git.class.php';

zdTable('job')->gen(0);
zdTable('repo')->config('repo')->gen(1);
zdTable('repohistory')->gen(0);
su('admin');

$git = new gitTest();

r($git->runTest()) && p() && e('0'); // 更新git提交信息到禅道，Gtilab的webhook调用