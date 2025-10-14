#!/usr/bin/env php
<?php

/**

title=测试 docZen::assignVarsForView();
timeout=0
cid=0

- 步骤1：execution类型且app->tab为project
 - 属性objectType @project
 - 属性objectID @2
- 步骤2：product类型正常情况
 - 属性docID @2
 - 属性type @product
- 步骤3：docID为0的边界值情况属性docID @0
- 步骤4：version为0的边界值情况属性version @0
- 步骤5：libs为空数组的边界值情况属性objectType @mine

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$doc = zenData('doc');
$doc->id->range('1-10');
$doc->lib->range('1-3');
$doc->title->range('测试文档1,测试文档2,测试文档3');
$doc->type->range('html{5},attachment{3},word{2}');
$doc->status->range('normal{8},draft{2}');
$doc->product->range('1-5');
$doc->project->range('1-3');
$doc->execution->range('1-3');
$doc->module->range('0{5},1{3},2{2}');
$doc->gen(10);

$doclib = zenData('doclib');
$doclib->id->range('1-5');
$doclib->name->range('测试文档库1,测试文档库2,API文档库,产品文档库,项目文档库');
$doclib->type->range('mine{1},custom{1},api{1},product{1},project{1}');
$doclib->product->range('0{2},1{2},2{1}');
$doclib->project->range('0{3},1{1},2{1}');
$doclib->execution->range('0{4},1{1}');
$doclib->gen(5);

$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$user->deleted->range('0{9},1{1}');
$user->gen(10);

$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目1,项目2,项目3,项目4,项目5');
$project->type->range('project');
$project->status->range('wait{2},doing{2},done{1}');
$project->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 模拟$this->app->tab设置
global $app;
$app->tab = 'project';

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 创建测试用的对象数据
$testDoc = new stdClass();
$testDoc->id = 1;
$testDoc->title = '测试文档';
$testDoc->module = 1;
$testDoc->product = 1;
$testDoc->project = 1;
$testDoc->execution = 1;

$testObject = new stdClass();
$testObject->id = 1;
$testObject->project = 2;
$testObject->name = '测试执行';

$testLibs = array(
    1 => (object)array('id' => 1, 'name' => '测试库1', 'type' => 'project'),
    2 => (object)array('id' => 2, 'name' => '测试库2', 'type' => 'product')
);

$testObjectDropdown = array(1 => '对象1', 2 => '对象2');

r($docTest->assignVarsForViewTest(1, 1, 'execution', 1, 1, $testDoc, $testObject, 'execution', $testLibs, $testObjectDropdown)) && p('objectType,objectID') && e('project,2'); // 步骤1：execution类型且app->tab为project
r($docTest->assignVarsForViewTest(2, 1, 'product', 2, 2, $testDoc, $testObject, 'product', $testLibs, $testObjectDropdown)) && p('docID,type') && e('2,product'); // 步骤2：product类型正常情况
r($docTest->assignVarsForViewTest(0, 1, 'project', 1, 1, $testDoc, $testObject, 'project', $testLibs, $testObjectDropdown)) && p('docID') && e('0'); // 步骤3：docID为0的边界值情况
r($docTest->assignVarsForViewTest(1, 0, 'custom', 1, 1, $testDoc, $testObject, 'custom', $testLibs, $testObjectDropdown)) && p('version') && e('0'); // 步骤4：version为0的边界值情况
r($docTest->assignVarsForViewTest(1, 1, 'mine', 1, 1, $testDoc, $testObject, 'mine', array(), $testObjectDropdown)) && p('objectType') && e('mine'); // 步骤5：libs为空数组的边界值情况