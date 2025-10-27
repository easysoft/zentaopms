#!/usr/bin/env php
<?php

/**

title=测试 projectZen::processBuildSearchParams();
timeout=0
cid=0

- 执行projectzenTest模块的processBuildSearchParamsTest方法，参数是$project1, $product1, $products1, '', 0
 - 属性afterFieldsCount @2
 - 属性hasProduct @1
 - 属性multiple @1
- 执行projectzenTest模块的processBuildSearchParamsTest方法，参数是$project2, $product2, $products2, '', 0
 - 属性addedFields @branch
 - 属性productType @platform
- 执行projectzenTest模块的processBuildSearchParamsTest方法，参数是$project3, $product3, $products3, '', 0 属性removedFields @product
- 执行projectzenTest模块的processBuildSearchParamsTest方法，参数是$project4, $product4, $products4, '', 0 属性afterFieldsCount @2
- 执行projectzenTest模块的processBuildSearchParamsTest方法，参数是$project5, $product5, $products5, 'bysearch', 123 属性queryID @123

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

// 2. zendata数据准备
$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目{1-10}');
$project->hasProduct->range('1{5},0{5}');
$project->multiple->range('1{5},0{5}');
$project->model->range('scrum{3},waterfall{3},kanban{4}');
$project->gen(5);

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品{1-5}');
$product->type->range('normal{2},platform{2},branch{1}');
$product->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$projectzenTest = new projectzenTest();

// 5. 执行测试步骤

// 测试步骤1：项目有产品且多执行，产品为普通类型
$project1 = new stdclass();
$project1->id = 1;
$project1->hasProduct = true;
$project1->multiple = true;
$project1->model = 'scrum';

$product1 = new stdclass();
$product1->id = 1;
$product1->type = 'normal';

$products1 = array(1 => $product1);

r($projectzenTest->processBuildSearchParamsTest($project1, $product1, $products1, '', 0)) && p('afterFieldsCount,hasProduct,multiple') && e('2,1,1');

// 测试步骤2：项目有产品且多执行，产品为分支类型
$project2 = new stdclass();
$project2->id = 2;
$project2->hasProduct = true;
$project2->multiple = true;
$project2->model = 'scrum';

$product2 = new stdclass();
$product2->id = 2;
$product2->type = 'platform';

$products2 = array(2 => $product2);

r($projectzenTest->processBuildSearchParamsTest($project2, $product2, $products2, '', 0)) && p('addedFields,productType') && e('branch,platform');

// 测试步骤3：项目无产品的情况
$project3 = new stdclass();
$project3->id = 3;
$project3->hasProduct = false;
$project3->multiple = true;
$project3->model = 'scrum';

$product3 = new stdclass();
$product3->id = 3;
$product3->type = 'normal';

$products3 = array();

r($projectzenTest->processBuildSearchParamsTest($project3, $product3, $products3, '', 0)) && p('removedFields') && e('product');

// 测试步骤4：项目无迭代的情况
$project4 = new stdclass();
$project4->id = 4;
$project4->hasProduct = true;
$project4->multiple = false;
$project4->model = 'scrum';

$product4 = new stdclass();
$product4->id = 4;
$product4->type = 'normal';

$products4 = array(4 => $product4);

r($projectzenTest->processBuildSearchParamsTest($project4, $product4, $products4, '', 0)) && p('afterFieldsCount') && e('2');

// 测试步骤5：搜索类型为bysearch的情况
$project5 = new stdclass();
$project5->id = 5;
$project5->hasProduct = true;
$project5->multiple = true;
$project5->model = 'scrum';

$product5 = new stdclass();
$product5->id = 5;
$product5->type = 'normal';

$products5 = array(5 => $product5);

r($projectzenTest->processBuildSearchParamsTest($project5, $product5, $products5, 'bysearch', 123)) && p('queryID') && e('123');