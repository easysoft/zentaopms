#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiCreatePipeline();
timeout=0
cid=1

- 使用空的gitlabID,projectID,pipeline对象创建GitLabpipeline @0
- 使用空的gitlabID、projectID,正确的pipeline对象创建GitLabpipeline @0
- 使用正确的gitlabID、pipeline信息，错误的projectID创建pipeline属性message @404 Project Not Found
- 通过gitlabID,projectID,pipeline对象正确创建GitLabpipeline属性status @created

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;
$projectID = 2;
$emptyPipeline = new stdclass();

$pipeline = new stdclass();
$pipeline->ref = 'master';

r($gitlab->apiCreatePipeline(0, 0, $emptyPipeline))             && p()          && e('0'); //使用空的gitlabID,projectID,pipeline对象创建GitLabpipeline
r($gitlab->apiCreatePipeline(0, 0, $pipeline))                  && p()          && e('0'); //使用空的gitlabID、projectID,正确的pipeline对象创建GitLabpipeline
r($gitlab->apiCreatePipeline($gitlabID, 0, $pipeline))          && p('message') && e('404 Project Not Found'); //使用正确的gitlabID、pipeline信息，错误的projectID创建pipeline
r($gitlab->apiCreatePipeline($gitlabID, $projectID, $pipeline)) && p('status')  && e('created');         //通过gitlabID,projectID,pipeline对象正确创建GitLabpipeline