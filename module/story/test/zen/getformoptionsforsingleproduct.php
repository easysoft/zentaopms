#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getFormOptionsForSingleProduct();
timeout=0
cid=0

- 执行storyZenTest模块的getFormOptionsForSingleProductTest方法，参数是1, 0,  属性branchProduct @1
- 执行storyZenTest模块的getFormOptionsForSingleProductTest方法，参数是1, 0,  属性branchProduct @1
- 执行storyZenTest模块的getFormOptionsForSingleProductTest方法，参数是1, 0,  属性branchProduct @1
- 执行storyZenTest模块的getFormOptionsForSingleProductTest方法，参数是1, 0,   @~~
- 执行storyZenTest模块的getFormOptionsForSingleProductTest方法，参数是2, 0,   @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

zenData('product')->loadYaml('product_getformoptionsforsingleproduct', false, 2)->gen(10);
zenData('branch')->loadYaml('branch_getformoptionsforsingleproduct', false, 2)->gen(10);
zenData('productplan')->loadYaml('productplan_getformoptionsforsingleproduct', false, 2)->gen(10);
zenData('module')->loadYaml('module_getformoptionsforsingleproduct', false, 2)->gen(20);

su('admin');

$storyZenTest = new storyZenTest();

r($storyZenTest->getFormOptionsForSingleProductTest(1, 0, (object)array('id' => 1, 'name' => '正常产品', 'type' => 'normal'))) && p('branchProduct') && e('1');
r($storyZenTest->getFormOptionsForSingleProductTest(1, 0, (object)array('id' => 1, 'name' => '正常产品', 'type' => 'branch'))) && p('branchProduct') && e('1');
r($storyZenTest->getFormOptionsForSingleProductTest(1, 0, (object)array('id' => 1, 'name' => '正常产品', 'type' => 'platform'))) && p('branchProduct') && e('1');
r($storyZenTest->getFormOptionsForSingleProductTest(1, 0, (object)array('id' => 1, 'name' => '正常产品', 'type' => 'normal'))) && p() && e('~~');
r($storyZenTest->getFormOptionsForSingleProductTest(2, 0, (object)array('id' => 2, 'name' => '分支产品', 'type' => 'branch'))) && p() && e('~~');