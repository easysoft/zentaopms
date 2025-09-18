#!/usr/bin/env php
<?php

/**

title=测试 projectZen::extractUnModifyForm();
timeout=0
cid=0

- 执行projectTest模块的extractUnModifyFormTest方法，参数是1, $project1  @normal_project_with_products
- 执行projectTest模块的extractUnModifyFormTest方法，参数是2, $project2  @project_with_unmodifiable_products
- 执行projectTest模块的extractUnModifyFormTest方法，参数是3, $project3  @waterfall_project_products
- 执行projectTest模块的extractUnModifyFormTest方法，参数是4, $project4  @kanban_project_products
- 执行projectTest模块的extractUnModifyFormTest方法，参数是10, $project5  @project_without_products

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

// 2. zendata数据准备
$projectTable = zenData('project');
$projectTable->loadYaml('project_extractunmodifyform', false, 2)->gen(10);

$productTable = zenData('product');
$productTable->loadYaml('product_extractunmodifyform', false, 2)->gen(15);

$projectProductTable = zenData('projectproduct');
$projectProductTable->loadYaml('projectproduct_extractunmodifyform', false, 2)->gen(11);

$projectStoryTable = zenData('projectstory');
$projectStoryTable->loadYaml('projectstory_extractunmodifyform', false, 2)->gen(6);

$branchTable = zenData('branch');
$branchTable->loadYaml('branch_extractunmodifyform', false, 2)->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$projectTest = new projectTest();

// 5. 测试步骤（必须包含至少5个测试步骤）

// 步骤1：测试正常项目关联产品的情况
$project1 = new stdClass();
$project1->id = 1;
$project1->model = 'scrum';
$project1->parent = 1;
$project1->hasProduct = 1;
$project1->multiple = 1;
$project1->path = ',1,';
r($projectTest->extractUnModifyFormTest(1, $project1)) && p() && e('normal_project_with_products');

// 步骤2：测试项目有故事关联的产品不可修改情况
$project2 = new stdClass();
$project2->id = 2;
$project2->model = 'scrum';
$project2->parent = 2;
$project2->hasProduct = 1;
$project2->multiple = 1;
$project2->path = ',2,';
r($projectTest->extractUnModifyFormTest(2, $project2)) && p() && e('project_with_unmodifiable_products');

// 步骤3：测试瀑布项目模型下的产品关联情况
$project3 = new stdClass();
$project3->id = 3;
$project3->model = 'waterfall';
$project3->parent = 3;
$project3->hasProduct = 1;
$project3->multiple = 0;
$project3->path = ',3,';
r($projectTest->extractUnModifyFormTest(3, $project3)) && p() && e('waterfall_project_products');

// 步骤4：测试看板项目模型的产品关联情况
$project4 = new stdClass();
$project4->id = 4;
$project4->model = 'kanban';
$project4->parent = 4;
$project4->hasProduct = 1;
$project4->multiple = 1;
$project4->path = ',4,';
r($projectTest->extractUnModifyFormTest(4, $project4)) && p() && e('kanban_project_products');

// 步骤5：测试无产品关联的项目情况
$project5 = new stdClass();
$project5->id = 10;
$project5->model = 'scrum';
$project5->parent = 0;
$project5->hasProduct = 0;
$project5->multiple = 0;
$project5->path = ',10,';
r($projectTest->extractUnModifyFormTest(10, $project5)) && p() && e('project_without_products');