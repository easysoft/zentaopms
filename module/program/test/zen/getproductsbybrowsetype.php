#!/usr/bin/env php
<?php

/**

title=测试 programZen::getProductsByBrowseType();
timeout=0
cid=17730

- 执行programTest模块的getProductsByBrowseTypeTest方法，参数是'all', $products  @10
- 执行programTest模块的getProductsByBrowseTypeTest方法，参数是'unclosed', $products  @10
- 执行programTest模块的getProductsByBrowseTypeTest方法，参数是'doing', $products  @7
- 执行programTest模块的getProductsByBrowseTypeTest方法，参数是'wait', $products  @8
- 执行programTest模块的getProductsByBrowseTypeTest方法，参数是'closed', $products  @6
- 执行programTest模块的getProductsByBrowseTypeTest方法，参数是'suspended', $products  @6
- 执行programTest模块的getProductsByBrowseTypeTest方法，参数是'all', array_slice  @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/programzen.unittest.class.php';

zenData('product')->loadYaml('getproductsbybrowsetype/product', false, 2)->gen(10);
zenData('project')->loadYaml('getproductsbybrowsetype/project', false, 2)->gen(3);

su('admin');

$programTest = new programTest();

$products = $tester->loadModel('product')->getList();

r(count($programTest->getProductsByBrowseTypeTest('all', $products))) && p() && e('10');
r(count($programTest->getProductsByBrowseTypeTest('unclosed', $products))) && p() && e('10');
r(count($programTest->getProductsByBrowseTypeTest('doing', $products))) && p() && e('7');
r(count($programTest->getProductsByBrowseTypeTest('wait', $products))) && p() && e('8');
r(count($programTest->getProductsByBrowseTypeTest('closed', $products))) && p() && e('6');
r(count($programTest->getProductsByBrowseTypeTest('suspended', $products))) && p() && e('6');
r(count($programTest->getProductsByBrowseTypeTest('all', array_slice($products, 8, 2, true)))) && p() && e('2');