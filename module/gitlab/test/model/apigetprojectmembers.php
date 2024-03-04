#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 gitlabModel::apiGetProjectMembers();
timeout=0
cid=1

- 使用空的数据查询群组用户 @return null
- 使用空的projectID查询群组用户属性message @404 Project Not Found
- 通过gitlabID,projectID查询群组用户 @1
- 通过gitlabID,projectID,userID查询群组用户属性name @测试用户1

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 0;
$projectID = 0;

$result = $gitlab->apiGetProjectMembers($gitlabID, $projectID);
if(!$result) $result = 'return null';
r($result) && p() && e('return null'); //使用空的数据查询群组用户

$gitlabID = 1;
r($gitlab->apiGetProjectMembers($gitlabID, $projectID)) && p('message') && e('404 Project Not Found'); //使用空的projectID查询群组用户

$projectID = 2;
$result    = $gitlab->apiGetProjectMembers($gitlabID, $projectID);
r($result[0]->id > 0) && p() && e('1'); //通过gitlabID,projectID查询群组用户

$userID = 4;
r($gitlab->apiGetProjectMembers($gitlabID, $projectID, $userID)) && p('name') && e('测试用户1'); //通过gitlabID,projectID,userID查询群组用户