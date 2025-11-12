#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printScrumOverviewBlock();
timeout=0
cid=0

- 步骤1:正常项目ID测试返回项目ID属性projectID @11
- 步骤2:验证项目对象存在属性hasProject @1
- 步骤3:验证故事点统计默认值属性storyPoints @0
- 步骤4:验证任务数统计默认值属性tasks @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备
$project = zenData('project');
$project->id->range('11-20');
$project->name->range('敏捷项目1,敏捷项目2,敏捷项目3,敏捷项目4,敏捷项目5,敏捷项目6,敏捷项目7,敏捷项目8,敏捷项目9,敏捷项目10');
$project->model->range('scrum,kanban,waterfall,scrum,scrum,scrum,waterfall,scrum,kanban,scrum');
$project->type->range('project');
$project->status->range('doing{5},suspended{2},closed{3}');
$project->acl->range('open');
$project->deleted->range('0');
$project->gen(10);

$execution = zenData('project');
$execution->id->range('101-110');
$execution->project->range('11{3},12{2},13{2},14{3}');
$execution->name->range('迭代1,迭代2,迭代3,迭代4,迭代5,迭代6,迭代7,迭代8,迭代9,迭代10');
$execution->type->range('sprint,stage');
$execution->status->range('wait{2},doing{4},suspended{2},closed{2}');
$execution->acl->range('open');
$execution->deleted->range('0');
$execution->gen(10);

$task = zenData('task');
$task->id->range('1-20');
$task->project->range('11{10},12{10}');
$task->execution->range('101,102,103,101,102,103,101,102,103,101,104,105,104,105,104,105,104,105,104,105');
$task->name->range('任务1,任务2,任务3,任务4,任务5,任务6,任务7,任务8,任务9,任务10,任务11,任务12,任务13,任务14,任务15,任务16,任务17,任务18,任务19,任务20');
$task->status->range('wait{5},doing{7},done{5},closed{3}');
$task->estimate->range('1-10');
$task->consumed->range('0-8');
$task->left->range('0-5');
$task->deleted->range('0');
$task->gen(20);

$story = zenData('story');
$story->id->range('1-15');
$story->product->range('1-5');
$story->title->range('需求1,需求2,需求3,需求4,需求5,需求6,需求7,需求8,需求9,需求10,需求11,需求12,需求13,需求14,需求15');
$story->status->range('active{5},closed{5},draft{5}');
$story->estimate->range('1-10');
$story->stage->range('developed{10},released{5}');
$story->deleted->range('0');
$story->gen(15);

$bug = zenData('bug');
$bug->id->range('1-15');
$bug->project->range('11{10},12{5}');
$bug->execution->range('101,102,103,101,102,103,101,102,103,101,104,105,104,105,104');
$bug->title->range('缺陷1,缺陷2,缺陷3,缺陷4,缺陷5,缺陷6,缺陷7,缺陷8,缺陷9,缺陷10,缺陷11,缺陷12,缺陷13,缺陷14,缺陷15');
$bug->status->range('active{5},resolved{5},closed{5}');
$bug->deleted->range('0');
$bug->gen(15);

// 准备度量数据
zendata('metriclib')->loadYaml('metriclib_printprojectoverviewblock', false, 2)->gen(20);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$blockTest = new blockZenTest();

// 设置session中的项目ID
global $app;
$app->session->set('project', 11);

// 5. 必须包含至少5个测试步骤
r($blockTest->printScrumOverviewBlockTest()) && p('projectID') && e('11'); // 步骤1:正常项目ID测试返回项目ID
r($blockTest->printScrumOverviewBlockTest()) && p('hasProject') && e('1'); // 步骤2:验证项目对象存在
r($blockTest->printScrumOverviewBlockTest()) && p('storyPoints') && e('0'); // 步骤3:验证故事点统计默认值
r($blockTest->printScrumOverviewBlockTest()) && p('tasks') && e('0'); // 步骤4:验证任务数统计默认值
$app->session->set('project', 12); r($blockTest->printScrumOverviewBlockTest()) && p('projectID') && e('12'); // 步骤5:切换到不同项目ID