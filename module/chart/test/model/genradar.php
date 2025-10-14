#!/usr/bin/env php
<?php

/**

title=测试 chartModel::genRadar();
timeout=0
cid=0

- 步骤1：正常雷达图数据生成第series条的type属性 @radar
- 步骤2：多指标雷达图生成第series条的data:0:name属性 @数量(计数)
- 步骤3：空数据雷达图处理第radar条的indicator属性 @~~
- 步骤4：过滤条件雷达图第series条的type属性 @radar
- 步骤5：多语言标签雷达图第series条的data:0:name属性 @计数值(计数)

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

// 2. zendata数据准备（因为使用模拟测试，不需要实际数据）
// zendata('chart')->loadYaml('chart_genradar', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
// su('admin'); // 不需要用户登录，因为使用模拟测试

// 4. 创建测试实例（变量名与模块名一致）
$chartTest = new chartTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($chartTest->genRadarTest('normal')) && p('series:type') && e('radar'); // 步骤1：正常雷达图数据生成
r($chartTest->genRadarTest('multi')) && p('series:data:0:name') && e('数量(计数)'); // 步骤2：多指标雷达图生成
r($chartTest->genRadarTest('empty')) && p('radar:indicator') && e('~~'); // 步骤3：空数据雷达图处理
r($chartTest->genRadarTest('filtered')) && p('series:type') && e('radar'); // 步骤4：过滤条件雷达图
r($chartTest->genRadarTest('multilang')) && p('series:data:0:name') && e('计数值(计数)'); // 步骤5：多语言标签雷达图