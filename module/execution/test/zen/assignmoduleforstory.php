#!/usr/bin/env php
<?php

/**

title=测试 executionZen::assignModuleForStory();
timeout=0
cid=16405

- 执行executionTest模块的assignModuleForStoryTest方法，参数是'bymodule', 1, 'story', 1, 1 
 - 属性moduleID @1
 - 属性view_module @1
- 执行executionTest模块的assignModuleForStoryTest方法，参数是'byproduct', 1, 'story', 2, 2 
 - 属性moduleID @0
 - 属性view_module @0
- 执行executionTest模块的assignModuleForStoryTest方法，参数是'bymodule', 2, 'requirement', 3, 1 
 - 属性moduleID @2
 - 属性view_module @1
- 执行executionTest模块的assignModuleForStoryTest方法，参数是'byproduct', 2, 'story', 4, 2 
 - 属性moduleID @0
 - 属性view_module @0
- 执行executionTest模块的assignModuleForStoryTest方法，参数是'bymodule', 3, 'story', 5, 3 
 - 属性moduleID @3
 - 属性view_module @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

$table = zenData('module');
$table->id->range('1-10');
$table->root->range('1-3');
$table->name->range('模块1,模块2,模块3,模块4,模块5,模块6,模块7,模块8,模块9,模块10');
$table->type->range('story{5},requirement{5}');
$table->parent->range('0{3},1{2},2{2},3{3}');
$table->grade->range('1{3},2{4},3{3}');
$table->deleted->range('0{8},1{2}');
$table->gen(10);

$table = zenData('project');
$table->id->range('1-5');
$table->name->range('执行1,执行2,执行3,执行4,执行5');
$table->type->range('sprint{3},stage{2}');
$table->parent->range('0{2},1{3}');
$table->hasProduct->range('1{3},0{2}');
$table->multiple->range('1{3},0{2}');
$table->deleted->range('0');
$table->gen(5);

$table = zenData('product');
$table->id->range('1-3');
$table->name->range('产品1,产品2,产品3');
$table->type->range('normal{2},branch{1}');
$table->deleted->range('0');
$table->gen(3);

$table = zenData('projectproduct');
$table->project->range('1-5');
$table->product->range('1-3');
$table->gen(5);

su('admin');

$executionTest = new executionZenTest();

r($executionTest->assignModuleForStoryTest('bymodule', 1, 'story', 1, 1)) && p('moduleID,view_module') && e('1,1');
r($executionTest->assignModuleForStoryTest('byproduct', 1, 'story', 2, 2)) && p('moduleID,view_module') && e('0,0');
r($executionTest->assignModuleForStoryTest('bymodule', 2, 'requirement', 3, 1)) && p('moduleID,view_module') && e('2,1');
r($executionTest->assignModuleForStoryTest('byproduct', 2, 'story', 4, 2)) && p('moduleID,view_module') && e('0,0');
r($executionTest->assignModuleForStoryTest('bymodule', 3, 'story', 5, 3)) && p('moduleID,view_module') && e('3,1');