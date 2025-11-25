#!/usr/bin/env php
<?php

/**

title=测试 biModel::getSqlTypeAndFields();
timeout=0
cid=15182

- 步骤1：正常SQL语句解析，测试第一个元素id字段类型第0条的id属性 @number
- 步骤2：正常SQL语句解析，测试第一个元素account字段类型第0条的account属性 @string
- 步骤3：正常SQL语句解析，测试第二个元素id字段映射第1条的id属性 @id
- 步骤4：正常SQL语句解析，测试第二个元素account字段映射第1条的account属性 @account
- 步骤5：测试方法返回结果包含两个元素 @2

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

r($biTest->getSqlTypeAndFieldsTest('SELECT id, account FROM zt_user LIMIT 1', 'mysql')) && p('0:id') && e('number');    // 步骤1：正常SQL语句解析，测试第一个元素id字段类型
r($biTest->getSqlTypeAndFieldsTest('SELECT id, account FROM zt_user LIMIT 1', 'mysql')) && p('0:account') && e('string'); // 步骤2：正常SQL语句解析，测试第一个元素account字段类型
r($biTest->getSqlTypeAndFieldsTest('SELECT id, account FROM zt_user LIMIT 1', 'mysql')) && p('1:id') && e('id');        // 步骤3：正常SQL语句解析，测试第二个元素id字段映射
r($biTest->getSqlTypeAndFieldsTest('SELECT id, account FROM zt_user LIMIT 1', 'mysql')) && p('1:account') && e('account'); // 步骤4：正常SQL语句解析，测试第二个元素account字段映射
r(count($biTest->getSqlTypeAndFieldsTest('SELECT id, account FROM zt_user LIMIT 1', 'mysql'))) && p() && e('2');         // 步骤5：测试方法返回结果包含两个元素