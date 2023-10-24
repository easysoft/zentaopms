#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/job.class.php';
su('admin');

/**

title=jobModel->getJobBySonarqubeProject();
cid=1
pid=1

根据sonarqube project查询job >> 1

*/

$sonarqubeID = 2;
$projectKeys = array('zentaopms');

$job = new jobTest();
r($job->getJobBySonarqubeProjectTest($sonarqubeID, $projectKeys)) && p('zentaopms') && e('1');  // 根据sonarqube project查询job
