#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiUpdateGroupMember();
timeout=0
cid=16629

- 执行$result @return false
- 执行$result @return false
- 执行gitlab模块的apiUpdateGroupMember方法，参数是0, $groupID, $groupMember  @0
- 执行$result @return false
- 执行gitlab模块的apiUpdateGroupMember方法，参数是$gitlabID, $groupID, $groupMember  @0
- 执行gitlab模块的apiUpdateGroupMember方法，参数是$gitlabID, $groupID, $groupMember 属性access_level @30

*/

zenData('pipeline')->gen(5);

global $app;
$app->rawModule = 'gitlab';
$app->rawMethod = 'browse';

$gitlab = $tester->loadModel('gitlab');

$gitlabID = 1;
$groupID  = 14;

/* 测试步骤1：使用空的user_id参数更新gitlab群组成员 */
$groupMember = new stdclass();
$groupMember->user_id      = '';
$groupMember->access_level = '40';

$result = $gitlab->apiUpdateGroupMember($gitlabID, $groupID, $groupMember);
if($result === false) $result = 'return false';
r($result) && p() && e('return false');

/* 测试步骤2：使用空的access_level参数更新gitlab群组成员 */
$groupMember = new stdclass();
$groupMember->user_id      = '4';
$groupMember->access_level = '';

$result = $gitlab->apiUpdateGroupMember($gitlabID, $groupID, $groupMember);
if($result === false) $result = 'return false';
r($result) && p() && e('return false');

/* 测试步骤3：使用无效的gitlabID参数更新群组成员 */
$groupMember = new stdclass();
$groupMember->user_id      = '4';
$groupMember->access_level = '30';
r($gitlab->apiUpdateGroupMember(0, $groupID, $groupMember)) && p() && e('0');

/* 测试步骤4：使用字符串类型的user_id参数更新群组成员 */
$groupMember = new stdclass();
$groupMember->user_id      = 'invalid_user';
$groupMember->access_level = '30';

$result = $gitlab->apiUpdateGroupMember($gitlabID, $groupID, $groupMember);
if($result === false) $result = 'return false';
r($result) && p() && e('return false');

/* 测试步骤5：使用无效的access_level值更新群组成员 */
$groupMember = new stdclass();
$groupMember->user_id      = '4';
$groupMember->access_level = '999';
r($gitlab->apiUpdateGroupMember($gitlabID, $groupID, $groupMember)) && p() && e('0');

/* 测试步骤6：使用正确参数更新GitLab群组成员权限级别 */
/* 首先确保成员存在，然后更新权限级别 */
$groupMember = new stdclass();
$groupMember->user_id      = '4';
$groupMember->access_level = '40';
$gitlab->apiCreateGroupMember($gitlabID, $groupID, $groupMember);

$groupMember->access_level = '30';
r($gitlab->apiUpdateGroupMember($gitlabID, $groupID, $groupMember)) && p('access_level') && e('30');