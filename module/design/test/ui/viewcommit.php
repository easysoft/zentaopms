#!/usr/bin/env php
<?php

/**

title=查看设计页数据检查测试
timeout=0
cid=8

- 执行tester模块的viewCommit方法，参数是'1' 测试结果 @查看提交页显示数据正确

*/

chdir(__DIR__);
include '../lib/ui/viewcommit.ui.class.php';

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
$pipeline->name->range('gitlabnew');
$pipeline->url->range('https://gitlabdev.qc.oop.cc');
$pipeline->account->range('root');
$pipeline->password->range('bGFjdTdCSUgwTEljY21tbnZRQUs=');
$pipeline->token->range('glpat-b8Sa1pM9k9ygxMZYPN6w');
$pipeline->private->range('6932a2750a805');
$pipeline->gen(1);

$repo = zendata('repo');
$repo->id->range('1');
$repo->product->range('1');
$repo->name->range('Lproject1');
$repo->path->range('https://gitlabdev.qc.oop.cc/liutao/lproject1');
$repo->encoding->range('utf-8');
$repo->SCM->range('Gitlab');
$repo->serviceHost->range('1');
$repo->serviceProject->range('1106');
$repo->commits->range('0');
$repo->account->range('[]');
$repo->password->range('6936284feb1f2');
$repo->encrypt->range('base64');
$repo->acl->range('{"acl":"open","groups":[""],"users":[""]}');
$repo->synced->range('1');
$repo->extra->range('1106');
$repo->gen(1);

$repohistory = zendata('repohistory');
$repohistory->id->range('1');
$repohistory->repo->range('2');
$repohistory->revision->range('7c2dab4e6ad91fa4105b5a9c369a5c494680b075');
$repohistory->commit->range('1');
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

$tester = new viewCommitTester();
$tester->login();

/* 检查查看提交页数据 */
r($tester->viewCommit('1')) && p('message') && e('查看提交页显示数据正确');

$tester->closeBrowser();
