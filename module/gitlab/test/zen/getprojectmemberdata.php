#!/usr/bin/env php
<?php

/**

title=测试 gitlabZen::getProjectMemberData();
timeout=0
cid=0

- 步骤1：正常情况 @0,0,0

- 步骤2：新增成员 @1,0,0

- 步骤3：删除成员 @0,1,0

- 步骤4：更新成员权限 @0,0,1

- 步骤5：复合操作 @2,1,1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('repo');
$table->id->range('1-10');
$table->name->range('repo1,repo2,repo3,repo4,repo5');
$table->serviceHost->range('https://gitlab.example.com');
$table->serviceProject->range('1-5');
$table->gen(5);

$oauth = zenData('oauth');
$oauth->openID->range('101-110');
$oauth->providerID->range('1{10}');
$oauth->providerType->range('gitlab{10}');
$oauth->account->range('user1,user2,user3,user4,user5,user6,user7,user8,user9,user10');
$oauth->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$gitlabTest = new gitlabTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 测试步骤1：正常情况-无变化的成员数据
$currentMembers = array();
$member1 = new stdClass();
$member1->id = 101;
$member1->access_level = 30;
$member1->expires_at = '2024-12-31';
$currentMembers[] = $member1;

$member2 = new stdClass();
$member2->id = 102;
$member2->access_level = 40;
$member2->expires_at = '2024-12-31';
$currentMembers[] = $member2;

$newMembers = array();
$newMembers[101] = clone $member1;
$newMembers[102] = clone $member2;

$bindedUsers = array('user1' => 101, 'user2' => 102);
$accounts = array('user1', 'user2');
$originalUsers = array('user1', 'user2');

r($gitlabTest->getProjectMemberDataTest($currentMembers, $newMembers, $bindedUsers, $accounts, $originalUsers)) && p() && e('0,0,0'); // 步骤1：正常情况

// 测试步骤2：新增成员的情况
$newMembers2 = $newMembers;
$member3 = new stdClass();
$member3->id = 103;
$member3->access_level = 20;
$member3->expires_at = '2024-12-31';
$newMembers2[103] = $member3;

r($gitlabTest->getProjectMemberDataTest($currentMembers, $newMembers2, $bindedUsers, $accounts, $originalUsers)) && p() && e('1,0,0'); // 步骤2：新增成员

// 测试步骤3：删除成员的情况
$newMembers3 = array();
$newMembers3[101] = clone $member1; // 只保留member1，member2被删除

$bindedUsers3 = array('user1' => 101, 'user2' => 102);
$accounts3 = array('user1'); // user2不在账号列表中
$originalUsers3 = array('user1', 'user2'); // user2在原始用户中

r($gitlabTest->getProjectMemberDataTest($currentMembers, $newMembers3, $bindedUsers3, $accounts3, $originalUsers3)) && p() && e('0,1,0'); // 步骤3：删除成员

// 测试步骤4：更新成员权限的情况
$newMembers4 = array();
$memberUpdated = clone $member1;
$memberUpdated->access_level = 50; // 权限从30变为50
$newMembers4[101] = $memberUpdated;
$newMembers4[102] = clone $member2;

r($gitlabTest->getProjectMemberDataTest($currentMembers, $newMembers4, $bindedUsers, $accounts, $originalUsers)) && p() && e('0,0,1'); // 步骤4：更新成员权限

// 测试步骤5：复合操作-同时新增删除更新
$complexCurrentMembers = array();
$complexMember1 = new stdClass();
$complexMember1->id = 201;
$complexMember1->access_level = 30;
$complexMember1->expires_at = '2024-12-31';
$complexCurrentMembers[] = $complexMember1;

$complexMember2 = new stdClass();
$complexMember2->id = 202;
$complexMember2->access_level = 40;
$complexMember2->expires_at = '2024-12-31';
$complexCurrentMembers[] = $complexMember2;

$complexMember3 = new stdClass();
$complexMember3->id = 203;
$complexMember3->access_level = 20;
$complexMember3->expires_at = '2024-12-31';
$complexCurrentMembers[] = $complexMember3;

$complexNewMembers = array();
// 更新member1的权限
$updatedMember1 = clone $complexMember1;
$updatedMember1->access_level = 50;
$complexNewMembers[201] = $updatedMember1;

// 保留member2不变
$complexNewMembers[202] = clone $complexMember2;

// member3被删除（不在新成员中）

// 新增member4和member5
$newMember4 = new stdClass();
$newMember4->id = 204;
$newMember4->access_level = 30;
$newMember4->expires_at = '2024-12-31';
$complexNewMembers[204] = $newMember4;

$newMember5 = new stdClass();
$newMember5->id = 205;
$newMember5->access_level = 40;
$newMember5->expires_at = '2024-12-31';
$complexNewMembers[205] = $newMember5;

$complexBindedUsers = array('user1' => 201, 'user2' => 202, 'user3' => 203);
$complexAccounts = array('user1', 'user2'); // user3不在账号中，应该被删除
$complexOriginalUsers = array('user1', 'user2', 'user3');

r($gitlabTest->getProjectMemberDataTest($complexCurrentMembers, $complexNewMembers, $complexBindedUsers, $complexAccounts, $complexOriginalUsers)) && p() && e('2,1,1'); // 步骤5：复合操作