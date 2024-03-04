#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiUpdateProjectMember();
timeout=0
cid=1

- 使用空的user_id更新gitlab群组 @return false
- 使用空的access_level更新gitlab群组 @return false
- 使用错误gitlabID更新群组 @0
- 通过gitlabID,projectID,分支对象正确更新GitLab分支属性access_level @30

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;
$projectID = 4;

$projectMember = new stdclass();
$projectMember->user_id      = '';
$projectMember->access_level = '40';

$result = $gitlab->apiUpdateProjectMember($gitlabID, $projectID, $projectMember);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的user_id更新gitlab群组

$projectMember->user_id      = '4';
$projectMember->access_level = '';
$result = $gitlab->apiUpdateProjectMember($gitlabID, $projectID, $projectMember);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的access_level更新gitlab群组

r($gitlab->apiUpdateProjectMember(0, $projectID, $projectMember)) && p() && e('0'); //使用错误gitlabID更新群组

$projectMember->access_level = '40';
$gitlab->apiCreateProjectMember($gitlabID, $projectID, $projectMember);
$projectMember->access_level = '30';
r($gitlab->apiUpdateProjectMember($gitlabID, $projectID, $projectMember)) && p('access_level') && e('30');         //通过gitlabID,projectID,分支对象正确更新GitLab分支