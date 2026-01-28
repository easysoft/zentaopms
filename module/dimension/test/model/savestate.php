#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('dimension');
$table->id->range('1-10');
$table->name->range('维度{10}');
$table->code->range('dimension{10}');
$table->desc->range('测试维度描述{10}');
$table->createdBy->range('admin{10}');
$table->editedBy->range('admin{10}');
$table->deleted->range('0{8},1{2}');
$table->gen(10);

/**

title=测试 dimensionModel::saveState();
timeout=0
cid=16037

- 步骤1：正常传入有效维度ID @1
- 步骤2：传入0获取第一个维度 @1
- 步骤3：传入不存在的维度ID，返回第一个可见维度ID @1
- 步骤4：从session中获取维度 @3
- 步骤5：从config中获取维度 @5

*/

$dimensionTest = new dimensionModelTest();
r($dimensionTest->saveStateTest(1)) && p() && e('1'); // 步骤1：正常传入有效维度ID
r($dimensionTest->saveStateTest(0)) && p() && e('1'); // 步骤2：传入0获取第一个维度
r($dimensionTest->saveStateTest(999)) && p() && e('1'); // 步骤3：传入不存在的维度ID，返回第一个可见维度ID
r($dimensionTest->saveStateTest(0, '3')) && p() && e('3'); // 步骤4：从session中获取维度
r($dimensionTest->saveStateTest(0, '', '5')) && p() && e('5'); // 步骤5：从config中获取维度