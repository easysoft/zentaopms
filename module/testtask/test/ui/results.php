#!/usr/bin/env php
<?php

/**
title=检查测试单下的分组视图
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/ui/results.ui.class.php';

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
$build->product->range('1');
$build->branch->range('0');
$build->execution->range('2{4}, 3{2}');
$build->name->range('构建1, 构建2, 构建3, 构建4, 构建5, 构建6');
$build->scmPath->range('[]');
$build->filePath->range('[]');
$build->deleted->range('0');
$build->gen(1);

$testtask = zenData('testtask');
$testtask->id->range('1-100');
$testtask->project->range('1');
$testtask->product->range('1');
$testtask->name->range('测试单1, 测试单2, 测试单3, 测试单4, 测试单5, 测试单6');
$testtask->execution->range('2{4}, 3{2}');
$testtask->build->range('1-6');
$testtask->begin->range('(-2D)-(-D):1D')->type('timestamp')->format('YY/MM/DD');
$testtask->end->range('(+D)-(+2D):1D')->type('timestamp')->format('YY/MM/DD');
$testtask->status->range('wait{5}, doing{5}, done{3}, blocked{2}');
$testtask->deleted->range('0');
$testtask->gen(1);

$case = zenData('case');
$case->id->range('1-100');
$case->project->range('1{2}, 0{100}');
$case->product->range('1{10}, 2{5}');
$case->execution->range('0{5}, 2{10}');
$case->story->range('1{2}, 2{3}, 0{100}');
$case->title->range('1-100');
$case->stage->range('feature');
$case->status->range('normal,blocked,investigate,normal{100}');
$case->deleted->range('0{14}, 1');
$case->gen(15);

$casestep = zenData('casestep');
$casestep->id->range('1-100');
$casestep->parent->range('0');
$casestep->case->range('2');
$casestep->version->range('1');
$casestep->type->range('step');
$casestep->desc->range('1');
$casestep->expect->range('1');
$casestep->gen(1);

$casespec = zenData('casespec');
$casespec->id->range('1-100');
$casespec->case->range('1-100');
$casespec->version->range('1');
$casespec->title->range('1-100');
$casespec->gen(15);

$testrun = zenData('testrun');
$testrun->id->range('1-100');
$testrun->task->range('1');
$testrun->case->range('1-100');
$testrun->version->range('1');
$testrun->lastRunner->range('[]');
$testrun->lastRunResult->range('[]');
$testrun->status->range('normal');
$testrun->gen(2);

$testresult = zenData('testresult');
$testresult->id->range('1-100');
$testresult->run->range('1, 2{100}');
$testresult->case->range('1, 2{100}');
$testresult->version->range('1');
$testresult->caseResult->range('pass{3}, fail, blocked');
$testresult->stepResults->range('`a:1:{i:0;a:2:{s:6:"result";s:4:"pass";s:4:"real";s:0:"";}}`, `a:1:{i:1;a:2:{s:6:"result";s:3:"n/a";s:4:"real";s:0:"";}}`, `a:1:{i:1;a:2:{s:6:"result";s:4:"pass";s:4:"real";s:0:"";}}`, `a:1:{i:1;a:2:{s:6:"result";s:4:"fail";s:4:"real";s:3:"aaa";}}`, `a:1:{i:1;a:2:{s:6:"result";s:7:"blocked";s:4:"real";s:3:"bbb";}}`');
$testresult->lastRunner->range('user1');
$testresult->gen(5);

$user = zenData('user');
$user->id->range('1-100');
$user->dept->range('0, 1{2}, 2{3}, 3{5}');
$user->account->range('admin, user1, user2, user3, user4, user5, user11, user12, user13, user14, user15');
$user->realname->range('admin, USER1, USER2, USER3, USER4, USER5, USER11, USER12, USER13, USER14, USER15');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->gen(5);

$tester = new resultsTester();
$tester->login();

$resulta = array('name' => '测试单1', 'build' => '构建1', 'user' => 'USER1', 'resultcn' => '阻塞', 'resulten' => 'Blocked', 'sResultcn' => '阻塞', 'sResulten' => 'Blocked', 'stepReal' => 'bbb');
$resultb = array('name' => '测试单1', 'build' => '构建1', 'user' => 'USER1', 'resultcn' => '失败', 'resulten' => 'Fail', 'sResultcn' => '失败', 'sResulten' => 'Fail', 'stepReal' => 'aaa');
$resultc = array('name' => '测试单1', 'build' => '构建1', 'user' => 'USER1', 'resultcn' => '通过', 'resulten' => 'Pass', 'sResultcn' => '通过', 'sResulten' => 'Pass', 'stepReal' => '');
$resultd = array('name' => '测试单1', 'build' => '构建1', 'user' => 'USER1', 'resultcn' => '通过', 'resulten' => 'Pass', 'sResultcn' => '忽略', 'sResulten' => 'Ignore', 'stepReal' => '');

r($tester->checkResults(1, $resulta)) && p('status,message') && e('SUCCESS,测试结果正确');
r($tester->checkResults(2, $resultb)) && p('status,message') && e('SUCCESS,测试结果正确');
r($tester->checkResults(3, $resultc)) && p('status,message') && e('SUCCESS,测试结果正确');
r($tester->checkResults(4, $resultd)) && p('status,message') && e('SUCCESS,测试结果正确');
$tester->closeBrowser();
