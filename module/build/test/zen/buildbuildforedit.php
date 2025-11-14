#!/usr/bin/env php
<?php

/**

title=测试 buildZen::buildBuildForEdit();
timeout=0
cid=15519

- 执行buildTest模块的buildBuildForEditTest方法，参数是1 属性name @Build1
- 执行buildTest模块的buildBuildForEditTest方法，参数是2 属性name @Build2
- 执行buildTest模块的buildBuildForEditTest方法，参数是3 属性name @Build3
- 执行buildTest模块的buildBuildForEditTest方法，参数是4 属性product @1
- 执行buildTest模块的buildBuildForEditTest方法，参数是5 属性builder @admin

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$build = zenData('build');
$build->id->range('1-10');
$build->project->range('1-3');
$build->product->range('1-5');
$build->branch->range('0,1,2,3');
$build->execution->range('0,11,12,13,0{6}');
$build->builds->range('[]');
$build->system->range('0-2');
$build->name->range('Build{10}');
$build->builder->range('admin,user1,user2');
$build->deleted->range('0');
$build->gen(10);

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('Product{10}');
$product->type->range('normal{10}');
$product->status->range('normal{10}');
$product->deleted->range('0');
$product->gen(10);

$project = zenData('project');
$project->id->range('1-10');
$project->name->range('Project{10}');
$project->type->range('project{10}');
$project->status->range('wait{2},doing{5},done{3}');
$project->parent->range('0');
$project->path->range('`,1,`,`,2,`,`,3,`,`,4,`,`,5,`,`,6,`,`,7,`,`,8,`,`,9,`,`,10,`');
$project->grade->range('1');
$project->deleted->range('0');
$project->gen(10);

$execution = zenData('project');
$execution->id->range('11-20');
$execution->project->range('1-3');
$execution->name->range('Sprint{10}');
$execution->type->range('sprint{10}');
$execution->status->range('wait{2},doing{5},done{3}');
$execution->parent->range('1-3');
$execution->path->range('`,1,11,`,`,1,12,`,`,2,13,`,`,2,14,`,`,3,15,`,`,3,16,`,`,1,17,`,`,2,18,`,`,3,19,`,`,1,20,`');
$execution->grade->range('2');
$execution->deleted->range('0');
$execution->gen(10);

global $tester;
$tester->app->rawModule   = 'build';
$tester->app->rawMethod   = 'edit';
$tester->app->tab         = 'project';
$tester->app->moduleName  = 'build';
$tester->app->methodName  = 'edit';
$_SESSION['project']      = 1;

su('admin');

$buildTest = new buildZenTest();

$_POST = array('name' => 'Build1', 'product' => '1', 'execution' => '0', 'builder' => 'admin', 'date' => '2024-01-01');
r($buildTest->buildBuildForEditTest(1)) && p('name') && e('Build1');
$_POST = array('name' => 'Build2', 'product' => '2', 'execution' => '11', 'builder' => 'user1', 'date' => '2024-01-02');
r($buildTest->buildBuildForEditTest(2)) && p('name') && e('Build2');
$_POST = array('name' => 'Build3', 'product' => '3', 'execution' => '12', 'builder' => 'user2', 'date' => '2024-01-03');
r($buildTest->buildBuildForEditTest(3)) && p('name') && e('Build3');
$_POST = array('name' => 'Build4', 'product' => '1', 'execution' => '13', 'builder' => 'admin', 'date' => '2024-01-04');
r($buildTest->buildBuildForEditTest(4)) && p('product') && e('1');
$_POST = array('name' => 'Build5', 'product' => '2', 'execution' => '0', 'builder' => 'admin', 'date' => '2024-01-05');
r($buildTest->buildBuildForEditTest(5)) && p('builder') && e('admin');