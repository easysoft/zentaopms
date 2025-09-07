#!/usr/bin/env php
<?php

/**

title=测试 chartModel::genPie();
timeout=0
cid=0

- 步骤1：正常饼图生成
 - 第series条的0:type属性 @pie
 - 第series条的legend:type属性 @scroll
 - 第series条的tooltip:trigger属性 @item
- 步骤2：空数据处理第series条的0:data属性 @~~
- 步骤3：大数据量归并处理第series条的0:data:50:name属性 @其他
- 步骤4：带过滤器的饼图第series条的0:data:0:name属性 @活动
- 步骤5：sum聚合方式第series条的0:data:0:value属性 @120.5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

// 2. zendata数据准备
$table = zenData('chart');
$table->loadYaml('chart_genpie', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$chartTest = new chartTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($chartTest->genPieTest('normal')) && p('series:0:type,legend:type,tooltip:trigger') && e('pie,scroll,item'); // 步骤1：正常饼图生成
r($chartTest->genPieTest('empty')) && p('series:0:data') && e('~~'); // 步骤2：空数据处理
r($chartTest->genPieTest('largeData')) && p('series:0:data:50:name') && e('其他'); // 步骤3：大数据量归并处理
r($chartTest->genPieTest('filtered')) && p('series:0:data:0:name') && e('活动'); // 步骤4：带过滤器的饼图
r($chartTest->genPieTest('sumAgg')) && p('series:0:data:0:value') && e('120.5'); // 步骤5：sum聚合方式