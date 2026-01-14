#!/usr/bin/env php
<?php

/**

title=测试 programplanTao::getStageCount();
timeout=0
cid=17772

- 步骤1：正常情况-planID=1有4个子阶段 @4
- 步骤2：边界值-planID为0 @5
- 步骤3：测试planID=999的子阶段数量 @5
- 步骤4：模式过滤-只统计里程碑阶段 @2
- 步骤5：测试planID=2的子阶段数量（不包含已删除） @3
- 步骤6：负数planID测试 @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备
$project = zenData('project');
$project->id->range('1-20');
$project->parent->range('0,1{4},2{3},999{5},0{7}');
$project->type->range('project,stage{19}');
$project->milestone->range('0,0{2},1{2},0{3},1{1},0{11}');
$project->deleted->range('0{18},1{2}');
$project->gen(20);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$programplanTest = new programplanTaoTest();

// 5. 测试步骤（至少5个）
r($programplanTest->getStageCountTest(1)) && p() && e('4'); // 步骤1：正常情况-planID=1有4个子阶段
r($programplanTest->getStageCountTest(0)) && p() && e('5'); // 步骤2：边界值-planID为0
r($programplanTest->getStageCountTest(999)) && p() && e('5'); // 步骤3：测试planID=999的子阶段数量
r($programplanTest->getStageCountTest(1, 'milestone')) && p() && e('2'); // 步骤4：模式过滤-只统计里程碑阶段
r($programplanTest->getStageCountTest(2)) && p() && e('3'); // 步骤5：测试planID=2的子阶段数量（不包含已删除）
r($programplanTest->getStageCountTest(-1)) && p() && e('0'); // 步骤6：负数planID测试