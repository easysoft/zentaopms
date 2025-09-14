#!/usr/bin/env php
<?php

/**

title=测试 gitlabZen::getGroupMemberData();
timeout=0
cid=0

- 步骤1：正常情况，有1个新增、1个删除、1个更新 @1,1,1

- 步骤2：当前成员为空，有1个新增、0个删除、0个更新 @1,0,0

- 步骤3：当前成员不为空，新成员为空，有0个新增、2个删除、0个更新 @0,2,0

- 步骤4：当前成员和新成员完全相同，有0个新增、0个删除、0个更新 @0,0,0

- 步骤5：只有访问级别变更，有0个新增、0个删除、1个更新 @0,0,1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

su('admin');

$gitlabTest = new gitlabTest();

// 准备测试数据
$currentMember1 = new stdClass();
$currentMember1->id = 1;
$currentMember1->access_level = 30;
$currentMember1->expires_at = '2024-12-31';

$currentMember2 = new stdClass();
$currentMember2->id = 2;
$currentMember2->access_level = 40;
$currentMember2->expires_at = '2024-12-31';

$currentMembers = array($currentMember1, $currentMember2);

$newMember1 = new stdClass();
$newMember1->access_level = 50;
$newMember1->expires_at = '2025-12-31';

$newMember3 = new stdClass();
$newMember3->access_level = 20;
$newMember3->expires_at = '2024-06-30';

$newMembers1 = array(1 => $newMember1, 3 => $newMember3);
$newMembers2 = array(3 => $newMember3);
$newMembers3 = array();

$sameMember1 = new stdClass();
$sameMember1->access_level = 30;
$sameMember1->expires_at = '2024-12-31';

$sameMember2 = new stdClass();
$sameMember2->access_level = 40;
$sameMember2->expires_at = '2024-12-31';

$newMembers4 = array(1 => $sameMember1, 2 => $sameMember2);

$updatedMember1 = new stdClass();
$updatedMember1->access_level = 50;
$updatedMember1->expires_at = '2024-12-31';

$newMembers5 = array(1 => $updatedMember1, 2 => $sameMember2);

r($gitlabTest->getGroupMemberDataTest($currentMembers, $newMembers1)) && p() && e('1,1,1'); // 步骤1：正常情况，有1个新增、1个删除、1个更新
r($gitlabTest->getGroupMemberDataTest(array(), $newMembers2)) && p() && e('1,0,0'); // 步骤2：当前成员为空，有1个新增、0个删除、0个更新
r($gitlabTest->getGroupMemberDataTest($currentMembers, $newMembers3)) && p() && e('0,2,0'); // 步骤3：当前成员不为空，新成员为空，有0个新增、2个删除、0个更新
r($gitlabTest->getGroupMemberDataTest($currentMembers, $newMembers4)) && p() && e('0,0,0'); // 步骤4：当前成员和新成员完全相同，有0个新增、0个删除、0个更新
r($gitlabTest->getGroupMemberDataTest($currentMembers, $newMembers5)) && p() && e('0,0,1'); // 步骤5：只有访问级别变更，有0个新增、0个删除、1个更新