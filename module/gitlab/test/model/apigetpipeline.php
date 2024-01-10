#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->apiGetPipeline();
timeout=0
cid=1

- 查询正确的pipeline信息
 - 属性iid @60
 - 属性project_id @2
- 使用不存在的gitlabID查询pipeline信息 @0
- 使用不存在的projectID查询pipeline信息属性message @404 Project Not Found
- 使用不存在的branch查询pipeline信息 @0

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;
$projectID = 2;
$branch    = 'master';

$pipeline1 = $gitlab->apiGetPipeline($gitlabID, $projectID, $branch);
$pipeline2 = $gitlab->apiGetPipeline(0, $projectID, $branch);
$pipeline3 = $gitlab->apiGetPipeline($gitlabID, 0, $branch);
$pipeline4 = $gitlab->apiGetPipeline($gitlabID, $projectID, 'branch123');

r(end($pipeline1)) && p('iid,project_id') && e('60,2');                  // 查询正确的pipeline信息
r($pipeline2)      && p()                 && e('0');                     // 使用不存在的gitlabID查询pipeline信息
r($pipeline3)      && p('message')        && e('404 Project Not Found'); // 使用不存在的projectID查询pipeline信息
r($pipeline4)      && p()                 && e('0');                     // 使用不存在的branch查询pipeline信息
