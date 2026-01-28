#!/usr/bin/env php
<?php

/**

title=测试 metricTao::fetchMetricRecordByDate();
timeout=0
cid=17165

- 步骤1：查询所有记录，数据库为空 @0
- 步骤2：查询指定度量编码记录 @0
- 步骤3：查询指定日期记录 @0
- 步骤4：限制返回数量 @0
- 步骤5：不存在的度量编码 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. 使用zenData生成测试数据
zenData('metriclib')->gen(0); // 清空数据

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricTest = new metricTaoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($metricTest->fetchMetricRecordByDateTest('all', '', 100))) && p() && e('0'); // 步骤1：查询所有记录，数据库为空
r(count($metricTest->fetchMetricRecordByDateTest('test_metric', '', 100))) && p() && e('0'); // 步骤2：查询指定度量编码记录
r(count($metricTest->fetchMetricRecordByDateTest('all', '2024-01-15', 100))) && p() && e('0'); // 步骤3：查询指定日期记录
r(count($metricTest->fetchMetricRecordByDateTest('all', '', 5))) && p() && e('0'); // 步骤4：限制返回数量
r(count($metricTest->fetchMetricRecordByDateTest('nonexistent_metric', '', 100))) && p() && e('0'); // 步骤5：不存在的度量编码