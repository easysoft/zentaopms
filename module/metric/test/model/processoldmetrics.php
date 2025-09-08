#!/usr/bin/env php
<?php

/**

title=测试 metricModel::processOldMetrics();
timeout=0
cid=0

- 步骤1：open版本处理第0条的isOldMetric属性 @1
- 步骤2：max版本处理旧度量项第0条的isOldMetric属性 @0
- 步骤3：空数据输入 @~~
- 步骤4：新度量项处理第0条的isOldMetric属性 @~~
- 步骤5：当前环境处理旧度量项第0条的isOldMetric属性 @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('basicmeas');
$table->id->range('1-10');
$table->name->range('基础度量项1,基础度量项2,基础度量项3,基础度量项4,基础度量项5,基础度量项6,基础度量项7,基础度量项8,基础度量项9,基础度量项10');
$table->code->range('metric1,metric2,metric3,metric4,metric5,metric6,metric7,metric8,metric9,metric10');
$table->unit->range('个,次,项,条,人,天,小时,百分比,元,台');
$table->deleted->range('0{10}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricTest = new metricTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($metricTest->processOldMetricsOpenTest()) && p('0:isOldMetric') && e('1'); // 步骤1：open版本处理
r($metricTest->processOldMetricsMaxTest()) && p('0:isOldMetric') && e('0'); // 步骤2：max版本处理旧度量项
r($metricTest->processOldMetricsEmptyTest()) && p() && e('~~'); // 步骤3：空数据输入
r($metricTest->processOldMetricsNewTest()) && p('0:isOldMetric') && e('~~'); // 步骤4：新度量项处理
r($metricTest->processOldMetricsTest()) && p('0:isOldMetric') && e('~~'); // 步骤5：当前环境处理旧度量项