#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/job.class.php';
su('admin');

/**

title=jobModel->getLastTagByRepo();
timeout=0
cid=1

- 查询id为1的job的版本库的last tag @v0.1.2-light

*/

zdTable('pipeline')->gen(5);
zdTable('repo')->config('repo')->gen(5);

$jobID = 1;
$job = new jobTest();
r($job->getLastTagByRepoTest($jobID)) && p() && e('v0.1.2-light');  // 查询id为1的job的版本库的last tag