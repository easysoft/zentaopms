#!/usr/bin/env php
<?php

/**

title=测试 projectZen::processBuildSearchParams();
timeout=0
cid=0

- 执行$searchConfig1['fields']['product'] @1
- 执行$searchConfig2['fields']['product'] @1
- 执行$searchConfig3 @1
- 执行$searchConfig4['fields'] @1
- 执行$searchConfig5['params'] @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('project')->gen(10);
zenData('product')->gen(5);

su('admin');

$projectTest = new projectZenTest();

$normalProduct = new stdclass();
$normalProduct->id = 1;
$normalProduct->type = 'normal';

$products = array(1 => 'Product1', 2 => 'Product2');

// 步骤1:无产品项目,product字段应该不存在
$noProductProject = new stdclass();
$noProductProject->id = 1;
$noProductProject->multiple = 0;
$noProductProject->hasProduct = 0;
$noProductProject->model = 'scrum';

$searchConfig1 = $projectTest->processBuildSearchParamsTest($noProductProject, $normalProduct, $products, 'all', 0);
r(!isset($searchConfig1['fields']['product'])) && p() && e('1');

// 步骤2:有产品项目,product字段应该存在
$productProject = new stdclass();
$productProject->id = 2;
$productProject->multiple = 0;
$productProject->hasProduct = 1;
$productProject->model = 'scrum';

$searchConfig2 = $projectTest->processBuildSearchParamsTest($productProject, $normalProduct, $products, 'all', 0);
r(isset($searchConfig2['fields']['product'])) && p() && e('1');

// 步骤3:测试配置是数组类型
$searchConfig3 = $projectTest->processBuildSearchParamsTest($productProject, $normalProduct, $products, 'normal', 0);
r(is_array($searchConfig3)) && p() && e('1');

// 步骤4:测试配置中包含fields键
$searchConfig4 = $projectTest->processBuildSearchParamsTest($productProject, $normalProduct, $products, 'all', 0);
r(isset($searchConfig4['fields'])) && p() && e('1');

// 步骤5:测试配置中包含params键
$searchConfig5 = $projectTest->processBuildSearchParamsTest($productProject, $normalProduct, $products, 'all', 0);
r(isset($searchConfig5['params'])) && p() && e('1');