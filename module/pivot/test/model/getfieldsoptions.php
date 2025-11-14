#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getFieldsOptions();
timeout=0
cid=17385

- 步骤1：空字段设置 @0
- 步骤2：单个option类型字段 @1
- 步骤3：单个object类型字段第user_field条的4属性 @4
- 步骤4：多个字段混合类型 @3
- 步骤5：包含无效字段配置 @2
- 步骤6：不同数据库驱动 @1
- 步骤7：字段配置参数不完整 @1
- 步骤8：空记录数据 @1
- 步骤9：单条记录数据第user_field条的1属性 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. zendata数据准备
$userTable = zenData('user');
$userTable->account->range('admin,user1,user2,tester');
$userTable->realname->range('管理员,用户1,用户2,测试员');
$userTable->role->range('admin,dev,qa,tester');
$userTable->dept->range('1-4');
$userTable->deleted->range('0{3},1{1}');
$userTable->gen(4);

$productTable = zenData('product');
$productTable->name->range('产品1,产品2,产品3');
$productTable->status->range('normal{2},closed{1}');
$productTable->type->range('normal,branch,platform');
$productTable->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 5. 测试数据准备
$testRecords = array(
    (object)array('id' => 1, 'account' => 'admin', 'deleted' => '0', 'role' => 'admin', 'dept' => 1),
    (object)array('id' => 2, 'account' => 'user1', 'deleted' => '0', 'role' => 'dev', 'dept' => 2),
    (object)array('id' => 3, 'account' => 'user2', 'deleted' => '1', 'role' => 'qa', 'dept' => 3),
    (object)array('id' => 4, 'account' => 'tester', 'deleted' => '0', 'role' => 'tester', 'dept' => 4)
);

$emptyRecords = array();
$singleRecord = array((object)array('id' => 1, 'account' => 'test', 'deleted' => '0'));

// 不同类型的字段配置
$emptyFieldSettings = array();
$optionFieldSettings = array(
    'role_field' => array('type' => 'option', 'object' => 'user', 'field' => 'role')
);
$objectFieldSettings = array(
    'user_field' => array('type' => 'object', 'object' => 'user', 'field' => 'id')
);
$multipleFieldSettings = array(
    'role_field' => array('type' => 'option', 'object' => 'user', 'field' => 'role'),
    'deleted_field' => array('type' => 'option', 'object' => 'user', 'field' => 'deleted'),
    'user_field' => array('type' => 'object', 'object' => 'user', 'field' => 'id')
);
$invalidFieldSettings = array(
    'valid_field' => array('type' => 'option', 'object' => 'user', 'field' => 'role'),
    'invalid_field' => array('type' => 'invalid_type', 'object' => 'nonexistent', 'field' => 'fake_field')
);
$incompleteFieldSettings = array(
    'incomplete_field' => array('type' => '', 'object' => '', 'field' => '')
);

// 6. 测试步骤执行 - 必须包含至少5个测试步骤
r($pivotTest->getFieldsOptionsCountTest($emptyFieldSettings, $testRecords)) && p() && e('0'); // 步骤1：空字段设置

r($pivotTest->getFieldsOptionsCountTest($optionFieldSettings, $testRecords)) && p() && e('1'); // 步骤2：单个option类型字段

r($pivotTest->getFieldsOptionsTest($objectFieldSettings, $testRecords)) && p('user_field:4') && e('4'); // 步骤3：单个object类型字段

r($pivotTest->getFieldsOptionsCountTest($multipleFieldSettings, $testRecords)) && p() && e('3'); // 步骤4：多个字段混合类型

r($pivotTest->getFieldsOptionsCountTest($invalidFieldSettings, $testRecords)) && p() && e('2'); // 步骤5：包含无效字段配置

r($pivotTest->getFieldsOptionsCountTest($optionFieldSettings, $testRecords, 'sqlite')) && p() && e('1'); // 步骤6：不同数据库驱动

r($pivotTest->getFieldsOptionsCountTest($incompleteFieldSettings, $testRecords)) && p() && e('1'); // 步骤7：字段配置参数不完整

r($pivotTest->getFieldsOptionsCountTest($optionFieldSettings, $emptyRecords)) && p() && e('1'); // 步骤8：空记录数据

r($pivotTest->getFieldsOptionsTest($multipleFieldSettings, $singleRecord)) && p('user_field:1') && e('1'); // 步骤9：单条记录数据