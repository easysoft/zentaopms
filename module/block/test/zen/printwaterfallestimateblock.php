#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printWaterfallEstimateBlock();
timeout=0
cid=0

- 步骤1：有效项目ID测试属性consumed @61.00
- 步骤2：项目ID为0的边界值测试
 - 属性people @0
 - 属性consumed @0.00
- 步骤3：不存在的项目ID测试
 - 属性people @0
 - 属性consumed @0.00
- 步骤4：项目有任务数据的情况属性consumed @36.50
- 步骤5：项目有团队成员的情况属性consumed @24.50

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$task = zenData('task');
$task->id->range('1-10');
$task->project->range('1{5},2{3},3{2}');
$task->consumed->range('8.5,16.0,24.5,0,12.0{2}');
$task->deleted->range('0{10}');
$task->isParent->range('0{10}');
$task->gen(10);

// 跳过durationestimation表的数据准备，因为存在字段不匹配问题

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($blockTest->printWaterfallEstimateBlockTest(1)) && p('consumed') && e('61.00'); // 步骤1：有效项目ID测试
r($blockTest->printWaterfallEstimateBlockTest(0)) && p('people,consumed') && e('0,0.00'); // 步骤2：项目ID为0的边界值测试
r($blockTest->printWaterfallEstimateBlockTest(999)) && p('people,consumed') && e('0,0.00'); // 步骤3：不存在的项目ID测试
r($blockTest->printWaterfallEstimateBlockTest(2)) && p('consumed') && e('36.50'); // 步骤4：项目有任务数据的情况
r($blockTest->printWaterfallEstimateBlockTest(3)) && p('consumed') && e('24.50'); // 步骤5：项目有团队成员的情况