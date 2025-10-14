#!/usr/bin/env php
<?php

/**

title=运营界面激活项目测试
timeout=0
cid=73

- 激活项目测试结果 @激活项目成功

*/
chdir(__DIR__);
include '../lib/ui/activeprojectlite.ui.class.php';

$project = zenData('project');
$project->id->range('1-4');
$project->project->range('0');
$project->model->range('kanban');
$project->type->range('project');
$project->auth->range('[]');
$project->grade->range('1');
$project->path->range('`,1,`, `,2,`, `,3,`, `,4,`');
$project->name->range('运营界面项目1, 运营界面项目2, 运营界面项目3, 运营界面项目4');
$project->hasProduct->range('0');
$project->begin->range('(-3w)-(-2w):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+5w)-(+6w):1D')->type('timestamp')->format('YY/MM/DD');
$project->acl->range('open');
$project->status->range('wait{1}, doing{1}, suspended{1}, closed{1}');
$project->vision->range('lite');
$project->gen(4);

$product = zenData('product');
$product->id->range('1-4');
$product->name->range('影子产品1, 影子产品2, 影子产品3, 影子产品4');
$product->shadow->range('1');
$product->type->range('normal');
$product->vision->range('lite');
$product->gen(4);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-4');
$projectProduct->product->range('1-4');
$projectProduct->gen(4);

$tester = new activeProjectLiteTester();
$tester->login();

$project = array();

r($tester->activeProject($project)) && p('message') && e('激活项目成功'); //激活项目

$tester->closeBrowser();
