#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiCreateGroupMember();
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

$gitlabID = 1;
$groupID  = 14;

$groupMember = new stdclass();
$groupMember->user_id      = '';
$groupMember->access_level = '40';

$result = $gitlab->apiCreateGroupMember($gitlabID, $groupID, $groupMember);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的user_id创建gitlab群组

$groupMember->user_id      = '4';
$groupMember->access_level = '';
$result = $gitlab->apiCreateGroupMember($gitlabID, $groupID, $groupMember);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的access_level创建gitlab群组

r($gitlab->apiCreateGroupMember(0, $groupID, $groupMember)) && p() && e('0'); //使用错误gitlabID创建群组

$groupMember->access_level = '40';
$result = $gitlab->apiCreateGroupMember($gitlabID, $groupID, $groupMember);
if(!empty($result->access_level) and $result->access_level == $groupMember->access_level) $result = true;
if(!empty($result->message) and $result->message == 'Member already exists') $result = true;
r($result) && p() && e('1');         //通过gitlabID,projectID,分支对象正确创建GitLab分支

r( $gitlab->apiCreateGroupMember($gitlabID, $groupID, $groupMember)) && p('message') && e('Member already exists'); //使用重复的分支信息创建分支