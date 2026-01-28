#!/usr/bin/env php
<?php

/**

title=测试 transferModel::processRows4Fields();
timeout=0
cid=19329

- 执行transferTest模块的processRows4FieldsTest方法，参数是$normalRows, $normalFields 第2条的title属性 @测试需求1
- 执行transferTest模块的processRows4FieldsTest方法，参数是$emptyRows, $emptyFields 属性message @没有数据
- 执行transferTest模块的processRows4FieldsTest方法，参数是$titleOnlyRows, $titleOnlyFields 属性message @没有数据
- 执行transferTest模块的processRows4FieldsTest方法，参数是$mixedRows, $mixedFields  @2
- 执行transferTest模块的processRows4FieldsTest方法，参数是$unmatchedRows, $unmatchedFields 属性message @没有数据

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$transferTest = new transferModelTest();

// 测试步骤1：正常数据处理情况
$normalRows = array(
    1 => array('标题', '状态', '优先级'),
    2 => array('测试需求1', 'active', '3'),
    3 => array('测试需求2', 'closed', '2')
);
$normalFields = array(
    'title' => '标题',
    'status' => '状态',
    'pri' => '优先级'
);
r($transferTest->processRows4FieldsTest($normalRows, $normalFields)) && p('2:title') && e('测试需求1');

// 测试步骤2：空数据数组输入
$emptyRows = array();
$emptyFields = array();
r($transferTest->processRows4FieldsTest($emptyRows, $emptyFields)) && p('message') && e('没有数据');

// 测试步骤3：只有标题行无数据行
$titleOnlyRows = array(
    1 => array('标题', '状态', '优先级')
);
$titleOnlyFields = array(
    'title' => '标题',
    'status' => '状态',
    'pri' => '优先级'
);
r($transferTest->processRows4FieldsTest($titleOnlyRows, $titleOnlyFields)) && p('message') && e('没有数据');

// 测试步骤4：包含空值和有效数据的混合处理
$mixedRows = array(
    1 => array('标题', '状态', '优先级'),
    2 => array('有效需求', 'active', '3'),
    3 => array('', '', ''),
    4 => array('另一个需求', 'draft', '2')
);
$mixedFields = array(
    'title' => '标题',
    'status' => '状态',
    'pri' => '优先级'
);
r(count($transferTest->processRows4FieldsTest($mixedRows, $mixedFields))) && p() && e('2');

// 测试步骤5：无匹配字段的数据处理
$unmatchedRows = array(
    1 => array('未知字段1', '未知字段2'),
    2 => array('数据1', '数据2')
);
$unmatchedFields = array(
    'title' => '标题',
    'status' => '状态'
);
r($transferTest->processRows4FieldsTest($unmatchedRows, $unmatchedFields)) && p('message') && e('没有数据');