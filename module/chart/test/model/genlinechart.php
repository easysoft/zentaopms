#!/usr/bin/env php
<?php

/**

title=测试 chartModel::genLineChart();
timeout=0
cid=0

- 步骤1：正常折线图生成第series条的0:type属性 @line
- 步骤2：正常折线图grid属性第grid条的containLabel属性 @1
- 步骤3：日期排序处理第xAxis条的type属性 @category
- 步骤4：多序列数据 @2
- 步骤5：空数据处理 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

// 2. zendata数据准备（因为使用模拟测试，不需要实际数据）
// zendata('bug')->loadYaml('bug_genlinechart', false, 2)->gen(50);

// 3. 用户登录（选择合适角色）
// su('admin'); // 不需要用户登录，因为使用模拟测试

// 4. 创建测试实例（变量名与模块名一致）
$chartTest = new chartTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($chartTest->genLineChartTest('normal')) && p('series:0:type') && e('line'); // 步骤1：正常折线图生成
r($chartTest->genLineChartTest('normal')) && p('grid:containLabel') && e('1'); // 步骤2：正常折线图grid属性
r($chartTest->genLineChartTest('dateSort')) && p('xAxis:type') && e('category'); // 步骤3：日期排序处理
r($chartTest->genLineChartSeriesCountTest('multiSeries')) && p('') && e('2'); // 步骤4：多序列数据
r($chartTest->genLineChartSeriesCountTest('empty')) && p('') && e('0'); // 步骤5：空数据处理