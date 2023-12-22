#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->apiGetSinglePipeline();
timeout=0
cid=1

- 查询正确的pipeline信息属性status @failed
- 使用不存在的gitlabID查询pipeline信息 @0
- 使用不存在的projectID查询pipeline信息属性message @404 Project Not Found
- 使用不存在的pipelineID查询pipeline信息属性message @404 Not found

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID   = 1;
$projectID  = 2;
$pipelineID = 8;

$pipeline1 = $gitlab->apiGetSinglePipeline($gitlabID, $projectID, $pipelineID);
$pipeline2 = $gitlab->apiGetSinglePipeline(0, $projectID, $pipelineID);
$pipeline3 = $gitlab->apiGetSinglePipeline($gitlabID, 0, $pipelineID);
$pipeline4 = $gitlab->apiGetSinglePipeline($gitlabID, $projectID, 10001);

r($pipeline1) && p('status')  && e('failed');                // 查询正确的pipeline信息
r($pipeline2) && p()          && e('0');                     // 使用不存在的gitlabID查询pipeline信息
r($pipeline3) && p('message') && e('404 Project Not Found'); // 使用不存在的projectID查询pipeline信息
r($pipeline4) && p('message') && e('404 Not found');         // 使用不存在的pipelineID查询pipeline信息