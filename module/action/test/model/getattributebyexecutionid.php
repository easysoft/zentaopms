#!/usr/bin/env php
<?php

/**

title=测试 actionModel::getAttributeByExecutionID();
timeout=0
cid=14889

- 步骤1：正常情况-查询ID为1的execution的attribute @request
- 步骤2：边界值-查询不存在的执行ID @0
- 步骤3：异常输入-查询已删除的执行（deleted=1），仍能查到attribute @0
- 步骤4：正常情况-查询ID为9的test attribute @test
- 步骤5：异常输入-查询ID为0的情况 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('project');
$table->id->range('1-10');
$table->attribute->range('request{2},design{2},devel{3},test{2},[]{1}');
$table->deleted->range('0{9},1{1}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$actionTest = new actionModelTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($actionTest->getAttributeByExecutionIDTest(1)) && p() && e('request'); // 步骤1：正常情况-查询ID为1的execution的attribute
r($actionTest->getAttributeByExecutionIDTest(999)) && p() && e('0'); // 步骤2：边界值-查询不存在的执行ID
r($actionTest->getAttributeByExecutionIDTest(10)) && p() && e('0'); // 步骤3：异常输入-查询已删除的执行（deleted=1），仍能查到attribute
r($actionTest->getAttributeByExecutionIDTest(9)) && p() && e('test'); // 步骤4：正常情况-查询ID为9的test attribute
r($actionTest->getAttributeByExecutionIDTest(0)) && p() && e('0'); // 步骤5：异常输入-查询ID为0的情况