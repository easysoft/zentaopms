#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/job.class.php';
su('admin');

/**

title=jobModel->getJobBySonarqubeProject();
timeout=0
cid=1

- 根据sonarqube project查询job属性zentaopms @1

*/

$sonarqubeID = 2;
$projectKeys = array('zentaopms');

$job = new jobTest();
r($job->getJobBySonarqubeProjectTest($sonarqubeID, $projectKeys)) && p('zentaopms') && e('1');  // 根据sonarqube project查询job