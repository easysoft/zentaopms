#!/usr/bin/env php
<?php

/**

title=测试 biModel::prepareColumns();
timeout=0
cid=15201

- 测试简单SELECT语句,验证返回结果包含两个元素 @2
- 测试简单SELECT语句,验证第一个元素columns包含id字段第id条的name属性 @id
- 测试简单SELECT语句,验证第一个元素columns包含account字段第account条的name属性 @account
- 测试简单SELECT语句,验证第二个元素relatedObjects包含id字段属性id @user
- 测试多字段SELECT语句,验证columns字段数量 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

// zendata数据准备
$table = zenData('user');
$table->id->range('1-10');
$table->account->range('admin,user1,user2,user3,test{1},qa{1},dev{1},pm{1},po{1},td{1}');
$table->realname->range('管理员,用户1,用户2,用户3,测试{1},QA{1},开发{1},项目经理{1},产品经理{1},测试主管{1}');
$table->role->range('admin,dev{3},qa{3},pm{2},po{1}');
$table->gen(10);

su('admin');
$biTest = new biTest();

// 测试用例1：简单SELECT语句
$sql1 = "SELECT id, account FROM zt_user LIMIT 1";
$statement1 = $biTest->objectModel->sql2Statement($sql1);

// 测试用例2：多字段SELECT语句
$sql2 = "SELECT id, account, realname FROM zt_user LIMIT 1";
$statement2 = $biTest->objectModel->sql2Statement($sql2);

r(count($biTest->prepareColumnsTest($sql1, $statement1, 'mysql'))) && p() && e('2');               // 测试简单SELECT语句,验证返回结果包含两个元素
r($biTest->prepareColumnsTest($sql1, $statement1, 'mysql')[0]) && p('id:name') && e('id');        // 测试简单SELECT语句,验证第一个元素columns包含id字段
r($biTest->prepareColumnsTest($sql1, $statement1, 'mysql')[0]) && p('account:name') && e('account'); // 测试简单SELECT语句,验证第一个元素columns包含account字段
r($biTest->prepareColumnsTest($sql1, $statement1, 'mysql')[1]) && p('id') && e('user');           // 测试简单SELECT语句,验证第二个元素relatedObjects包含id字段
r(count($biTest->prepareColumnsTest($sql2, $statement2, 'mysql')[0])) && p() && e('3');           // 测试多字段SELECT语句,验证columns字段数量