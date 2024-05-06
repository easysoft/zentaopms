#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/job.unittest.class.php';
su('admin');

/**

title=jobModel->create();
timeout=0
cid=1

- 测试执行jenkins job的情况属性status @create_fail
- 测试执行gitlab job的情况属性status @created

*/

zenData('pipeline')->loadYaml('pipeline')->gen(5);
zenData('job')->loadYaml('job')->gen(5);
zenData('repo')->gen(5);
zenData('compile')->gen(0);

$job = new jobTest();
r($job->execTest(1)) && p('status')&& e('create_fail'); // 测试执行jenkins job的情况
r($job->execTest(2)) && p('status')&& e('created');     // 测试执行gitlab job的情况