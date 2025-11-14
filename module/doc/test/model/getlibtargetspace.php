#!/usr/bin/env php
<?php

/**

title=测试 docModel::getLibTargetSpace();
timeout=0
cid=16104

- 步骤1：product类型 @product.1
- 步骤2：project类型 @project.2
- 步骤3：custom类型 @custom.3
- 步骤4：api类型 @api.4
- 步骤5：mine类型 @mine.5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('doclib');
$table->loadYaml('doclib_getlibtargetspace', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 强制要求：必须包含至少5个测试步骤

// 创建product类型的lib对象
$productLib = new stdClass();
$productLib->type = 'product';
$productLib->product = 1;
$productLib->parent = 0;
r($docTest->getLibTargetSpaceTest($productLib)) && p() && e('product.1'); // 步骤1：product类型

// 创建project类型的lib对象
$projectLib = new stdClass();
$projectLib->type = 'project';
$projectLib->project = 2;
$projectLib->parent = 0;
r($docTest->getLibTargetSpaceTest($projectLib)) && p() && e('project.2'); // 步骤2：project类型

// 创建custom类型的lib对象
$customLib = new stdClass();
$customLib->type = 'custom';
$customLib->parent = 3;
$customLib->product = 0;
$customLib->project = 0;
r($docTest->getLibTargetSpaceTest($customLib)) && p() && e('custom.3'); // 步骤3：custom类型

// 创建api类型的lib对象
$apiLib = new stdClass();
$apiLib->type = 'api';
$apiLib->parent = 4;
$apiLib->product = 0;
$apiLib->project = 0;
r($docTest->getLibTargetSpaceTest($apiLib)) && p() && e('api.4'); // 步骤4：api类型

// 创建mine类型的lib对象
$mineLib = new stdClass();
$mineLib->type = 'mine';
$mineLib->parent = 5;
$mineLib->product = 0;
$mineLib->project = 0;
r($docTest->getLibTargetSpaceTest($mineLib)) && p() && e('mine.5'); // 步骤5：mine类型