#!/usr/bin/env php
<?php

/**

title=运营界面创建看板。
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/kanbaninlite.ui.class.php';

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
$kanbangroup->gen(5);

$kanbanlane = zenData('kanbanlane');
$kanbanlane->id->range('1-100');
$kanbanlane->execution->range('2');
$kanbanlane->type->range('parentStory, story, bug, task');
$kanbanlane->region->range('1');
$kanbanlane->group->range('2-5');
$kanbanlane->name->range('父目标, 目标, Bug, 任务');
$kanbanlane->order->range('5, 10, 15, 20');
$kanbanlane->gen(4);

$kanbancell = zenData('kanbancell');
$kanbancell->id->range('1-100');
$kanbancell->kanban->range('2');
$kanbancell->lane->range('1{7}, 2{15}, 3{9}, 4{6}');
$kanbancell->column->range('1-100');
$kanbancell->type->range('parentStory{7}, story{15}, bug{9}, task{6}');
$kanbancell->cards->range('');
$kanbancell->gen(37);

$kanbancolumn = zenData('kanbancolumn');
$kanbancolumn->id->range('1-100');
$kanbancolumn->parent->range('0{9}, -1, 10{2}, -1, 13{2}, -1, 16{2}, 0{6}, -1, 25{2}, -1, 28{2}, 0{7}');
$kanbancolumn->type->range('wait, planned, projected, developing, delivering, delivered, closed, backlog, ready, design, designing, designed, develop, developing, developed, test, testing, tested, verified, rejected, released, closed, unconfirmed, confirmed, resolving, fixing, fixed, test, testing, tested, closed, wait, developing, developed, pause, canceled, closed');
$kanbancolumn->region->range('1');
$kanbancolumn->group->range('2{7}, 3{15}, 4{9}, 5{6}');
$kanbancolumn->name->range('1-31, 未开始, 进行中, 已完成, 已暂停, 已取消, 已关闭');
$kanbancolumn->limit->range('-1');
$kanbancolumn->gen(37);

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
$task->project->range('1{9}, 2');
$task->parent->range('0{5}, 2{3}, 3, 0');
$task->isParent->range('0, 1{2}, 0{100}');
$task->path->range('`,1,`, `,2,`, `,3,`, `,4,`, `,5,`, `,2,6,`, `,2,7,`, `,2,8, `,3,9,`, `,10,`');
$task->execution->range('3{9}, 4');
$task->module->range('1{2}, 2{3}, []{100}');
$task->story->range('0, 1, 0{100}');
$task->name->range('1-100');
$task->pri->range('1{2}, 3{100}');
$task->status->range('developing{2}, wait{2}, developed{2}, pause, canceled, closed, wait{100}');
$task->assignedTo->range('admin{3}, []{100}');
$task->vision->range('lite');
$task->gen(10);

$tester = new kanbanTester();
$tester->login();

r($tester->checkKanban('4', 1))           && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('8', 2))           && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('17', 1))          && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('18', 0))          && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('23', 2))          && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('24', 1))          && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('27', 2))          && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('32', 1))          && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('34', 2))          && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('35', 3))          && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('36', 4))          && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('37', 5))          && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('38', 6))          && p('status,message') && e('SUCCESS,数据正确');
r($tester->checkKanban('1', 1, '2', '2')) && p('status,message') && e('SUCCESS,数据正确');
$tester->closeBrowser();
