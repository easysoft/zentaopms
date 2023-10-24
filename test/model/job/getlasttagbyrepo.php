#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/job.class.php';
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
