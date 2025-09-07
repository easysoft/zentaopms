#!/usr/bin/env php
<?php

/**

title=测试 chartModel::genWaterpolo();
timeout=0
cid=0

- 步骤1：正常情况
 - 第series条的0:type属性 @liquidFill
 - 第series条的tooltip:show属性 @1
- 步骤2：分母为零的边界值第series条的0:data:0属性 @0
- 步骤3：高百分比情况第series条的0:data:0属性 @0.95
- 步骤4：低百分比情况第series条的0:data:0属性 @0.05
- 步骤5：带过滤器情况第series条的0:data:0属性 @0.75

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

// 2. zendata数据准备
$table = zenData('chart');
$table->loadYaml('chart_genwaterpolo', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$chartTest = new chartTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($chartTest->genWaterpoloTest('normal')) && p('series:0:type,tooltip:show') && e('liquidFill,1'); // 步骤1：正常情况
r($chartTest->genWaterpoloTest('zeroPercent')) && p('series:0:data:0') && e('0'); // 步骤2：分母为零的边界值
r($chartTest->genWaterpoloTest('highPercent')) && p('series:0:data:0') && e('0.95'); // 步骤3：高百分比情况
r($chartTest->genWaterpoloTest('lowPercent')) && p('series:0:data:0') && e('0.05'); // 步骤4：低百分比情况
r($chartTest->genWaterpoloTest('withFilters')) && p('series:0:data:0') && e('0.75'); // 步骤5：带过滤器情况