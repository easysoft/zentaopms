#!/usr/bin/env php
<?php

/**

title=测试 projectZen::processBuildSearchParams();
timeout=0
cid=0

- 执行projectTest模块的processBuildSearchParamsTest方法，参数是$noProductProject, $normalProduct, $products, 'all', 0 属性fieldsRemoved @1
- 执行projectTest模块的processBuildSearchParamsTest方法，参数是$productProject, $normalProduct, $products, 'all', 0 属性fieldsRemoved @0
- 执行projectTest模块的processBuildSearchParamsTest方法，参数是$productProject, $branchProduct, $products, 'all', 0 属性fieldsAdded @1
- 执行projectTest模块的processBuildSearchParamsTest方法，参数是$productProject, $normalProduct, $products, 'bysearch', 5 属性queryID @5
- 执行projectTest模块的processBuildSearchParamsTest方法，参数是$productProject, $normalProduct, $products, 'normal', 0 属性queryID @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

su('admin');

$projectTest = new projectzenTest();

// 构建测试数据
$noProductProject = new stdclass();
$noProductProject->id = 2;
$noProductProject->multiple = 0;
$noProductProject->hasProduct = 0;
$noProductProject->model = 'scrum';

$productProject = new stdclass();
$productProject->id = 1;
$productProject->multiple = 0;
$productProject->hasProduct = 1;
$productProject->model = 'scrum';

$normalProduct = new stdclass();
$normalProduct->id = 1;
$normalProduct->type = 'normal';

$branchProduct = new stdclass();
$branchProduct->id = 2;
$branchProduct->type = 'branch';

$products = array(1 => 'Product1', 2 => 'Product2');

r($projectTest->processBuildSearchParamsTest($noProductProject, $normalProduct, $products, 'all', 0)) && p('fieldsRemoved') && e('1');
r($projectTest->processBuildSearchParamsTest($productProject, $normalProduct, $products, 'all', 0)) && p('fieldsRemoved') && e('0');
r($projectTest->processBuildSearchParamsTest($productProject, $branchProduct, $products, 'all', 0)) && p('fieldsAdded') && e('1');
r($projectTest->processBuildSearchParamsTest($productProject, $normalProduct, $products, 'bysearch', 5)) && p('queryID') && e('5');
r($projectTest->processBuildSearchParamsTest($productProject, $normalProduct, $products, 'normal', 0)) && p('queryID') && e('0');