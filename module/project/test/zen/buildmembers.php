#!/usr/bin/env php
<?php

/**

title=测试 projectZen::buildMembers();
timeout=0
cid=0

- 执行$result1 @10
- 执行$result2 @5
- 执行$result3 @6
- 执行$result4 @7
- 执行$result5['user1']->memberType @default

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('user');
$table->account->range('admin,user1,user2,dept1,dept2,import1,import2');
$table->realname->range('管理员,用户1,用户2,部门用户1,部门用户2,导入用户1,导入用户2');
$table->role->range('admin,dev,tester,qa,pm,dev,tester');
$table->gen(7);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectTest = new projectTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 步骤1：正常情况 - 包含所有类型成员，验证数组总数（2当前+2部门+1导入+5新增=10）
$currentMembers = array(
    'admin' => (object)array('account' => 'admin', 'role' => 'admin', 'days' => 10, 'hours' => 8, 'limited' => 'no'),
    'user1' => (object)array('account' => 'user1', 'role' => 'dev', 'days' => 10, 'hours' => 8, 'limited' => 'no')
);
$members2Import = array(
    'import1' => (object)array('account' => 'import1', 'role' => 'tester', 'hours' => 8)
);
$deptUsers = array('dept1' => '部门用户1', 'dept2' => '部门用户2');
$result1 = $projectTest->buildMembersTest($currentMembers, $members2Import, $deptUsers, 10);
r(count($result1)) && p('') && e('10');

// 步骤2：空参数情况，验证只有5个新增成员
$result2 = $projectTest->buildMembersTest(array(), array(), array(), 5);
r(count($result2)) && p('') && e('5');

// 步骤3：只有当前成员，验证总数为6（1个当前+5个新增）
$currentMembers = array(
    'admin' => (object)array('account' => 'admin', 'role' => 'admin', 'days' => 5, 'hours' => 8, 'limited' => 'no')
);
$result3 = $projectTest->buildMembersTest($currentMembers, array(), array(), 5);
r(count($result3)) && p('') && e('6');

// 步骤4：只有部门用户，验证总数为7（2个部门+5个新增）
$deptUsers = array('dept1' => '部门用户1', 'dept2' => '部门用户2');
$result4 = $projectTest->buildMembersTest(array(), array(), $deptUsers, 8);
r(count($result4)) && p('') && e('7');

// 步骤5：验证成员类型标识正确设置
$currentMembers = array('user1' => (object)array('account' => 'user1', 'role' => 'dev'));
$members2Import = array('import1' => (object)array('account' => 'import1', 'role' => 'tester'));
$deptUsers = array('dept1' => '部门用户1');
$result5 = $projectTest->buildMembersTest($currentMembers, $members2Import, $deptUsers, 15);
r($result5['user1']->memberType) && p('') && e('default');