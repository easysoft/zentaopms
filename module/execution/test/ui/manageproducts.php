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

$execution = zenData('project');
$execution->id->range('5-9');
$execution->project->range('1-4');
$execution->type->range('sprint{3}, stage{2}');
$execution->attribute->range('[]{3}, request{2}');
$execution->auth->range('extend');
$execution->parent->range('1, 1-4');
$execution->grade->range('1');
$execution->name->range('项目1迭代1, 项目1迭代2, 项目2迭代1, 项目3阶段, 项目4阶段');
$execution->path->range('`,1,5,`, `,1,6,`, `,2,7,`, `,3,8,`, `,4,9,`');
$execution->hasProduct->range('1, 1, 0, 1, 1');
$execution->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$execution->status->range('wait');
$execution->gen(4, false);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('1{3}, 2, 3{2}, 4{2}, 5-9, 9');
$projectproduct->product->range('1-3, 5, 1, 2, 1, 2, 1{2}, 5, 1{2}, 2');
$projectproduct->gen(14);
