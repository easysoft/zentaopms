#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/job.class.php';
su('admin');

/**

title=jobModel->getById();
timeout=0
cid=1

- 查询id为1的job的engine属性engine @jenkins
- 查询id为1的job的frame属性frame @sonarqube
- 查询id为1000001的job的name属性engine @~~
- 查询id为1000001的job的account属性frame @~~

*/

zdTable('job')->gen(1);

$jobIDList = array('1', '1000001');

$job = new jobTest();

r($job->getByIdTest($jobIDList[0])) && p('engine') && e('jenkins');  // 查询id为1的job的engine
r($job->getByIdTest($jobIDList[0])) && p('frame')  && e('sonarqube'); // 查询id为1的job的frame
r($job->getByIdTest($jobIDList[1])) && p('engine') && e('~~');        // 查询id为1000001的job的name
r($job->getByIdTest($jobIDList[1])) && p('frame')  && e('~~');         // 查询id为1000001的job的account