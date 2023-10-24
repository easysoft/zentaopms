#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/job.class.php';
su('admin');

/**

title=jobModel::getTriggerConfig();
cid=1
pid=1

获取job为1的trigger config >> 目录改动(/module/caselib)
获取job为100001的trigger config >> 0

*/
$job = new jobTest();

$jobIdList = array('1', '100001');

r($job->getTriggerConfigTest($jobIdList[0])) && p() && e('目录改动(/module/caselib)'); // 获取job为1的trigger config
r($job->getTriggerConfigTest($jobIdList[1])) && p() && e('0');                         // 获取job为100001的trigger config
