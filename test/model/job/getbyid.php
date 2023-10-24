#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/job.class.php';
su('admin');

/**

title=jobModel->getById();
cid=1
pid=1

查询id为1的job的engine >> jenkins
查询id为1的job的frame >> sonarqube

*/

$jobIDList = array('1', '1000001');

$job = new jobTest();

r($job->getByIdTest($jobIDList[0])) && p('engine') && e('jenkins');  // 查询id为1的job的engine
r($job->getByIdTest($jobIDList[0])) && p('frame') && e('sonarqube'); // 查询id为1的job的frame
r($job->getByIdTest($jobIDList[1])) && p('engine') && e(NULL);        // 查询id为1000001的job的name
r($job->getByIdTest($jobIDList[1])) && p('frame') && e(NULL);         // 查询id为1000001的job的account
