#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getFormOptionsForSingleProduct();
timeout=0
cid=18688

- 执行storyTest模块的getFormOptionsForSingleProductTest方法，参数是1, 0, $normalProduct 属性branchProduct @1
- 执行storyTest模块的getFormOptionsForSingleProductTest方法，参数是4, 0, $branchProduct 属性branchProduct @1
- 执行storyTest模块的getFormOptionsForSingleProductTest方法，参数是7, 0, $platformProduct 属性branchProduct @~~
- 执行storyTest模块的getFormOptionsForSingleProductTest方法，参数是2, 0, $normalProduct2 属性branchProduct @1
- 执行storyTest模块的getFormOptionsForSingleProductTest方法，参数是5, 0, $branchProduct5 属性branchProduct @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

zenData('product')->loadYaml('product_getformoptionsforsingleproduct', false, 2)->gen(10);
zenData('branch')->loadYaml('branch_getformoptionsforsingleproduct', false, 2)->gen(10);
zenData('module')->loadYaml('module_getformoptionsforsingleproduct', false, 2)->gen(20);
zenData('productplan')->loadYaml('productplan_getformoptionsforsingleproduct', false, 2)->gen(10);

su('admin');

$storyTest = new storyZenTest();

$normalProduct = new stdClass();
$normalProduct->id = 1;
$normalProduct->name = '正常产品';
$normalProduct->type = 'normal';

$normalProduct2 = new stdClass();
$normalProduct2->id = 2;
$normalProduct2->name = '分支产品';
$normalProduct2->type = 'normal';

$branchProduct = new stdClass();
$branchProduct->id = 4;
$branchProduct->name = '普通产品';
$branchProduct->type = 'branch';

$platformProduct = new stdClass();
$platformProduct->id = 7;
$platformProduct->name = '正常产品';
$platformProduct->type = 'platform';

$branchProduct5 = new stdClass();
$branchProduct5->id = 5;
$branchProduct5->name = '测试产品';
$branchProduct5->type = 'branch';

r($storyTest->getFormOptionsForSingleProductTest(1, 0, $normalProduct)) && p('branchProduct') && e(1);
r($storyTest->getFormOptionsForSingleProductTest(4, 0, $branchProduct)) && p('branchProduct') && e(1);
r($storyTest->getFormOptionsForSingleProductTest(7, 0, $platformProduct)) && p('branchProduct') && e('~~');
r($storyTest->getFormOptionsForSingleProductTest(2, 0, $normalProduct2)) && p('branchProduct') && e(1);
r($storyTest->getFormOptionsForSingleProductTest(5, 0, $branchProduct5)) && p('branchProduct') && e('~~');