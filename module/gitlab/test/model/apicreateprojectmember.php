#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiCreateProjectMember();
timeout=0
cid=1

- 使用空的user_id创建gitlab群组 @return false
- 使用空的access_level创建gitlab群组 @return false
- 使用错误gitlabID创建群组 @0
- 通过gitlabID,projectID,分支对象正确创建GitLab分支 @1
- 使用重复的分支信息创建分支属性message @Member already exists

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;
$projectID = 4;

$projectMember = new stdclass();
$projectMember->user_id      = '';
$projectMember->access_level = '40';

$result = $gitlab->apiCreateProjectMember($gitlabID, $projectID, $projectMember);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的user_id创建gitlab群组

$projectMember->user_id      = '4';
$projectMember->access_level = '';
$result = $gitlab->apiCreateProjectMember($gitlabID, $projectID, $projectMember);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的access_level创建gitlab群组

r($gitlab->apiCreateProjectMember(0, $projectID, $projectMember)) && p() && e('0'); //使用错误gitlabID创建群组

$projectMember->access_level = '40';
$result = $gitlab->apiCreateProjectMember($gitlabID, $projectID, $projectMember);
if(!empty($result->access_level) and $result->access_level == $projectMember->access_level) $result = true;
if(!empty($result->message) and $result->message == 'Member already exists') $result = true;
r($result) && p() && e('1');         //通过gitlabID,projectID,分支对象正确创建GitLab分支

r( $gitlab->apiCreateProjectMember($gitlabID, $projectID, $projectMember)) && p('message') && e('Member already exists'); //使用重复的分支信息创建分支