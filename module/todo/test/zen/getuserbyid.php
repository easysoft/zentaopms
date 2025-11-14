#!/usr/bin/env php
<?php

/**

title=测试 todoZen::getUserById();
timeout=0
cid=19302

- 步骤1：正常用户查询属性account @admin
- 步骤2：边界值ID查询（ID为0） @0
- 步骤3：负数ID查询 @0
- 步骤4：不存在用户ID查询 @0
- 步骤5：超大ID查询 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$userTable = zenData('user');
$userTable->id->range('1-10');
$userTable->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$userTable->password->range('123456{10}');
$userTable->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$userTable->role->range('admin,dev,qa,pm,td,po,rd,ue,ui,user');
$userTable->email->range('admin@test.com,user1@test.com,user2@test.com,user3@test.com,user4@test.com,user5@test.com,user6@test.com,user7@test.com,user8@test.com,user9@test.com');
$userTable->deleted->range('0{9},1');
$userTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$todoTest = new todoTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($todoTest->getUserByIdTest(1)) && p('account') && e('admin'); // 步骤1：正常用户查询
r($todoTest->getUserByIdTest(0)) && p() && e('0'); // 步骤2：边界值ID查询（ID为0）
r($todoTest->getUserByIdTest(-1)) && p() && e('0'); // 步骤3：负数ID查询
r($todoTest->getUserByIdTest(999)) && p() && e('0'); // 步骤4：不存在用户ID查询
r($todoTest->getUserByIdTest(9999)) && p() && e('0'); // 步骤5：超大ID查询