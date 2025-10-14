#!/usr/bin/env php
<?php

/**

title=测试 metricModel::updateMetricFields();
timeout=0
cid=0

- 步骤1：正常更新度量项 @
- 步骤2：边界值ID更新 @
- 步骤3：不存在ID更新 @
- 步骤4：空数据对象更新 @
- 步骤5：验证更新后数据属性name @数据一致性验证

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

// 2. zendata数据准备 - 简化版本
$table = zenData('metric');
$table->id->range('1-5');
$table->name->range('原始度量项{1-5}');
$table->code->range('original_code_{1-5}');
$table->purpose->range('QC,scale,rate');
$table->scope->range('system,product,project');
$table->object->range('project,product,execution');
$table->stage->range('wait,released');
$table->type->range('php,sql');
$table->unit->range('个,人,天');
$table->createdBy->range('admin,user1,user2');
$table->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$metricTest = new metricTest();

// 5. 测试步骤：必须包含至少5个测试步骤

// 步骤1：正常更新测试 - 更新存在的度量项
$updateData1 = new stdClass();
$updateData1->name = '更新后的度量项名称';
$updateData1->alias = 'updated_metric';
$updateData1->desc = '这是更新后的描述信息';
$updateData1->unit = '次';
r($metricTest->updateMetricFieldsTest('1', $updateData1)) && p() && e(''); // 步骤1：正常更新度量项

// 步骤2：边界值ID测试 - 使用最小ID值
$updateData2 = new stdClass();
$updateData2->name = '边界值测试';
$updateData2->stage = 'released';
r($metricTest->updateMetricFieldsTest('1', $updateData2)) && p() && e(''); // 步骤2：边界值ID更新

// 步骤3：不存在的ID测试 - 使用不存在的ID
$updateData3 = new stdClass();
$updateData3->name = '不存在ID测试';
$updateData3->desc = '测试不存在ID的更新';
r($metricTest->updateMetricFieldsTest('999', $updateData3)) && p() && e(''); // 步骤3：不存在ID更新

// 步骤4：空对象测试 - 使用空数据对象
$emptyData = new stdClass();
r($metricTest->updateMetricFieldsTest('2', $emptyData)) && p() && e(''); // 步骤4：空数据对象更新

// 步骤5：验证数据一致性测试 - 更新后验证数据
$updateData5 = new stdClass();
$updateData5->name = '数据一致性验证';
$updateData5->code = 'consistency_test';
$updateData5->purpose = 'QC';
$metricTest->updateMetricFieldsTest('3', $updateData5);
r($metricTest->getMetricByIdTest('3')) && p('name') && e('数据一致性验证'); // 步骤5：验证更新后数据