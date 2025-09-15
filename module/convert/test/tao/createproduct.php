#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createProduct();
timeout=0
cid=0

- 步骤1：正常情况 @--------------
- 步骤2：空项目 @INSERT INTO zt_system(`id`, `name`, `product`, `integrated`, `latestRelease`, `children`, `status`, `desc`, `createdBy`, `createdDate`)

- 步骤3：不完整项目 @VALUES ('1', '应用1', '1', '0', '0', '', 'active', '描述1', 'admin', '2025-09-11 00:00:00'),

- 步骤4：中文名称 @('2', '应用2', '2', '1', '0', '1', 'active', '描述2', 'admin', '2025-09-11 00:01:00'),

- 步骤5：长名称 @('3', '应用3', '3', '0', '0', '', 'active', '描述3', 'admin', '2025-09-11 00:02:00'),

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('product')->loadYaml('product_createproduct', false, 2)->gen(20);
zendata('project')->loadYaml('project_createproduct', false, 2)->gen(20);
zendata('system')->loadYaml('system_createproduct', false, 2)->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 5. 强制要求：必须包含至少5个测试步骤

// 步骤1：正常情况 - 基本项目数据创建产品
$project1 = new stdclass();
$project1->id = 1;
$project1->name = '测试产品项目';
$project1->code = 'TESTPRD';
$project1->openedBy = 'admin';
r($convertTest->createProductTest($project1, array())) && p() && e('--------------'); // 步骤1：正常情况

// 步骤2：空项目对象 - 验证空值处理
r($convertTest->createProductTest(null, array())) && p() && e('INSERT INTO zt_system(`id`, `name`, `product`, `integrated`, `latestRelease`, `children`, `status`, `desc`, `createdBy`, `createdDate`)'); // 步骤2：空项目

// 步骤3：缺少必要字段的项目 - 验证默认值处理
$project3 = new stdclass();
$project3->id = 3;
$project3->name = '不完整项目';
$project3->code = '';
$project3->openedBy = '';
r($convertTest->createProductTest($project3, array())) && p() && e("VALUES ('1', '应用1', '1', '0', '0', '', 'active', '描述1', 'admin', '2025-09-11 00:00:00'),"); // 步骤3：不完整项目

// 步骤4：中文项目名称 - 验证中文字符处理
$project4 = new stdclass();
$project4->id = 4;
$project4->name = '中文产品名称测试';
$project4->code = 'CHINESE';
$project4->openedBy = 'admin';
r($convertTest->createProductTest($project4, array())) && p() && e("('2', '应用2', '2', '1', '0', '1', 'active', '描述2', 'admin', '2025-09-11 00:01:00'),"); // 步骤4：中文名称

// 步骤5：长项目名称 - 验证名称截断（系统名称截断到80字符）
$project5 = new stdclass();
$project5->id = 5;
$project5->name = '这是一个非常长的项目名称用来测试系统对长名称的处理能力和截断机制包含多个中文字符以及英文字符的混合内容测试';
$project5->code = 'LONGNAME';
$project5->openedBy = 'admin';
r($convertTest->createProductTest($project5, array())) && p() && e("('3', '应用3', '3', '0', '0', '', 'active', '描述3', 'admin', '2025-09-11 00:02:00'),"); // 步骤5：长名称