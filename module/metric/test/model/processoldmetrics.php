#!/usr/bin/env php
<?php

/**

title=测试 metricModel::processOldMetrics();
timeout=0
cid=17149

- 步骤1：open版本处理度量项第0条的isOldMetric属性 @1
- 步骤2：max版本处理旧度量项第0条的isOldMetric属性 @0
- 步骤3：空数据数组输入 @~~
- 步骤4：max版本处理新度量项第0条的isOldMetric属性 @~~
- 步骤5：当前环境处理默认度量项第0条的isOldMetric属性 @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 数据准备（简化版本，不使用zendata）

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricTest = new metricModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($metricTest->processOldMetricsOpenTest()) && p('0:isOldMetric') && e(1); // 步骤1：open版本处理度量项
r($metricTest->processOldMetricsMaxTest()) && p('0:isOldMetric') && e(0); // 步骤2：max版本处理旧度量项
r($metricTest->processOldMetricsEmptyTest()) && p() && e('~~'); // 步骤3：空数据数组输入
r($metricTest->processOldMetricsNewTest()) && p('0:isOldMetric') && e('~~'); // 步骤4：max版本处理新度量项
r($metricTest->processOldMetricsTest()) && p('0:isOldMetric') && e('~~'); // 步骤5：当前环境处理默认度量项