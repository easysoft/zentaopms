#!/usr/bin/env php
<?php

/**

title=测试 docZen::setObjectsForEdit();
timeout=0
cid=16225

- 执行docTest模块的setObjectsForEditTest方法，参数是'project', 1 属性hasObjects @1
- 执行docTest模块的setObjectsForEditTest方法，参数是'execution', 6 属性hasObjects @1
- 执行docTest模块的setObjectsForEditTest方法，参数是'execution', 7 属性hasObjects @1
- 执行docTest模块的setObjectsForEditTest方法，参数是'product', 1
 - 属性hasObjects @1
 - 属性objectsCount @10
- 执行docTest模块的setObjectsForEditTest方法，参数是'mine', 0
 - 属性hasObjects @0
 - 属性hasAclList @1
- 执行docTest模块的setObjectsForEditTest方法，参数是'', 1
 - 属性hasObjects @0
 - 属性objectsCount @0
- 执行docTest模块的setObjectsForEditTest方法，参数是'custom', 1
 - 属性hasObjects @0
 - 属性objectsCount @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$project = zenData('project');
$project->id->range('1-20');
$project->project->range('0{5},1{5},2{5},3{5}');
$project->name->range('项目1,项目2,项目3,项目4,项目5,Sprint1-1{3},Sprint1-2{2},Sprint2-1{3},Sprint2-2{2},阶段A{3},阶段B{2}');
$project->type->range('project{5},sprint{10},stage{5}');
$project->status->range('wait{3},doing{12},done{5}');
$project->grade->range('1{5},2{15}');
$project->parent->range('0{5},1{3},2{2},2{3},3{2},3{3},4{2}');
$project->deleted->range('0');
$project->gen(20);

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品A,产品B,产品C,产品D,产品E,测试产品{5}');
$product->status->range('normal{8},closed{2}');
$product->deleted->range('0');
$product->gen(10);

su('admin');

$docTest = new docZenTest();

r($docTest->setObjectsForEditTest('project', 1)) && p('hasObjects') && e('1');
r($docTest->setObjectsForEditTest('execution', 6)) && p('hasObjects') && e('1');
r($docTest->setObjectsForEditTest('execution', 7)) && p('hasObjects') && e('1');
r($docTest->setObjectsForEditTest('product', 1)) && p('hasObjects,objectsCount') && e('1,10');
r($docTest->setObjectsForEditTest('mine', 0)) && p('hasObjects,hasAclList') && e('0,1');
r($docTest->setObjectsForEditTest('', 1)) && p('hasObjects,objectsCount') && e('0,0');
r($docTest->setObjectsForEditTest('custom', 1)) && p('hasObjects,objectsCount') && e('0,0');