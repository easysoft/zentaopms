#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getProductsAndBranchesForCreate();
timeout=0
cid=18693

- 执行storyTest模块的getProductsAndBranchesForCreateTest方法，参数是1, 1 第0条的1属性 @产品1
- 执行storyTest模块的getProductsAndBranchesForCreateTest方法，参数是0, 1 第0条的1属性 @产品1
- 执行storyTest模块的getProductsAndBranchesForCreateTest方法，参数是5, 0 第0条的5属性 @产品5
- 执行storyTest模块的getProductsAndBranchesForCreateTest方法，参数是6, 6 第0条的6属性 @分支产品1
- 执行storyTest模块的getProductsAndBranchesForCreateTest方法，参数是7, 0 第0条的7属性 @分支产品2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

zenData('product')->loadYaml('product_getproductsandbranchesforcreate', false, 2)->gen(10);
zenData('branch')->loadYaml('branch_getproductsandbranchesforcreate', false, 2)->gen(10);
zenData('projectproduct')->loadYaml('projectproduct_getproductsandbranchesforcreate', false, 2)->gen(10);

su('admin');

$storyTest = new storyZenTest();

r($storyTest->getProductsAndBranchesForCreateTest(1, 1)) && p('0:1') && e('产品1');
r($storyTest->getProductsAndBranchesForCreateTest(0, 1)) && p('0:1') && e('产品1');
r($storyTest->getProductsAndBranchesForCreateTest(5, 0)) && p('0:5') && e('产品5');
r($storyTest->getProductsAndBranchesForCreateTest(6, 6)) && p('0:6') && e('分支产品1');
r($storyTest->getProductsAndBranchesForCreateTest(7, 0)) && p('0:7') && e('分支产品2');