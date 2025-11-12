#!/usr/bin/env php
<?php

/**

title=测试 buildZen::buildLinkStorySearchForm();
timeout=0
cid=0

- 执行buildTest模块的buildLinkStorySearchFormTest方法，参数是$build1, 1, 'normal'
 - 属性queryID @1
 - 属性style @simple
 - 属性hasProductField @0
 - 属性hasProjectField @0
 - 属性hasBranchField @0
- 执行buildTest模块的buildLinkStorySearchFormTest方法，参数是$build2, 2, 'branch'
 - 属性queryID @2
 - 属性style @simple
 - 属性hasProductField @0
 - 属性hasProjectField @0
 - 属性hasBranchField @1
- 执行buildTest模块的buildLinkStorySearchFormTest方法，参数是$build3, 3, 'normal'
 - 属性queryID @3
 - 属性style @simple
 - 属性hasProductField @0
 - 属性hasProjectField @0
- 执行buildTest模块的buildLinkStorySearchFormTest方法，参数是$build4, 4, 'normal'
 - 属性queryID @4
 - 属性style @simple
 - 属性hasPlanField @0
 - 属性hasProductField @0
 - 属性hasProjectField @0
- 执行buildTest模块的buildLinkStorySearchFormTest方法，参数是$build5, 5, 'branch'
 - 属性queryID @5
 - 属性style @simple
 - 属性hasBranchField @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$build = zenData('build');
$build->id->range('1-10');
$build->project->range('11,11,13,13,15,11,13,15,11,13');
$build->product->range('1,2,3,1,2,3,1,2,3,1');
$build->branch->range('0,1,2,1,1,0,1,2,3,0');
$build->execution->range('12,12,14,14,16,12,14,16,12,14');
$build->name->range('Build1,Build2,Build3,Build4,Build5,Build6,Build7,Build8,Build9,Build10');
$build->deleted->range('0');
$build->gen(10);

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('Product1,Product2,Product3,Product4,Product5,Product6,Product7,Product8,Product9,Product10');
$product->type->range('normal,branch,platform,normal,branch,platform,normal,branch,platform,normal');
$product->status->range('normal');
$product->deleted->range('0');
$product->gen(10);

$project = zenData('project');
$project->id->range('11-20');
$project->project->range('0,11,0,13,0,11,13,0,11,13');
$project->name->range('Project1,Sprint1,Project2,Sprint2,Project3,Project4,Sprint4,Project5,Sprint5,Project6');
$project->type->range('project,sprint,project,sprint,project,project,sprint,project,sprint,project');
$project->parent->range('0,11,0,13,0,0,13,0,11,13');
$project->path->range('`,11,`,`,11,12,`,`,13,`,`,13,14,`,`,15,`,`,16,`,`,17,`,`,18,`,`,19,`,`,20,`');
$project->grade->range('1,2,1,2,1,1,2,1,2,2');
$project->model->range('scrum,scrum,kanban,kanban,waterfall,scrum,kanban,waterfall,scrum,kanban');
$project->status->range('doing');
$project->hasProduct->range('1,1,0,1,0,0,0,1,1,1');
$project->multiple->range('1,1,0,1,0,1,1,1,1,0');
$project->deleted->range('0');
$project->gen(10);

$branch = zenData('branch');
$branch->id->range('1-10');
$branch->product->range('2,3,2,6,6,8,8,9,9,3');
$branch->name->range('Branch1,Branch2,Branch3,Branch4,Branch5,Branch6,Branch7,Branch8,Branch9,Branch10');
$branch->deleted->range('0');
$branch->gen(10);

$productplan = zenData('productplan');
$productplan->id->range('1-10');
$productplan->product->range('1,2,3,1,2,3,1,2,3,1');
$productplan->branch->range('0,1,2,0,1,2,0,1,2,0');
$productplan->title->range('Plan1,Plan2,Plan3,Plan4,Plan5,Plan6,Plan7,Plan8,Plan9,Plan10');
$productplan->deleted->range('0');
$productplan->gen(10);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('11-20');
$projectProduct->product->range('1,2,3,1,2,3,1,2,3,1');
$projectProduct->gen(10);

$module = zenData('module');
$module->id->range('1-30');
$module->root->range('1-10');
$module->branch->range('0,1,2,3');
$module->type->range('story,bug');
$module->name->range('模块1,模块2,模块3,模块4,模块5');
$module->deleted->range('0');
$module->gen(30);

zenData('user')->gen(0);

global $tester;
$tester->app->rawModule   = 'build';
$tester->app->rawMethod   = 'view';
$tester->app->tab         = 'project';
$tester->app->moduleName  = 'build';
$tester->app->methodName  = 'view';
$_SESSION['project']      = 11;

su('admin');

$buildTest = new buildZenTest();

$build1 = $tester->loadModel('build')->getById(1);
r($buildTest->buildLinkStorySearchFormTest($build1, 1, 'normal')) && p('queryID,style,hasProductField,hasProjectField,hasBranchField') && e('1,simple,0,0,0');

$build2 = $tester->loadModel('build')->getById(2);
r($buildTest->buildLinkStorySearchFormTest($build2, 2, 'branch')) && p('queryID,style,hasProductField,hasProjectField,hasBranchField') && e('2,simple,0,0,1');

$build3 = $tester->loadModel('build')->getById(3);
r($buildTest->buildLinkStorySearchFormTest($build3, 3, 'normal')) && p('queryID,style,hasProductField,hasProjectField') && e('3,simple,0,0');

$build4 = $tester->loadModel('build')->getById(4);
r($buildTest->buildLinkStorySearchFormTest($build4, 4, 'normal')) && p('queryID,style,hasPlanField,hasProductField,hasProjectField') && e('4,simple,0,0,0');

$build5 = $tester->loadModel('build')->getById(5);
r($buildTest->buildLinkStorySearchFormTest($build5, 5, 'branch')) && p('queryID,style,hasBranchField') && e('5,simple,1');