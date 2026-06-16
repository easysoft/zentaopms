#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

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

$gitlab = new gitlabModelTest();

$gitlabID = 1;
$groupID  = 14;

/* 测试步骤1：使用空的user_id参数更新gitlab群组成员 */
$groupMember = new stdclass();
$groupMember->user_id      = '';
$groupMember->access_level = '40';

$result = $gitlab->apiUpdateGroupMemberTest($gitlabID, $groupID, $groupMember);
if($result === false) $result = 'return false';
r($result) && p() && e('return false');

/* 测试步骤2：使用空的access_level参数更新gitlab群组成员 */
$groupMember = new stdclass();
$groupMember->user_id      = '4';
$groupMember->access_level = '';

$result = $gitlab->apiUpdateGroupMemberTest($gitlabID, $groupID, $groupMember);
if($result === false) $result = 'return false';
r($result) && p() && e('return false');

/* 测试步骤3：使用无效的gitlabID参数更新群组成员 */
$groupMember = new stdclass();
$groupMember->user_id      = '4';
$groupMember->access_level = '30';
$result = $gitlab->apiUpdateGroupMemberTest(0, $groupID, $groupMember);
if($result === false) $result = '0';
r($result) && p() && e('0');

/* 测试步骤4：使用字符串类型的user_id参数更新群组成员 */
$groupMember = new stdclass();
$groupMember->user_id      = 'invalid_user';
$groupMember->access_level = '30';

$result = $gitlab->apiUpdateGroupMemberTest($gitlabID, $groupID, $groupMember);
if($result === false) $result = 'return false';
r($result) && p() && e('return false');

/* 测试步骤5：使用无效的access_level值更新群组成员 */
$groupMember = new stdclass();
$groupMember->user_id      = '4';
$groupMember->access_level = '999';
$result = $gitlab->apiUpdateGroupMemberTest($gitlabID, $groupID, $groupMember);
if($result === false) $result = '0';
r($result) && p() && e('0');

/* 测试步骤6：使用正确参数更新GitLab群组成员权限级别 */
$groupMember = new stdclass();
$groupMember->user_id      = '4';
$groupMember->access_level = '40';
$groupMember->access_level = '30';
r($gitlab->apiUpdateGroupMemberTest($gitlabID, $groupID, $groupMember)) && p('access_level') && e('30');
