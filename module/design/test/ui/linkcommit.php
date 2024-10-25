#!/usr/bin/env php
<?php

/**

title=关联设计检查测试
timeout=0
cid=9

- 执行tester模块的linkCommit方法，参数是'3' 测试结果 @关联提交成功

*/

chdir(__DIR__);
include '../lib/linkcommit.ui.class.php';

$project = zendata('project');
$project->id->range('1');
$project->project->range('0');
$project->model->range('waterfall');
$project->type->range('project');
$project->parent->range('0');
$project->auth->range('extend');
$project->grade->range('1');
$project->name->range('瀑布项目1');
$project->path->range('`,1,`');
$project->begin->range('(-2M)-(-M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+2M)-(+3M):1D')->type('timestamp')->format('YY/MM/DD');
$project->acl->range('open');
$project->gen(1);

$product = zenData('product');
$product->id->range('1-2');
$product->name->range('产品1, 产品2');
$product->type->range('normal');
$product->gen(2);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1');
$projectProduct->product->range('1{1}, 2{1}');
$projectProduct->gen(2);

$pipeline = zendata('pipeline');
$pipeline->id->range('1');
$pipeline->type->range('gitlab');
$pipeline->name->range('gitlab');
$pipeline->url->range('https://gitlab.axb.oop.cc/');
$pipeline->token->range('y2UBqwPPzaLxsniy8R6A');
$pipeline->private->range('6708ece75bde4');
$pipeline->gen(1);
