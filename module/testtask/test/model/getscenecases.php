#!/usr/bin/env php
<?php

/**

title=测试 testtaskModel::getSceneCases();
timeout=0
cid=19194

- 步骤1：四级场景下的用例应包含全部父场景 @scene-1,scene-2,scene-3,scene-4
- 步骤2：一级场景的 parent 为 0 @0
- 步骤3：二级场景的 parent 为一级场景 @scene-1
- 步骤4：无场景用例不返回场景 @0
- 步骤5：空runs不返回场景 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$scene = zenData('scene');
$scene->id->range('1-5');
$scene->product->range('1{4},2{1}');
$scene->title->range('一级场景,二级场景,三级场景,四级场景,其他产品场景');
$scene->parent->range('0,1,2,3,0');
$scene->grade->range('1,2,3,4,1');
$scene->path->range('`,1,`,`,1,2,`,`,1,2,3,`,`,1,2,3,4,`,`,5,`');
$scene->sort->range('1-5');
$scene->deleted->range('0');
$scene->gen(5);

su('admin');

$testtaskTest = new testtaskModelTest();

$runOnLevel4 = new stdclass();
$runOnLevel4->scene = 4;
$runOnLevel4->id    = 1;

$runWithoutScene = new stdclass();
$runWithoutScene->scene = 0;
$runWithoutScene->id    = 2;

r($testtaskTest->getSceneCasesTest(1, array($runOnLevel4), 'sceneIds')) && p() && e('scene-1,scene-2,scene-3,scene-4'); // 步骤1：四级场景下的用例应包含全部父场景
r($testtaskTest->getSceneCasesTest(1, array($runOnLevel4), 'sceneParents')) && p('scene-1') && e('0'); // 步骤2：一级场景的 parent 为 0
r($testtaskTest->getSceneCasesTest(1, array($runOnLevel4), 'sceneParents')) && p('scene-2') && e('scene-1'); // 步骤3：二级场景的 parent 为一级场景
r($testtaskTest->getSceneCasesTest(1, array($runWithoutScene), 'sceneIds')) && p() && e('0'); // 步骤4：无场景用例不返回场景
r($testtaskTest->getSceneCasesTest(1, array(), 'sceneIds')) && p() && e('0'); // 步骤5：空runs不返回场景
