#!/usr/bin/env php
<?php

/**

title=关联设计检查测试
timeout=0

- 执行tester模块的linkCommit方法，参数是'3' 测试结果 @关联提交成功

*/

chdir(__DIR__);
include '../lib/ui/linkcommit.ui.class.php';

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

$repohistory = zendata('repohistory');
$repohistory->id->range('1');
$repohistory->repo->range('1');
$repohistory->revision->range('b59be0c9604497ae26c621cc848a738cca73fc85');
$repohistory->commit->range('31');
$repohistory->gen(1);

$design = zendata('design');
$design->id->range('1-4');
$design->project->range('1{4}');
$design->product->range('1{2}, 2{2}');
$design->commit->range('1, []{3}');
$design->commitedBy->range('admin');
$design->name->range('概要设计1, 详细设计1, 数据库设计1, 接口设计1');
$design->type->range('HLDS, DDS, DBDS, ADS');
$design->gen(4);

$tester = new linkCommitTester();
$tester->login();

$design = array(
    array('begin' => '2024-10-01', 'end' => '2025-02-18'),
);

/* 检查关联提交 */
r($tester->linkCommit('3', $design['0'])) && p('message') && e('关联提交成功');

$tester->closeBrowser();
