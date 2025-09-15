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

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 4. 测试数据准备
$records = array(
    (object)array('id' => 1, 'name' => 'test1', 'status' => 'active'),
    (object)array('id' => 2, 'name' => 'test2', 'status' => 'inactive')
);

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($pivotTest->getFieldsOptionsTest(array(), $records)) && p() && e(0); // 步骤1：空字段设置

r($pivotTest->getFieldsOptionsTest(array(
    'field1' => array('type' => 'option', 'object' => 'user', 'field' => 'status')
), $records)) && p() && e(1); // 步骤2：单个option类型字段

r($pivotTest->getFieldsOptionsTest(array(
    'field2' => array('type' => 'object', 'object' => 'project', 'field' => 'id')
), $records)) && p() && e(1); // 步骤3：单个object类型字段

r($pivotTest->getFieldsOptionsTest(array(
    'field1' => array('type' => 'option', 'object' => 'user', 'field' => 'status'),
    'field2' => array('type' => 'object', 'object' => 'project', 'field' => 'id')
), $records)) && p() && e(2); // 步骤4：多个字段混合类型

r($pivotTest->getFieldsOptionsTest(array(
    'validField' => array('type' => 'option', 'object' => 'user', 'field' => 'status'),
    'invalidField' => array('type' => 'invalid', 'object' => '', 'field' => '')
), $records)) && p() && e(2); // 步骤5：包含无效字段的情况