#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->apiGetJobLog();
timeout=0
cid=1

- 查询正确的job信息 @0
- 使用不存在的projectID查询job信息属性message @404 Project Not Found
- 使用不存在的jobID查询job信息属性message @404 Not found

*/

zdTable('job')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;
$projectID = 2;
$jobID     = 8;

$jobLog1 = $gitlab->apiGetJobLog($gitlabID, $projectID, $jobID);
$jobLog2 = $gitlab->apiGetJobLog($gitlabID, 0, $jobID);
$jobLog3 = $gitlab->apiGetJobLog($gitlabID, $projectID, 10001);

r($jobLog1)              && p()          && e('0');                     // 查询正确的job信息
r(json_decode($jobLog2)) && p('message') && e('404 Project Not Found'); // 使用不存在的projectID查询job信息
r(json_decode($jobLog3)) && p('message') && e('404 Not found');         // 使用不存在的jobID查询job信息