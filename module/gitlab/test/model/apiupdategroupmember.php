#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiUpdateGroupMember();
timeout=0
cid=1

- 使用空的user_id更新gitlab群组 @return false
- 使用空的access_level更新gitlab群组 @return false
- 使用错误gitlabID更新群组 @0
- 通过gitlabID,groupID,分支对象正确更新GitLab群组成员属性access_level @30

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID = 1;
$groupID  = 14;

$groupMember = new stdclass();
$groupMember->user_id      = '';
$groupMember->access_level = '40';

$result = $gitlab->apiUpdateGroupMember($gitlabID, $groupID, $groupMember);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的user_id更新gitlab群组

$groupMember->user_id      = '4';
$groupMember->access_level = '';
$result = $gitlab->apiUpdateGroupMember($gitlabID, $groupID, $groupMember);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的access_level更新gitlab群组

r($gitlab->apiUpdateGroupMember(0, $groupID, $groupMember)) && p() && e('0'); //使用错误gitlabID更新群组

$groupMember->access_level = '40';
$gitlab->apiCreateGroupMember($gitlabID, $groupID, $groupMember);
$groupMember->access_level = '30';
r($gitlab->apiUpdateGroupMember($gitlabID, $groupID, $groupMember)) && p('access_level') && e('30');         //通过gitlabID,groupID,分支对象正确更新GitLab群组成员