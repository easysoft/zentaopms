#!/usr/bin/env php
<?php

/**

title=测试 actionTao::processChangedStoryStageActionExtra();
timeout=0
cid=0

- 步骤1：linkPlan触发，验证包含"关联到计划" @1
- 步骤2：unlinkPlan触发，验证包含"移除了计划" @1
- 步骤3：linkProject触发，验证包含"关联到项目" @1
- 步骤4：unlinkProject触发，验证包含"移除了项目" @1
- 步骤5：startTask触发设计任务，验证包含"开始了任务" @1
- 步骤6：finishTask触发开发任务，验证包含"完成了任务" @1
- 步骤7：linkRelease触发，验证包含"关联到发布" @1
- 步骤8：unlinkRelease触发，验证包含"移除了发布" @1
- 步骤9：editStory触发，验证包含"编辑需求后" @1
- 步骤10：无效触发类型，验证不包含任何有效actionText @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备
zenData('action')->gen(100);
$productplanTable = zenData('productplan');
$productplanTable->id->range('10001');
$productplanTable->title->range('测试计划');
$productplanTable->product->range('1');
$productplanTable->gen(1);

$projectTable = zenData('project');
$projectTable->id->range('10001');
$projectTable->name->range('测试项目');
$projectTable->type->range('project');
$projectTable->model->range('scrum');
$projectTable->gen(1);

$taskTable = zenData('task');
$taskTable->id->range('10001-10002');
$taskTable->name->range('设计任务1,开发任务1');
$taskTable->type->range('design,devel');
$taskTable->status->range('doing,done');
$taskTable->project->range('1');
$taskTable->execution->range('1');
$taskTable->gen(2);

$releaseTable = zenData('release');
$releaseTable->id->range('10001');
$releaseTable->name->range('测试发布');
$releaseTable->product->range('1');
$releaseTable->gen(1);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$actionTest = new actionTaoTest();

// 5. 测试步骤
r(strpos($actionTest->processChangedStoryStageActionExtraTest('linkPlan|10001|planned'), '关联到计划') !== false)        && p() && e('1'); // 步骤1：linkPlan触发，验证包含"关联到计划"
r(strpos($actionTest->processChangedStoryStageActionExtraTest('unlinkPlan|10001|wait'), '移除了计划') !== false)         && p() && e('1'); // 步骤2：unlinkPlan触发，验证包含"移除了计划"
r(strpos($actionTest->processChangedStoryStageActionExtraTest('linkProject|10001|projected'), '关联到项目') !== false)   && p() && e('1'); // 步骤3：linkProject触发，验证包含"关联到项目"
r(strpos($actionTest->processChangedStoryStageActionExtraTest('unlinkProject|10001|wait'), '移除了项目') !== false)      && p() && e('1'); // 步骤4：unlinkProject触发，验证包含"移除了项目"
r(strpos($actionTest->processChangedStoryStageActionExtraTest('startTask|10001|designing'), '开始了任务') !== false)     && p() && e('1'); // 步骤5：startTask触发设计任务，验证包含"开始了任务"
r(strpos($actionTest->processChangedStoryStageActionExtraTest('finishTask|10002|developed'), '完成了任务') !== false)    && p() && e('1'); // 步骤6：finishTask触发开发任务，验证包含"完成了任务"
r(strpos($actionTest->processChangedStoryStageActionExtraTest('linkRelease|10001|released'), '关联到发布') !== false)    && p() && e('1'); // 步骤7：linkRelease触发，验证包含"关联到发布"
r(strpos($actionTest->processChangedStoryStageActionExtraTest('unlinkRelease|10001|projected'), '移除了发布') !== false) && p() && e('1'); // 步骤8：unlinkRelease触发，验证包含"移除了发布"
r(strpos($actionTest->processChangedStoryStageActionExtraTest('editStory|0|testing'), '编辑需求后') !== false)           && p() && e('1'); // 步骤9：editStory触发，验证包含"编辑需求后"
r(strpos($actionTest->processChangedStoryStageActionExtraTest('invalid|0|wait'), '关联到') === false && strpos($actionTest->processChangedStoryStageActionExtraTest('invalid|0|wait'), '移除了') === false && strpos($actionTest->processChangedStoryStageActionExtraTest('invalid|0|wait'), '开始了') === false && strpos($actionTest->processChangedStoryStageActionExtraTest('invalid|0|wait'), '完成了') === false) && p() && e('1'); // 步骤10：无效触发类型，验证不包含任何有效actionText