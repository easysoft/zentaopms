#!/usr/bin/env php
<?php

/**

title=测试 executionZen::buildMembers();
timeout=0
cid=16416

- 步骤1：正常情况（2个当前成员+1个导入成员+2个部门用户+5个空成员） @10
- 步骤2：只有当前成员（2个当前成员+5个空成员） @7
- 步骤3：只有导入成员（1个导入成员+5个空成员） @6
- 步骤4：只有部门用户（2个部门用户+5个空成员） @7
- 步骤5：空参数情况（只有5个空成员） @5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$user = zenData('user');
$user->account->range('admin,test1,test2,user1,user2,user3');
$user->realname->range('管理员,测试1,测试2,用户1,用户2,用户3');
$user->role->range('admin,qa,dev,pm,po,td');
$user->deleted->range('0');
$user->gen(6);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$executionTest = new executionZenTest();

// 5. 准备测试数据
// 准备当前成员数据
$currentMembers = array();
$currentMember1 = new stdclass();
$currentMember1->account = 'admin';
$currentMember1->role = 'admin';
$currentMember1->days = 10;
$currentMember1->hours = 8;
$currentMembers['admin'] = $currentMember1;

$currentMember2 = new stdclass();
$currentMember2->account = 'test1';
$currentMember2->role = 'qa';
$currentMember2->days = 10;
$currentMember2->hours = 8;
$currentMembers['test1'] = $currentMember2;

// 准备导入成员数据
$members2Import = array();
$importMember1 = new stdclass();
$importMember1->account = 'test2';
$importMember1->role = 'dev';
$members2Import['test2'] = $importMember1;

// 准备部门用户数据
$deptUsers = array(
    'user1' => '用户1',
    'user2' => '用户2'
);

// 6. 🔴 强制要求：必须包含至少5个测试步骤
r(count($executionTest->buildMembersTest($currentMembers, $members2Import, $deptUsers, 15))) && p() && e('10'); // 步骤1：正常情况（2个当前成员+1个导入成员+2个部门用户+5个空成员）
r(count($executionTest->buildMembersTest($currentMembers, array(), array(), 10))) && p() && e('7'); // 步骤2：只有当前成员（2个当前成员+5个空成员）
r(count($executionTest->buildMembersTest(array(), $members2Import, array(), 20))) && p() && e('6'); // 步骤3：只有导入成员（1个导入成员+5个空成员）
r(count($executionTest->buildMembersTest(array(), array(), $deptUsers, 5))) && p() && e('7'); // 步骤4：只有部门用户（2个部门用户+5个空成员）
r(count($executionTest->buildMembersTest(array(), array(), array(), 30))) && p() && e('5'); // 步骤5：空参数情况（只有5个空成员）