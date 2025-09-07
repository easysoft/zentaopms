#!/usr/bin/env php
<?php

/**

title=测试 chartModel::genCluBar();
timeout=0
cid=0

- 步骤1：正常簇状条形图生成第series条的0:type属性 @bar
- 步骤2：堆积条形图生成第series条的0:stack属性 @total
- 步骤3：垂直簇状条形图生成第xAxis条的type属性 @value
- 步骤4：带过滤器的条形图生成第tooltip条的trigger属性 @axis
- 步骤5：带多语言标签的条形图生成第grid条的containLabel属性 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$chartTest = new chartTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($chartTest->genCluBarTest('normal')) && p('series:0:type') && e('bar'); // 步骤1：正常簇状条形图生成
r($chartTest->genCluBarTest('stackedBar')) && p('series:0:stack') && e('total'); // 步骤2：堆积条形图生成
r($chartTest->genCluBarTest('cluBarY')) && p('xAxis:type') && e('value'); // 步骤3：垂直簇状条形图生成
r($chartTest->genCluBarTest('withFilters')) && p('tooltip:trigger') && e('axis'); // 步骤4：带过滤器的条形图生成
r($chartTest->genCluBarTest('withLangs')) && p('grid:containLabel') && e('1'); // 步骤5：带多语言标签的条形图生成