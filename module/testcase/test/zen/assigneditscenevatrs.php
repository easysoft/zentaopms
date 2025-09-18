#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignEditSceneVars();
timeout=0
cid=0

- 执行testcaseTest模块的assignEditSceneVarsTest方法，参数是$scene1 属性executed @0
- 执行testcaseTest模块的assignEditSceneVarsTest方法，参数是$scene2 属性executed @0
- 执行testcaseTest模块的assignEditSceneVarsTest方法，参数是$scene3 属性executed @0
- 执行testcaseTest模块的assignEditSceneVarsTest方法，参数是$scene4 属性executed @0
- 执行testcaseTest模块的assignEditSceneVarsTest方法，参数是$scene5 属性executed @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

zendata('product')->loadYaml('product_assigneditscenevatrs', false, 2)->gen(5);
zendata('branch')->loadYaml('branch_assigneditscenevatrs', false, 2)->gen(10);
zendata('module')->loadYaml('module_assigneditscenevatrs', false, 2)->gen(15);
zendata('scene')->loadYaml('scene_assigneditscenevatrs', false, 2)->gen(20);

su('admin');

$testcaseTest = new testcaseZenTest();

// 创建测试场景对象
$scene1 = new stdClass();
$scene1->id = 1;
$scene1->product = 1;
$scene1->branch = 0;
$scene1->module = 1;
$scene1->parent = 0;

$scene2 = new stdClass();
$scene2->id = 2;
$scene2->product = 2;
$scene2->branch = 1;
$scene2->module = 2;
$scene2->parent = 1;

$scene3 = new stdClass();
$scene3->id = 3;
$scene3->product = 1;
$scene3->branch = 0;
$scene3->module = 1;
$scene3->parent = 0;

$scene4 = new stdClass();
$scene4->id = 4;
$scene4->product = 1;
$scene4->branch = 0;
$scene4->module = 1;
$scene4->parent = 0;

$scene5 = new stdClass();
$scene5->id = 5;
$scene5->product = 1;
$scene5->branch = 0;
$scene5->module = 1;
$scene5->parent = 0;

r($testcaseTest->assignEditSceneVarsTest($scene1)) && p('executed') && e('0');
r($testcaseTest->assignEditSceneVarsTest($scene2)) && p('executed') && e('0');
r($testcaseTest->assignEditSceneVarsTest($scene3)) && p('executed') && e('0');
r($testcaseTest->assignEditSceneVarsTest($scene4)) && p('executed') && e('0');
r($testcaseTest->assignEditSceneVarsTest($scene5)) && p('executed') && e('0');