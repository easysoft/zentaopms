#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/job.unittest.class.php';
su('admin');

/**

title=jobModel->getLastTagByRepo();
timeout=0
cid=1

- 查询id为1的job的版本库的last tag @test_tag17

*/

zenData('pipeline')->gen(5);
zenData('repo')->loadYaml('repo')->gen(5);

$jobID = 1;
$job = new jobTest();
r($job->getLastTagByRepoTest($jobID)) && p() && e('test_tag17');  // 查询id为1的job的版本库的last tag