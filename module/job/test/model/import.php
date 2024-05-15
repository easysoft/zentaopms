#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/job.unittest.class.php';
su('admin');

/**

title=测试jobModel->import
timeout=0
cid=1

- 测试导入gitlab流水线 @1

*/

zenData('pipeline')->gen(5);
zenData('repo')->loadYaml('repo')->gen(5);

$job = $tester->loadModel('job');

r($job->import(1)) && p() && e(1); // 测试导入gitlab流水线
