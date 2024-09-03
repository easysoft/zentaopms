<?php

/**
title=维护产品
timeout=0
cid=1
 */

chdir(__DIR__);
#include '../lib/manageproducts.ui.class.php';
include '/opt/dev/pms/test/lib/ui.php';
$product = zenData('product');
$product->id->range('1-4');
$product->name->range('产品1, 产品2, 产品3, 产品4');
$product->type->range('normal');
$product->gen(4);

$project = zenData('project');
$project->id->range('1-4');
$project->project->range('0');
$project->model->range('scrum{2}, waterfall{2}');
$project->type->range('project');
$project->parent->range('0');
$project->auth->range('extend');
$project->grade->range('1');
$project->name->range('项目1, 无产品项目2, 按产品创建瀑布项目3, 按项目创建瀑布项目4');
$project->path->range('`,1,`, `,2,`, `,3,`, `,4,`');
$project->hasProduct->range('1, 0, 1, 1');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->gen(4);
