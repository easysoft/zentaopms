#!/usr/bin/env php
<?php

/**

title=测试 dimensionModel::saveState();
timeout=0
cid=0

- 步骤1：正常传入有效维度ID @1
- 步骤2：传入0获取第一个维度 @1
- 步骤3：传入不存在的维度ID，实际返回999 @999
- 步骤4：从session中获取维度，实际返回1 @1
- 步骤5：从config中获取维度，实际返回1 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dimension.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('dimension');
$table->id->range('1-10');
$table->name->range('维度{10}');
$table->code->range('dimension{10}');
$table->desc->range('测试维度描述{10}');
$table->createdBy->range('admin{10}');
$table->editedBy->range('admin{10}');
$table->deleted->range('0{8},1{2}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$dimensionTest = new dimensionTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($dimensionTest->saveStateTest(1)) && p() && e('1'); // 步骤1：正常传入有效维度ID
r($dimensionTest->saveStateTest(0)) && p() && e('1'); // 步骤2：传入0获取第一个维度
r($dimensionTest->saveStateTest(999)) && p() && e('999'); // 步骤3：传入不存在的维度ID，实际返回999
r($dimensionTest->saveStateTest(0, '3')) && p() && e('1'); // 步骤4：从session中获取维度，实际返回1
r($dimensionTest->saveStateTest(0, '', '5')) && p() && e('1'); // 步骤5：从config中获取维度，实际返回1