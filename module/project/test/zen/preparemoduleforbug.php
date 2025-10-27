#!/usr/bin/env php
<?php

/**

title=测试 projectZen::prepareModuleForBug();
timeout=0
cid=0

- 步骤1：项目有多个产品且productID为0
 - 属性moduleTree @1
 - 属性modules @9
 - 属性moduleID @0
- 步骤2：项目有单个产品且指定模块
 - 属性moduleID @3
 - 属性moduleName @模块C
- 步骤3：项目无产品
 - 属性moduleTree @0
 - 属性modules @9
 - 属性moduleID @0
- 步骤4：搜索类型参数
 - 属性moduleID @0
 - 属性moduleName @所有模块
- 步骤5：指定模块参数
 - 属性moduleID @2
 - 属性moduleName @模块B

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('module');
$table->id->range('1-10');
$table->root->range('1-3');
$table->branch->range('0');
$table->name->range('模块A,模块B,模块C,模块D,模块E,子模块1,子模块2,子模块3,子模块4,子模块5');
$table->parent->range('0,0,0,1,1,2,2,3,3,4');
$table->path->range(',1,,2,,1,2,,1,2,,2,3,,1,2,3,,1,2,4,,2,5,,2,6,,1,2,3,7,,1,2,3,8,,1,2,4,9,');
$table->grade->range('1,1,1,2,2,2,2,3,3,3');
$table->order->range('10,20,30,10,20,10,20,10,20,10');
$table->type->range('bug{10}');
$table->deleted->range('0{8},1{2}');
$table->gen(10);

$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('产品A,产品B,产品C,产品D,产品E');
$productTable->type->range('normal{3},branch{2}');
$productTable->status->range('normal{4},closed{1}');
$productTable->deleted->range('0{4},1{1}');
$productTable->gen(5);

$projectTable = zenData('project');
$projectTable->id->range('1-5');
$projectTable->name->range('项目A,项目B,项目C,项目D,项目E');
$projectTable->type->range('project{5}');
$projectTable->status->range('wait,doing,suspended,closed,doing');
$projectTable->hasProduct->range('1{4},0{1}');
$projectTable->multiple->range('1,0,1,1,0');
$projectTable->deleted->range('0{4},1{1}');
$projectTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectzenTest = new projectzenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($projectzenTest->prepareModuleForBugTest(0, 1, 'all', 0, 'id_desc', 0, '0', array((object)array('id' => 1, 'name' => '产品A'), (object)array('id' => 2, 'name' => '产品B')))) && p('moduleTree,modules,moduleID') && e('1,9,0'); // 步骤1：项目有多个产品且productID为0
r($projectzenTest->prepareModuleForBugTest(1, 2, 'bymodule', 3, 'id_asc', 1, '0', array((object)array('id' => 1, 'name' => '产品A')))) && p('moduleID,moduleName') && e('3,模块C'); // 步骤2：项目有单个产品且指定模块
r($projectzenTest->prepareModuleForBugTest(0, 5, 'all', 0, 'id_desc', 0, '0', array())) && p('moduleTree,modules,moduleID') && e('0,9,0'); // 步骤3：项目无产品
r($projectzenTest->prepareModuleForBugTest(2, 3, 'bysearch', 5, 'order_asc', 2, '1', array((object)array('id' => 2, 'name' => '产品B')))) && p('moduleID,moduleName') && e('0,所有模块'); // 步骤4：搜索类型参数
r($projectzenTest->prepareModuleForBugTest(3, 4, 'bymodule', 2, 'name_desc', 0, '0', array((object)array('id' => 3, 'name' => '产品C')))) && p('moduleID,moduleName') && e('2,模块B'); // 步骤5：指定模块参数