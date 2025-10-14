#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignCreateSceneVars();
timeout=0
cid=0

- 执行testcaseTest模块的assignCreateSceneVarsTest方法，参数是1 第product条的name属性 @产品1
- 执行testcaseTest模块的assignCreateSceneVarsTest方法，参数是1, '1' 属性branch @1
- 执行testcaseTest模块的assignCreateSceneVarsTest方法，参数是1, '', 5 属性moduleID @5
- 执行testcaseTest模块的assignCreateSceneVarsTest方法，参数是999, '', 0 属性moduleID @0
- 执行testcaseTest模块的assignCreateSceneVarsTest方法，参数是2, '', 0 第product条的name属性 @产品2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

$productTable = zenData('product');
$productTable->id->range('1-10');
$productTable->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$productTable->type->range('normal{5},branch{5}');
$productTable->status->range('normal{8},closed{2}');
$productTable->deleted->range('0{8},1{2}');
$productTable->gen(10);

$branchTable = zenData('branch');
$branchTable->id->range('1-5');
$branchTable->product->range('6-10');
$branchTable->name->range('主分支,开发分支,测试分支,发布分支,紧急分支');
$branchTable->status->range('active{4},closed{1}');
$branchTable->deleted->range('0');
$branchTable->gen(5);

$moduleTable = zenData('module');
$moduleTable->id->range('1-10');
$moduleTable->root->range('1-10');
$moduleTable->name->range('模块1,模块2,模块3,模块4,模块5,模块6,模块7,模块8,模块9,模块10');
$moduleTable->type->range('case{10}');
$moduleTable->deleted->range('0{8},1{2}');
$moduleTable->gen(10);

$sceneTable = zenData('scene');
$sceneTable->id->range('1-5');
$sceneTable->product->range('1-5');
$sceneTable->module->range('1-5');
$sceneTable->title->range('场景1,场景2,场景3,场景4,场景5');
$sceneTable->deleted->range('0');
$sceneTable->gen(5);

su('admin');

$testcaseTest = new testcaseTest();

r($testcaseTest->assignCreateSceneVarsTest(1)) && p('product:name') && e('产品1');
r($testcaseTest->assignCreateSceneVarsTest(1, '1')) && p('branch') && e('1');
r($testcaseTest->assignCreateSceneVarsTest(1, '', 5)) && p('moduleID') && e(5);
r($testcaseTest->assignCreateSceneVarsTest(999, '', 0)) && p('moduleID') && e(0);
r($testcaseTest->assignCreateSceneVarsTest(2, '', 0)) && p('product:name') && e('产品2');