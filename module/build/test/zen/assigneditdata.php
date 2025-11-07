#!/usr/bin/env php
<?php

/**

title=测试 buildZen::assignEditData();
timeout=0
cid=0

- 执行buildTest模块的assignEditDataTest方法，参数是$build1 第products条的1属性 @Product1
- 执行buildTest模块的assignEditDataTest方法，参数是$build2 属性executionType @0
- 执行buildTest模块的assignEditDataTest方法，参数是$build3 第products条的4属性 @ProductBranch1
- 执行buildTest模块的assignEditDataTest方法，参数是$build4 第product条的name属性 @Product6
- 执行buildTest模块的assignEditDataTest方法，参数是$build5 属性executionType @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('Product1,Product2,Product3,ProductBranch1,ProductBranch2,Product6{5}');
$product->type->range('normal{6},branch{4}');
$product->status->range('normal{10}');
$product->acl->range('open{6},private{2},custom{2}');
$product->deleted->range('0');
$product->gen(10);

$project = zenData('project');
$project->id->range('1-10');
$project->name->range('Project{10}');
$project->type->range('project{10}');
$project->status->range('wait{2},doing{5},done{3}');
$project->model->range('scrum{5},waterfall{3},kanban{2}');
$project->hasProduct->range('1{8},0{2}');
$project->parent->range('0');
$project->path->range('`,1,`,`,2,`,`,3,`,`,4,`,`,5,`,`,6,`,`,7,`,`,8,`,`,9,`,`,10,`');
$project->grade->range('1');
$project->deleted->range('0');
$project->gen(10);

$branch = zenData('branch');
$branch->id->range('1-10');
$branch->product->range('1-5');
$branch->name->range('主干,分支一,分支二,分支三,分支四{6}');
$branch->status->range('active{8},closed{2}');
$branch->deleted->range('0');
$branch->gen(10);

$execution = zenData('project');
$execution->id->range('11-20');
$execution->project->range('1-3');
$execution->name->range('Sprint1,Sprint2,Sprint3,迭代1,迭代2{6}');
$execution->type->range('sprint{5},stage{5}');
$execution->status->range('wait{2},doing{5},done{3}');
$execution->parent->range('1-3');
$execution->path->range('`,1,11,`,`,1,12,`,`,2,13,`,`,2,14,`,`,3,15,`,`,3,16,`,`,1,17,`,`,2,18,`,`,3,19,`,`,1,20,`');
$execution->grade->range('2');
$execution->deleted->range('0');
$execution->gen(10);

$build = zenData('build');
$build->id->range('1-10');
$build->project->range('1-3');
$build->product->range('1-5');
$build->branch->range('0,1,2,3');
$build->execution->range('0,1,2,3');
$build->builds->range('[]');
$build->system->range('0-2');
$build->name->range('Build{10}');
$build->builder->range('admin,user1,user2');
$build->deleted->range('0');
$build->gen(10);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-20');
$projectProduct->product->range('1-5');
$projectProduct->gen(20);

$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,user3,user4');
$user->realname->range('管理员,用户1,用户2,用户3,用户4');
$user->deleted->range('0');
$user->gen(5);

global $tester;
$tester->app->rawModule   = 'build';
$tester->app->rawMethod   = 'edit';
$tester->app->tab         = 'project';
$tester->app->moduleName  = 'build';
$tester->app->methodName  = 'edit';
$_SESSION['project']      = 1;

su('admin');

$buildTest = new buildZenTest();

$build1 = new stdclass();
$build1->id = 1;
$build1->project = 1;
$build1->product = 1;
$build1->branch = '0';
$build1->execution = 11;
$build1->builds = '';
$build1->system = 0;
$build1->name = 'Build1';

$build2 = new stdclass();
$build2->id = 2;
$build2->project = 1;
$build2->product = 2;
$build2->branch = '0';
$build2->execution = 0;
$build2->builds = '1';
$build2->system = 1;
$build2->name = 'Build2';

$build3 = new stdclass();
$build3->id = 3;
$build3->project = 2;
$build3->product = 4;
$build3->branch = '1,2';
$build3->execution = 13;
$build3->builds = '';
$build3->system = 0;
$build3->name = 'Build3';

$build4 = new stdclass();
$build4->id = 4;
$build4->project = 1;
$build4->product = 6;
$build4->branch = '0';
$build4->execution = 11;
$build4->builds = '';
$build4->system = 0;
$build4->name = 'Build4';

$build5 = new stdclass();
$build5->id = 5;
$build5->project = 1;
$build5->product = 1;
$build5->branch = '0';
$build5->execution = 21;
$build5->builds = '';
$build5->system = 0;
$build5->name = 'Build5';

r($buildTest->assignEditDataTest($build1)) && p('products:1') && e('Product1');
r($buildTest->assignEditDataTest($build2)) && p('executionType') && e('0');
r($buildTest->assignEditDataTest($build3)) && p('products:4') && e('ProductBranch1');
r($buildTest->assignEditDataTest($build4)) && p('product:name') && e('Product6');
r($buildTest->assignEditDataTest($build5)) && p('executionType') && e('0');