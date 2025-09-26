#!/usr/bin/env php
<?php

/**

title=测试 biModel::prepareColumns();
timeout=0
cid=0

- 步骤1：正常SQL查询返回包含columns和relatedObjects的数组 @1
- 步骤2：检查返回的columns数组包含id字段的name属性 @1
- 步骤3：包含聚合函数的查询检查字段结构 @1
- 步骤4：检查account字段包含type属性 @1
- 步骤5：处理包含别名的字段查询检查别名字段 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$user = zendata('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,user3,user4');
$user->realname->range('管理员,用户1,用户2,用户3,用户4');
$user->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$biTest = new biTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$sql1 = 'SELECT id, account FROM zt_user WHERE id <= 3';
$statement1 = $biTest->objectModel->sql2Statement($sql1);
$result1 = $biTest->prepareColumnsTest($sql1, $statement1, 'mysql');
r(is_array($result1) && count($result1) == 2) && p() && e('1'); // 步骤1：正常SQL查询返回包含columns和relatedObjects的数组

$sql2 = 'SELECT u.id, u.account FROM zt_user u WHERE u.id <= 3';
$statement2 = $biTest->objectModel->sql2Statement($sql2);
$result2 = $biTest->prepareColumnsTest($sql2, $statement2, 'mysql');
r(isset($result2[0]['id']['name'])) && p() && e('1'); // 步骤2：检查返回的columns数组包含id字段的name属性

$sql3 = 'SELECT COUNT(id) as total_users FROM zt_user';
$statement3 = $biTest->objectModel->sql2Statement($sql3);
$result3 = $biTest->prepareColumnsTest($sql3, $statement3, 'mysql');
r(isset($result3[0]['total_users']['field'])) && p() && e('1'); // 步骤3：包含聚合函数的查询检查字段结构

$sql4 = 'SELECT id, account FROM zt_user WHERE id = 1';
$statement4 = $biTest->objectModel->sql2Statement($sql4);
$result4 = $biTest->prepareColumnsTest($sql4, $statement4, 'mysql');
r(isset($result4[0]['account']['type'])) && p() && e('1'); // 步骤4：检查account字段包含type属性

$sql5 = 'SELECT account AS user_account FROM zt_user WHERE id <= 3';
$statement5 = $biTest->objectModel->sql2Statement($sql5);
$result5 = $biTest->prepareColumnsTest($sql5, $statement5, 'mysql');
r(isset($result5[0]['user_account']['name'])) && p() && e('1'); // 步骤5：处理包含别名的字段查询检查别名字段