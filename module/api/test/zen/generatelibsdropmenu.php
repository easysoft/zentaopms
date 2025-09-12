#!/usr/bin/env php
<?php

/**

title=测试 apiZen::generateLibsDropMenu();
timeout=0
cid=0

- 步骤1：空库对象返回默认文本属性text @独立接口
- 步骤2：关联产品的库返回产品名称属性text @正常产品1
- 步骤3：关联项目的库返回项目名称属性text @项目集1
- 步骤4：无关联的库显示默认文本属性text @独立接口
- 步骤5：版本参数测试属性link @/home/z/rzto/module/api/test/zen/generatelibsdropmenu.php?m=api&f=ajaxGetDropMenu&objectType=product&objectID=1&libID=4&version=2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/api.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('doclib');
$table->loadYaml('doclib_generatelibsdropmenu', false, 2)->gen(10);

$productTable = zenData('product');
$productTable->loadYaml('product_generatelibsdropmenu', false, 2)->gen(3);

$projectTable = zenData('project');
$projectTable->loadYaml('project_generatelibsdropmenu', false, 2)->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$apiTest = new apiTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 创建测试用的库对象
$emptyLib = new stdClass();
$emptyLib->id = null;
$emptyLib->product = null;
$emptyLib->project = null;

$productLib = new stdClass();
$productLib->id = 1;
$productLib->product = 1;
$productLib->project = 0;

$projectLib = new stdClass();
$projectLib->id = 2;
$projectLib->product = 0;
$projectLib->project = 1;

$nolinkLib = new stdClass();
$nolinkLib->id = 3;
$nolinkLib->product = 0;
$nolinkLib->project = 0;

$versionLib = new stdClass();
$versionLib->id = 4;
$versionLib->product = 1;
$versionLib->project = 0;

r($apiTest->generateLibsDropMenuTest($emptyLib)) && p('text') && e('独立接口'); // 步骤1：空库对象返回默认文本
r($apiTest->generateLibsDropMenuTest($productLib)) && p('text') && e('正常产品1'); // 步骤2：关联产品的库返回产品名称
r($apiTest->generateLibsDropMenuTest($projectLib)) && p('text') && e('项目集1'); // 步骤3：关联项目的库返回项目名称
r($apiTest->generateLibsDropMenuTest($nolinkLib)) && p('text') && e('独立接口'); // 步骤4：无关联的库显示默认文本
r($apiTest->generateLibsDropMenuTest($versionLib, 2)) && p('link') && e('/home/z/rzto/module/api/test/zen/generatelibsdropmenu.php?m=api&f=ajaxGetDropMenu&objectType=product&objectID=1&libID=4&version=2'); // 步骤5：版本参数测试