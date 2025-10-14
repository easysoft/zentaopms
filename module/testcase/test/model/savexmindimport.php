#!/usr/bin/env php
<?php

/**

title=测试 testcaseModel::saveXmindImport();
timeout=0
cid=0

- 步骤1：场景和用例数据导入失败测试属性result @fail
- 步骤2：空场景列表导入失败测试属性result @fail
- 步骤3：空用例列表导入成功测试属性result @success
- 步骤4：空场景名称导入测试属性result @success
- 步骤5：无效产品ID场景导入测试属性result @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->type->range('normal{5}');
$product->status->range('normal{5}');
$product->gen(5);

$scene = zenData('scene');
$scene->id->range('1-10');
$scene->product->range('1-5');
$scene->title->range('场景1,场景2,场景3,场景4,场景5');
$scene->parent->range('0{5},1-5');
$scene->grade->range('1{5},2{5}');
$scene->path->range(',1,,2,,3,,4,,5,');
$scene->gen(10);

$case = zenData('case');
$case->id->range('1-20');
$case->product->range('1-5');
$case->title->range('测试用例1,测试用例2,测试用例3,测试用例4,测试用例5');
$case->type->range('feature{20}');
$case->status->range('normal{20}');
$case->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->saveXmindImportTest(array(array('tmpId' => 'scene1', 'tmpPId' => '', 'name' => '测试场景1', 'product' => 1, 'branch' => 0)), array((object)array('tmpPId' => 'scene1', 'title' => '测试用例1', 'product' => 1, 'module' => 0, 'type' => 'feature', 'pri' => 3, 'status' => 'normal', 'stage' => 'unittest', 'story' => 0)))) && p('result') && e('fail'); // 步骤1：场景和用例数据导入失败测试
r($testcaseTest->saveXmindImportTest(array(), array((object)array('tmpPId' => '', 'title' => '测试用例2', 'product' => 1, 'module' => 0, 'type' => 'feature', 'pri' => 3, 'status' => 'normal', 'stage' => 'unittest', 'story' => 0)))) && p('result') && e('fail'); // 步骤2：空场景列表导入失败测试
r($testcaseTest->saveXmindImportTest(array(array('tmpId' => 'scene2', 'tmpPId' => '', 'name' => '测试场景2', 'product' => 1, 'branch' => 0)), array())) && p('result') && e('success'); // 步骤3：空用例列表导入成功测试
r($testcaseTest->saveXmindImportTest(array(array('tmpId' => 'scene3', 'tmpPId' => '', 'name' => '', 'product' => 1, 'branch' => 0)), array())) && p('result') && e('success'); // 步骤4：空场景名称导入测试
r($testcaseTest->saveXmindImportTest(array(array('tmpId' => 'scene4', 'tmpPId' => '', 'name' => '测试场景4', 'product' => 999, 'branch' => 0)), array())) && p('result') && e('success'); // 步骤5：无效产品ID场景导入测试