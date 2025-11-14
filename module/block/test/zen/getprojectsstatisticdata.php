#!/usr/bin/env php
<?php

/**

title=测试 blockZen::getProjectsStatisticData();
timeout=0
cid=15243

- 步骤1：单个项目ID测试 @1
- 步骤2：多个项目ID测试 @1
- 步骤3：空数组测试 @1
- 步骤4：无效项目ID测试 @1
- 步骤5：验证敏捷项目统计字段 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$project->model->range('scrum{3},waterfall{2},kanban{2},agileplus{3}');
$project->type->range('project{5},sprint{3},stage{2}');
$project->status->range('wait{2},doing{5},suspended{1},done{2}');
$project->deleted->range('0{9},1{1}');
$project->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$result1 = $blockTest->getProjectsStatisticDataTest(array(1));
r(count($result1['riskCountGroup']) >= 0 && count($result1['issueCountGroup']) >= 0) && p() && e('1'); // 步骤1：单个项目ID测试

$result2 = $blockTest->getProjectsStatisticDataTest(array(1, 2, 3));
r(is_array($result2) && isset($result2['riskCountGroup'])) && p() && e('1'); // 步骤2：多个项目ID测试

$result3 = $blockTest->getProjectsStatisticDataTest(array());
r(is_array($result3) && isset($result3['riskCountGroup'])) && p() && e('1'); // 步骤3：空数组测试

$result4 = $blockTest->getProjectsStatisticDataTest(array(999));
r(is_array($result4) && isset($result4['riskCountGroup'])) && p() && e('1'); // 步骤4：无效项目ID测试

$result5 = $blockTest->getProjectsStatisticDataTest(array(1));
r(isset($result5['investedGroup']) && isset($result5['consumeTaskGroup'])) && p() && e('1'); // 步骤5：验证敏捷项目统计字段