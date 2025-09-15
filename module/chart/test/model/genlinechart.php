#!/usr/bin/env php
<?php

/**

title=测试 chartModel::genLineChart();
timeout=0
cid=0

- 步骤1：正常折线图生成
 - 第series条的0:type属性 @line
 - 第series条的grid:containLabel属性 @1
 - 第series条的tooltip:trigger属性 @axis
- 步骤2：日期排序处理第xAxis条的data:0属性 @2024-01
- 步骤3：多序列数据属性series @2
- 步骤4：语言配置第series条的0:name属性 @用户总数(计数)
- 步骤5：空数据处理属性series @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

// 2. zendata数据准备
$table = zenData('chart');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$chartTest = new chartTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($chartTest->genLineChartTest('normal')) && p('series:0:type,grid:containLabel,tooltip:trigger') && e('line,1,axis'); // 步骤1：正常折线图生成
r($chartTest->genLineChartTest('dateSort')) && p('xAxis:data:0') && e('2024-01'); // 步骤2：日期排序处理
r($chartTest->genLineChartTest('multiSeries')) && p('series') && e('2'); // 步骤3：多序列数据
r($chartTest->genLineChartTest('withLangs')) && p('series:0:name') && e('用户总数(计数)'); // 步骤4：语言配置
r($chartTest->genLineChartTest('empty')) && p('series') && e('0'); // 步骤5：空数据处理