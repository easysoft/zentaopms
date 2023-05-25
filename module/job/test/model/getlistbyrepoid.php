#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/job.class.php';
su('admin');

/**

title=jobModel::getListByRepoId();
cid=1
pid=1

获取repo为1的name >> 这是一个Job1
获取repo为100001的name >> 0

*/
$job = new jobTest();

$repoIdList = array('1', '100001');

r($job->getListByRepoIDTest($repoIdList[0])) && p('1:name') && e('这是一个Job1'); // 获取repo为1的name
r($job->getListByRepoIDTest($repoIdList[1])) && p('100001:name') && e('0');       // 获取repo为100001的name
