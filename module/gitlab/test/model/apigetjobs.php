#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->apiGetJobs();
timeout=0
cid=1

- 查询正确的pipeline job信息第0条的stage属性 @deploy
- 使用不存在的gitlabID查询pipeline job信息 @0
- 使用不存在的projectID查询pipeline job信息属性message @404 Project Not Found
- 使用不存在的pipelineID查询pipeline job信息属性message @404 Not found

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID   = 1;
$projectID  = 2;
$pipelineID = 8;

$pipeline1 = $gitlab->apiGetJobs($gitlabID, $projectID, $pipelineID);
$pipeline2 = $gitlab->apiGetJobs(0, $projectID, $pipelineID);
$pipeline3 = $gitlab->apiGetJobs($gitlabID, 0, $pipelineID);
$pipeline4 = $gitlab->apiGetJobs($gitlabID, $projectID, 10001);

r($pipeline1) && p('0:stage') && e('deploy');                // 查询正确的pipeline job信息
r($pipeline2) && p()          && e('0');                     // 使用不存在的gitlabID查询pipeline job信息
r($pipeline3) && p('message') && e('404 Project Not Found'); // 使用不存在的projectID查询pipeline job信息
r($pipeline4) && p('message') && e('404 Not found');         // 使用不存在的pipelineID查询pipeline job信息