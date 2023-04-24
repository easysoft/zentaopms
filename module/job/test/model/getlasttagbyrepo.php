#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/job.class.php';
su('admin');

/**

title=jobModel->getLastTagByRepo();
cid=1
pid=1

查询id为1的job的last tag >> tag_test1

*/

$jobID = 1;
$job = new jobTest();
r($job->getLastTagByRepoTest($jobID)) && p() && e('tag_test1');  // 查询id为1的job的last tag
