#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printWaterfallGeneralReportBlock();
timeout=0
cid=0

- 步骤1：无项目ID情况
 - 属性pv @0
 - 属性ev @0
 - 属性ac @55.00
 - 属性sv @0
 - 属性cv @-100.00
 - 属性progress @100
- 步骤2：项目ID为0情况
 - 属性pv @0
 - 属性ev @0
 - 属性ac @55.00
 - 属性sv @0
 - 属性cv @-100.00
 - 属性progress @100
- 步骤3：项目ID为1情况
 - 属性pv @0
 - 属性ev @0
 - 属性ac @0.00
- 步骤4：项目ID为2情况
 - 属性pv @0
 - 属性ev @0
 - 属性ac @0.00
- 步骤5：不存在的项目ID情况
 - 属性pv @0
 - 属性ev @0
 - 属性ac @0.00
 - 属性sv @0
 - 属性cv @0
 - 属性progress @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备
$projectTable = zenData('project');
$projectTable->loadYaml('project_printwaterfallgeneralreportblock', false, 2)->gen(10);

$taskTable = zenData('task');
$taskTable->loadYaml('task_printwaterfallgeneralreportblock', false, 2)->gen(50);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($blockTest->printWaterfallGeneralReportBlockTest()) && p('pv,ev,ac,sv,cv,progress') && e('0,0,55.00,0,-100.00,100'); // 步骤1：无项目ID情况
r($blockTest->printWaterfallGeneralReportBlockTest(0)) && p('pv,ev,ac,sv,cv,progress') && e('0,0,55.00,0,-100.00,100'); // 步骤2：项目ID为0情况
r($blockTest->printWaterfallGeneralReportBlockTest(1)) && p('pv,ev,ac') && e('0,0,0.00'); // 步骤3：项目ID为1情况
r($blockTest->printWaterfallGeneralReportBlockTest(2)) && p('pv,ev,ac') && e('0,0,0.00'); // 步骤4：项目ID为2情况
r($blockTest->printWaterfallGeneralReportBlockTest(999)) && p('pv,ev,ac,sv,cv,progress') && e('0,0,0.00,0,0,0'); // 步骤5：不存在的项目ID情况