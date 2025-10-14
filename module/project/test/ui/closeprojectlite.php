#!/usr/bin/env php
<?php

/**

title=运营界面关闭项目测试
timeout=0
cid=73

- 关闭项目测试结果 @关闭项目成功

*/
chdir(__DIR__);
include '../lib/ui/closeprojectlite.ui.class.php';

$project = zenData('project');
$project->id->range('1-7');
$project->project->range('0');
$project->model->range('kanban');
$project->type->range('project');
$project->auth->range('[]');
$project->grade->range('1');
$project->path->range('`,1,`, `,2,`, `,3,`, `,4,`, `,5,`, `,6,`, `,6,`, `,7,`');
$project->name->range('运营界面项目1, 运营界面项目2, 运营界面项目3, 运营界面项目4, 运营界面项目5, 运营界面项目6, 运营界面项目7');
$project->hasProduct->range('0');
$project->begin->range('(-3w)-(-2w):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+5w)-(+6w):1D')->type('timestamp')->format('YY/MM/DD');
$project->acl->range('open');
$project->status->range('wait{1}, doing{4}, suspended{1}, closed{1}');
$project->vision->range('lite');
$project->gen(7);

$product = zenData('product');
$product->id->range('1-7');
$product->name->range('影子产品1, 影子产品2, 影子产品3, 影子产品4, 影子产品5, 影子产品6, 影子产品7');
$product->shadow->range('1');
$product->type->range('normal');
$product->vision->range('lite');
$product->gen(7);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-7');
$projectProduct->product->range('1-7');
$projectProduct->gen(7);

$tester = new closeProjectLiteTester();
$tester->login();

$project = array();

r($tester->closeProject($project)) && p('message') && e('关闭项目成功'); //关闭项目

$tester->closeBrowser();
