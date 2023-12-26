#!/usr/bin/env php
<?php

/**

title=测试jenkinsModel->getDepthJobs();
cid=1

- 测试获取获取深度 1 userPWD userPWD1 的 全部流水线。 @folder1.1.1.1:0,folder1.1.1.2:0,folder1.1.2:0,folder1.2:0,/job/folder1/job/paramsJob1/:paramsJob1,/job/hello%20world/:hello world,/job/paramsJob/:paramsJob,/job/simple-job/:simple-job

- 测试获取获取深度 2 userPWD userPWD1 的 全部流水线。 @folder1.1.1:0,folder1.1.2:0,folder1.2:0,/job/folder1/job/paramsJob1/:paramsJob1,/job/hello%20world/:hello world,/job/paramsJob/:paramsJob,/job/simple-job/:simple-job

- 测试获取获取深度 3 userPWD userPWD1 的 全部流水线。 @folder1.1:0,folder1.2:0,/job/folder1/job/paramsJob1/:paramsJob1,/job/hello%20world/:hello world,/job/paramsJob/:paramsJob,/job/simple-job/:simple-job

- 测试获取获取深度 4 userPWD userPWD1 的 全部流水线。 @/job/hello%20world/:hello world,/job/paramsJob/:paramsJob,/job/simple-job/:simple-job


*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/jenkins.class.php';

zdTable('user')->gen('1');

su('admin');

$jenkins = new jenkinsTest();

$depth = array(1, 2, 3, 4);

r($jenkins->getDepthJobsTest($depth[0])) && p() && e('folder1.1.1.1:0,folder1.1.1.2:0,folder1.1.2:0,folder1.2:0,/job/folder1/job/paramsJob1/:paramsJob1,/job/hello%20world/:hello world,/job/paramsJob/:paramsJob,/job/simple-job/:simple-job'); // 测试获取获取深度 1 userPWD userPWD1 的 全部流水线。
r($jenkins->getDepthJobsTest($depth[1])) && p() && e('folder1.1.1:0,folder1.1.2:0,folder1.2:0,/job/folder1/job/paramsJob1/:paramsJob1,/job/hello%20world/:hello world,/job/paramsJob/:paramsJob,/job/simple-job/:simple-job'); // 测试获取获取深度 2 userPWD userPWD1 的 全部流水线。
r($jenkins->getDepthJobsTest($depth[2])) && p() && e('folder1.1:0,folder1.2:0,/job/folder1/job/paramsJob1/:paramsJob1,/job/hello%20world/:hello world,/job/paramsJob/:paramsJob,/job/simple-job/:simple-job'); // 测试获取获取深度 3 userPWD userPWD1 的 全部流水线。
r($jenkins->getDepthJobsTest($depth[3])) && p() && e('/job/hello%20world/:hello world,/job/paramsJob/:paramsJob,/job/simple-job/:simple-job'); // 测试获取获取深度 4 userPWD userPWD1 的 全部流水线。
