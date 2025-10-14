#!/usr/bin/env php
<?php

/**

title=测试 projectZen::buildUsers();
timeout=0
cid=0

- 步骤1：返回数组长度为2 @2
- 步骤2：userPairs数组包含5个用户 @5
- 步骤3：验证admin的真实姓名 @管理员
- 步骤4：验证user1的账号 @user1
- 步骤5：验证user2的真实姓名 @用户2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('user');
$table->account->range('admin,user1,user2,user3,user4');
$table->realname->range('管理员,用户1,用户2,用户3,用户4');
$table->password->range('123456{5}');
$table->role->range('admin{1},dev{2},qa{1},pm{1}');
$table->deleted->range('0{5}');
$table->type->range('inside{5}');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectTest = new projectTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$result1 = $projectTest->buildUsersTest();
$result2 = $projectTest->buildUsersTest();
$result3 = $projectTest->buildUsersTest();
$result4 = $projectTest->buildUsersTest();
$result5 = $projectTest->buildUsersTest();

r(count($result1)) && p() && e('2'); // 步骤1：返回数组长度为2
r(count($result2[0])) && p() && e('5'); // 步骤2：userPairs数组包含5个用户
r(isset($result3[0]['admin']) ? $result3[0]['admin'] : false) && p() && e('管理员'); // 步骤3：验证admin的真实姓名
r(isset($result4[1]['user1']) ? $result4[1]['user1']->account : false) && p() && e('user1'); // 步骤4：验证user1的账号
r(isset($result5[1]['user2']) ? $result5[1]['user2']->realname : false) && p() && e('用户2'); // 步骤5：验证user2的真实姓名