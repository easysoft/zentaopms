#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getFieldsOptions();
timeout=0
cid=0

- 步骤1：空字段设置 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. zendata数据准备（简化数据准备，避免复杂数据库依赖）
$table = zenData('user');
$table->account->range('admin,user1,user2');
$table->realname->range('管理员,用户1,用户2');
$table->role->range('admin,dev,qa');
$table->dept->range('1-3');
$table->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 5. 测试数据准备
$records = array(
    (object)array('id' => 1, 'name' => 'test1', 'status' => 'active', 'type' => 'user'),
    (object)array('id' => 2, 'name' => 'test2', 'status' => 'inactive', 'type' => 'admin'),
    (object)array('id' => 3, 'name' => 'test3', 'status' => 'active', 'type' => 'qa')
);

// 6. 🔴 强制要求：必须包含至少5个测试步骤
r($pivotTest->getFieldsOptionsTest(array(), $records)) && p() && e(0); // 步骤1：空字段设置

r($pivotTest->getFieldsOptionsTest(array(
    'role_field' => array('type' => 'option', 'object' => 'user', 'field' => 'role')
), $records)) && p() && e(1); // 步骤2：单个option类型字段

r($pivotTest->getFieldsOptionsTest(array(
    'user_field' => array('type' => 'object', 'object' => 'user', 'field' => 'id')
), $records)) && p() && e(1); // 步骤3：单个object类型字段

r($pivotTest->getFieldsOptionsTest(array(
    'role_field' => array('type' => 'option', 'object' => 'user', 'field' => 'role'),
    'account_field' => array('type' => 'option', 'object' => 'user', 'field' => 'account')
), $records)) && p() && e(2); // 步骤4：多个字段混合类型

r($pivotTest->getFieldsOptionsTest(array(
    'valid_field' => array('type' => 'option', 'object' => 'user', 'field' => 'role'),
    'invalid_field' => array('type' => 'invalid', 'object' => '', 'field' => '')
), $records)) && p() && e(2); // 步骤5：包含无效字段配置

r($pivotTest->getFieldsOptionsTest(array(
    'role_field' => array('type' => 'option', 'object' => 'user', 'field' => 'role')
), $records, 'sqlite')) && p() && e(1); // 步骤6：sqlite数据库驱动

r($pivotTest->getFieldsOptionsTest(array(
    'incomplete_field' => array('type' => '', 'object' => '', 'field' => '')
), $records)) && p() && e(1); // 步骤7：字段配置参数不完整