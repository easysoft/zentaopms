#!/usr/bin/env php
<?php

/**
 *
 * title=检查产品看板数据准确性
 * timeout=0
 * cid=0
 *
 */
chdir(__DIR__);
include '../lib/kanban.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->program->range('0');
$product->name->range('产品1');
$product->PO->range('admin');
$product->status->range('normal');
$product->type->range('normal');
$product->gen(1);

$project = zenData('project');
$project->id->range('1-6');
$project->project->range('0{3},1{3}');
$project->model->range('scrum{3},[]{3}');
$project->type->range('project{3},sprint{3}');
$project->parent->range('0{3},1{3}');
$project->auth->range('extend');
$project->storytype->range('story');
$project->path->range('`,1,`','`,2,`','`,3,`,`,1,4,`','`,1,5,`','`,1,6,`');
$project->grade->range('1');
$project->name->range('项目1,项目2,项目3,迭代1,迭代2,迭代3');
$project->hasProduct->range('1');
$project->status->range('doing{2},wait{1},doing{2},wait{1}');
$project->gen(6);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-6');
$projectProduct->product->range('1');
$projectProduct->gen(6);

$productplan = zenData('productplan');
$productplan->id->range('1-7');
$productplan->product->range(1);
$productplan->title->range('计划1,计划2,计划3,计划4,计划5,计划6,计划7');
$productplan->parent->range(0);
$productplan->begin->range('(-10M)-(-7M):7D')->type('timestamp')->format('YYYY-MM-DD');
$productplan->end->range('(-1M)-(+6M):10D')->type('timestamp')->format('YYYY-MM-DD');
$productplan->status->range('wait{3},doing{2},done{1},closed{1}');
$productplan->gen(7);

$release = zenData('release');
$release->id->range('1-5');
$release->product->range('1');
$release->name->range('发布1,发布2,发布3,发布4,发布5');
$release->status->range('normal{4},closed{1}');
$release->gen(5);

$tester = new kanbanTester();
$tester->login();

r($tester->checkKanbanData('plan', '3'))      && p('message,status') && e('未过期计划数正确,SUCCESS');//检查未过期计划数
r($tester->checkKanbanData('project', '2'))   && p('message,status') && e('进行中的项目数正确,SUCCESS');//检查进行中的项目数
r($tester->checkKanbanData('execution', '1')) && p('message,status') && e('进行中的执行数正确,SUCCESS');//检查进行中的执行数
r($tester->checkKanbanData('release', '4'))   && p('message,status') && e('正常发布数正确,SUCCESS');//检查正常发布数

$tester->closeBrowser();
