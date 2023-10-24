#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/job.class.php';
su('admin');

/**

title=jobModel::getParis();
cid=1
pid=1

获取repo为1且engine为jenkins的name >> 这是一个Job1

*/
$job = new jobTest();

$repoID = '1';
$engine = 'jenkins';

r($job->getPairsTest($repoID, $engine)) && p('1') && e('这是一个Job1'); // 获取repo为1且engine为jenkins的name
