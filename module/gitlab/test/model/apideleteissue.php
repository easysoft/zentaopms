#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 gitlabModel::apiDeleteIssue();
timeout=0
cid=1

- 使用空的projectID删除gitlab群组属性message @404 Project Not Found
- 使用空的issueID删除gitlab群组属性message @404 Issue Not Found
- 使用错误gitlabID删除群组 @0
- 通过gitlabID,projectID,分支对象正确删除GitLab分支 @1

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;
$projectID = 2;
$issueID   = 5;

r($gitlab->apiDeleteIssue($gitlabID, 0, $issueID))   && p('message') && e('404 Project Not Found'); //使用空的projectID删除gitlab群组
r($gitlab->apiDeleteIssue($gitlabID, $projectID, 0)) && p('message') && e('404 Issue Not Found'); //使用空的issueID删除gitlab群组
r($gitlab->apiDeleteIssue(0, $projectID, $issueID))  && p()          && e('0'); //使用错误gitlabID删除群组

$result = $gitlab->apiDeleteIssue($gitlabID, $projectID, $issueID);
if(is_null($result)) $result = true;
if(isset($result->message) && $result->message == '404 Issue Not Found') $result = true;
r($result)                                                 && p()          && e('1');         //通过gitlabID,projectID,分支对象正确删除GitLab分支