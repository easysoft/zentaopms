#!/usr/bin/env php
<?php

/**

title=测试 productZen::setSelectFormOptions();
timeout=0
cid=0

步骤1：正常情况带项目集ID的字段处理 >> name
步骤2：空项目集ID的字段处理 >> name
步骤3：包含用户选项的字段处理 >> QD
步骤4：包含分组选项的字段处理 >> groups
步骤5：包含产品线选项的字段处理 >> line

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 简化数据准备，避免数据库字段不匹配的问题

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($productTest->setSelectFormOptionsTest(1, array(
    'name' => array('type' => 'string', 'control' => 'input'),
    'PO' => array('type' => 'string', 'control' => 'picker', 'options' => 'users')
))) && p('name:name') && e('name'); // 步骤1：正常情况带项目集ID的字段处理

r($productTest->setSelectFormOptionsTest(0, array(
    'name' => array('type' => 'string', 'control' => 'input'),
    'code' => array('type' => 'string', 'control' => 'input')
))) && p('name:name') && e('name'); // 步骤2：空项目集ID的字段处理

r($productTest->setSelectFormOptionsTest(1, array(
    'QD' => array('type' => 'string', 'control' => 'picker', 'options' => 'users'),
    'RD' => array('type' => 'string', 'control' => 'picker', 'options' => 'users')
))) && p('QD:name') && e('QD'); // 步骤3：包含用户选项的字段处理

r($productTest->setSelectFormOptionsTest(1, array(
    'groups' => array('type' => 'array', 'control' => 'picker'),
    'status' => array('type' => 'string', 'control' => 'radio')
))) && p('groups:name') && e('groups'); // 步骤4：包含分组选项的字段处理

r($productTest->setSelectFormOptionsTest(1, array(
    'line' => array('type' => 'int', 'control' => 'picker'),
    'program' => array('type' => 'int', 'control' => 'picker')
))) && p('line:name') && e('line'); // 步骤5：包含产品线选项的字段处理