#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

/**

title=测试 productModel->getSwitcher();
timeout=0
cid=17520

- 测试设置产品1的1.5级下拉菜单 @1
- 测试设置产品2的1.5级下拉菜单 @1
- 测试设置产品3的1.5级下拉菜单 @1
- 测试设置产品4的1.5级下拉菜单 @1
- 测试设置产品5的1.5级下拉菜单 @1

*/

su('admin');
zenData('product')->loadYaml('product')->gen(10);

$productIdList = range(1, 5);
$productTester = new productTest();
r($productTester->getSwitcherTest($productIdList[0])) && p() && e('1'); // 测试设置产品1的1.5级下拉菜单
r($productTester->getSwitcherTest($productIdList[1])) && p() && e('1'); // 测试设置产品2的1.5级下拉菜单
r($productTester->getSwitcherTest($productIdList[2])) && p() && e('1'); // 测试设置产品3的1.5级下拉菜单
r($productTester->getSwitcherTest($productIdList[3])) && p() && e('1'); // 测试设置产品4的1.5级下拉菜单
r($productTester->getSwitcherTest($productIdList[4])) && p() && e('1'); // 测试设置产品5的1.5级下拉菜单
