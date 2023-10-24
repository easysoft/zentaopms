#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/job.class.php';
su('admin');

/**

title=jobModel::getListByTriggerType();
cid=1
pid=1

获取trigger type为tag的repo为1的name >> 这是一个Job1
获取trigger type为commit的repo为2的name >> 这是一个Job2

*/
$job = new jobTest();

$triggerType = array('tag', 'commit');
$repoIdList  = array('1', '2', '100001');

r($job->getListByTriggerTypeTest($triggerType[0], $repoIdList)) && p('1:name') && e('这是一个Job1'); // 获取trigger type为tag的repo为1的name
r($job->getListByTriggerTypeTest($triggerType[1], $repoIdList)) && p('2:name') && e('这是一个Job2'); // 获取trigger type为commit的repo为2的name
