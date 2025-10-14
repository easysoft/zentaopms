#!/usr/bin/env php
<?php

/**

title=测试 productZen::getProjectProductList();
timeout=0
cid=0

- 执行productTest模块的getProjectProductListZenTest方法，参数是1, true  @0
- 执行productTest模块的getProjectProductListZenTest方法，参数是0, true  @5
- 执行productTest模块的getProjectProductListZenTest方法，参数是999, true  @0
- 执行productTest模块的getProjectProductListZenTest方法，参数是1, false  @0
- 执行productTest模块的getProjectProductListZenTest方法，参数是-1, true  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

zenData('product')->loadYaml('product_getprojectproductlist', false, 2)->gen(5);
zenData('projectproduct')->loadYaml('projectproduct_getprojectproductlist', false, 2)->gen(10);
zenData('project')->loadYaml('project_getprojectproductlist', false, 2)->gen(3);

su('admin');

$productTest = new productTest();

r($productTest->getProjectProductListZenTest(1, true)) && p() && e('0');
r($productTest->getProjectProductListZenTest(0, true)) && p() && e('5');
r($productTest->getProjectProductListZenTest(999, true)) && p() && e('0');
r($productTest->getProjectProductListZenTest(1, false)) && p() && e('0');
r($productTest->getProjectProductListZenTest(-1, true)) && p() && e('0');