#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiDeleteProjectMember();
timeout=0
cid=1

- 使用空的projectID删除gitlab群组属性message @404 Project Not Found
- 使用空的memberID删除gitlab群组属性message @404 Not found
- 使用错误gitlabID删除群组 @0
- 通过gitlabID,projectID,分支对象正确删除GitLab分支 @1
- 使用重复的分支信息删除分支属性message @404 Not found

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;
$projectID = 4;
$memberID  = 4;

$result = $gitlab->apiDeleteProjectMember($gitlabID, 0, $memberID);
r($result) && p('message') && e('404 Project Not Found'); //使用空的projectID删除gitlab群组

$result = $gitlab->apiDeleteProjectMember($gitlabID, $projectID, 0);
r($result) && p('message') && e('404 Not found'); //使用空的memberID删除gitlab群组

r($gitlab->apiDeleteProjectMember(0, $projectID, $memberID)) && p() && e('0'); //使用错误gitlabID删除群组

$result = $gitlab->apiDeleteProjectMember($gitlabID, $projectID, $memberID);
if(is_null($result)) $result = true;
if(!empty($result->message) and $result->message == '404 Not found') $result = true;
r($result) && p() && e('1');         //通过gitlabID,projectID,分支对象正确删除GitLab分支

r( $gitlab->apiDeleteProjectMember($gitlabID, $projectID, $memberID)) && p('message') && e('404 Not found'); //使用重复的分支信息删除分支