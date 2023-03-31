#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/job.class.php';
su('admin');

/**

title=jobModel->create();
cid=1
pid=1

测试执行job id为1的情况 >> 1

*/

$jobID = 1;
$job = new jobTest();
r($job->execTest($jobID)) && p('id')&& e('1');     // 测试执行job id为1的情况
