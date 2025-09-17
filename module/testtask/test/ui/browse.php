#!/usr/bin/env php
<?php

/**
title=检查测试单列表
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/ui/browse.ui.class.php';

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
$project->gen(4);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1, 2, 3, 4');
$projectProduct->product->range('1{4}, 2{4}');
$projectProduct->gen(8);

$build = zenData('build');
$build->id->range('1-100');
$build->project->range('1');
$build->product->range('1{4}, 2{4}');
$build->branch->range('0');
$build->execution->range('2');
$build->name->range('构建1, 构建2, 构建3, 构建4, 构建5, 构建6');
$build->scmPath->range('[]');
$build->filePath->range('[]');
$build->deleted->range('0');
$build->gen(6);

$testtask = zenData('testtask');
$testtask->id->range('1-100');
$testtask->project->range('1');
$testtask->product->range('1, 2');
$testtask->name->range('1-100');
$testtask->execution->range('2');
$testtask->build->range('1{6}, 2{6}');
$testtask->begin->range('(-2D)-(-D):1D')->type('timestamp')->format('YY/MM/DD');
$testtask->end->range('(+D)-(+2D):1D')->type('timestamp')->format('YY/MM/DD');
$testtask->status->range('wait{3}, doing{4}, done{2}, blocked{1}');
$testtask->deleted->range('0');
$testtask->members->range('`admin,USER1,USER2`{2}, `admin`{2}, USER1{2}, []{100}');
$testtask->gen(10);

$user = zenData('user');
$user->id->range('1-100');
$user->dept->range('0, 1{2}, 2{3}, 3{5}');
$user->account->range('admin, user1, user2, user3, user4, user5, user11, user12, user13, user14, user15');
$user->realname->range('admin, USER1, USER2, USER3, USER4, USER5, USER11, USER12, USER13, USER14, USER15');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->gen(2);

$tester = new browseTester();
$tester->login();

/* 单个产品下 */
r($tester->checkNum('total', 5))      && p('status,message') && e('SUCCESS,标签下的测试单数量正确');
r($tester->checkNum('myinvolved', 2)) && p('status,message') && e('SUCCESS,标签下的测试单数量正确');
r($tester->checkNum('wait', 2))       && p('status,message') && e('SUCCESS,标签下的测试单数量正确');
r($tester->checkNum('doing', 2))      && p('status,message') && e('SUCCESS,标签下的测试单数量正确');
r($tester->checkNum('blocked', 0))    && p('status,message') && e('SUCCESS,测试单数量为0');
r($tester->checkNum('done', 1))       && p('status,message') && e('SUCCESS,标签下的测试单数量正确');
/* 所有产品下 */
r($tester->checkNum('total', 10, true))     && p('status,message') && e('SUCCESS,标签下的测试单数量正确');
r($tester->checkNum('myinvolved', 4, true)) && p('status,message') && e('SUCCESS,标签下的测试单数量正确');
r($tester->checkNum('wait', 3, true))       && p('status,message') && e('SUCCESS,标签下的测试单数量正确');
r($tester->checkNum('doing', 4, true))      && p('status,message') && e('SUCCESS,标签下的测试单数量正确');
r($tester->checkNum('blocked', 1, true))    && p('status,message') && e('SUCCESS,标签下的测试单数量正确');
r($tester->checkNum('done', 2, true))       && p('status,message') && e('SUCCESS,标签下的测试单数量正确');
/* 按时间筛选后 */
r($tester->checkNum('total', 0, false, date('Y-m-d', strtotime('+3 days')))) && p('status,message') && e('SUCCESS,测试单数量为0');
$tester->closeBrowser();
