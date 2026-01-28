#!/usr/bin/env php
<?php

/**

title=测试 dimensionModel::getDimension();
timeout=0
cid=16034

- 步骤1：传入有效维度ID @1
- 步骤2：传入0，获取默认维度ID @1
- 步骤3：传入不存在的维度ID @1
- 步骤4：传入负数维度ID @1
- 步骤5：传入另一个有效维度ID @5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
$table = zenData('dimension');
$table->id->range('1-5');
$table->name->range('维度1,维度2,维度3,维度4,维度5');
$table->code->range('dim1,dim2,dim3,dim4,dim5');
$table->desc->range('描述1,描述2,描述3,描述4,描述5');
$table->createdBy->range('admin{5}');
$table->createdDate->range('`2023-01-01 10:00:00`,`2023-01-02 10:00:00`,`2023-01-03 10:00:00`,`2023-01-04 10:00:00`,`2023-01-05 10:00:00`');
$table->deleted->range('0{5}');
$table->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$dimensionTest = new dimensionModelTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($dimensionTest->getDimensionTest(1, '1,2,3,4,5')) && p() && e(1); // 步骤1：传入有效维度ID
r($dimensionTest->getDimensionTest(0, '1,2,3,4,5')) && p() && e(1); // 步骤2：传入0，获取默认维度ID
r($dimensionTest->getDimensionTest(999, '1,2,3,4,5')) && p() && e(1); // 步骤3：传入不存在的维度ID
r($dimensionTest->getDimensionTest(-1, '1,2,3,4,5')) && p() && e(1); // 步骤4：传入负数维度ID
r($dimensionTest->getDimensionTest(5, '1,2,3,4,5')) && p() && e(5); // 步骤5：传入另一个有效维度ID