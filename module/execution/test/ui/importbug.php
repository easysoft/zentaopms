<?php

/**
title=导入Bug
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/importbug.ui.class.php';

$product = zenData('product');
$product->id->range('1-100');
$product->name->range('产品1, 产品2');
$product->type->range('normal');
$product->gen(2);

$project = zenData('project');
$project->id->range('1-100');
$project->project->range('0, 1{3}');
$project->model->range('scrum, []{3}');
$project->type->range('project, sprint{3}');
$project->auth->range('extend, []{3}');
$project->storytype->range('`story,epic,requirement`');
$project->path->range('`,1,`, `,1,2,`, `,1,3,`, `,1,4,`');
$project->grade->range('1');
$project->name->range('项目1, 项目1执行1, 项目1执行2, 项目1执行3');
$project->hasProduct->range('1');
$project->status->range('wait');
$project->acl->range('open');
$project->gen(5);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-3');
$projectProduct->product->range('1{2}, 2{3}');
$projectProduct->gen(5);

$bug = zenData('bug');
$bug->id->range('1-100');
$bug->project->range('0');
$bug->product->range('1{5}, 2{5}');
$bug->execution->range('0');
$bug->task->range('0');
$bug->toTask->range('0');
$bug->title->range('1-100');
$bug->status->range('active{3}, resolved, closed');
$bug->deleted->range('1, 0{4}');
$bug->gen(10);

$tester = new importBugTester();
$tester->login();

r($tester->importBug('4', '0')) && p('status,message') && e('success','可导入的Bug数目正确');
r($tester->importBug('3', '2')) && p('status,message') && e('success','导入Bug成功');
r($tester->importBug('2', '3')) && p('status,message') && e('success','导入Bug成功');
r($tester->importBug('2', '2')) && p('status,message') && e('success','导入Bug成功');
$tester->closeBrowser();
