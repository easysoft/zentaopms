#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/viewcommit.ui.class.php';

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

$repo = zendata('repo');
$repo->id->range('1');
$repo->product->range('1');
$repo->name->range('Lproject 01');
$repo->path->range('http://gitlab.axb.oop.cc/liutao/lproject-01');
$repo->encoding->range('utf-8');
$repo->SCM->range('Gitlab');
$repo->serviceHost->range('1');
$repo->serviceProject->range('952');
$repo->commits->range('31');
$repo->account->range('[]');
$repo->password->range('6708d3ae5d71a');
$repo->encrypt->range('base64');
$repo->synced->range('1');
$repo->extra->range('952');
$repo->gen(1);
