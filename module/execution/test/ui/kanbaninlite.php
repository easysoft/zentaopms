#!/usr/bin/env php
<?php

/**

title=运营界面检查看板数据
timeout=0
cid=1

- 执行tester模块的checkKanban方法，参数是'1', 2▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkKanban方法，参数是'2', 2▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkKanban方法，参数是'3', 1▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkKanban方法，参数是'4', 1▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkKanban方法，参数是'5', 1▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkKanban方法，参数是'6', 1▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkKanban方法，参数是'1', 0, '2', 'story1'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkKanban方法，参数是'2', 1, '2', 'story1'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkKanban方法，参数是'1', 2, '2', 'story0'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkKanban方法，参数是'2', 1, '2', 'story0'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkKanban方法，参数是'1', 0, '3', 'module1'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkKanban方法，参数是'2', 1, '3', 'module1'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkKanban方法，参数是'2', 1, '3', 'module0'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkKanban方法，参数是'4', 1, '3', 'module0'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkKanban方法，参数是'1', 1, '4', 'pri1'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkKanban方法，参数是'2', 1, '4', 'pri1'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkKanban方法，参数是'2', 1, '4', 'pri3'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkKanban方法，参数是'3', 0, '4', 'pri0'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkKanban方法，参数是'1', 1, '5', 'assignedToadmin'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkKanban方法，参数是'2', 2, '5', 'assignedToadmin'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkKanban方法，参数是'3', 0, '5', 'assignedToadmin'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkKanban方法，参数是'1', 1, '5', 'assignedTo0'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkKanban方法，参数是'2', 0, '5', 'assignedTo0'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确
- 执行tester模块的checkKanban方法，参数是'5', 1, '5', 'assignedTo0'▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @数据正确

 */

chdir(__DIR__);
include '../lib/ui/kanbaninlite.ui.class.php';

$project = zenData('project');
$project->id->range('1-100');
$project->project->range('0, 1{2}');
$project->model->range('kanban ,[]{2}');
$project->type->range('project, kanban{2}');
$project->auth->range('[]');
$project->storytype->range('[]');
$project->parent->range('0, 1{2}');
$project->path->range('`,1,`, `,1,2,`, `,1,3,`');
$project->grade->range('1');
$project->name->range('运营项目1, 看板1, 看板2');
$project->hasProduct->range('0');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->status->range('wait');
$project->acl->range('open');
$project->vision->range('lite');
$project->gen(3);

$product = zenData('product');
$product->id->range('1-100');
$product->program->range('0');
$product->name->range('运营项目1');
$product->shadow->range('1');
$product->bind->range('1');
$product->type->range('normal');
$product->gen(1);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-3');
$projectProduct->product->range('1');
$projectProduct->branch->range('0');
$projectProduct->plan->range('0');
$projectProduct->gen(3);

$kanbanregion = zenData('kanbanregion');
$kanbanregion->id->range('1-100');
$kanbanregion->space->range('0');
$kanbanregion->kanban->range('2');
$kanbanregion->name->range('默认区域');
$kanbanregion->order->range('1');
$kanbanregion->gen(1);

$kanbangroup = zenData('kanbangroup');
$kanbangroup->id->range('1-100');
$kanbangroup->kanban->range('2');
$kanbangroup->region->range('1');
$kanbangroup->gen(1);

$kanbanlane = zenData('kanbanlane');
$kanbanlane->id->range('1-100');
$kanbanlane->execution->range('2');
$kanbanlane->type->range('task');
$kanbanlane->region->range('1');
$kanbanlane->group->range('1');
$kanbanlane->name->range('任务泳道');
$kanbanlane->color->range('#7ec5ff');
$kanbanlane->order->range('1');
$kanbanlane->gen(1);

$kanbancell = zenData('kanbancell');
$kanbancell->id->range('1-100');
$kanbancell->kanban->range('2');
$kanbancell->lane->range('1');
$kanbancell->column->range('1-100');
$kanbancell->type->range('task');
$kanbancell->cards->range('');
$kanbancell->gen(6);

$kanbancolumn = zenData('kanbancolumn');
$kanbancolumn->id->range('1-100');
$kanbancolumn->parent->range('0');
$kanbancolumn->type->range('wait, developing, developed, pause, canceled, closed');
$kanbancolumn->region->range('1');
$kanbancolumn->group->range('1');
$kanbancolumn->name->range('未开始, 进行中, 已完成, 已暂停, 已取消, 已关闭');
$kanbancolumn->color->range('#333');
$kanbancolumn->limit->range('100');
$kanbancolumn->order->range('1-100');
$kanbancolumn->gen(6);

$module = zenData('module');
$module->id->range('1-100');
$module->root->range('1, 1, 2');
$module->name->range('项目1模块1, 项目1模块2, 项目2模块1');
$module->parent->range('0');
$module->path->range('`,1,`, `,2,`, `,3,`');
$module->type->range('story');
$module->gen(3);

$story = zenData('story');
$story->id->range('1-100');
$story->vision->range('lite');
$story->root->range('1-100');
$story->path->range('`,1,`');
$story->product->range('1');
$story->module->range('1');
$story->title->range('目标');
$story->type->range('story');
$story->status->range('active');
$story->stage->range('projected');
$story->version->range('1');
$story->gen(1);

$projectStory = zenData('projectstory');
$projectStory->project->range('1, 2');
$projectStory->product->range('1, 2');
$projectStory->branch->range('0');
$projectStory->story->range('1-100 ');
$projectStory->version->range('1');
$projectStory->order->range('1-100');
$projectStory->gen(1);

$task = zenData('task');
$task->id->range('1-100');
$task->project->range('1');
$task->parent->range('0{5}, 2{3}, 3');
$task->isParent->range('0, 1{2}, 0{100}');
$task->path->range('`,1,`, `,2,`, `,3,`, `,4,`, `,5,`, `,2,6,`, `,2,7,`, `,2,8,`, `,3,9,`, `,10,`');
$task->execution->range('2');
$task->module->range('1, 0{100}');
$task->story->range('1, 0{100}');
$task->name->range('1-100');
$task->pri->range('1{4}, 3{100}');
$task->status->range('doing{2}, wait{2}, doing, done, pause, wait, cancel, closed');
$task->assignedTo->range('admin{5}, []{100}');
$task->vision->range('lite');
$task->gen(10);

$tester = new kanbanTester();
$tester->login();

/* 按默认方式分组 */
r($tester->checkKanban('1', 2)) && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('2', 2)) && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('3', 1)) && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('4', 1)) && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('5', 1)) && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('6', 1)) && p('status,message') && e('SUCCESS,数据正确');
/* 按目标分组 */
r($tester->checkKanban('1', 0, '2', 'story1')) && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('2', 1, '2', 'story1')) && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('1', 2, '2', 'story0')) && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('2', 1, '2', 'story0')) && p('status,message') && e('SUCCESS,数据正确');
/* 按所属目录分组 */
r($tester->checkKanban('1', 0, '3', 'module1')) && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('2', 1, '3', 'module1')) && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('2', 1, '3', 'module0')) && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('4', 1, '3', 'module0')) && p('status,message') && e('SUCCESS,数据正确');
/* 按优先级分组 */
r($tester->checkKanban('1', 1, '4', 'pri1')) && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('2', 1, '4', 'pri1')) && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('2', 1, '4', 'pri3')) && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('3', 0, '4', 'pri0')) && p('status,message') && e('SUCCESS,数据正确');
/* 按指派人分组 */
r($tester->checkKanban('1', 1, '5', 'assignedToadmin')) && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('2', 2, '5', 'assignedToadmin')) && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('3', 0, '5', 'assignedToadmin')) && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('1', 1, '5', 'assignedTo0'))     && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('2', 0, '5', 'assignedTo0'))     && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('5', 1, '5', 'assignedTo0'))     && p('status,message') && e('SUCCESS,数据正确');
$tester->closeBrowser();
