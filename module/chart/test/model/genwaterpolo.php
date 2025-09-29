#!/usr/bin/env php
<?php

/**

title=测试 chartModel::genWaterpolo();
timeout=0
cid=0

- 步骤1：正常情况属性series @liquidFill
 @liquidFill
属性type @liquidFill
- 步骤2：边界值属性series @0
 @0
属性data @0
 @0
- 步骤3：高百分比属性series @0.95
 @0.95
属性data @0.95
 @0.95
- 步骤4：低百分比属性series @0.05
 @0.05
属性data @0.05
 @0.05
- 步骤5：100%边界值属性series @1
 @1
属性data @1
 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

// 2. 创建测试实例（变量名与模块名一致）
$chartTest = new chartTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($chartTest->genWaterpoloTest('normal')) && p('series,0,type') && e('liquidFill'); // 步骤1：正常情况
r($chartTest->genWaterpoloTest('zeroPercent')) && p('series,0,data,0') && e('0'); // 步骤2：边界值
r($chartTest->genWaterpoloTest('highPercent')) && p('series,0,data,0') && e('0.95'); // 步骤3：高百分比
r($chartTest->genWaterpoloTest('lowPercent')) && p('series,0,data,0') && e('0.05'); // 步骤4：低百分比
r($chartTest->genWaterpoloTest('exactOne')) && p('series,0,data,0') && e('1'); // 步骤5：100%边界值